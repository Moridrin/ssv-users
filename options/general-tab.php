<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	global $options;
	update_option('mp_ssv_frontend_members_register_page', $_POST['mp_ssv_frontend_members_register_page']);
	if (isset($_POST['mp_ssv_frontend_members_show_admin_bar_front'])) {
		update_option('mp_ssv_frontend_members_show_admin_bar_front', 'true');
	} else {
		update_option('mp_ssv_frontend_members_show_admin_bar_front', 'false');
	}
	update_option('mp_ssv_recaptcha_site_key', $_POST['mp_ssv_recaptcha_site_key']);
	update_option('mp_ssv_recaptcha_secret_key', $_POST['mp_ssv_recaptcha_secret_key']);
}
?>
<form method="post" action="#">
	<table class="form-table">
		<tr>
			<th scope="row">Register Page</th>
			<td>
				<select name="mp_ssv_frontend_members_register_page">
					<option value="same_as_profile_page" <?php if (esc_attr(stripslashes(get_option('mp_ssv_frontend_members_register_page'))) == 'same_as_profile_page') { echo "selected"; } ?>>Same as Profile Page</option>
					<option value="required_profile_page_fields_only"<?php if (esc_attr(stripslashes(get_option('mp_ssv_frontend_members_register_page'))) == 'required_profile_page_fields_only') { echo "selected"; } ?>>Required fields Only</option>
					<option value="custom"<?php if (esc_attr(stripslashes(get_option('mp_ssv_frontend_members_register_page'))) == 'custom') { echo "selected"; } ?>>Custom</option>
				</select>
			</td>
		</tr>
		<tr>
			<th scope="row">Show Admin Bar</th>
			<td>
				<?php
				if (is_plugin_active('user-role-editor/user-role-editor.php')) { ?>
					<input type="checkbox" name="mp_ssv_frontend_members_show_admin_bar_front" value="true" checked disabled/>
					Show the wordpress admin bar for new members. Specify this in <a href="http://allterrain.nl/wp-admin/users.php?page=users-user-role-editor.php">User Role Editor</a>
				<?php } else { ?>
					<input type="checkbox" name="mp_ssv_frontend_members_show_admin_bar_front" value="true" <?php if (get_option('mp_ssv_frontend_members_show_admin_bar_front') == 'true') { echo "checked"; } ?>/>
					Show the wordpress admin bar for new members.
				<?php } ?>
			</td>
		</tr>
		<tr>
			<th scope="row">reCAPTCHA Site Key</th>
			<td>
				<input type="text" name="mp_ssv_recaptcha_site_key" value="<?php echo get_option('mp_ssv_recaptcha_site_key'); ?>">
			</td>
		</tr>
		<tr>
			<th scope="row">reCAPTCHA Secret Key</th>
			<td>
				<input type="text" name="mp_ssv_recaptcha_secret_key" value="<?php echo get_option('mp_ssv_recaptcha_secret_key'); ?>">
			</td>
		</tr>
	</table>
	<?php submit_button(); ?>
</form>