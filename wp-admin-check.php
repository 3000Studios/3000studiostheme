<?php
/*
 *   Copyright (c) 2025 NAME.
 *   All rights reserved.
 *   Unauthorized copying, modification, distribution, or use of this is prohibited without express written permission.
 */

/**
 * WordPress Admin Access Check
 * Temporary debugging file to check WordPress admin access
 */

// Check if WordPress is loaded
if (!defined('ABSPATH')) {
    define('ABSPATH', dirname(dirname(dirname(dirname(__FILE__)))) . '/');
}

// Try to load WordPress
if (file_exists(ABSPATH . 'wp-load.php')) {
    require_once(ABSPATH . 'wp-load.php');

    echo "<h1>WordPress Admin Check</h1>";
    echo "<h2>Status: WordPress Loaded Successfully ✅</h2>";

    echo "<h3>Environment Information:</h3>";
    echo "<ul>";
    echo "<li><strong>WordPress Version:</strong> " . get_bloginfo('version') . "</li>";
    echo "<li><strong>Site URL:</strong> " . get_site_url() . "</li>";
    echo "<li><strong>Admin URL:</strong> " . admin_url() . "</li>";
    echo "<li><strong>Current User:</strong> " . (is_user_logged_in() ? wp_get_current_user()->user_login : 'Not logged in') . "</li>";
    echo "<li><strong>Current Theme:</strong> " . wp_get_theme()->get('Name') . " v" . wp_get_theme()->get('Version') . "</li>";
    echo "<li><strong>WP_DEBUG:</strong> " . (defined('WP_DEBUG') && WP_DEBUG ? 'Enabled' : 'Disabled') . "</li>";
    echo "</ul>";

    echo "<h3>Theme Files Check:</h3>";
    echo "<ul>";
    $theme_dir = get_template_directory();
    $critical_files = ['functions.php', 'index.php', 'style.css', 'header.php', 'footer.php'];
    foreach ($critical_files as $file) {
        $exists = file_exists($theme_dir . '/' . $file);
        echo "<li><strong>$file:</strong> " . ($exists ? '✅ Exists' : '❌ Missing') . "</li>";
    }
    echo "</ul>";

    echo "<h3>PHP Configuration:</h3>";
    echo "<ul>";
    echo "<li><strong>PHP Version:</strong> " . phpversion() . "</li>";
    echo "<li><strong>Memory Limit:</strong> " . ini_get('memory_limit') . "</li>";
    echo "<li><strong>Max Execution Time:</strong> " . ini_get('max_execution_time') . "s</li>";
    echo "<li><strong>Display Errors:</strong> " . (ini_get('display_errors') ? 'On' : 'Off') . "</li>";
    echo "</ul>";

    echo "<h3>Plugin Check:</h3>";
    $active_plugins = get_option('active_plugins');
    if (!empty($active_plugins)) {
        echo "<ul>";
        foreach ($active_plugins as $plugin) {
            echo "<li>" . $plugin . "</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>No active plugins</p>";
    }

    echo "<h3>Recent Errors:</h3>";
    if (defined('WP_DEBUG_LOG') && WP_DEBUG_LOG) {
        $log_file = WP_CONTENT_DIR . '/debug.log';
        if (file_exists($log_file)) {
            $log_content = file_get_contents($log_file);
            $lines = explode("\n", $log_content);
            $recent_lines = array_slice($lines, -20);
            echo "<pre style='background:#000;color:#0f0;padding:1rem;border-radius:8px;max-height:400px;overflow:auto;'>";
            echo htmlspecialchars(implode("\n", $recent_lines));
            echo "</pre>";
        } else {
            echo "<p>Debug log file not found</p>";
        }
    } else {
        echo "<p>Debug logging not enabled</p>";
    }

    echo "<hr>";
    echo "<h3>Quick Links:</h3>";
    echo "<p><a href='" . admin_url() . "' style='padding:10px 20px;background:#00ffe7;color:#000;text-decoration:none;border-radius:5px;font-weight:bold;'>Go to Admin Dashboard →</a></p>";
    echo "<p><a href='" . wp_login_url() . "' style='padding:10px 20px;background:#00ff66;color:#000;text-decoration:none;border-radius:5px;font-weight:bold;'>Go to Login Page →</a></p>";
} else {
    echo "<h1>❌ Error: WordPress Not Found</h1>";
    echo "<p>WordPress installation not found at: " . ABSPATH . "</p>";
    echo "<p>This file should be in: <code>wp-content/themes/3000studios/</code></p>";
}
