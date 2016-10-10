<?php

class StaffDirectoryShortcode {

    public static $staff_query;

	static function register_shortcode() {
        //Main shortcode to initiate plugin
		add_shortcode( 'staff-directory', array( 'StaffDirectoryShortcode', 'shortcode' ) );

        //Shortcode to initiate the loop
        add_shortcode( 'staff_loop', array( 'StaffDirectoryShortcode', 'staff_loop_shortcode' ) );

        //List of predefined shortcode tags
        $predefined_shortcodes = array(
            'name',
            'name_header',
            'photo',
            'photo_url',
            'bio',
            'bio_paragraph',
            'category'
        );

        //Add shortcodes for all $predefined_shortcodes, link to function by
        //the name of $code_shortcode
        foreach($predefined_shortcodes as $code){
            add_shortcode( $code, array( 'StaffDirectoryShortcode', $code . '_shortcode' ) );
        }

        //Retrieve custom fields
        $staff_meta_fields = get_option( 'staff_meta_fields' );

        if ( !empty($staff_meta_fields) ) {
            foreach ( $staff_meta_fields as $field ) {
                $meta_key = $field['slug'];
                add_shortcode( $meta_key, array( 'StaffDirectoryShortcode', 'meta_shortcode' ) );
            }
        }
	}

    /*** Begin shortcode functions ***/

    static function meta_shortcode( $atts, $content = NULL, $tag) {
        $meta_key             = $tag;
        $meta_value           = get_post_meta( get_the_ID(), $meta_key, true );
        if($meta_value) {
            return $meta_value;
        } else {
            return ""; //print nothing and remove tag if no value
        }

    }

    static function staff_loop_shortcode( $atts, $content = NULL ) {

        $query = StaffDirectoryShortcode::$staff_query;
        $output = "";

        if ( $query->have_posts() ) {

            while ( $query->have_posts() ) {
                $query->the_post();
                $output .= do_shortcode($content);
            }

        }
        return $output;
    }

    static function name_shortcode(){
        return get_the_title();
    }

    static function name_header_shortcode(){
        return "<h3>" . self::name_shortcode() . "</h3>";
    }

    static function photo_url_shortcode(){
        if ( has_post_thumbnail() ) {
            $attachment_array = wp_get_attachment_image_src( get_post_thumbnail_id() );
            return $attachment_array[0];
        } else {
            return "";
        }
    }

    static function photo_shortcode(){
        if(!empty(self::photo_url_shortcode())){
            return '<img src="' . self::photo_url_shortcode() . '" />';
        } else {
            return "";
        }
    }

    static function bio_shortcode(){
        return get_the_content();
    }

    static function bio_paragraph_shortcode(){
        return "<p>" . self::bio_shortcode() . "</p>";
    }

    static function category_shortcode($atts){
        $atts = shortcode_atts( array(
            'all' => false,
        ), $atts);
        $staff_categories     = wp_get_post_terms( get_the_ID(), 'staff_category' );
        $all_staff_categories = "";

        if ( count( $staff_categories ) > 0 ) {
            $staff_category = $staff_categories[0]->name;
            foreach ( $staff_categories as $category ) {
                $all_staff_categories .= $category->name . ", ";
            }
            $all_staff_categories = substr( $all_staff_categories, 0, strlen( $all_staff_categories ) - 2 );
        } else {
            $staff_category = "";
        }

        if( $atts['all'] === true ) {
            return $all_staff_categories;
        } else {
            return $staff_category;
        }

    }

	static function shortcode( $params ) {
		extract( shortcode_atts( array(
			'id'       => '',
			'cat'      => '',
			'orderby'  => '',
			'order'    => '',
			'meta_key' => ''
		), $params ) );

		$output = '';

		$staff_settings = StaffSettings::sharedInstance();
		if ( isset( $params['template'] ) ) {
			$template = $params['template'];
		} else {
			$template = $staff_settings->getCurrentDefaultStaffTemplate();
		}

		// get all staff
		$param = "id=$id&cat=$cat&orderby=$orderby&order=$order&meta_key=$meta_key";

		return StaffDirectoryShortcode::show_staff_directory( $param, $template );
	}

    /*** End shortcode functions ***/

	static function show_staff_directory( $param = null, $template = null ) {
		parse_str( $param );
		global $wpdb;

		// make sure we aren't calling both id and cat at the same time
		if ( isset( $id ) && $id != '' && isset( $cat ) && $cat != '' ) {
			return "<strong>ERROR: You cannot set both a single ID and a category ID for your Staff Directory</strong>";
		}

		$query_args = array(
			'post_type'      => 'staff',
			'posts_per_page' => - 1
		);

		// check if it's a single staff member first, since single members won't be ordered
		if ( ( isset( $id ) && $id != '' ) && ( ! isset( $cat ) || $cat == '' ) ) {
			$query_args['p'] = $id;
		}
		// ends single staff

		// check if we're returning a staff category
		if ( ( isset( $cat ) && $cat != '' ) && ( ! isset( $id ) || $id == '' ) ) {
			$query_args['tax_query'] = array(
				array(
					'taxonomy' => 'staff_category',
					'terms'    => array( $cat )
				)
			);
		}

		if ( isset( $orderby ) && $orderby != '' ) {
			$query_args['orderby'] = $orderby;
		}
		if ( isset( $order ) && $order != '' ) {
			$query_args['order'] = $order;
		}
		if ( isset( $meta_key ) && $meta_key != '' ) {
			$query_args['meta_key'] = $meta_key;
		}

        //Store in class scope so we can access query from staff_loop shortcode
		StaffDirectoryShortcode::$staff_query = new WP_Query( $query_args );

		$output = self::retrieve_template_html($template);

        wp_reset_query();

		return $output;
	}

    static function retrieve_template_html($slug) {

        // $slug => 'File Name'
        $template_slugs = array(
            'grid' => 'staff_grid.php',
            'list' => 'staff_list.php'
        );

        $cur_template = $template_slugs[$slug];

        if ($cur_template) {
            $template_contents = file_get_contents( STAFF_LIST_TEMPLATES . $cur_template);
            return do_shortcode($template_contents);
        } else {
            echo $slug;
        }

    }

	static function html_for_custom_template( $template_slug, $wp_query ) {
		$staff_settings = StaffSettings::sharedInstance();

		$output = '';

		$template      = $staff_settings->getCustomStaffTemplateForSlug( $template_slug );
		$template_html = stripslashes( $template['html'] );
		$template_css  = stripslashes( $template['css'] );

		$output .= "<style type=\"text/css\">$template_css</style>";

		if ( strpos( $template_html, '[staff_loop]' ) ) {
			$before_loop_markup = substr( $template_html, 0, strpos( $template_html, "[staff_loop]" ) );
			$after_loop_markup  = substr( $template_html, strpos( $template_html, "[/staff_loop]" ) + strlen( "[/staff_loop]" ), strlen( $template_html ) - strpos( $template_html, "[/staff_loop]" ) );
			$loop_markup        = str_replace( "[staff_loop]", "", substr( $template_html, strpos( $template_html, "[staff_loop]" ), strpos( $template_html, "[/staff_loop]" ) - strpos( $template_html, "[staff_loop]" ) ) );
			$output .= $before_loop_markup;
		} else {
			$loop_markup = $template_html;
		}

		if ( isset( $after_loop_markup ) ) {
			$output .= $after_loop_markup;
		}

		return $output;
	}
}
