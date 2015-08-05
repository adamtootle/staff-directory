<?php

class StaffDirectoryShortcode {
  static function register_shortcode() {
    add_shortcode('staff-directory', array('StaffDirectoryShortcode', 'shortcode'));
  }

  static function shortcode($params) {
    extract(shortcode_atts(array(
  		'id' => '',
  		'cat' => '',
  		'orderby' => '',
  		'order' => ''
  	), $params));

  	$output = '';

    $staff_settings = StaffSettings::sharedInstance();
    if(isset($params['template'])) {
      $template = $params['template'];
    } else {
      $template = $staff_settings->getCurrentDefaultStaffTemplate();
    }

  	// get all staff
  	$param = "id=$id&cat=$cat&orderby=$orderby&order=$order";
  	return StaffDirectoryShortcode::show_staff_directory($param, $template);
  }

  static function show_staff_directory($param = null, $template = NULL){
  	parse_str($param);
  	global $wpdb;

  	// make sure we aren't calling both id and cat at the same time
  	if(isset($id) && $id != '' && isset($cat) && $cat != ''){
  		return "<strong>ERROR: You cannot set both a single ID and a category ID for your Staff Directory</strong>";
  	}

    $query_args = array(
      'post_type' => 'staff',
      'posts_per_page' => -1
    );

  	// check if it's a single staff member first, since single members won't be ordered
  	if((isset($id) && $id != '') && (!isset($cat) || $cat == '')){
      $query_args['p'] = $id;
  	}
  	// ends single staff

  	// check if we're returning a staff category
  	if((isset($cat) && $cat != '') && (!isset($id) || $id == '')){
  		$query_args['tax_query'] = array(
        array(
          'taxonomy' => 'staff_category',
          'terms' => array($cat)
        )
      );
  	}

    if(isset($orderby) && $orderby != ''){
      $query_args['orderby'] = $orderby;
    }
    if(isset($order) && $order != ''){
      $query_args['order'] = $order;
    }

    $staff_query = new WP_Query($query_args);

    switch($template){
      case 'list':
        $output = StaffDirectoryShortcode::html_for_list_template($staff_query);
        break;
      case 'grid':
        $output = StaffDirectoryShortcode::html_for_grid_template($staff_query);
        break;
      default:
        $output = StaffDirectoryShortcode::html_for_custom_template($template, $staff_query);
        break;

    }

    wp_reset_query();

  	return $output;
  }

  static function html_for_list_template($wp_query) {
    $output = <<<EOT
      <style type="text/css">
        .clearfix {
          clear: both;
        }
        .single-staff {
          margin-bottom: 50px;
        }
        .single-staff .photo {
          float: left;
          margin-right: 15px;
        }
        .single-staff .photo img {
          max-width: 100px;
          height: auto;
        }
        .single-staff .name {
          font-size: 1em;
          line-height: 1em;
          margin-bottom: 4px;
        }
        .single-staff .position {
          font-size: .9em;
          line-height: .9em;
          margin-bottom: 10px;
        }
        .single-staff .bio {
          margin-bottom: 8px;
        }
        .single-staff .email {
          font-size: .9em;
          line-height: .9em;
          margin-bottom: 10px;
        }
        .single-staff .phone {
          font-size: .9em;
          line-height: .9em;
        }
        .single-staff .website {
          font-size: .9em;
          line-height: .9em;
        }
      </style>
      <div id="staff-directory-wrapper">
EOT;
    while($wp_query->have_posts()) {
      $wp_query->the_post();

      $name = get_the_title();
      $position = get_post_meta(get_the_ID(), 'position', true);
      $bio = get_the_content();

      if(has_post_thumbnail()) {
        $attachment_array = wp_get_attachment_image_src(get_post_thumbnail_id());
        $photo_url = $attachment_array[0];
        $photo_html = '<div class="photo"><img src="' . $photo_url . '" /></div>';
      } else {
        $photo_html = '';
      }

      if(get_post_meta(get_the_ID(), 'email', true) != '') {
        $email = get_post_meta(get_the_ID(), 'email', true);
        $email_html = '<div class="email">Email: <a href="mailto:' . $email . '">' . $email . '</a></div>';
      } else {
        $email_html = '';
      }

      if(get_post_meta(get_the_ID(), 'phone', true) != '') {
        $phone_html = '<div class="phone">Phone: ' . get_post_meta(get_the_ID(), 'phone', true) . '</div>';
      } else {
        $phone_html = '';
      }

      if(get_post_meta(get_the_ID(), 'website', true) != '') {
        $website = get_post_meta(get_the_ID(), 'website', true);
        $website_html = '<div class="website">Website: <a href="' . $website . '">' . $website . '</a></div>';
      } else {
        $website_html = '';
      }

      $output .= <<<EOT
        <div class="single-staff">
          $photo_html
          <div class="name">$name</div>
          <div class="position">$position</div>
          <div class="bio">$bio</div>
          $email_html
          $phone_html
          $website_html
          <div class="clearfix"></div>
        </div>
EOT;
    }
    $output .= "</div>";
    return $output;
  }

