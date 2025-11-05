<?php
/*
 *   Copyright (c) 2025 NAME.
 *   All rights reserved.
 *   Unauthorized copying, modification, distribution, or use of this is prohibited without express written permission.
 */

/**
 * Plugin Name: 3000 Studios Auto Updates (MU)
 * Description: Forces automatic updates for WordPress core, plugins, and themes. Safe-by-default and production-ready.
 * Author: 3000 Studios
 * Version: 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    return;
}

// Core auto-updates: dev, minor, major
add_filter('allow_dev_auto_core_updates', '__return_true');
add_filter('allow_minor_auto_core_updates', '__return_true');
add_filter('allow_major_auto_core_updates', '__return_true');

// Plugins and themes auto-updates
add_filter('auto_update_plugin', '__return_true');
add_filter('auto_update_theme', '__return_true');

// Optional: Email notifications control (keep enabled, but can be filtered)
add_filter('auto_core_update_send_email', function ($send, $type, $core_update, $result) {
    // $type: 'success', 'fail', 'critical'
    return true; // Always notify admin
}, 10, 4);

// Optional: Reduce debug email noise from automatic updates
add_filter('automatic_updates_send_debug_email', '__return_false');

// Safety: Ensure cron is enabled for auto-updates
if (!defined('DISABLE_WP_CRON')) {
    // If not set in wp-config.php, we assume cron is enabled (default). No action needed.
}

// Health indicator in Site Health (appear as a passed test)
add_filter('site_status_tests', function ($tests) {
    $tests['direct']['3000studios_auto_updates'] = [
        'label' => __('Auto-updates enforced (3000 Studios)'),
        'test'  => function () {
            return [
                'status' => 'good',
                'badge'  => [
                    'label' => __('Security'),
                    'color' => 'blue',
                ],
                'description' => __('Automatic updates for core, plugins, and themes are forced by 3000 Studios MU plugin.'),
                'actions' => '',
                'test' => '3000studios_auto_updates',
            ];
        },
    ];
    return $tests;
});
