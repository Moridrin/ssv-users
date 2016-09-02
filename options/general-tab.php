<?php
if (!defined('ABSPATH')) {
    exit;
}
if (!current_user_can('manage_options')) {
    ?>
    <p>You are unauthorized to view or edit this page.</p>
    <?php
    return;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && check_admin_referer('ssv_save_frontend_members_general_options')) {
    global $options;
    update_option('ssv_frontend_members_register_page', sanitize_text_field($_POST['ssv_frontend_members_register_page']));
    update_option('ssv_frontend_members_default_member_role', sanitize_text_field($_POST['ssv_frontend_members_default_member_role']));
    update_option('ssv_frontend_members_board_role', sanitize_text_field($_POST['ssv_frontend_members_board_role']));
    if (isset($_POST['ssv_view_advanced_profile_page'])) {
        update_option('ssv_view_advanced_profile_page', 'true');
    } else {
        update_option('ssv_view_advanced_profile_page', 'false');
    }
    update_option('ssv_recaptcha_site_key', sanitize_text_field($_POST['ssv_recaptcha_site_key']));
    update_option('ssv_recaptcha_secret_key', sanitize_text_field($_POST['ssv_recaptcha_secret_key']));
}
?>
<form method="post" action="#">
    <table class="form-table">
        <tr>
            <th scope="row">Register Page</th>
            <td>
                <select name="ssv_frontend_members_register_page" title="Register Page">
                    <option value="same_as_profile_page" <?php if (esc_attr(stripslashes(get_option('ssv_frontend_members_register_page'))) == 'same_as_profile_page') {
                        echo "selected";
                    } ?>>Same as Profile Page
                    </option>
                    <option value="required_profile_page_fields_only"<?php if (esc_attr(stripslashes(get_option('ssv_frontend_members_register_page'))) == 'required_profile_page_fields_only') {
                        echo "selected";
                    } ?>>Required fields Only
                    </option>
                    <option value="custom"<?php if (esc_attr(stripslashes(get_option('ssv_frontend_members_register_page'))) == 'custom') {
                        echo "selected";
                    } ?>>Custom
                    </option>
                </select>
            </td>
        </tr>
        <tr>
            <th scope="row">Default Member Role</th>
            <td>
                <select name="ssv_frontend_members_default_member_role" title="Default Member Role">
                    <?php wp_dropdown_roles(esc_attr(stripslashes(get_option('ssv_frontend_members_default_member_role')))); ?>
                </select>
            </td>
        </tr>
        <tr>
            <th scope="row">Board Role</th>
            <td>
                <select name="ssv_frontend_members_board_role" title="Board Role">
                    <?php wp_dropdown_roles(esc_attr(stripslashes(get_option('ssv_frontend_members_board_role')))); ?>
                </select>
            </td>
        </tr>
        <tr>
            <th scope="row">Advanced Profile Page Tab</th>
            <td>
                <label>
                    <input type="checkbox" name="ssv_view_advanced_profile_page" value="true" <?php if (get_option('ssv_view_advanced_profile_page') == 'true') {
                        echo "checked";
                    } ?>/>
                </label>
            </td>
        </tr>
        <!--Removed because this feature is not tested.-->
        <!--        <tr>-->
        <!--            <th scope="row">Show Admin Bar</th>-->
        <!--            <td>-->
        <!--                --><?php
        //                if (is_plugin_active('user-role-editor/user-role-editor.php')) { ?>
        <!--                    <label>-->
        <!--                        <input type="checkbox" name="ssv_frontend_members_show_admin_bar_front" value="true" checked disabled/>-->
        <!--                        Show the wordpress admin bar for new members. Specify this in <a href="--><? //= get_site_url() ?><!--/wp-admin/users.php?page=users-user-role-editor.php">User Role Editor</a>-->
        <!--                    </label>-->
        <!--                --><?php //} else { ?>
        <!--                    <label>-->
        <!--                        <input type="checkbox" name="ssv_frontend_members_show_admin_bar_front" value="true" --><?php //if (get_option('ssv_frontend_members_show_admin_bar_front') == 'true') {
        //                            echo "checked";
        //                        } ?><!--/>-->
        <!--                        Show the wordpress admin bar for new members.-->
        <!--                    </label>-->
        <!--                --><?php //} ?>
        <!--            </td>-->
        <!--        </tr>-->
        <tr>
            <th scope="row">reCAPTCHA Site Key</th>
            <td>
                <input type="text" name="ssv_recaptcha_site_key" value="<?php echo get_option('ssv_recaptcha_site_key'); ?>" title="reCAPTCHA Site Key">
            </td>
        </tr>
        <tr>
            <th scope="row">reCAPTCHA Secret Key</th>
            <td>
                <input type="text" name="ssv_recaptcha_secret_key" value="<?php echo get_option('ssv_recaptcha_secret_key'); ?>" title="reCAPTCHA Secret Key">
            </td>
        </tr>
    </table>
    <?php wp_nonce_field('ssv_save_frontend_members_general_options'); ?>
    <?php submit_button(); ?>
</form>