
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

if (! defined('ABSPATH')) {
    exit;
} // Prevent direct access

function studios_enqueue_assets()
{
    // Main stylesheet
    wp_enqueue_style('3000studios-style', get_stylesheet_uri());

    // Additional theme CSS scaffold (ripples, cursor, mobile tweaks)
    if (file_exists(get_template_directory() . '/assets/css/theme.css')) {
        wp_enqueue_style('3000studios-theme', get_template_directory_uri() . '/assets/css/theme.css', array('3000studios-style'), '1.0.0');
    }

    // Howler for audio handling (optional)
    wp_enqueue_script('howler-cdn', 'https://cdnjs.cloudflare.com/ajax/libs/howler/2.2.3/howler.min.js', array(), '2.2.3', true);

    // Main javascript (page features)
    wp_enqueue_script('3000studios-main', get_template_directory_uri() . '/js/main.js', array(), '1.1', true);

    // Theme behaviour script (cursor, ripples, audio helpers)
    if (file_exists(get_template_directory() . '/assets/js/theme.js')) {
        wp_enqueue_script('3000studios-theme-js', get_template_directory_uri() . '/assets/js/theme.js', array('3000studios-main', 'howler-cdn'), '1.0.0', true);
    }

    // Enqueue galaxy background for homepage
    if (is_front_page() || is_home()) {
        wp_enqueue_script('3000studios-galaxy', get_template_directory_uri() . '/assets/js/galaxy-background.js', array(), '1.0.0', true);
    }

    // Enqueue ball pit footer animation
    wp_enqueue_script('3000studios-ballpit', get_template_directory_uri() . '/assets/js/ball-pit-footer.js', array(), '1.0.0', true);
}
add_action('wp_enqueue_scripts', 'studios_enqueue_assets');

// Register navigation menus
register_nav_menus(array(
    'primary' => __('Primary Menu', 'threek')
));

// Load AI Intelligence Systems
require_once get_template_directory() . '/includes/ai-learning.php';
require_once get_template_directory() . '/includes/wp-intelligence.php';
require_once get_template_directory() . '/includes/api-settings.php';
require_once get_template_directory() . '/includes/api-connector.php';
require_once get_template_directory() . '/includes/monetization.php';

// Initialize AI database tables
add_action('after_switch_theme', 'studios_ai_create_tables');

/**
 * AJAX Handler: Preview AI Command
 */
add_action('wp_ajax_studios_preview_command', 'studios_ajax_preview_command');
function studios_ajax_preview_command()
{
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

    // Generate enhanced HTML preview
    $preview_html = studios_generate_preview($parsed);
    
    wp_send_json_success([
        'parsed' => $parsed,
        'page_structure' => $page_structure,
        'preview' => $preview_html
    ]);
}

/**
 * Generate HTML Preview for Commands
 */
