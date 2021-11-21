<?php

namespace mark;

use WP_Query;

class MarkedPosts
{
  public $posts;

  public function __construct()
  {
    if (isset($_COOKIE['wp-ky-data'])) {
      $wp_ky_data = wp_unslash($_COOKIE['wp-ky-data']);
      $wp_ky_data = json_decode($wp_ky_data, true);
      $wp_ky_data = is_array($wp_ky_data) ? $wp_ky_data : [];
      $wp_ky_data = array_map('absint', $wp_ky_data);
      $wp_ky_data = array_filter($wp_ky_data);
    } else {
      $wp_ky_data = [];
    }
    $this->posts = $wp_ky_data;
  }

  public function hooks()
  {
    add_action('wp_enqueue_scripts', [$this, 'register_styles']);
    add_action('wp_enqueue_scripts', [$this, 'register_scripts']);
    add_shortcode('marked_posts', [$this, 'marked_posts']);
  }

  public function register_styles()
  {
    wp_register_style(
      'marked_posts',
      MARK_URL . '/assets/css/marked_posts.css',
      [],
      MARK_VERSION
    );
  }

  public function register_scripts()
  {
    wp_register_script(
      'marked_posts',
      MARK_URL . '/assets/js/marked_posts.js',
      [],
      MARK_VERSION,
      true
    );
  }

  public function marked_posts()
  {
    if (!$this->posts) {
      return '';
    }
    wp_enqueue_style('marked_posts');
    wp_enqueue_script('marked_posts');
    $query = new WP_Query([
      'post_type'    => 'any',
      'post__in'  => $this->posts,
      'orderby' => 'post__in',
      'posts_per_page' => 100,
      'post_status' => 'publish',
    ]);
    ob_start();

    if ($query->have_posts()) :
      echo '<ul class="ky-favorite-posts-list">';
      while ($query->have_posts()) :
        $query->the_post(); ?>

        <li class="ky-favorite-posts-item">
          <svg data-ky-post-id='<?php the_ID() ?>' class='favorite-trigger-list' version="1.1" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 32 32">
            <title>favorite</title>
            <path d="M23.6 2c-3.363 0-6.258 2.736-7.599 5.594-1.342-2.858-4.237-5.594-7.601-5.594-4.637 0-8.4 3.764-8.4 8.401 0 9.433 9.516 11.906 16.001 21.232 6.13-9.268 15.999-12.1 15.999-21.232 0-4.637-3.763-8.401-8.4-8.401z"></path>
          </svg>
          <a href="<?php the_permalink() ?>">
            <h3><?php echo esc_html(get_the_title()); ?></h3>
          </a>

        </li>
<?php
      endwhile;
      wp_reset_postdata();
      echo '</ul><button class="ky-clear-btn">Clear posts</button>';
    endif;

    return ob_get_clean();
  }
}
