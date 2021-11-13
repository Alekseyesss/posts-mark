<?php

namespace mark;

class Options
{
  public function hooks()
  {
    add_action('admin_menu', [$this, 'add_settings']);
    add_action('admin_init', [$this, 'favorite_settings']);
  }
  public function add_settings()
  {
    add_options_page('Settings Favorites', 'Favorites', 'edit_pages', 'ky_favorites', [$this, 'favorites_options_page']);
  }
  public function favorites_options_page()
  {
?>
    <div class="wrap">
      <h2><?php echo get_admin_page_title() ?></h2>

      <form action="options.php" method="POST">
        <?php
        settings_fields('favorite_group');
        do_settings_sections('favorites_page');
        submit_button();
        ?>
      </form>
    </div>
  <?php
  }
  public function favorite_settings()
  {
    register_setting('favorite_group', 'select_post_type', [$this, 'sanitize_callback']);

    add_settings_section('favorite_id', 'Main settings', '', 'favorites_page');

    add_settings_field('favorite_field1', 'Mark_post_type', [$this, 'fill_favorite_field1'], 'favorites_page', 'favorite_id');
  }
  public function fill_favorite_field1()
  {
    $val = get_option('option_name');
    $val = $val ? $val['checkbox'] : null;
  ?>
    <label><input type="checkbox" name="option_name[checkbox]" value="1" <?php checked(1, $val) ?> /> Mark</label>
<?php
  }
  public function sanitize_callback($options)
  {
    foreach ($options as $name => &$val) {
      if ($name == 'input')
        $val = strip_tags($val);

      if ($name == 'checkbox')
        $val = intval($val);
    }
    return $options;
  }
}