function studios_generate_preview($parsed) {
    $action = $parsed['action'] ?? 'unknown';
    $value = $parsed['value'] ?? '';
    $target = $parsed['target'] ?? 'main';
    $color = $parsed['color'] ?? '';
    $animation = $parsed['animation'] ?? '';
    
    $preview_html = '<div style="border:2px dashed #00ffe7;padding:1.5rem;border-radius:15px;background:rgba(0,255,231,0.1);backdrop-filter:blur(10px);">';
    $preview_html .= '<h3 style="color:#00ffe7;margin:0 0 1rem 0;text-shadow:0 0 15px currentColor;">🤖 AI Preview</h3>';
    
    switch($action) {
        case 'update_text':
        case 'change_title':
            $style = 'font-size:72px;font-weight:bold;text-align:center;margin:2rem 0;';
            if ($color) $style .= 'color:' . $color . ';';
            if ($animation) $style .= 'animation:ai-' . $animation . ' 2s ease-in-out infinite;';
            
            $preview_html .= '<p style="color:#00ffe7;margin-bottom:1rem;"><strong>Action:</strong> Update ' . ucfirst($target) . ' Text</p>';
            $preview_html .= '<div style="background:rgba(0,0,0,0.5);padding:2rem;border-radius:10px;margin:1rem 0;">';
            $preview_html .= '<h1 style="' . $style . '">' . esc_html($value) . '</h1>';
            $preview_html .= '</div>';
            break;
            
        case 'change_color':
            $preview_html .= '<p style="color:#00ffe7;margin-bottom:1rem;"><strong>Action:</strong> Change Color</p>';
            $preview_html .= '<div style="background:rgba(0,0,0,0.5);padding:2rem;border-radius:10px;margin:1rem 0;">';
            $preview_html .= '<p style="color:' . $color . ';font-size:24px;font-weight:bold;text-align:center;">Sample text in new color: ' . $color . '</p>';
            $preview_html .= '</div>';
            break;
            
        case 'add_animation':
            $preview_html .= '<p style="color:#00ffe7;margin-bottom:1rem;"><strong>Action:</strong> Add Animation</p>';
            $preview_html .= '<div style="background:rgba(0,0,0,0.5);padding:2rem;border-radius:10px;margin:1rem 0;">';
            $preview_html .= '<p style="animation:ai-' . $animation . ' 2s ease-in-out infinite;font-size:24px;text-align:center;">✨ Animated Element</p>';
            $preview_html .= '</div>';
            break;
            
        case 'add_media':
            $preview_html .= '<p style="color:#00ffe7;margin-bottom:1rem;"><strong>Action:</strong> Add Media</p>';
            $preview_html .= '<div style="background:rgba(0,0,0,0.5);padding:2rem;border-radius:10px;margin:1rem 0;text-align:center;">';
            $preview_html .= '<div style="width:300px;height:200px;background:linear-gradient(45deg,#333,#666);border-radius:10px;display:flex;align-items:center;justify-content:center;margin:0 auto;color:#fff;font-size:18px;">🖼️ Media Preview</div>';
            $preview_html .= '</div>';
            break;
            
        case 'change_background':
            $preview_html .= '<p style="color:#00ffe7;margin-bottom:1rem;"><strong>Action:</strong> Change Background</p>';
            $preview_html .= '<div style="background:linear-gradient(135deg,' . ($color ?: '#667eea') . ',#764ba2);padding:2rem;border-radius:10px;margin:1rem 0;text-align:center;color:#fff;font-size:20px;font-weight:bold;">';
            $preview_html .= '🌈 New Background Preview';
            $preview_html .= '</div>';
            break;
            
        default:
            $preview_html .= '<p style="color:#00ffe7;margin-bottom:1rem;"><strong>Action:</strong> ' . ucfirst(str_replace('_', ' ', $action)) . '</p>';
            $preview_html .= '<div style="background:rgba(0,0,0,0.5);padding:2rem;border-radius:10px;margin:1rem 0;text-align:center;">';
            $preview_html .= '<p style="color:#00ffe7;font-size:18px;">⚡ Command ready to execute!</p>';
            $preview_html .= '</div>';
    }
    
    // Add animation CSS if needed
    if ($animation) {
        $preview_html .= '<style>';
        $preview_html .= '@keyframes ai-fade { 0%, 100% { opacity: 1; } 50% { opacity: 0.5; } }';
        $preview_html .= '@keyframes ai-bounce { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-20px); } }';
        $preview_html .= '@keyframes ai-glow { 0%, 100% { text-shadow: 0 0 10px currentColor; } 50% { text-shadow: 0 0 30px currentColor; } }';
        $preview_html .= '@keyframes ai-pulse { 0%, 100% { transform: scale(1); } 50% { transform: scale(1.1); } }';
        $preview_html .= '@keyframes ai-slide { 0%, 100% { transform: translateX(0); } 50% { transform: translateX(20px); } }';
        $preview_html .= '@keyframes ai-zoom { 0%, 100% { transform: scale(1); } 50% { transform: scale(1.2); } }';
        $preview_html .= '@keyframes ai-spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }';
        $preview_html .= '@keyframes ai-shake { 0%, 100% { transform: translateX(0); } 25% { transform: translateX(-10px); } 75% { transform: translateX(10px); } }';
        $preview_html .= '</style>';
    }
    
    $preview_html .= '<p style="color:#00ffe7;margin-top:1rem;text-align:center;font-weight:bold;"><em>👆 This is how it will look!</em></p>';
    $preview_html .= '</div>';
    
    return $preview_html;
}

