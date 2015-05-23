<p>
  <?php if($current_template == 'custom_' . $template['index']): ?>
    <input type="radio" name="staff_templates[slug]" value="custom_<?php echo $template['index']; ?>" checked />
  <?php else: ?>
    <input type="radio" name="staff_templates[slug]" value="custom_<?php echo $template['index']; ?>">
  <?php endif; ?>
  Custom Template <?php echo $template['index']; ?> <a href="#" class="fa fa-angle-down custom-template-dropdown-arrow"></a>
</p>

<div class="custom-template">
  <div class="staff-template-textarea-wrapper">
    <label for="custom_staff_templates[<?php echo $template['index']; ?>][html]">HTML:</label>
    <p>
      <textarea name="custom_staff_templates[<?php echo $template['index']; ?>][html]" class="large-text code"><?php echo stripslashes($template['html']); ?></textarea>
    </p>
  </div>

  <div class="staff-template-textarea-wrapper">
    <label for="custom_staff_templates[<?php echo $template['index']; ?>][css]">CSS:</label>
    <p>
      <textarea name="custom_staff_templates[<?php echo $template['index']; ?>][css]" class="large-text code"><?php echo stripslashes($template['css']); ?></textarea>
    </p>
  </div>

  <div class="clear"></div>

  <a href="#" class="delete-template" data-template-index="<?php echo $template['index']; ?>">Delete Custom Template <?php echo $template['index']; ?></a>

</div>

<div class="clear"></div>