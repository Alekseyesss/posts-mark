<?php

namespace mark;

use WP_Query;

class MarkedPosts
{
  public $posts;

  public function __construct()
  {
    $wp_ky_data = wp_unslash($_COOKIE['wp-ky-data']);
    $wp_ky_data = json_decode($wp_ky_data, true);
    $wp_ky_data = is_array($wp_ky_data) ? $wp_ky_data : [];
    $wp_ky_data = array_map('absint', $wp_ky_data);
    $wp_ky_data = array_filter($wp_ky_data);
    $this->posts = $wp_ky_data;
  }

  public function hooks()
  {
    add_shortcode('marked_posts', [$this, 'marked_posts']);
  }

  public function marked_posts()
  {
    if (!$this->posts) {
      return '';
    }
    $query = new WP_Query([
      'post_type'    => 'any',
      'post__in'  => $this->posts,
      'orderby' => 'post__in',
      'posts_per_page' => 100,
      'post_status' => 'publish',
    ]);
    ob_start();
    if ($query->have_posts()) :
      while ($query->have_posts()) :
        $query->the_post();

        the_title();
        echo '<br>';
        the_content();
        echo '<hr>';

      endwhile;
      wp_reset_postdata();
    endif;
    return ob_get_clean();
  }
}
