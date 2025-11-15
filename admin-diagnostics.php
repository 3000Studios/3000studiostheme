<?php
/*
 * Template Name: Admin Diagnostics
 * 
 * Copyright (c) 2025 NAME.
 * All rights reserved.
 * Unauthorized copying, modification, distribution, or use of this is prohibited without express written permission.
 */

/**
 * WordPress Admin Diagnostics Page
 * Helps diagnose theme-related admin issues
 * 
 * Usage: Visit this page from frontend (not wp-admin) to see diagnostics
 */

if (!defined('ABSPATH')) {
    define('ABSPATH', dirname(dirname(dirname(dirname(__FILE__)))) . '/');
}

// Try to load WordPress
if (file_exists(ABSPATH . 'wp-load.php')) {
    require_once(ABSPATH . 'wp-load.php');
}

get_header();
?>

<div style="max-width:1200px;margin:50px auto;padding:2rem;background:rgba(0,0,0,0.8);border-radius:15px;">
    <h1 style="color:#00ffe7;text-align:center;margin-bottom:2rem;">⚡ 3000 Studios Admin Diagnostics</h1>

    <!-- WordPress Status -->
    <div style="background:rgba(255,255,255,0.05);padding:2rem;border-radius:10px;margin-bottom:2rem;">
        <h2 style="color:#00ffe7;margin:0 0 1rem 0;">📊 WordPress Status</h2>
        <table style="width:100%;color:#fff;">
            <tr>
                <td style="padding:0.5rem;border-bottom:1px solid rgba(255,255,255,0.1);"><strong>WordPress Version:</strong></td>
                <td style="padding:0.5rem;border-bottom:1px solid rgba(255,255,255,0.1);"><?php echo get_bloginfo('version'); ?></td>
            </tr>
            <tr>
                <td style="padding:0.5rem;border-bottom:1px solid rgba(255,255,255,0.1);"><strong>Site URL:</strong></td>
                <td style="padding:0.5rem;border-bottom:1px solid rgba(255,255,255,0.1);"><?php echo get_site_url(); ?></td>
            </tr>
            <tr>
                <td style="padding:0.5rem;border-bottom:1px solid rgba(255,255,255,0.1);"><strong>Admin URL:</strong></td>
                <td style="padding:0.5rem;border-bottom:1px solid rgba(255,255,255,0.1);">
                    <a href="<?php echo admin_url(); ?>" style="color:#00ffe7;"><?php echo admin_url(); ?></a>
                </td>
            </tr>
            <tr>
                <td style="padding:0.5rem;border-bottom:1px solid rgba(255,255,255,0.1);"><strong>Current User:</strong></td>
                <td style="padding:0.5rem;border-bottom:1px solid rgba(255,255,255,0.1);">
                    <?php echo is_user_logged_in() ? wp_get_current_user()->user_login : 'Not logged in'; ?>
                </td>
            </tr>
            <tr>
                <td style="padding:0.5rem;"><strong>Current Theme:</strong></td>
                <td style="padding:0.5rem;"><?php echo wp_get_theme()->get('Name'); ?> v<?php echo wp_get_theme()->get('Version'); ?></td>
            </tr>
        </table>
    </div>

    <!-- Theme Files Check -->
    <div style="background:rgba(255,255,255,0.05);padding:2rem;border-radius:10px;margin-bottom:2rem;">
        <h2 style="color:#00ffe7;margin:0 0 1rem 0;">📁 Theme Files Status</h2>
        <table style="width:100%;color:#fff;">
            <?php
            $theme_dir = get_template_directory();
            $critical_files = [
                'functions.php' => 'Core theme functions',
                'index.php' => 'Main template',
                'style.css' => 'Main stylesheet',
                'header.php' => 'Header template',
                'footer.php' => 'Footer template',
                'includes/ai-learning.php' => 'AI Learning (admin safe)',
                'includes/api-settings.php' => 'API Settings (admin safe)',
                'includes/wp-intelligence.php' => 'WP Intelligence (frontend only)',
                'includes/api-connector.php' => 'API Connector (frontend only)',
                'includes/monetization.php' => 'Monetization (frontend only)',
                'includes/live-reload-inject.php' => 'Live Reload (frontend only)',
            ];
            
            foreach ($critical_files as $file => $description) {
                $exists = file_exists($theme_dir . '/' . $file);
                $status = $exists ? '✅' : '❌';
                $color = $exists ? '#00ff66' : '#ff6b6b';
                echo "<tr>";
                echo "<td style='padding:0.5rem;border-bottom:1px solid rgba(255,255,255,0.1);'><strong>$file</strong></td>";
                echo "<td style='padding:0.5rem;border-bottom:1px solid rgba(255,255,255,0.1);'>$description</td>";
                echo "<td style='padding:0.5rem;border-bottom:1px solid rgba(255,255,255,0.1);color:$color;'>$status " . ($exists ? 'OK' : 'MISSING') . "</td>";
                echo "</tr>";
            }
            ?>
        </table>
    </div>

    <!-- PHP Configuration -->
    <div style="background:rgba(255,255,255,0.05);padding:2rem;border-radius:10px;margin-bottom:2rem;">
        <h2 style="color:#00ffe7;margin:0 0 1rem 0;">⚙️ PHP Configuration</h2>
        <table style="width:100%;color:#fff;">
            <tr>
                <td style="padding:0.5rem;border-bottom:1px solid rgba(255,255,255,0.1);"><strong>PHP Version:</strong></td>
                <td style="padding:0.5rem;border-bottom:1px solid rgba(255,255,255,0.1);"><?php echo phpversion(); ?></td>
            </tr>
            <tr>
                <td style="padding:0.5rem;border-bottom:1px solid rgba(255,255,255,0.1);"><strong>Memory Limit:</strong></td>
                <td style="padding:0.5rem;border-bottom:1px solid rgba(255,255,255,0.1);"><?php echo ini_get('memory_limit'); ?></td>
            </tr>
            <tr>
                <td style="padding:0.5rem;border-bottom:1px solid rgba(255,255,255,0.1);"><strong>Max Execution Time:</strong></td>
                <td style="padding:0.5rem;border-bottom:1px solid rgba(255,255,255,0.1);"><?php echo ini_get('max_execution_time'); ?>s</td>
            </tr>
            <tr>
                <td style="padding:0.5rem;"><strong>WP_DEBUG:</strong></td>
                <td style="padding:0.5rem;"><?php echo defined('WP_DEBUG') && WP_DEBUG ? 'Enabled ⚠️' : 'Disabled ✅'; ?></td>
            </tr>
        </table>
    </div>

    <!-- Loaded Classes Check -->
    <div style="background:rgba(255,255,255,0.05);padding:2rem;border-radius:10px;margin-bottom:2rem;">
        <h2 style="color:#00ffe7;margin:0 0 1rem 0;">🔧 Theme Classes Status</h2>
        <table style="width:100%;color:#fff;">
            <?php
            $theme_classes = [
                'Studios_AI_Learning' => 'AI Learning System (should always load)',
                'Studios_WP_Intelligence' => 'WP Intelligence (frontend only)',
                'Studios_API_Connector' => 'API Connector (frontend only)',
            ];
            
            foreach ($theme_classes as $class => $description) {
                $exists = class_exists($class);
                $status = $exists ? '✅' : '❌';
                $color = $exists ? '#00ff66' : '#ff6b6b';
                $note = '';
                
                if (!$exists && !is_admin()) {
                    $note = ' (ERROR: Should be loaded on frontend)';
                    $color = '#ff6b6b';
                } elseif (!$exists && is_admin()) {
                    $note = ' (OK: Not needed in admin)';
                    $color = '#ffaa00';
                }
                
                echo "<tr>";
                echo "<td style='padding:0.5rem;border-bottom:1px solid rgba(255,255,255,0.1);'><strong>$class</strong></td>";
                echo "<td style='padding:0.5rem;border-bottom:1px solid rgba(255,255,255,0.1);'>$description</td>";
                echo "<td style='padding:0.5rem;border-bottom:1px solid rgba(255,255,255,0.1);color:$color;'>$status " . ($exists ? 'Loaded' : 'Not Loaded') . "$note</td>";
                echo "</tr>";
            }
            ?>
        </table>
    </div>

    <!-- Active Plugins -->
    <div style="background:rgba(255,255,255,0.05);padding:2rem;border-radius:10px;margin-bottom:2rem;">
        <h2 style="color:#00ffe7;margin:0 0 1rem 0;">🔌 Active Plugins</h2>
        <?php
        $active_plugins = get_option('active_plugins');
        if (!empty($active_plugins)) {
            echo "<ul style='color:#fff;line-height:2;'>";
            foreach ($active_plugins as $plugin) {
                echo "<li>" . esc_html($plugin) . "</li>";
            }
            echo "</ul>";
        } else {
            echo "<p style='color:#fff;'>No active plugins</p>";
        }
        ?>
    </div>

    <!-- Quick Links -->
    <div style="background:rgba(0,255,231,0.1);padding:2rem;border-radius:10px;border:2px solid #00ffe7;">
        <h2 style="color:#00ffe7;margin:0 0 1rem 0;">🚀 Quick Actions</h2>
        <div style="display:flex;gap:1rem;flex-wrap:wrap;">
            <a href="<?php echo admin_url(); ?>" style="padding:12px 24px;background:#00ffe7;color:#000;text-decoration:none;border-radius:8px;font-weight:bold;display:inline-block;">
                🎛️ Go to Admin Dashboard
            </a>
            <a href="<?php echo wp_login_url(); ?>" style="padding:12px 24px;background:#00ff66;color:#000;text-decoration:none;border-radius:8px;font-weight:bold;display:inline-block;">
                🔐 Go to Login Page
            </a>
            <a href="<?php echo home_url(); ?>" style="padding:12px 24px;background:#9d4edd;color:#fff;text-decoration:none;border-radius:8px;font-weight:bold;display:inline-block;">
                🏠 Go to Homepage
            </a>
        </div>
    </div>

    <!-- Instructions -->
    <div style="background:rgba(255,255,255,0.05);padding:2rem;border-radius:10px;margin-top:2rem;">
        <h3 style="color:#00ffe7;margin:0 0 1rem 0;">📖 About This Page</h3>
        <p style="color:#fff;line-height:1.8;">
            This diagnostic page helps troubleshoot WordPress admin access issues. It shows which theme files and classes are loaded,
            which is especially useful for debugging admin area conflicts.
        </p>
        <p style="color:#fff;line-height:1.8;margin-top:1rem;">
            <strong>Recent Fix (<?php echo date('Y-m-d'); ?>):</strong> Frontend-only includes (wp-intelligence.php, api-connector.php, monetization.php, live-reload-inject.php) 
            are now prevented from loading in wp-admin to improve performance and prevent conflicts.
        </p>
    </div>
</div>

<?php get_footer(); ?>
