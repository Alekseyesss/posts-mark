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
        <?php

        if (isset($_POST["ky-post-btn"]) && $_POST["ky-post-btn"] == 333) {
          check_admin_referer("ky_shield", "shield_nonce");
        }
        $ky_option = get_option("ky_option", []);

        foreach ($this->posts_list as $post_type) :
          //check submit used
          if (isset($_POST["ky-post-btn"]) && $_POST["ky-post-btn"] == 333) {
            $this->manage_options($post_type, $ky_option);
          }
        ?>
          <div style="margin-bottom: 12px">
            <label>
              <?php
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
        <?php wp_nonce_field("ky_shield", "shield_nonce"); ?>
      </form>
    </div>
<?php
  }

  // manage_options() - function creates or edits an option with an array of checked post types
  // $post_type - one post type from the list of post types
  // $ky_option - option with an array in which we put checked post types
  // $submit_checked - boolean value marked post or not
  // $key_exists - boolean value is the current post type in the array or not
  public function manage_options($post_type, &$ky_option)
  {
    if (isset($_POST[$post_type])) {
      $submit_checked = $_POST[$post_type] == 1;
    } else {
      $submit_checked = false;
    }

    $key_exists = array_key_exists($post_type, $ky_option);

    if ($submit_checked && !$key_exists) {
      $ky_option[$post_type] = 1;
      update_option('ky_option', $ky_option);
      return;
    } elseif (!$submit_checked && $key_exists) {
      unset($ky_option[$post_type]);
      update_option('ky_option', $ky_option);
      return;
    }
  }
}
