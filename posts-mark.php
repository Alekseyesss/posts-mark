<?php

/**
 * Adding posts to favorites.
 *
 * Plugin Name:         Simple favorite posts
 * Description:         Plugin allows you to add posts to favorites.
 * Version:             1.0.1
 * Requires at least:   4.9
 * Requires PHP:        5.5
 * Author:              Kuryliuk Oleksii
 * Author URI:          http://anna.h67993fh.beget.tech/
 * License:             MIT
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
  exit;
}

define('MARK_VERSION', '1.0.1');
define('MARK_PATH', plugin_dir_path(__FILE__));
define('MARK_URL', plugin_dir_url(__FILE__));

require_once MARK_PATH . 'assets/src/Options.php';
require_once MARK_PATH . 'assets/src/Mark.php';
require_once MARK_PATH . 'assets/src/MarkedPosts.php';

$ky_options = new mark\Options();
$ky_mark = new mark\Mark();
$ky_posts = new mark\MarkedPosts();
$ky_options->hooks();
$ky_mark->hooks();
$ky_posts->hooks();

register_deactivation_hook(__FILE__, 'favorite_delete_option');
function favorite_delete_option()
{
  delete_option('ky_option');
}