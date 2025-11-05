<?php
/*
 *   Copyright (c) 2025 NAME.
 *   All rights reserved.
 *   Unauthorized copying, modification, distribution, or use of this is prohibited without express written permission.
 */

/**
 * Plugin Name: 3000 Studios Recovery Endpoint (MU)
 * Description: Secure, one-click rescue endpoint to disable plugins, switch theme, and view status. Protect with RECOVERY_TOKEN in wp-config.php.
 * Author: 3000 Studios
 * Version: 1.0.0
 */

if (!defined('ABSPATH')) {
    return;
}

// Authorization helper: allow with RECOVERY_TOKEN+token param OR logged-in admin
function studios_recovery_is_authorized(): bool
{
    $has_token = defined('RECOVERY_TOKEN') && RECOVERY_TOKEN;
    $provided  = isset($_GET['token']) ? (string) $_GET['token'] : '';

    if ($has_token && hash_equals((string) RECOVERY_TOKEN, $provided)) {
        return true;
    }
    if (function_exists('is_user_logged_in') && is_user_logged_in() && current_user_can('manage_options')) {
        return true;
    }
    return false;
}

// Utility: safe json response
function studios_recovery_json($data, int $code = 200)
{
    status_header($code);
    header('Content-Type: application/json; charset=utf-8');
    echo wp_json_encode($data);
    exit;
}

// Core actions (non-multisite focus)
function studios_recovery_disable_all_plugins(): array
{
    if (is_multisite()) {
        // Only handle per-site active plugins for now
    }
    update_option('active_plugins', array());
    return array('ok' => true, 'message' => 'All plugins disabled');
}

function studios_recovery_enable_plugin(string $plugin_file): array
{
    $plugin_file = trim($plugin_file);
    $full = WP_PLUGIN_DIR . '/' . $plugin_file;
    if (!file_exists($full)) {
        return array('ok' => false, 'message' => 'Plugin file not found: ' . $plugin_file);
    }
    $active = (array) get_option('active_plugins', array());
    if (!in_array($plugin_file, $active, true)) {
        $active[] = $plugin_file;
        update_option('active_plugins', $active);
    }
    return array('ok' => true, 'message' => 'Plugin enabled: ' . $plugin_file);
}

function studios_recovery_status(): array
{
    $theme   = wp_get_theme();
    $plugins = get_plugins();
    $active  = (array) get_option('active_plugins', array());
    return array(
        'site_url' => site_url(),
        'wp_version' => get_bloginfo('version'),
        'php_version' => PHP_VERSION,
        'theme' => array(
            'name' => $theme->get('Name'),
            'template' => get_option('template'),
            'stylesheet' => get_option('stylesheet'),
        ),
        'active_plugins' => $active,
        'plugins_count' => count($plugins),
        'debug_log_exists' => file_exists(WP_CONTENT_DIR . '/debug.log'),
    );
}

function studios_recovery_switch_theme(?string $target = null): array
{
    if (!function_exists('switch_theme')) {
        require_once ABSPATH . 'wp-includes/theme.php';
    }
    $themes = wp_get_themes();
    if ($target && isset($themes[$target])) {
        switch_theme($target);
        return array('ok' => true, 'message' => 'Theme switched to ' . $target);
    }
    // try a default twenty* theme
    foreach ($themes as $slug => $info) {
        if (strpos($slug, 'twenty') === 0) {
            switch_theme($slug);
            return array('ok' => true, 'message' => 'Theme switched to ' . $slug);
        }
    }
    return array('ok' => false, 'message' => 'No default theme (twenty*) found.');
}

// REST routes
add_action('rest_api_init', function () {
    register_rest_route('3000studios/v1', '/recovery', array(
        'methods'  => 'GET',
        'callback' => function ($req) {
            if (!studios_recovery_is_authorized()) {
                return new WP_REST_Response(array('error' => 'Unauthorized'), 401);
            }
            $action = isset($_GET['action']) ? sanitize_text_field($_GET['action']) : 'status';
            switch ($action) {
                case 'disable_all_plugins':
                    return studios_recovery_disable_all_plugins();
                case 'enable_plugin':
                    $plugin = isset($_GET['plugin']) ? sanitize_text_field($_GET['plugin']) : '';
                    if (!$plugin) return new WP_REST_Response(array('error' => 'Missing plugin param'), 400);
                    return studios_recovery_enable_plugin($plugin);
                case 'switch_theme':
                    $theme = isset($_GET['theme']) ? sanitize_text_field($_GET['theme']) : null;
                    return studios_recovery_switch_theme($theme);
                case 'status':
                default:
                    return studios_recovery_status();
            }
        },
        'permission_callback' => '__return_true', // We gate via token/admin check inside
    ));
});