/**
 * AJAX Handler: Execute AI Command - BLACKVAULT SUPREME Edition
 */
add_action('wp_ajax_studios_execute_command', 'studios_ajax_execute_command');
function studios_ajax_execute_command()
{
    check_ajax_referer('studios_ai_nonce', 'nonce');

    $command = sanitize_text_field($_POST['command'] ?? '');
    $page_id = intval($_POST['page_id'] ?? get_option('page_on_front', 1));

    if (empty($command)) {
        wp_send_json_error(['message' => 'Command is required, sexy!']);
    }

    $start_time = microtime(true);

    // Process with AI
    $wp_intel = class_exists('Studios_WP_Intelligence') ? new Studios_WP_Intelligence() : null;
    $page_structure = $wp_intel ? $wp_intel->analyze_page($page_id) : [];
    $parsed = Studios_API_Connector::process_command_with_ai($command, $page_structure);

    // Enhanced execution with live file updates
    $result = studios_execute_live_command($parsed, $page_id);

    $exec_time = microtime(true) - $start_time;

    // Log the execution
    if (class_exists('Studios_AI_Learning')) {
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
    }

    wp_send_json_success([
        'result' => $result,
        'parsed' => $parsed,
        'execution_time' => round($exec_time, 3),
        'ai_response' => $parsed['ai_response'] ?? 'Command executed successfully!',
        'live_preview_url' => home_url()
    ]);
}

/**
 * BLACKVAULT SUPREME Live Command Executor
 */
function studios_execute_live_command($parsed, $page_id = null)
{
    $action = $parsed['action'] ?? 'unknown';
    $target = $parsed['target'] ?? 'main';
    $value = $parsed['value'] ?? '';
    $page = $parsed['page'] ?? 'homepage';

    $result = ['success' => false, 'message' => 'Unknown error'];

    try {
        switch ($action) {
            case 'update_text':
            case 'change_color':
                $result = studios_update_page_content($page, $target, $value, $parsed);
                break;

            case 'add_animation':
                $result = studios_add_css_animation($parsed);
                break;

            case 'add_media':
                $result = studios_add_media_content($parsed);
                break;

            case 'change_background':
                $result = studios_update_background($parsed);
                break;

            case 'add_button':
                $result = studios_add_payment_button($parsed);
                break;

            case 'monetize':
                $result = studios_add_monetization($parsed);
                break;

            default:
                $result = studios_generic_update($parsed);
        }

        // Clear cache after any change
        if ($result['success']) {
            studios_clear_cache();
        }
    } catch (Exception $e) {
        $result = [
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ];
    }

    return $result;
}

/**
 * Update page content with smart targeting
 */
function studios_update_page_content($page, $target, $value, $parsed)
{
    if (empty($value)) {
        return ['success' => false, 'message' => 'No content to update'];
    }

    // Determine target file
    $file_path = get_template_directory() . '/index.php';
    if ($page !== 'homepage') {
        $page_file = get_template_directory() . '/page-' . sanitize_file_name($page) . '.php';
        if (file_exists($page_file)) {
            $file_path = $page_file;
        }
    }

    if (!file_exists($file_path)) {
        return ['success' => false, 'message' => 'Target file not found'];
    }

    // Backup original
    $backup_path = $file_path . '.backup.' . time();
    copy($file_path, $backup_path);

    $content = file_get_contents($file_path);
    $original_content = $content;

    // Smart content replacement based on target
    $style_attributes = '';
    if (isset($parsed['style'])) {
        foreach ($parsed['style'] as $prop => $val) {
            $style_attributes .= $prop . ':' . $val . ';';
        }
    }

    if (isset($parsed['animation']) && $parsed['animation']) {
        $style_attributes .= 'animation:ai-' . $parsed['animation'] . ' 2s ease-in-out infinite;';
    }

    // Target-specific replacements
    if (strpos($target, 'title') !== false || strpos($target, 'h1') !== false) {
        $new_element = '<h1 style="' . $style_attributes . '">' . esc_html($value) . '</h1>';
        $content = preg_replace('/<h1[^>]*>.*?<\/h1>/s', $new_element, $content, 1);
    } elseif (strpos($target, 'text') !== false || strpos($target, 'paragraph') !== false) {
        $new_element = '<p style="' . $style_attributes . '">' . esc_html($value) . '</p>';
        $content = preg_replace('/<p[^>]*>.*?<\/p>/s', $new_element, $content, 1);
    } else {
        // Generic text replacement
        $content = preg_replace('/<h1[^>]*>.*?<\/h1>/s', '<h1 style="' . $style_attributes . '">' . esc_html($value) . '</h1>', $content, 1);
    }

    // Write updated content
    if ($content !== $original_content && file_put_contents($file_path, $content)) {
        return [
            'success' => true,
            'message' => 'Content updated successfully!',
            'backup_created' => $backup_path,
            'target_file' => $file_path
        ];
    }

    return ['success' => false, 'message' => 'No changes made or write failed'];
}

