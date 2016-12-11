<?php
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Created by: Jeroen Berkvens
 * Date: 23-4-2016
 * Time: 16:10
 */
class FrontendMembersFieldInputRoleCheckbox extends FrontendMembersFieldInput
{
    public $role;
    public $display;
    public $defaultValue;

    /**
     * FrontendMembersFieldInputRoleCheckbox constructor.
     *
     * @param FrontendMembersFieldInput $field        is the parent field.
     * @param string|\WP_Role           $role         is the name of the role or the role itself associated with this checkbox.
     * @param string                    $display      is the way the input field is displayed (readonly, disabled or normal) default is normal.
     * @param string                    $defaultValue is whether the checkbox is checked or not when filling in the form.
     */
    protected function __construct($field, $role, $display, $defaultValue)
    {
        parent::__construct($field, $field->input_type, $field->name);
        $this->role         = $role;
        $this->display      = $display;
        $this->defaultValue = $defaultValue;
        $this->name         = $this->role . '_role';
    }

    /**
     * A checkbox always has a value ('no' or 'yes')
     *
     * @param FrontendMember|null $frontend_member is the member to check if this member already has the required value.
     *
     * @return bool required
     */
    public function isValueRequiredForMember($frontend_member = null)
    {
        if (!$this->isEditable()) {
            return false;
        }
        if (FrontendMember::get_current_user() != null && FrontendMember::get_current_user()->isBoard()) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * If the field is displayed normally than this field is editable.
     *
     * @return bool returns if the field is displayed normally.
     */
    public function isEditable()
    {
        if (FrontendMember::get_current_user() != null && FrontendMember::get_current_user()->isBoard()) {
            return true;
        }
        return $this->display == 'normal';
    }

    /**
     * @return string row that can be added to the profile page options table.
     */
    public function getOptionRow()
    {
        ob_start();
        echo ssv_get_td(ssv_get_role_select($this->id, "Role", $this->role));
        echo ssv_get_td('<div class="' . $this->id . '_empty"></div>');
        if (get_option('ssv_frontend_members_view_display__preview_column', 'true') == 'true') {
            echo ssv_get_td(ssv_get_select("Display", $this->id, $this->display, array("Normal", "ReadOnly", "Disabled"), array()));
        } else {
            echo ssv_get_hidden($this->id, "Display", $this->display);
        }
        if (get_option('ssv_frontend_members_view_default_column', 'true') == 'true') {
            echo ssv_get_td(ssv_get_checkbox("Checked by Default", '\' + sender_id + \'', "no", array(), false, false));
        }
        if (get_option('ssv_frontend_members_view_placeholder_column', 'true') == 'true') {
            echo ssv_get_td('<div class="' . $this->id . '_empty"></div>');
        }
        $content = ob_get_clean();

        return parent::getOptionRowInput($content);
    }

    /**
     * This function creates an input field for the filter.
     *
     * @return string div with a filter field.
     */
    public function getFilter()
    {
        ob_start();
        $value = isset($_SESSION['filter_' . $this->name]) ? $_SESSION['filter_' . $this->name] : '';
        ?>
        <select id="<?php echo esc_html($this->id); ?>" name="filter_<?php echo esc_html($this->name); ?>" title="<?php echo esc_html($this->title); ?>">
            <option value="">[<?php echo esc_html($this->title); ?>]</option>
            <option value="yes" <?= $value == 'yes' ? 'selected' : '' ?>>Selected</option>
            <option value="no" <?= $value == 'no' ? 'selected' : '' ?>>Not Selected</option>
        </select>
        <?php
        return trim(preg_replace('/\s+/', ' ', ob_get_clean()));
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
            $value         = null;
            $this->display = 'normal';
        } else {
            $value = $frontend_member->getMeta($this->name);
        }
        global $wp_roles;
        $this->class  = $this->class ?: 'filled-in';
        $checked      = ($value == "yes" || ($value == null && $this->defaultValue == "yes")) ? 'checked' : '';
        $userRoleName = translate_user_role($wp_roles->roles[$this->role]['name']);
        if (current_theme_supports('materialize')) {
            ?>
            <div class="col s12">
                <input type="hidden" id="<?= $this->id ?>" name="<?= $this->name ?>" value="no"/>
                <p>
                    <input type="checkbox" id="field_<?= $this->id ?>" name="<?= $this->name ?>" value="yes" class="<?= $this->class ?>" style="<?= $this->style; ?>" <?= $checked ?>/>
                    <label for="field_<?= $this->id ?>"><?= $userRoleName ?></label>
                </p>
            </div>
            <?php
        }
        return ob_get_clean();
    }

    public function save($remove = false, $user = null)
    {
        parent::save($remove);
        global $wpdb;
        $table = FRONTEND_MEMBERS_FIELD_META_TABLE_NAME;
        $wpdb->replace(
            $table,
            array("field_id" => $this->id, "meta_key" => "role", "meta_value" => $this->role),
            array('%d', '%s', '%s')
        );
        $wpdb->replace(
            $table,
            array("field_id" => $this->id, "meta_key" => "display", "meta_value" => $this->display),
            array('%d', '%s', '%s')
        );
        $wpdb->replace(
            $table,
            array("field_id" => $this->id, "meta_key" => "default_value", "meta_value" => $this->defaultValue),
            array('%d', '%s', '%s')
        );
    }
}