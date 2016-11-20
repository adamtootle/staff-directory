<style type="text/css">
  div.help-topic {
    margin-bottom: 40px;
  }
</style>

<div class="help-topic" id="staff-shortcodes">
  <h2>Shortcodes</h2>

  <p>
    Use the <code>[staff-directory]</code> shortcode in a post or page to display your staff.
  </p>

  <p>
    The following parameters are accepted:
    <ul>
      <li><code>id</code> - the ID for a single staff member. (Ex: [staff-directory id=4])</li>
      <li><code>cat</code> - possibly category IDs or slugs. (Ex: [staff-directory cat=1,4] or [staff-directory cat="administration"])</li>
      <li><code>cat_field</code> - used with cat. (Ex: [staff-directory cat="administration" cat_field="slug"])</li>
      <li><code>cat_relation</code> - used with cat and cat_field. Possible values are "OR" and "AND". (Ex: [staff-directory cat="administration,corporate" cat_relation="OR"])</li>
      <li><code>orderby</code> - the attribute to use for ordering. Supported values are 'name' and 'ID'. (Ex: [staff-directory orderby=name])</li>
      <li><code>order</code> - the order in which to arrange the staff members. Supported values are 'asc' and 'desc'. (Ex: [staff-directory orbder=asc])</li>
      <li><code>template</code> - the slug for the staff template to use. (Ex: [staff-directory template=custom_1])</li>
    </ul>
    Note - Ordering options can be viewed here - <a href="https://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters">https://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters</a>
  </p>
</div>

<div class="help-topic" id="staff-templates">
    <h2>Staff Directory Single Profile Template</h2>
    <aside>Note: The difference between the Single Profile Template and the Listing Templates - Listing Templates are used to display the list of staff members, while the Single Profile Template is the file used to format the single profile page (i.e. the page you see after clicking on a staff member).</aside>
    <p>
        By default, Staff Directory uses the single-staff.php file, located in the plugin's templates folder, to display individual profile data. But you may create your own templates if you wish.
    </p>
    <p>
        Warning: Do not edit the single-staff.php file directly. If you do, your changes will be overwritten when Staff Directory updates.
    </p>
    <p>
        To create a custom profile template, the easiest way would be to either copy your single.php file, or to copy the single-staff.php file. Once copied, place the file in your theme's template directory (for most themes this is simply your themes main folder), and then give the file any name you choose, making sure to prefix the name with 'single-staff-' ( i.e. single-staff-myCustomTemplate.php ). If you do not use the prefix 'single-staff-', Staff Directory will not recognize your template.
    </p>
</div>

<div class="help-topic" id="staff-templates">
  <h2>Staff Directory Listing Templates</h2>

  <p>
    The the <code>[staff-directory]</code> shortcode supports a default template (set in <a href="<?php echo get_admin_url(); ?>edit.php?post_type=staff&page=staff-directory-settings">Staff Settings</a>) or custom templates per use.
  </p>

  <p>
    Each template is identified by a slug. The provided templates are "List" and "Grid", with their slugs being "list" and "grid" respectively. Each custom template uses the slug format "custom_[n]" where [n] is the custom template ID.
    So to use "Custom Template 1" you would use the shortcode like so: <code>[staff-directory template=custom_1]</code>.
  </p>
</div>

<div class="help-topic" id="staff-template-tags">
  <h2>Staff Directory Template Tags</h2>

  <p>
    Custom Shortcodes are listed in the Custom Details Fields table on the <a href="<?php echo get_admin_url(); ?>edit.php?post_type=staff&page=staff-directory-settings">Staff Settings page</a>. All template shortcodes must be contained within the <code>[staff_loop]</code> shortcodes.
  </p>

  <p>
    Preformatted shortcodes are listed below. There were more options in this list previously, but due to the addition of the Custom Details Fields above some of them were removed from the suggestions. They will still work for now, but deprecated shortcodes are marked below and will no longer work at some point in the future.
  </p>

  <ul>
    <li><code>[photo_url]</code> - the url to the featured image for the staff member</li>
    <li><code>[photo]</code> - an &lt;img&gt; tag with the featured image for the staff member</li>
    <li><code>[name]</code> - the staff member's name</li>
    <li><code>[name_header]</code> - the staff member's name with &lt;h3&gt; tags</li>
    <li><code>[bio]</code> - the staff member's bio</li>
    <li><code>[bio_paragraph]</code> - the staff member's bio with &lt;p&gt; tags</li>
    <li><code>[category]</code> - the staff member's category (first category only)</li>
    <li><code>[category all=true]</code> - all of the staff member's categories in a comma-separated list</li>

    <li><code>[email_link]</code> (deprecated, requires an Email field above)</li>
    <li><code>[website_link]</code> (deprecated, requires a Website field above)</li>

    <li><code>[profile_link]</code> - wrapper or standalone - creates a link to the staff member's profile
        <ul style="text-indent:25px;">
            <li>Used as a wrapper: <code>[profile_link target="_self"] Some Content [/profile_link]</code></li>
            <li>Used standalone: <code>[profile_link inner_text="Some Text" target="_self"]</code></li>
            <li>Notice the 'inner_text' and 'target' attributes. 'inner_text' is only available for standalone profile_link tags, while target is available for either.</li>
        </ul>
    </li>

  </ul>
</div>

<div class="help-topic" id="staff-theme-tags">
  <h2>WordPress Theme Template Tag</h2>

  <p>
    This plugin previsouly supported a custom template function, but it's now
    recommended to use the following if you need to hardcode a staff directory
    into a template:
    <br />
    <code>&lt;?php echo do_shortcode( '[staff-directory]' ); ?&gt;</code>
  </p>
</div>
