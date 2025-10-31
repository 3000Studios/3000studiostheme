<?php
/**
 * 3000 Studios API Settings Page
 * Manage all API keys in one place
 * 
 * @package     3000Studios
 * @author      Mr. jwswain
 * @copyright   Copyright (c) 2025, Mr. jwswain & 3000 Studios
 * @license     Proprietary - All Rights Reserved
 */

if ( ! defined('ABSPATH') ) { exit; }

// Add settings menu
add_action('admin_menu', 'studios_api_settings_menu');

function studios_api_settings_menu() {
    add_theme_page(
        '3000 Studios API Settings',
        'API Settings',
        'manage_options',
        '3000-studios-api',
        'studios_api_settings_page'
    );
}

// Register settings
add_action('admin_init', 'studios_register_api_settings');

function studios_register_api_settings() {
    register_setting('studios_api_settings', 'studios_openai_key');
    register_setting('studios_api_settings', 'studios_pexels_key');
    register_setting('studios_api_settings', 'studios_unsplash_key');
    register_setting('studios_api_settings', 'studios_pixabay_key');
}

// Settings page HTML
function studios_api_settings_page() {
    ?>
    <div class="wrap" style="max-width:900px;">
        <h1 style="color:#00ffe7;">🔑 3000 Studios API Settings</h1>
        <p style="font-size:16px;color:#666;">Configure your API keys to unlock advanced AI features.</p>
        
        <form method="post" action="options.php">
            <?php settings_fields('studios_api_settings'); ?>
            <?php do_settings_sections('studios_api_settings'); ?>
            
            <div style="background:#fff;padding:30px;border-radius:8px;margin-top:20px;box-shadow:0 2px 8px rgba(0,0,0,0.1);">
                
                <!-- OpenAI -->
                <div style="margin-bottom:30px;padding-bottom:30px;border-bottom:1px solid #eee;">
                    <h2 style="margin:0 0 10px 0;color:#00ffe7;">🤖 OpenAI API</h2>
                    <p style="color:#666;margin:0 0 15px 0;">
                        <strong>Cost:</strong> ~$0.002 per request | 
                        <a href="https://platform.openai.com/api-keys" target="_blank" style="color:#00ffe7;">Get API Key →</a>
                    </p>
                    <input 
                        type="text" 
                        name="studios_openai_key" 
                        value="<?php echo esc_attr(get_option('studios_openai_key')); ?>" 
                        placeholder="sk-proj-..." 
                        style="width:100%;padding:12px;font-size:14px;border:2px solid #ddd;border-radius:6px;font-family:monospace;"
                    >
                    <p style="margin:10px 0 0 0;color:#999;font-size:13px;">
                        ✅ Enables: ChatGPT-style conversations, advanced natural language understanding
                    </p>
                </div>
                
                <!-- Pexels -->
                <div style="margin-bottom:30px;padding-bottom:30px;border-bottom:1px solid #eee;">
                    <h2 style="margin:0 0 10px 0;color:#00ffe7;">📷 Pexels API</h2>
                    <p style="color:#666;margin:0 0 15px 0;">
                        <strong>Cost:</strong> FREE ✅ | 
                        <a href="https://www.pexels.com/api/" target="_blank" style="color:#00ffe7;">Get API Key →</a>
                    </p>
                    <input 
                        type="text" 
                        name="studios_pexels_key" 
                        value="<?php echo esc_attr(get_option('studios_pexels_key')); ?>" 
                        placeholder="Your Pexels API Key" 
                        style="width:100%;padding:12px;font-size:14px;border:2px solid #ddd;border-radius:6px;font-family:monospace;"
                    >
                    <p style="margin:10px 0 0 0;color:#999;font-size:13px;">
                        ✅ Enables: Free stock photos, unlimited searches
                    </p>
                </div>
                
                <!-- Unsplash -->
                <div style="margin-bottom:30px;padding-bottom:30px;border-bottom:1px solid #eee;">
                    <h2 style="margin:0 0 10px 0;color:#00ffe7;">🖼️ Unsplash API</h2>
                    <p style="color:#666;margin:0 0 15px 0;">
                        <strong>Cost:</strong> FREE ✅ (50/hour) | 
                        <a href="https://unsplash.com/developers" target="_blank" style="color:#00ffe7;">Get API Key →</a>
                    </p>
                    <input 
                        type="text" 
                        name="studios_unsplash_key" 
                        value="<?php echo esc_attr(get_option('studios_unsplash_key')); ?>" 
                        placeholder="Your Unsplash Access Key" 
                        style="width:100%;padding:12px;font-size:14px;border:2px solid #ddd;border-radius:6px;font-family:monospace;"
                    >
                    <p style="margin:10px 0 0 0;color:#999;font-size:13px;">
                        ✅ Enables: High-quality free photos
                    </p>
                </div>
                
                <!-- Pixabay -->
                <div style="margin-bottom:30px;">
                    <h2 style="margin:0 0 10px 0;color:#00ffe7;">🎨 Pixabay API</h2>
                    <p style="color:#666;margin:0 0 15px 0;">
                        <strong>Cost:</strong> FREE ✅ | 
                        <a href="https://pixabay.com/api/docs/" target="_blank" style="color:#00ffe7;">Get API Key →</a>
                    </p>
                    <input 
                        type="text" 
                        name="studios_pixabay_key" 
                        value="<?php echo esc_attr(get_option('studios_pixabay_key')); ?>" 
                        placeholder="Your Pixabay API Key" 
                        style="width:100%;padding:12px;font-size:14px;border:2px solid #ddd;border-radius:6px;font-family:monospace;"
                    >
                    <p style="margin:10px 0 0 0;color:#999;font-size:13px;">
                        ✅ Enables: Free photos, videos, music, sound effects
                    </p>
                </div>
                
                <?php submit_button('💾 Save API Keys', 'primary', 'submit', false, ['style' => 'padding:15px 40px;font-size:16px;background:#00ffe7;border:none;color:#000;font-weight:bold;']); ?>
            </div>
        </form>
        
        <!-- Instructions -->
        <div style="background:#f0f9ff;padding:25px;border-radius:8px;margin-top:30px;border-left:4px solid #00ffe7;">
            <h3 style="margin:0 0 15px 0;color:#00ffe7;">📖 Quick Setup Guide</h3>
            <ol style="line-height:2;">
                <li><strong>OpenAI:</strong> Go to <a href="https://platform.openai.com/api-keys" target="_blank">platform.openai.com/api-keys</a> → Create API Key</li>
                <li><strong>Pexels:</strong> Visit <a href="https://www.pexels.com/api/" target="_blank">pexels.com/api</a> → Sign up (free) → Get API Key</li>
                <li><strong>Unsplash:</strong> Go to <a href="https://unsplash.com/developers" target="_blank">unsplash.com/developers</a> → Create App → Copy Access Key</li>
                <li><strong>Pixabay:</strong> Visit <a href="https://pixabay.com/api/docs/" target="_blank">pixabay.com/api/docs</a> → Sign up → Get Key instantly</li>
            </ol>
            <p style="margin:15px 0 0 0;"><strong>💡 Tip:</strong> Only OpenAI costs money (~$0.002/request). All others are 100% FREE! ✅</p>
        </div>
        
        <!-- Status Check -->
        <div style="background:#fff;padding:25px;border-radius:8px;margin-top:20px;box-shadow:0 2px 8px rgba(0,0,0,0.1);">
            <h3 style="margin:0 0 15px 0;">🔍 API Status</h3>
            <table style="width:100%;border-collapse:collapse;">
                <tr style="border-bottom:1px solid #eee;">
                    <td style="padding:15px;font-weight:bold;">OpenAI</td>
                    <td style="padding:15px;text-align:right;">
                        <?php echo get_option('studios_openai_key') ? '<span style="color:green;">✅ Connected</span>' : '<span style="color:red;">❌ Not configured</span>'; ?>
                    </td>
                </tr>
                <tr style="border-bottom:1px solid #eee;">
                    <td style="padding:15px;font-weight:bold;">Pexels</td>
                    <td style="padding:15px;text-align:right;">
                        <?php echo get_option('studios_pexels_key') ? '<span style="color:green;">✅ Connected</span>' : '<span style="color:red;">❌ Not configured</span>'; ?>
                    </td>
                </tr>
                <tr style="border-bottom:1px solid #eee;">
                    <td style="padding:15px;font-weight:bold;">Unsplash</td>
                    <td style="padding:15px;text-align:right;">
                        <?php echo get_option('studios_unsplash_key') ? '<span style="color:green;">✅ Connected</span>' : '<span style="color:red;">❌ Not configured</span>'; ?>
                    </td>
                </tr>
                <tr>
                    <td style="padding:15px;font-weight:bold;">Pixabay</td>
                    <td style="padding:15px;text-align:right;">
                        <?php echo get_option('studios_pixabay_key') ? '<span style="color:green;">✅ Connected</span>' : '<span style="color:red;">❌ Not configured</span>'; ?>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <?php
}

// Helper functions to get API keys
function studios_get_openai_key() {
    return get_option('studios_openai_key', '');
}

function studios_get_pexels_key() {
    return get_option('studios_pexels_key', '');
}

function studios_get_unsplash_key() {
    return get_option('studios_unsplash_key', '');
}

function studios_get_pixabay_key() {
    return get_option('studios_pixabay_key', '');
}
