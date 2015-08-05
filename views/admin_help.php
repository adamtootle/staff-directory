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
      <li><code>cat</code> - the staff category ID to use. (Ex: [staff-directory cat=1])</li>
      <li><code>id</code> - the ID for a single staff member. (Ex: [staff-directory id=4])</li>
      <li><code>orderby</code> - the attribute to use for ordering. Supported values are 'name' and 'ID'. (Ex: [staff-directory orderby=name])</li>
      <li><code>order</code> - the order in which to arrange the staff members. Supported values are 'asc' and 'desc'. (Ex: [staff-directory orbder=asc])</li>
    </ul>
    Note - Ordering options can be viewed here - <a href="https://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters">https://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters</a>
  </p>
</div>

<div class="help-topic" id="staff-templates">
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
    <li><code>[email_link]</code> (deprecated, requires and Email field above)</li>
    <li><code>[website_link]</code> (deprecated, requires a Website field above)</li>
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
