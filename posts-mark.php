<?php

/**
 * Adding posts to favorites.
 *
 * Plugin Name:         Mark posts
 * Description:         Plugin allows you to add posts to favorites.
 * Version:             1.0.1
 * Requires at least:   4.9
 * Requires PHP:        5.5
 * Author:              Kuryliuk
 * License:             MIT
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
  exit;
}

define('MARK_VERSION', '1.0.1');
define('MARK_PATH', plugin_dir_path(__FILE__));
define('MARK_URL', plugin_dir_url(__FILE__));

require_once MARK_PATH . 'assets/src/Mark.php';
require_once MARK_PATH . 'assets/src/MarkedPosts.php';

$ky_mark = new mark\Mark();
$ky_posts = new mark\MarkedPosts();
$ky_mark->hooks();
$ky_posts->hooks();
