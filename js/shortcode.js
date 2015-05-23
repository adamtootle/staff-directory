jQuery(document).ready(function($) {
  tinymce.create('tinymce.plugins.staff_directory_shortcode_plugin', {
    init : function(ed, url) {
      ed.addCommand('staff_directory_insert_shortcode', function() {
        tb_show('Staff Directory Shortcode Options', 'admin-ajax.php?action=get_my_form');
      });
      ed.addButton('staff_directory_button', {title : 'Insert Staff Directory Shortcode', cmd : 'staff_directory_insert_shortcode', image: url + '/../images/wp-editor-icon.png' });
    },
  });
  tinymce.PluginManager.add('staff_directory_button', tinymce.plugins.staff_directory_shortcode_plugin);
});

StaffDirectory = {
  formatShortCode: function(){
    var categoryVal = jQuery('[name="staff-category"]').val();
    var orderVal = jQuery('[name="staff-order"]').val();
    var templateVal = jQuery('[name="staff-template"]').val();
    
    var shortcode = '[staff-directory';

    if(categoryVal != '') {
      shortcode += ' cat=' + categoryVal;
    }

    if(orderVal != '') {
      shortcode += ' order=' + orderVal;
    }

    if(templateVal != '') {
      shortcode += ' template=' + templateVal;
    }

    shortcode += ']';
    
    tinymce.execCommand('mceInsertContent', false, shortcode);
    tb_remove();
  }
};