<?php

class Staff_Directory_Admin {
	static function register_admin_menu_items() {
		add_action( 'admin_menu', array( 'Staff_Directory_Admin', 'add_admin_menu_items' ) );
	}

	static function add_admin_menu_items() {
		add_submenu_page( 'edit.php?post_type=staff', 'Staff Directory Settings', 'Settings', 'publish_posts',
			'staff-directory-settings', array( 'Staff_Directory_Admin', 'settings' ) );
		add_submenu_page( 'edit.php?post_type=staff', 'Staff Directory Help', 'Help', 'publish_posts',
			'staff-directory-help', array( 'Staff_Directory_Admin', 'help' ) );
		add_submenu_page( 'edit.php?post_type=staff', 'Staff Directory Import', 'Import Old Staff', 'publish_posts',
			'staff-directory-import', array( 'Staff_Directory_Admin', 'import' ) );
	}

	static function settings() {

		$staff_settings = Staff_Directory_Settings::shared_instance();
		$did_update_options = false;

		if ( isset( $_GET['delete-template'] ) ) {
			$staff_settings->delete_custom_template( $_GET['delete-template'] );
		}

		if ( isset( $_POST['staff_single_template'] ) ) {
            update_option( 'staff_single_template', $_POST['staff_single_template'] );
			$did_update_options = true;
		} else {
            if ( get_option( 'staff_single_template' ) == '' ) {
    			update_option( 'staff_single_template', 'default' );
                $did_update_options = true;
    		}
        }

		if ( isset( $_POST['staff_templates']['slug'] ) ) {
			$staff_settings->update_default_staff_template_slug( $_POST['staff_templates']['slug'] );
			$did_update_options = true;
		}

		if ( isset( $_POST['custom_staff_templates'] ) ) {
			$staff_settings->update_custom_staff_templates( $_POST['custom_staff_templates'] );
			$did_update_options = true;
		}

		if ( isset( $_POST['staff_meta_fields_labels'] ) ) {
			$staff_settings->update_custom_staff_meta_fields( $_POST['staff_meta_fields_labels'],
				$_POST['staff_meta_fields_types'] );
			$did_update_options = true;
		}

		$current_template = $staff_settings->get_current_default_staff_template();
		$custom_templates = $staff_settings->get_custom_staff_templates();

		require_once( plugin_dir_path( __FILE__ ) . '../views/admin-settings.php' );
	}

	static function help() {
		require_once( plugin_dir_path( __FILE__ ) . '../views/admin-help.php' );
	}

	static function import() {
		$did_import_old_staff = false;
		if ( isset( $_GET['import'] ) && $_GET['import'] == 'true' ) {
			Staff_Directory::import_old_staff();
			$did_import_old_staff = true;
		}
		if ( Staff_Directory::has_old_staff_table() ):
			?>

			<h2>Staff Directory Import</h2>
			<p>
				This tool is provided to import staff from an older version of this plugin.
				This will copy old staff members over to the new format, but it is advised
				that you backup your database before proceeding. Chances are you won't need
				it, but it's always better to be safe than sorry! WordPress provides some
				<a href="https://codex.wordpress.org/Backing_Up_Your_Database" target="_blank">instructions</a>
				on how to backup your database.
			</p>

			<p>
				Once you're ready to proceed, simply use the button below to import old
				staff members to the newer version of the plugin.
			</p>

			<p>
				<a href="<?php echo get_admin_url(); ?>edit.php?post_type=staff&page=staff-directory-import&import=true"
				   class="button button-primary">Import Old Staff</a>
			</p>

		<?php else: ?>

			<?php if ( $did_import_old_staff ): ?>

				<div class="updated">
					<p>
						Old staff was successfully imported! You can <a
							href="<?php echo get_admin_url(); ?>edit.php?post_type=staff">view all staff here</a>.
					</p>
				</div>

			<?php else: ?>

				<p>
					It doesn't look like you have any staff members from an older version of the plugin. You're good to
					go!
				</p>

			<?php endif; ?>

			<?php

		endif;
	}

	static function register_import_old_staff_message() {
		add_action( 'admin_notices', array( 'Staff_Directory_Admin', 'show_import_old_staff_message' ) );
	}

	static function show_import_old_staff_message() {
		?>

		<div class="update-nag">
			It looks like you have staff from an older version of the Staff Directory plugin.
			You can <a href="<?php echo get_admin_url(); ?>edit.php?post_type=staff&page=staff-directory-import">import
				them</a> to the newer version if you would like.
		</div>

		<?php
	}
}
