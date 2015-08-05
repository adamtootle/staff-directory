<?php

class StaffDirectory {

  #
  # Init custom post types
  #

  static function register_post_types() {
    add_action('init', array('StaffDirectory', 'create_post_types'));
    add_action('init', array('StaffDirectory', 'create_staff_taxonomies'));
    add_filter("manage_edit-staff_columns", array('StaffDirectory', 'set_staff_admin_columns'));
    add_filter("manage_staff_posts_custom_column", array('StaffDirectory', 'custom_staff_admin_columns'), 10, 3);
    add_filter("manage_edit-staff_category_columns", array('StaffDirectory', 'set_staff_category_columns'));
    add_filter("manage_staff_category_custom_column", array('StaffDirectory', 'custom_staff_category_columns'), 10, 3);
    add_filter('enter_title_here', array('StaffDirectory', 'staff_title_text'));
    add_filter('admin_head', array('StaffDirectory', 'remove_media_buttons'));
    add_action('add_meta_boxes_staff', array('StaffDirectory', 'add_staff_custom_meta_boxes'));
    add_action('save_post', array('StaffDirectory', 'save_meta_boxes'));
    add_action('wp_enqueue_scripts', array('StaffDirectory', 'enqueue_fontawesome'));
    add_action('admin_enqueue_scripts', array('StaffDirectory', 'enqueue_fontawesome'));

    add_action('init', array('StaffDirectory', 'init_tinymce_button'));
    add_action('wp_ajax_get_my_form', array('StaffDirectory', 'thickbox_ajax_form'));
  }

  static function create_post_types() {
    register_post_type( 'staff',
      array(
        'labels' => array(
          'name' => __( 'Staff' )
        ),
        'supports' => array(
          'title',
          'editor',
          'thumbnail'
        ),
        'public' => true,
        'menu_icon' => 'dashicons-groups',
        'taxonomies' => array('staff_category')
      )
    );
  }

  static function create_staff_taxonomies() {
    register_taxonomy('staff_category', 'staff', array(
  		'hierarchical' => true,
  		'labels' => array(
  			'name' => _x( 'Staff Category', 'taxonomy general name' ),
  			'singular_name' => _x( 'staff-category', 'taxonomy singular name' ),
  			'search_items' =>  __( 'Search Staff Categories' ),
  			'all_items' => __( 'All Staff Categories' ),
  			'parent_item' => __( 'Parent Staff Category' ),
  			'parent_item_colon' => __( 'Parent Staff Category:' ),
  			'edit_item' => __( 'Edit Staff Category' ),
  			'update_item' => __( 'Update Staff Category' ),
  			'add_new_item' => __( 'Add New Staff Category' ),
  			'new_item_name' => __( 'New Staff Category Name' ),
  			'menu_name' => __( 'Staff Categories' ),
  		),
  		'rewrite' => array(
  			'slug' => 'staff-categories',
  			'with_front' => false,
  			'hierarchical' => true
  		),
  	));
  }

  static function set_staff_admin_columns() {
    $new_columns = array(
  	  'cb' => '<input type="checkbox" />',
  	  'title' => __('Title'),
      'id' => __('ID'),
      'featured_image' => __('Featured Image'),
      'date' => __('Date')
  	);
  	return $new_columns;
  }

  static function custom_staff_admin_columns($column_name, $post_id) {
    $out = '';
    switch ($column_name) {
      case 'featured_image':
        $attachment_array = wp_get_attachment_image_src(get_post_thumbnail_id($post_id));
        $photo_url = $attachment_array[0];
        $out .= '<img src="' . $photo_url . '" style="max-height: 60px; width: auto;" />';
        break;

      case 'id':
          $out .= $post_id;
          break;

      default:
        break;
    }
    echo $out;
  }

  static function set_staff_category_columns() {
    $new_columns = array(
  	  'cb' => '<input type="checkbox" />',
  	  'name' => __('Name'),
      'id' => __('ID'),
  		'description' => __('Description'),
      'slug' => __('Slug'),
      'posts' => __('Posts')
  	);
  	return $new_columns;
  }

  static function custom_staff_category_columns($out, $column_name, $theme_id) {
      switch ($column_name) {
          case 'id':
              $out .= $theme_id;
              break;

          default:
              break;
      }
      return $out;
  }

  static function enqueue_fontawesome() {
    wp_enqueue_style('font-awesome', '//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css', array(), '4.0.3');
  }

  #
  # Custom post type customizations
  #

  static function staff_title_text( $title ){
    $screen = get_current_screen();
    if ($screen->post_type == 'staff') {
      $title = "Enter staff member's name";
    }

    return $title;
  }

  static function remove_media_buttons() {
    $screen = get_current_screen();
		if($screen->post_type == 'staff') {
		    remove_action('media_buttons', 'media_buttons');
    }
	}

  static function add_staff_custom_meta_boxes() {
    add_meta_box( 'staff-meta-box', __('Staff Details'), array('StaffDirectory', 'staff_meta_box_output'), 'staff', 'normal', 'high' );
  }

