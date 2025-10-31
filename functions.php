
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
require_once get_template_directory() . '/includes/api-settings.php';
require_once get_template_directory() . '/includes/api-connector.php';

// Initialize AI database tables
add_action('after_switch_theme', 'studios_ai_create_tables');

/**
 * AJAX Handler: Preview AI Command
 */
add_action('wp_ajax_studios_preview_command', 'studios_ajax_preview_command');
function studios_ajax_preview_command() {
    check_ajax_referer('studios_ai_nonce', 'nonce');
    
    $command = sanitize_text_field($_POST['command'] ?? '');
    $page_id = intval($_POST['page_id'] ?? 0);
    
    if (empty($command)) {
        wp_send_json_error(['message' => 'Command is required']);
    }
    
    // Get page context
    $wp_intel = new Studios_WP_Intelligence();
    $page_structure = $wp_intel->analyze_page($page_id);
    
    // Process with AI
    $parsed = Studios_API_Connector::process_command_with_ai($command, $page_structure);
    
    // Log command
    $ai_learning = new Studios_AI_Learning();
    $ai_learning->log_command(
        $command,
        $parsed['action'] ?? 'unknown',
        $page_id,
        $parsed['target'] ?? '',
        wp_json_encode($parsed),
        false, // Not executed yet
        0
    );
    
    wp_send_json_success([
        'parsed' => $parsed,
        'page_structure' => $page_structure,
        'preview' => "Preview: Would perform '{$parsed['action']}' on page #{$page_id}"
    ]);
}

/**
 * AJAX Handler: Execute AI Command
 */
add_action('wp_ajax_studios_execute_command', 'studios_ajax_execute_command');
function studios_ajax_execute_command() {
    check_ajax_referer('studios_ai_nonce', 'nonce');
    
    $command = sanitize_text_field($_POST['command'] ?? '');
    $page_id = intval($_POST['page_id'] ?? 0);
    
    if (empty($command) || empty($page_id)) {
        wp_send_json_error(['message' => 'Command and page ID are required']);
    }
    
    $start_time = microtime(true);
    
    // Process with AI
    $wp_intel = new Studios_WP_Intelligence();
    $page_structure = $wp_intel->analyze_page($page_id);
    $parsed = Studios_API_Connector::process_command_with_ai($command, $page_structure);
    
    // Execute the change
    $result = $wp_intel->apply_change($page_id, $parsed['action'] ?? 'unknown', $parsed);
    
    $exec_time = microtime(true) - $start_time;
    
    // Log the execution
    $ai_learning = new Studios_AI_Learning();
    $ai_learning->log_command(
        $command,
        $parsed['action'] ?? 'unknown',
        $page_id,
        $parsed['target'] ?? '',
        wp_json_encode($parsed),
        $result['success'] ?? false,
        $exec_time
    );
    
    // Learn from success/failure
    if ($result['success'] ?? false) {
        $ai_learning->learn_pattern(
            'command_execution',
            explode(' ', strtolower($command)),
            $parsed['action'],
            true
        );
    }
    
    wp_send_json_success([
        'result' => $result,
        'execution_time' => round($exec_time, 3),
        'command_id' => $ai_learning->last_insert_id ?? null
    ]);
}

/**
 * AJAX Handler: Search Images
 */
add_action('wp_ajax_studios_search_images', 'studios_ajax_search_images');
function studios_ajax_search_images() {
    check_ajax_referer('studios_ai_nonce', 'nonce');
    
    $query = sanitize_text_field($_POST['query'] ?? '');
    $source = sanitize_text_field($_POST['source'] ?? 'pexels');
    
    if (empty($query)) {
        wp_send_json_error(['message' => 'Search query is required']);
    }
    
    $results = [];
    switch ($source) {
        case 'unsplash':
            $results = Studios_API_Connector::search_unsplash($query);
            break;
        case 'pixabay':
            $results = Studios_API_Connector::search_pixabay($query, 'image');
            break;
        default:
            $results = Studios_API_Connector::search_pexels($query);
    }
    
    wp_send_json_success($results);
}

/**
 * AJAX Handler: Get Learning Stats
 */
add_action('wp_ajax_studios_get_stats', 'studios_ajax_get_stats');
function studios_ajax_get_stats() {
    global $wpdb;
    
    $total_commands = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}ai_commands");
    $successful = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}ai_commands WHERE success = 1");
    $success_rate = $total_commands > 0 ? round(($successful / $total_commands) * 100, 1) : 0;
    
    $top_patterns = $wpdb->get_results("
        SELECT pattern_type, action_type, confidence_score, usage_count 
        FROM {$wpdb->prefix}ai_patterns 
        ORDER BY confidence_score DESC, usage_count DESC 
        LIMIT 5
    ");
    
    wp_send_json_success([
        'total_commands' => $total_commands,
        'success_rate' => $success_rate,
        'top_patterns' => $top_patterns
    ]);
}

