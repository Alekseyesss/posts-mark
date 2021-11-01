<?php

namespace mark;

class Mark
{
  public $posts;

  public function __construct()
  {
    $obj = new MarkedPosts();
    $this->posts = $obj->posts;
  }

  public function hooks()
  {
    add_action('wp_enqueue_scripts', [$this, 'register_styles']);
    add_action('wp_enqueue_scripts', [$this, 'register_scripts']);
    add_shortcode('mark_content', [$this, 'mark']);
    add_shortcode('mark_excerpt', [$this, 'excerpt']);
  }
  public function mark()
  {
    wp_enqueue_style('ky_mark');
    wp_enqueue_script('ky_mark');
    add_filter('the_content', [$this, 'favorite_text']);
  }
  public function excerpt()
  {
    wp_enqueue_style('ky_mark');
    wp_enqueue_script('ky_mark');
    add_filter('the_excerpt', [$this, 'favorite_text']);
  }
  public function register_styles()
  {
    wp_register_style(
      'ky_mark',
      MARK_URL . '/assets/css/mark.css',
      [],
      MARK_VERSION
    );
  }

  public function register_scripts()
  {
    wp_register_script(
      'ky_mark',
      MARK_URL . '/assets/js/mark.js',
      [],
      MARK_VERSION,
      true
    );
  }
  public function favorite_text($text)
  {
    global $post;
    $id = $post->ID;
    $ky_favorite = '';
    if (in_array($id, $this->posts)) {
      $ky_favorite = 'ky-favorite';
    }
    ob_start();
?>
    <div>
      <svg data-ky-post-id='<?php echo $id ?>' class='favorite-trigger <?php echo $ky_favorite ?>' version="1.1" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 32 32">
        <title>favorite</title>
        <path d="M23.6 2c-3.363 0-6.258 2.736-7.599 5.594-1.342-2.858-4.237-5.594-7.601-5.594-4.637 0-8.4 3.764-8.4 8.401 0 9.433 9.516 11.906 16.001 21.232 6.13-9.268 15.999-12.1 15.999-21.232 0-4.637-3.763-8.401-8.4-8.401z"></path>
      </svg>
    </div> <?php echo $text ?>
<?php
    return ob_get_clean();
  }
}