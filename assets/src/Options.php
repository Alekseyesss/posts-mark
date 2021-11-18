<?php

namespace mark;

class Options
{

  public $posts_list;
  public $checked_post_type = [];

  public function hooks()
  {
    add_action('wp_loaded', [$this, 'set_posts_list'], 10);
    add_action('admin_menu', [$this, 'add_settings']);
  }

  public function set_posts_list()
  {
    $post_types = get_post_types(
      [
        'public'   => true,
        '_builtin' => false
      ]
    );

    $post_types['post'] = 'post';
    $this->posts_list = $post_types;
  }

  public function add_settings()
  {
    // Generate Favorites admin page
    add_menu_page('Settings Favorites', 'Favorites', 'edit_pages', 'ky_favorites', [$this, 'favorites_options_page'], 'dashicons-heart', 81);
  }

  public function favorites_options_page()
  {
    // Generation of our admin page
?>
    <div class="wrap">
      <h2>
        <?php echo get_admin_page_title(); ?>
      </h2>

      <form action="" method="POST">
        <h2>Select the post types you want to add to favorites:</h2>
        <?php foreach ($this->posts_list as $element) :
          $this->manage_options($element); ?>
          <div style="margin-bottom: 14px">
            <label>
              <input type="checkbox" name="<?php echo $element; ?>" value="1" <?php checked(get_option($element, false)) ?>>
              <?php echo $element; ?>
            </label>
          </div>
        <?php endforeach; ?>

        <button type="submit" name="ky-post-btn" value="333">Submit</button>
      </form>
    </div>
<?php
  }

  public function manage_options($type)
  {
    if ($_POST[$type] == 1) {
      update_option();
      add_option($type, '1');
    } elseif ($_POST['ky-post-btn'] == 333) {
      delete_option($type);
    }
  }
}