  static function staff_meta_box_output( $post ) {

    wp_nonce_field('staff_meta_box_nonce_action', 'staff_meta_box_nonce');

    $staff_settings = StaffSettings::sharedInstance();

    ?>

    <style type="text/css">
      label.staff-label {
        float: left;
        line-height: 27px;
        width: 130px;
      }
    </style>

    <?php foreach($staff_settings->getStaffDetailsFields() as $field): ?>
      <p>
        <label for="staff[<?php echo $field['slug'] ?>]" class="staff-label"><?php _e($field['name']); ?>:</label>
        <?php if($field['type'] == 'text'): ?>
          <input type="text" name="staff_meta[<?php echo $field['slug'] ?>]" value="<?php echo get_post_meta($post->ID, $field['slug'], true); ?>" />
        <?php elseif($field['type'] == 'textarea'): ?>
          <textarea cols=40 rows=5 name="staff_meta[<?php echo $field['slug'] ?>]"><?php echo get_post_meta($post->ID, $field['slug'], true); ?></textarea>
        <?php endif; ?>
      </p>
    <?php endforeach; ?>

    <?php
  }

  static function save_meta_boxes($post_id) {
    if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
      return;

    if(!isset( $_POST['staff_meta_box_nonce'] ) || !wp_verify_nonce($_POST['staff_meta_box_nonce'], 'staff_meta_box_nonce_action'))
      return;

    if(!current_user_can('edit_post', get_the_id()))
      return;

    foreach(array_keys($_POST['staff_meta']) as $meta_field_slug) {
      update_post_meta($post_id, $meta_field_slug, esc_attr($_POST['staff_meta'][$meta_field_slug]));
    }
  }

  static function set_default_meta_fields_if_necessary() {
    $current_meta_fields = get_option('staff_meta_fields');

    if($current_meta_fields == NULL || $current_meta_fields = '') {
      $default_meta_fields = array(
        array(
          'name' => 'Position',
          'type' => 'text',
          'slug' => 'position'
        ),
        array(
          'name' => 'Email',
          'type' => 'text',
          'slug' => 'email'
        ),
        array(
          'name' => 'Phone Number',
          'type' => 'text',
          'slug' => 'phone_number'
        ),
        array(
          'name' => 'Website',
          'type' => 'text',
          'slug' => 'website'
        )
      );
      update_option('staff_meta_fields', $default_meta_fields);
    }
  }

  #
  # Default templates
  #

  static function set_default_templates_if_necessary() {
    if(get_option('staff_directory_template_slug') == '') {
      update_option('staff_directory_template_slug', 'list');
    }

    if (get_option('staff_directory_html_template') == '') {
      $default_html_template = <<<EOT
<div class="staff-directory">

  [staff_loop]

    [name_header]
    [bio_paragraph]

    <div class="staff-directory-divider"></div>

  [/staff_loop]

</div>
EOT;
        update_option('staff_directory_html_template', $default_html_template);
    }

    if (get_option('staff_directory_css_template') == '') {
      $default_css_template = <<<EOT
.staff-directory-divider{
  border-top: solid black thin;
  width: 90%;
  margin:15px 0;
}
EOT;
        update_option('staff_directory_css_template', $default_css_template);
    }
  }

  #
  # Related to old staff members
  #

  static function has_old_staff_table() {
    global $wpdb;
    $staff_directory_table = $wpdb->prefix . 'staff_directory';

    $old_staff_sql = "SHOW TABLES LIKE '$staff_directory_table'";
    $old_staff_table_results = $wpdb->get_results($old_staff_sql);

    return count($old_staff_table_results) > 0;
  }

  static function show_import_message() {
    if (
      isset($_GET['page'])
      &&
      $_GET['page'] == 'staff-directory-import'
      &&
      isset($_GET['import'])
      &&
      $_GET['import'] == 'true'
    )
      return false;

    return StaffDirectory::has_old_staff_table();
  }

  static function get_old_staff($orderby = null, $order = null, $filter = null){
  	global $wpdb;
  	$staff_directory_table = $wpdb->prefix . 'staff_directory';
  	$staff_directory_categories = $wpdb->prefix . 'staff_directory_categories';

  	if((isset($orderby) AND $orderby != '') AND (isset($order) AND $order != '') AND (isset($filter) AND $filter != '')){

  		if($orderby == 'name'){

  			$all_staff = $wpdb->get_results("SELECT * FROM " . STAFF_DIRECTORY_TABLE . " WHERE `category` = $filter ORDER BY `name` $order");

  		}

  		if($orderby == 'category'){

  			$categories = $wpdb->get_results("SELECT * FROM $staff_directory_categories WHERE `cat_id` = $filter ORDER BY name $order");

  			foreach($categories as $category){
  				$cat_id = $category->cat_id;
  				//echo $cat_id;
  				$staff_by_cat = $wpdb->get_results("SELECT * FROM " . STAFF_DIRECTORY_TABLE . " WHERE `category` = $cat_id");
  				foreach($staff_by_cat as $staff){
  					$all_staff[] = $staff;
  				}
  			}
  		}

  		return $all_staff;


  	}elseif((isset($orderby) AND $orderby != '') AND (isset($order) AND $order != '')){

  		if($orderby == 'name'){

  			$all_staff = $wpdb->get_results("SELECT * FROM " . STAFF_DIRECTORY_TABLE . " ORDER BY `name` $order");

  		}

  		if($orderby == 'category'){

  			$all_staff = $wpdb->get_results("SELECT * FROM " . STAFF_DIRECTORY_TABLE . " ORDER BY category $order");

  		}


  		return $all_staff;

  	}elseif(isset($filter) AND $filter != ''){

  		$all_staff = $wpdb->get_results("SELECT * FROM " . STAFF_DIRECTORY_TABLE . " WHERE `category` = $filter");
  		if(isset($all_staff)){
  			return $all_staff;
  		}

  	}else{

  		return $wpdb->get_results("SELECT * FROM " . STAFF_DIRECTORY_TABLE);

  	}
  }

