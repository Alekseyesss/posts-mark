<?php

namespace mark;

class Options
{

  public $posts_list;

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
        <?php foreach ($this->posts_list as $post_type) :
          //check submit used
          if ($_POST['ky-post-btn'] == 333) {
            $this->manage_options($post_type);
          }
        ?>
          <div style="margin-bottom: 12px">
            <label>
              <?php
              $ky_option = get_option('ky_option', []);
              if (array_key_exists($post_type, $ky_option)) {
                $ky_checked = 1;
              } else {
                $ky_checked = 0;
              } ?>
              <input type="checkbox" name="<?php echo $post_type; ?>" value="1" <?php checked($ky_checked) ?>>
              <?php echo $post_type; ?>
            </label>
          </div>
        <?php endforeach; ?>

        <button type="submit" name="ky-post-btn" value="333">Submit</button>
      </form>
    </div>
<?php
  }

  // manage_options() - function creates or edits an option with an array of checked post types
  // $post_type - one post type from the list of post types
  // $ky_option - option with an array in which we put checked post types
  // $submit_checked - boolean value marked post or not
  public function manage_options($post_type)
  {
    $submit_checked = $_POST[$post_type] == 1;
    $ky_option = get_option('ky_option', false);

    // create an option if it was not created. If the post type has been marked, add to the option.
    if (!$ky_option && $submit_checked) {
      update_option('ky_option', [$post_type => 1]);
      return;
    } elseif (!$ky_option && !$submit_checked) {
      return;
    }

    // edit the option depending on whether the post type is checked or not
    $key_exists = array_key_exists($post_type, $ky_option);
    if ($submit_checked && $key_exists) {
      return;
    } elseif ($submit_checked && !$key_exists) {
      $ky_option[$post_type] = 1;
      update_option('ky_option', $ky_option);
      return;
    } elseif (!$submit_checked && !$key_exists) {
      return;
    } elseif (!$submit_checked && $key_exists) {
      unset($ky_option[$post_type]);
      update_option('ky_option', $ky_option);
      return;
    }

    var_dump(get_option('ky_option'));
  }
}
