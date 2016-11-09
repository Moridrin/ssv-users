<?php
if (!defined('ABSPATH')) {
    exit;
}
/**
 * Created by: Jeroen Berkvens
 * Date: 23-4-2016
 * Time: 16:08
 */

require_once 'FrontendMembersFieldInputSelectRoleOption.php';

class FrontendMembersFieldInputSelectRole extends FrontendMembersFieldInputSelect
{
	/**
	 * FrontendMembersFieldInputRoleSelect constructor.
	 *
	 * @param FrontendMembersFieldInput $field   is the parent field.
	 * @param string                    $display is the way the input field is displayed (readonly, disabled or normal) default is normal.
	 */
	protected function __construct($field, $display)
	{
		parent::__construct($field, $field->input_type, $field->name);
		$this->options = $this->getOptions();
		$this->display = $display;
	}

	/**
	 * @param int    $index   is an index that specifies the display (/tab) order for the field.
	 * @param string $title   is the title of this component.
	 * @param string $name    is the name of the input field.
	 * @param array  $options is an array with all the options for the select field.
	 * @param string $display is the way the input field is displayed (readonly, disabled or normal) default is normal.
	 *
     * @return FrontendMembersFieldInputSelectRole
	 */
	public static function create($index, $title, $name, $options = array(), $display = "normal")
	{
        return new FrontendMembersFieldInputSelectRole(parent::createInput($index, $title, 'role_select', $name), $options, $display);
	}

	public function getOptionsFromPOST($variables)
	{
		$options = array();
		$index = 0;
		foreach ($variables as $name => $value) {
			if (strpos($name, "_option") !== false) {
				$id = str_replace("option", "", str_replace("_", "", $name));
                $options[] = new FrontendMembersFieldInputSelectRoleOption($id, $index, $this->id, $value);
				$index++;
			}
		}

		return $options;
	}

	/**
	 * @return string row that can be added to the profile page options table.
	 */
	public function getOptionRow()
	{
		ob_start();
        echo parent::getOptionRow();
        echo ssv_get_td(ssv_get_options($this->id, self::getOptionsAsArray(), "role"));
        echo ssv_get_td(ssv_get_select("Display", $this->id, $this->display, array("Normal", "ReadOnly", "Disabled")));
        echo ssv_get_td('<div class="' . $this->id . '_empty"></div>');
		$content = ob_get_clean();

		return parent::getOptionRowInput($content);
	}

	private function getOptionsAsArray($names_only = false)
	{
		$array = array();
		if (count($this->options) > 0) {
			foreach ($this->options as $option) {
				if ($names_only) {
					$array[] = $option->value;
				} else {
					$array[] = array('id' => $option->id, 'type' => 'role', 'value' => $option->value);
				}
			}
		}

		return $array;
	}

    /**
     * @param FrontendMember $frontend_member
     *
     * @return string the HTML element
     */
    public function getHTML($frontend_member = null)
    {
        ob_start();
        if ($frontend_member == null) {
            $value = "";
            $this->display = 'normal';
        } else {
            $value = $frontend_member->getMeta($this->name);
        }
        if (current_theme_supports('mui')) {
            ?>
            <div class="mui-select mui-textfield">
                <label for="<?php echo esc_html($this->id); ?>"><?php echo esc_html($this->title); ?></label>
                <select id="<?php echo esc_html($this->id); ?>" name="<?php echo esc_html($this->name); ?>_role_select" class="<?php echo esc_html($this->class); ?>" style="<?php echo $this->style; ?>">
                    <?php foreach ($this->options as $option) {
                        /* @var $option FrontendMembersFieldInputSelectRoleOption|FrontendMembersFieldInputSelectTextOption */
                        echo $option->getHTML($value);
                    }
                    ?>
                </select>
            </div>
            <?php
        } else {
            ?>
            <label for="<?php echo esc_html($this->id); ?>"><?php echo esc_html($this->title); ?></label>
            <select id="<?php echo esc_html($this->id); ?>" name="<?php echo esc_html($this->name); ?>_role_select" class="<?php echo esc_html($this->class); ?>" style="<?php echo $this->style; ?>">
                <?php foreach ($this->options as $option) {
                    /* @var $option FrontendMembersFieldInputSelectRoleOption|FrontendMembersFieldInputSelectTextOption */
                    echo $option->getHTML($value);
                }
                ?>
            </select>
            <br/>
            <?php
        }

        return ob_get_clean();
    }
}