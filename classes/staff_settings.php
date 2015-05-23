<?php

class StaffSettings {
  public static function sharedInstance()
  {
    static $shared_instance = NULL;
    if ($shared_instance === NULL) {
      $shared_instance = new static();
    }
    return $shared_instance;
  }

  public static function setupDefaults() {
    $staff_settings = StaffSettings::sharedInstance();

    $current_template_slug = $staff_settings->getCurrentDefaultStaffTemplate();
    if($current_template_slug == '' || $current_template_slug == NULL) {
      
      $staff_settings->updateDefaultStaffTemplateSlug('list');

    } else if ($current_template_slug == 'custom' || get_option('staff_directory_html_template', '') != '') {

      $templates_array = array();
      $templates_array[] = array(
        'html' => get_option('staff_directory_html_template'),
        'css' => get_option('staff_directory_css_template')
      );
      $staff_settings->updateCustomStaffTemplates($templates_array);
      $staff_settings->updateDefaultStaffTemplateSlug('custom_1');

      delete_option('staff_directory_html_template');
      delete_option('staff_directory_css_template');
      
    }
  }

  #
  # setters
  #

  public function updateDefaultStaffTemplateSlug($slug = 'list') {
    update_option('staff_directory_template_slug', $slug);
  }

  public function updateCustomStaffTemplates($templates = array()) {
    $updated_templates_array = array();
    $index = 1;
    foreach($templates as $template) {
      if($template['html'] != '' || $template['css'] != '') {
        $template['index'] = $index;
        $template['slug'] = 'custom_' . $index;
        $updated_templates_array[] = $template;
        $index++;
      }
    }
    update_option('staff_directory_custom_templates', $updated_templates_array);
  }

  public function updateCustomStaffMetaFields($labels = array(), $types = array()) {
    $index = 0;
    $meta_fields_array = array();
    foreach($labels as $meta_label) {
      $slug = strtolower($meta_label);
      $slug = str_replace(' ', '_', $slug);
      if($meta_label != '') {
        $meta_fields_array[] = array(
          'name' => $meta_label,
          'slug' => $slug,
          'type' => $types[$index]
        );
      }
      $index++;
    }
    update_option('staff_meta_fields', $meta_fields_array);
  }

  #
  # getters
  #

  public function getCurrentDefaultStaffTemplate() {
    $current_template = get_option('staff_directory_template_slug');

    if($current_template == '' && get_option('staff_directory_html_template') != '') {
      update_option('staff_directory_template_slug', 'custom');
      $current_template = 'custom';
    } else if($current_template == '') {
      update_option('staff_directory_template_slug', 'list');
      $current_template = 'list';
    }

    return $current_template;
  }

  public function getCustomStaffTemplates() {
    return get_option('staff_directory_custom_templates', array());
  }

  public function getCustomStaffTemplateForSlug($slug = '') {
    $templates = $this->getCustomStaffTemplates();
    foreach($templates as $template) {
      if($template['slug'] == $slug) {
        return $template;
      }
    }
  }

  public function getStaffDetailsFields() {
    return get_option('staff_meta_fields', array());
  }

  #
  # delete functions
  #

  public function deleteCustomTemplate($index = NULL) {
    if($index != NULL) {
      $custom_templates = $this->getCustomStaffTemplates();
      $new_custom_templates == array();
      foreach($custom_templates as $template) {
        if($template['index'] != $index) {
          $new_custom_templates[] = $template;
        }
      }
      $this->updateCustomStaffTemplates($new_custom_templates);
    }
  }
}