/**
 * Add CSS animations to theme
 */
function studios_add_css_animation($parsed)
{
    $animation = $parsed['animation'] ?? '';

    if (empty($animation)) {
        return ['success' => false, 'message' => 'No animation specified'];
    }

    $css_file = get_template_directory() . '/style.css';
    if (!file_exists($css_file)) {
        return ['success' => false, 'message' => 'Style file not found'];
    }

    $content = file_get_contents($css_file);

    // Animation keyframes
    $keyframes = studios_get_animation_keyframes($animation);

    // Check if animation already exists
    if (strpos($content, "@keyframes ai-{$animation}") === false) {
        $content .= "\n\n/* BLACKVAULT SUPREME Animation: {$animation} */\n{$keyframes}\n";

        if (file_put_contents($css_file, $content)) {
            return [
                'success' => true,
                'message' => "Animation '{$animation}' added successfully!",
                'animation_code' => $keyframes
            ];
        }
    }

    return [
        'success' => true,
        'message' => "Animation '{$animation}' is ready to use!"
    ];
}

/**
 * Get CSS keyframes for animations
 */
function studios_get_animation_keyframes($animation)
{
    $keyframes = [
        'fade' => '@keyframes ai-fade { 0%, 100% { opacity: 1; } 50% { opacity: 0.5; } }',
        'bounce' => '@keyframes ai-bounce { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-20px); } }',
        'glow' => '@keyframes ai-glow { 0%, 100% { text-shadow: 0 0 10px currentColor; } 50% { text-shadow: 0 0 30px currentColor; } }',
        'pulse' => '@keyframes ai-pulse { 0%, 100% { transform: scale(1); } 50% { transform: scale(1.1); } }',
        'spin' => '@keyframes ai-spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }',
        'shake' => '@keyframes ai-shake { 0%, 100% { transform: translateX(0); } 25% { transform: translateX(-10px); } 75% { transform: translateX(10px); } }',
        'zoom' => '@keyframes ai-zoom { 0%, 100% { transform: scale(1); } 50% { transform: scale(1.2); } }',
        'slide' => '@keyframes ai-slide { 0%, 100% { transform: translateX(0); } 50% { transform: translateX(20px); } }'
    ];

    return $keyframes[$animation] ?? $keyframes['pulse'];
}

/**
 * Clear various caches
 */
function studios_clear_cache()
{
    // Clear WordPress object cache
    if (function_exists('wp_cache_flush')) {
        wp_cache_flush();
    }

    // Clear common caching plugins
    if (function_exists('w3tc_flush_all')) {
        w3tc_flush_all();
    }

    if (function_exists('wp_cache_clear_cache')) {
        wp_cache_clear_cache();
    }

    if (function_exists('rocket_clean_domain')) {
        rocket_clean_domain();
    }
}

/**
 * AJAX Handler: Search Images
 */
add_action('wp_ajax_studios_search_images', 'studios_ajax_search_images');
function studios_ajax_search_images()
{
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
function studios_ajax_get_stats()
{
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
