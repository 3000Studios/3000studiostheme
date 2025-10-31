
<?php
/**
 * 3000 Studios Theme - Functions & Configuration
 * 
 * @package     3000Studios
 * @author      Mr. jwswain
 * @copyright   Copyright (c) 2025, Mr. jwswain & 3000 Studios
 * @license     Proprietary - All Rights Reserved
 * @link        https://3000studios.com
 * @since       1.0.0
 * 
 * ⚠️ COPYRIGHT NOTICE:
 * This code is protected by U.S. and international copyright law.
 * 
 * ALL RIGHTS RESERVED. No part of this code may be reproduced,
 * distributed, or transmitted in any form or by any means, including
 * photocopying, recording, or other electronic or mechanical methods,
 * without the prior written permission of Mr. jwswain & 3000 Studios.
 * 
 * Unauthorized use, copying, modification, or distribution of this
 * code is STRICTLY PROHIBITED and will result in severe civil and
 * criminal penalties. Violators will be prosecuted to the maximum
 * extent possible under the law.
 * 
 * For licensing information, contact: https://3000studios.com
 * 
 * © 2025 Mr. jwswain & 3000 Studios. All Rights Reserved.
 */

if ( ! defined('ABSPATH') ) { exit; } // Prevent direct access

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

// Load AI Intelligence Systems
require_once get_template_directory() . '/includes/ai-learning.php';
require_once get_template_directory() . '/includes/wp-intelligence.php';

// Initialize AI database tables
add_action('after_switch_theme', 'studios_ai_create_tables');