  static function html_for_grid_template($wp_query) {
    $output = <<<EOT
      <style type="text/css">
        .clearfix {
          clear: both;
        }
        .single-staff {
          float: left;
          width: 25%;
          text-align: center;
          padding: 0px 10px;
        }
        .single-staff .photo {
          margin-bottom: 5px;
        }
        .single-staff .photo img {
          max-width: 100px;
          height: auto;
        }
        .single-staff .name {
          font-size: 1em;
          line-height: 1em;
          margin-bottom: 4px;
        }
        .single-staff .position {
          font-size: .9em;
          line-height: .9em;
          margin-bottom: 10px;
        }
      </style>
      <div id="staff-directory-wrapper">
EOT;
    while($wp_query->have_posts()) {
      $wp_query->the_post();

      $name = get_the_title();
      $position = get_post_meta(get_the_ID(), 'position', true);

      if(has_post_thumbnail()) {
        $attachment_array = wp_get_attachment_image_src(get_post_thumbnail_id());
        $photo_url = $attachment_array[0];
        $photo_html = '<div class="photo"><img src="' . $photo_url . '" /></div>';
      } else {
        $photo_html = '';
      }

      $output .= <<<EOT
        <div class="single-staff">
          $photo_html
          <div class="name">$name</div>
          <div class="position">$position</div>
        </div>
EOT;
    }
    $output .= "</div>";
    return $output;
  }

  static function html_for_custom_template($template_slug, $wp_query) {
    $staff_settings = StaffSettings::sharedInstance();

    $output = '';

    $template = $staff_settings->getCustomStaffTemplateForSlug($template_slug);
    $template_html = stripslashes($template['html']);
  	$template_css = stripslashes($template['css']);

  	$output .= "<style type=\"text/css\">$template_css</style>";

    if(strpos($template_html, '[staff_loop]')) {
      $before_loop_markup = substr($template_html, 0, strpos($template_html, "[staff_loop]"));
      $after_loop_markup = substr($template_html, strpos($template_html, "[/staff_loop]") + strlen("[/staff_loop]"), strlen($template_html) - strpos($template_html, "[/staff_loop]"));
      $loop_markup = str_replace("[staff_loop]", "", substr($template_html, strpos($template_html, "[staff_loop]"), strpos($template_html, "[/staff_loop]") - strpos($template_html, "[staff_loop]")));
      $output .= $before_loop_markup;
    } else {
      $loop_markup = $template_html;
    }

    while($wp_query->have_posts()) {
      $wp_query->the_post();

      $staff_name = get_the_title();
      if (has_post_thumbnail()) {
        $attachment_array = wp_get_attachment_image_src(get_post_thumbnail_id());
        $photo_url = $attachment_array[0];
        $photo_tag = '<img src="' . $photo_url . '" />';
      } else {
        $photo_url = "";
        $photo_tag = "";
      }

      $staff_email = get_post_meta(get_the_ID(), 'email', true);
      $staff_email_link = $staff_email != '' ? "<a href=\"mailto:$staff_email\">Email $staff_name</a>" : "";
      $staff_phone_number = get_post_meta(get_the_ID(), 'phone_number', true);
      $staff_bio = get_the_content();
      $staff_website = get_post_meta(get_the_ID(), 'website', true);
      $staff_website_link = $staff_website != '' ? "<a href=\"$staff_website\" target=\"_blank\">View website</a>" : "";

      $staff_categories = wp_get_post_terms(get_the_ID(), 'staff_category');
      $all_staff_categories = "";

      if (count($staff_categories) > 0) {
        $staff_category = $staff_categories[0]->name;
        foreach($staff_categories as $category) {
          $all_staff_categories .= $category->name . ", ";
        }
        $all_staff_categories = substr($all_staff_categories, 0, strlen($all_staff_categories) - 2);
      } else {
        $staff_category = "";
      }

      $accepted_single_tags = array("[name]", "[photo_url]", "[bio]", "[category]", "[category all=true]");
  		$replace_single_values = array($staff_name, $photo_url, $staff_bio, $staff_category, $all_staff_categories);

  		$accepted_formatted_tags = array("[name_header]", "[photo]", "[email_link]", "[bio_paragraph]", "[website_link]");
  		$replace_formatted_values = array("<h3>$staff_name</h3>", $photo_tag, $staff_email_link, "<p>$staff_bio</p>", $staff_website_link);

  		$current_staff_markup = str_replace($accepted_single_tags, $replace_single_values, $loop_markup);
  		$current_staff_markup = str_replace($accepted_formatted_tags, $replace_formatted_values, $current_staff_markup);

      preg_match_all("/\[(.*?)\]/", $current_staff_markup, $other_matches);
      $staff_meta_fields = get_option('staff_meta_fields');

      if($staff_meta_fields != '' && count($other_matches[0]) > 0) {
        foreach($other_matches[0] as $match) {
          foreach($staff_meta_fields as $field) {
            $meta_key = $field['slug'];
            $shortcode_without_brackets = substr($match, 1, strlen($match) - 2);
            if($meta_key == $shortcode_without_brackets) {
              $meta_value = get_post_meta(get_the_ID(), $meta_key, true);
              $current_staff_markup = str_replace($match, $meta_value, $current_staff_markup);
            }
          }
        }
      }

  		$output .= $current_staff_markup;
    }

    if(isset($after_loop_markup)) {
      $output .= $after_loop_markup;
    }

    return $output;
  }
}
