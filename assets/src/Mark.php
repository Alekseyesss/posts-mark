<?php

namespace mark;

class Mark
{
  public function hooks()
  {
    add_action('wp_enqueue_scripts', [$this, 'register_styles']);
    add_action('wp_enqueue_scripts', [$this, 'register_scripts']);
    add_shortcode('mark', [$this, 'mark']);
  }
  public function mark()
  {
    wp_enqueue_style('ky_mark');
    wp_enqueue_script('ky_mark');
    add_filter('the_content', [$this, 'favorite_content']);
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
  public function favorite_content($content)
  {
    global $post;
    $id = $post->ID;
    return "<p data-ky-post-id='$id' class='favorite-trigger'>Favorite</p>" . $content;
  }
}
