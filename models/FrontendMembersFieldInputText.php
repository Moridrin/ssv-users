<?php

/**
 * Created by: Jeroen Berkvens
 * Date: 23-4-2016
 * Time: 16:08
 */
class FrontendMembersFieldInputText extends FrontendMembersFieldInput
{

	public $required;
	public $display;
	public $placeholder;

	/**
	 * FrontendMembersFieldInputText constructor.
	 *
	 * @param FrontendMembersFieldInput $field       is the parent field.
	 * @param bool                      $required    is true if this is a required input field.
	 * @param string                    $display     is the way the input field is displayed (readonly, disabled or normal) default is normal.
	 * @param string                    $placeholder is the placeholder text that gives an example of what to enter.
	 */
	protected function __construct($field, $required, $display, $placeholder)
	{
		parent::__construct($field, $field->input_type, $field->name);
		$this->required = $required;
		$this->display = $display;
		$this->placeholder = $placeholder;
	}

	/**
	 * @param int    $index       is an index that specifies the display (/tab) order for the field.
	 * @param string $title       is the title of this component.
	 * @param string $name        is the name of the input field.
	 * @param bool   $required    is true if this is a required input field.
	 * @param string $display     is the way the input field is displayed (readonly, disabled or normal) default is normal.
	 * @param string $placeholder is the placeholder text that gives an example of what to enter.
	 *
	 * @return FrontendMembersFieldInputText
	 */
	public static function create($index, $title, $name, $required = false, $display = "normal", $placeholder = "")
	{
		return new FrontendMembersFieldInputText(parent::createInput($index, $title, 'text', $name), $required, $display, $placeholder);
	}

	/**
	 * @return string row that can be added to the profile page options table.
	 */
	public function getOptionRow()
	{
		ob_start();
		echo mp_ssv_get_td(mp_ssv_get_text_input("Name", $this->id, $this->name, "text", array("required")));
		echo mp_ssv_get_td(mp_ssv_get_checkbox("Required", $this->id, $this->required));
		echo mp_ssv_get_td(mp_ssv_get_select("Display", $this->id, $this->display, array("Normal", "ReadOnly", "Disabled")));
		echo mp_ssv_get_td(mp_ssv_get_text_input("Placeholder", $this->id, $this->placeholder));
		$content = ob_get_clean();

		return parent::getOptionRowInput($content);
	}

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
			<div class="mui-textfield <?php if ($this->placeholder == "") echo "mui-textfield--float-label"; ?>">
				<input type="text" id="<?php echo $this->id; ?>" name="<?php echo $this->name; ?>" value="<?php echo $value; ?>" <?php echo $this->display; ?>
				       placeholder="<?php echo $this->placeholder; ?>" <?php if ($this->required == "yes") echo "required"; ?>/>
				<label><?php echo $this->title; ?></label>
			</div>
			<?php
		} else {
			?>
			<label><?php echo $this->title; ?></label>
			<input type="text" id="<?php echo $this->id; ?>" name="<?php echo $this->name; ?>" value="<?php echo $value; ?>" <?php echo $this->display; ?>
			       placeholder="<?php echo $this->placeholder; ?>" <?php if ($this->required == "yes") echo "required"; ?>/>
			<br/>
			<?php
		}

		return ob_get_clean();
	}

	public function save($remove = false)
	{
		parent::save($remove);
		global $wpdb;
		$table = FRONTEND_MEMBERS_FIELD_META_TABLE_NAME;
		$wpdb->replace($table, array("field_id" => $this->id, "meta_key" => "required", "meta_value" => $this->required), array('%d', '%s', '%s'));
		$wpdb->replace($table, array("field_id" => $this->id, "meta_key" => "display", "meta_value" => $this->display), array('%d', '%s', '%s'));
		$wpdb->replace($table, array("field_id" => $this->id, "meta_key" => "placeholder", "meta_value" => $this->placeholder), array('%d', '%s', '%s'));
	}
}