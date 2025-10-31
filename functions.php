
<?php
/**
 * Minimal functions for 3000 Studios theme - basic enqueue
 */

if ( ! defined('ABSPATH') ) { exit; }

function studios_enqueue_assets() {
  // Main stylesheet
  wp_enqueue_style('3000studios-style', get_stylesheet_uri());

  // Additional theme CSS scaffold (ripples, cursor, mobile tweaks)
  if ( file_exists( get_template_directory() . '/assets/css/theme.css' ) ) {
    wp_enqueue_style('3000studios-theme', get_template_directory_uri() . '/assets/css/theme.css', array('3000studios-style'), '1.0.0');
  }

  // Howler for audio handling (optional)
  wp_enqueue_script('howler-cdn', 'https://cdnjs.cloudflare.com/ajax/libs/howler/2.2.3/howler.min.js', array(), '2.2.3', true);

  // Main javascript (page features)
  wp_enqueue_script('3000studios-main', get_template_directory_uri().'/js/main.js', array(), '1.1', true);

  // Theme behaviour script (cursor, ripples, audio helpers)
  if ( file_exists( get_template_directory() . '/assets/js/theme.js' ) ) {
    wp_enqueue_script('3000studios-theme-js', get_template_directory_uri() . '/assets/js/theme.js', array('3000studios-main','howler-cdn'), '1.0.0', true);
  }
}
add_action('wp_enqueue_scripts', 'studios_enqueue_assets');

// Register navigation menus
register_nav_menus(array(
    'primary' => __('Primary Menu','threek')
));

