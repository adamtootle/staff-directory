<?php
/*
Plugin Name: Staff Directory
Plugin URI: https://wordpress.org/plugins/staff-directory/
Description: Allows Wordpress to keep track of your staff directory for your website. Good for churches, small companies, etc.
Version: 1.1.2
Author: Adam Tootle
Author URI: http://www.adamtootle.com
*/

global $wpdb;
$staff_directory_table = $wpdb->prefix . 'staff_directory';

define( 'STAFF_DIRECTORY_TABLE', $wpdb->prefix . 'staff_directory' );
define( 'STAFF_TEMPLATES', $wpdb->prefix . 'staff_directory_templates' );
define( 'STAFF_PHOTOS_DIRECTORY', WP_CONTENT_DIR . "/uploads/staff-photos/" );
define( 'STAFF_LIST_TEMPLATES', plugin_dir_path(__FILE__) . "templates/" );

require_once( dirname( __FILE__ ) . '/classes/staff-directory-settings.php' );

Staff_Directory_Settings::setup_defaults();

require_once( dirname( __FILE__ ) . '/classes/staff-directory.php' );
require_once( dirname( __FILE__ ) . '/classes/staff-directory-shortcode.php' );
require_once( dirname( __FILE__ ) . '/classes/staff-directory-admin.php' );

Staff_Directory::register_post_types();
Staff_Directory::set_default_meta_fields_if_necessary();
Staff_Directory_Admin::register_admin_menu_items();
Staff_Directory_Shortcode::register_shortcode();

if ( Staff_Directory::show_import_message() ) {
	Staff_Directory_Admin::register_import_old_staff_message();
}

register_activation_hook( __FILE__, array( 'Staff_Directory', 'set_default_templates_if_necessary' ) );
