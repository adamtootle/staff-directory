<?php

  $staff_settings = Staff_Directory_Settings::shared_instance();

?>

<style type="text/css">
  #staff-categories-wrapper,
  #staff-order-wrapper,
  #staff-template-wrapper {
    margin: 20px 0px;
  }
</style>

<div id="staff-categories-wrapper">
  <label for="staff-category">Staff Category</label>
  <select name="staff-category">
    <option value=''>-- Select Category --</option>
    <?php foreach(get_terms('staff_category') as $cat): ?>
      <option value="<?php echo $cat->term_id; ?>"><?php echo $cat->name; ?></option>
    <?php endforeach; ?>
  </select>
</div>

<div id="staff-order-wrapper">
  <label for="staff-order">Staff Order</label>
  <select name="staff-order">
    <option value=''>-- Use Default --</option>
    <option value="asc">Ascending</option>
    <option value="desc">Descending</option>
  </select>
</div>

<div id="staff-template-wrapper">
  <label for="staff-template">Staff Template</label>
  <select name="staff-template">
    <option value=''>-- Use Default --</option>
    <option value='list'>List</option>
    <option value='grid'>Grid</option>
    <?php foreach($staff_settings->get_custom_staff_templates() as $template): ?>
      <option value="<?php echo $template['slug'] ?>">Custom Template <?php echo $template['index']; ?></option>
    <?php endforeach; ?>
  </select>
</div>

<a href="javascript:StaffDirectory.formatShortCode();" class="button button-primary button-large">Insert Shortcode</a>