  static function import_old_staff() {
    global $wpdb;

    $old_categories_table = $wpdb->prefix . 'staff_directory_categories';
    $old_staff_directory_table = $wpdb->prefix . 'staff_directory';
    $old_templates_table = STAFF_TEMPLATES;

    #
    # Copy old categories over first
    #

    $old_staff_categories_sql = "
      SELECT
        cat_id, name

      FROM
        $old_categories_table
    ";

    $old_staff_categories = $wpdb->get_results($old_staff_categories_sql);

    foreach($old_staff_categories as $category) {
      wp_insert_term($category->name, 'staff_category');
    }

    #
    # Now copy old staff members over
    #

    $old_staff = StaffDirectory::get_old_staff();
    foreach ($old_staff as $staff) {
      $new_staff_array = array(
        'post_title'  => $staff->name,
        'post_content'  => $staff->bio,
        'post_type' => 'staff',
        'post_status' => 'publish'
      );
      $new_staff_post_id = wp_insert_post($new_staff_array);
      update_post_meta($new_staff_post_id, 'position', $staff->position);
      update_post_meta($new_staff_post_id, 'email', $staff->email_address);
      update_post_meta($new_staff_post_id, 'phone_number', $staff->phone_number);

      if (isset($staff->category)) {
        $old_category_sql = "
          SELECT
            cat_id, name

          FROM
            $old_categories_table

          WHERE
            cat_id=$staff->category
        ";
        $old_category = $wpdb->get_results($old_category_sql);
        $new_category = get_term_by('name', $old_category[0]->name, 'staff_category');
        wp_set_post_terms($new_staff_post_id, array($new_category->term_id), 'staff_category');
      }

      if (isset($staff->photo) && $staff->photo != '') {
        $upload_dir = wp_upload_dir();
        $upload_dir = $upload_dir['basedir'];
        $image_path = $upload_dir . '/staff-photos/' . $staff->photo;
        $filetype = wp_check_filetype($image_path);
        $attachment_id = wp_insert_attachment(array(
          'post_title' => $staff->photo,
          'post_content' => '',
          'post_status' => 'publish',
          'post_mime_type' => $filetype['type']
        ), $image_path, $new_staff_post_id);
        set_post_thumbnail($new_staff_post_id, $attachment_id);
      }
    }

    #
    # Now copy templates over
    #

    $old_html_template_sql = "
      SELECT
        template_code

      FROM
        $old_templates_table

      WHERE
        template_name='staff_index_html'
    ";
    $old_html_template_results = $wpdb->get_results($old_html_template_sql);
    update_option('staff_directory_html_template', $old_html_template_results[0]->template_code);

    $old_css_template_sql = "
      SELECT
        template_code

      FROM
        $old_templates_table

      WHERE
        template_name='staff_index_css'
    ";
    $old_css_template_results = $wpdb->get_results($old_css_template_sql);
    update_option('staff_directory_css_template', $old_css_template_results[0]->template_code);

    #
    # Now delete the old tables
    #

    $drop_tables_sql = "
      DROP TABLE
        $old_categories_table, $old_staff_directory_table, $old_templates_table
    ";
    $wpdb->get_results($drop_tables_sql);
  }

  static function init_tinymce_button() {
    if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') && get_user_option('rich_editing') == 'true')
         return;

    add_filter("mce_external_plugins", array('StaffDirectory', 'register_tinymce_plugin'));
    add_filter('mce_buttons', array('StaffDirectory', 'add_tinymce_button'));
  }

  static function register_tinymce_plugin($plugin_array) {
    $plugin_array['staff_directory_button'] = plugins_url('/../js/shortcode.js', __FILE__);;
    return $plugin_array;
  }

  static function add_tinymce_button($buttons) {
    $buttons[] = "staff_directory_button";
    return $buttons;
  }

  static function thickbox_ajax_form(){
    require_once(plugin_dir_path(__FILE__) . '/../views/shortcode_thickbox.php');
    exit;
  }
}