// Simple HTML UI via query param: ?recovery=1&token=XXX
add_action('template_redirect', function () {
    if (!isset($_GET['recovery'])) {
        return;
    }
    if (!studios_recovery_is_authorized()) {
        status_header(401);
        echo '<h1>Unauthorized</h1><p>Provide a valid token via RECOVERY_TOKEN or login as an administrator.</p>';
        exit;
    }
    $status = studios_recovery_status();
    $token  = isset($_GET['token']) ? esc_attr((string) $_GET['token']) : '';
    $base   = esc_url(add_query_arg(array('recovery' => '1', 'token' => $token), site_url('/')));
    echo '<!doctype html><meta charset="utf-8"><title>3000 Studios Recovery</title>';
    echo '<style>body{font-family:system-ui,Segoe UI,Roboto,Arial;background:#0b0b10;color:#e6f3ff;padding:20px}a.button{display:inline-block;margin:6px 8px;padding:10px 14px;background:#0ea5e9;color:#00111f;text-decoration:none;border-radius:8px;font-weight:700}code,pre{background:#0f172a;color:#cbd5e1;padding:6px 8px;border-radius:6px}</style>';
    echo '<h1>3000 Studios Recovery</h1>';
    echo '<p><strong>Site:</strong> ' . esc_html($status['site_url']) . ' · <strong>WP:</strong> ' . esc_html($status['wp_version']) . ' · <strong>PHP:</strong> ' . esc_html($status['php_version']) . '</p>';
    echo '<p><strong>Theme:</strong> ' . esc_html($status['theme']['name']) . ' (' . esc_html($status['theme']['stylesheet']) . ')</p>';
    echo '<p><strong>Active plugins:</strong> ' . count($status['active_plugins']) . '</p>';

    echo '<div>';
    echo '<a class="button" href="' . esc_url(add_query_arg('action', 'disable_all_plugins', $base)) . '">Disable ALL plugins</a>';
    echo '<a class="button" href="' . esc_url(add_query_arg('action', 'switch_theme', $base)) . '">Switch to default theme</a>';
    echo '<a class="button" href="' . esc_url(rest_url('3000studios/v1/recovery') . '?action=status&token=' . rawurlencode($token)) . '" target="_blank">View JSON status</a>';
    echo '</div>';

    if (!empty($status['active_plugins'])) {
        echo '<h3>Enable a specific plugin (by file):</h3><ul>';
        foreach (get_plugins() as $file => $data) {
            $label = esc_html($data['Name'] . ' (' . $file . ')');
            $url   = esc_url(add_query_arg(array('action' => 'enable_plugin', 'plugin' => $file), $base));
            echo '<li><a class="button" href="' . $url . '">Enable: ' . $label . '</a></li>';
        }
        echo '</ul>';
    }

    // Perform action if requested via UI
    $action = isset($_GET['action']) ? sanitize_text_field($_GET['action']) : '';
    if ($action) {
        echo '<hr />';
        echo '<h3>Action result:</h3><pre>';
        switch ($action) {
            case 'disable_all_plugins':
                echo esc_html(wp_json_encode(studios_recovery_disable_all_plugins(), JSON_PRETTY_PRINT));
                break;
            case 'enable_plugin':
                $plugin = isset($_GET['plugin']) ? sanitize_text_field($_GET['plugin']) : '';
                echo esc_html(wp_json_encode(studios_recovery_enable_plugin($plugin), JSON_PRETTY_PRINT));
                break;
            case 'switch_theme':
                $theme = isset($_GET['theme']) ? sanitize_text_field($_GET['theme']) : null;
                echo esc_html(wp_json_encode(studios_recovery_switch_theme($theme), JSON_PRETTY_PRINT));
                break;
            default:
                echo 'No action performed.';
        }
        echo '</pre>';
    }
    exit;
});
