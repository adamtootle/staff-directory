<p>
  <input type="radio" name="staff_templates[slug]" value="custom_<?php echo $template['index']; ?>"
  <?php checked( $current_template, 'custom_' . $template['index'], true ); ?> />
  Custom Template <?php echo $template['index']; ?> (<?php echo $template['slug']; ?>)
  <a href="#" class="fa fa-angle-down custom-template-dropdown-arrow"></a>
</p>

<div class="custom-template">
  <div class="staff-template-textarea-wrapper">
    <label for="custom_staff_templates[<?php echo $template['index']; ?>][html]">HTML:</label>
    <p>
      <textarea name="custom_staff_templates[<?php echo $template['index']; ?>][html]" class="large-text code"><?php echo html_entity_decode(stripslashes($template['html'])); ?></textarea>
    </p>
  </div>

  <div class="staff-template-textarea-wrapper">
    <label for="custom_staff_templates[<?php echo $template['index']; ?>][css]">CSS:</label>
    <p>
      <textarea name="custom_staff_templates[<?php echo $template['index']; ?>][css]" class="large-text code"><?php echo html_entity_decode(stripslashes($template['css'])); ?></textarea>
    </p>
  </div>

  <div class="clear"></div>

  <a href="#" class="delete-template" data-template-index="<?php echo $template['index']; ?>">Delete Custom Template <?php echo $template['index']; ?></a>

</div>

<div class="clear"></div>
