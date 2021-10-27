<?php

namespace mark;

use WP_Query;

class MarkedPosts
{
  public $posts;

  public function __construct()
  {
    $this->posts = json_decode($_COOKIE["ky_post_ids"]);
  }

  public function hooks()
  {
    add_shortcode('marked_posts', [$this, 'marked_posts']);
  }

  public function marked_posts()
  {
    $query = new WP_Query([
      'post__in'  => $this->posts,
      'orderby' => 'date',
      'order' => 'DESC',
      'posts_per_page' => 99,
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
