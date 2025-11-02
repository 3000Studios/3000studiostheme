<?php
/*
Plugin Name: 3000 Studios Command Center
Plugin URI:  https://github.com/3000Studios/3000studiostheme
Description: Admin-only voice Command Center that proxies OpenAI and creates drafts/suggestions. No API keys in code.
Version:     0.1
Author:      3000 Studios
Text Domain: command-center
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

add_action('admin_menu', function() {
    add_menu_page(
        'Command Center',
        'Command Center',
        'manage_options',
        'cc-command-center',
        'cc_render_page',
        'dashicons-format-chat',
        3
    );
});

function cc_render_page() {
    if ( ! current_user_can('manage_options') ) {
        wp_die(__('Insufficient permissions'));
    }
    $nonce = wp_create_nonce('cc-nonce');
    ?>
    <div class="wrap">
        <h1>3000 Studios Command Center</h1>
        <p>Use voice commands to interact with AI and create draft posts.</p>
        
        <div style="margin: 20px 0;">
            <button id="cc-start" class="button button-primary">Start Listening</button>
            <button id="cc-stop" class="button" disabled>Stop Listening</button>
        </div>
        
        <div style="margin: 20px 0;">
            <h3>Transcript:</h3>
            <div id="cc-transcript" style="padding: 10px; background: #f0f0f0; min-height: 60px; border: 1px solid #ccc;">
                Click "Start Listening" to begin...
            </div>
        </div>
        
        <div style="margin: 20px 0;">
            <h3>AI Response:</h3>
            <div id="cc-response" style="padding: 10px; background: #f9f9f9; min-height: 100px; border: 1px solid #ccc;">
                Response will appear here...
            </div>
        </div>
        
        <div style="margin: 20px 0;">
            <button id="cc-create-draft" class="button button-secondary" style="display:none;">Create Draft Post</button>
        </div>
    </div>
    <?php
    wp_enqueue_script('cc-admin-js', plugin_dir_url(__FILE__) . 'js/cc-admin.js', [], '0.1', true);
    wp_localize_script('cc-admin-js', 'ccData', [
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce' => $nonce
    ]);
}

add_action('wp_ajax_cc_call_openai', 'cc_call_openai_handler');
function cc_call_openai_handler() {
    check_ajax_referer('cc-nonce');
    if ( ! current_user_can('manage_options') ) {
        wp_send_json_error(['error' => 'permission_denied']);
    }
    $prompt = isset($_POST['prompt']) ? sanitize_text_field(wp_unslash($_POST['prompt'])) : '';
    if ( empty($prompt) ) wp_send_json_error(['error' => 'empty_prompt']);

    // Read OpenAI key from environment or defined constant. DO NOT store keys in code.
    $openai_key = getenv('OPENAI_API_KEY') ?: ( defined('OPENAI_API_KEY') ? OPENAI_API_KEY : '' );
    if ( ! $openai_key ) {
        wp_send_json_error(['error' => 'missing_openai_key', 'message' => 'OpenAI API key not found in environment or OPENAI_API_KEY constant. Add it to server env or wp-config.php.']);
    }

    $model = getenv('OPENAI_MODEL') ?: 'gpt-4o-mini';

    $system_msg = "You are 3000 Studios assistant. Reply with clear text the admin can read. If asked to provide patches, describe actions as simple JSON in plain text. Do not include secrets.";

    $body = wp_json_encode([
        'model' => $model,
        'messages' => [
            ['role' => 'system', 'content' => $system_msg],
            ['role' => 'user', 'content' => $prompt]
        ],
        'max_tokens' => 800
    ]);

    $resp = wp_remote_post('https://api.openai.com/v1/chat/completions', [
        'headers' => [
            'Authorization' => 'Bearer ' . $openai_key,
            'Content-Type'  => 'application/json'
        ],
        'body' => $body,
        'timeout' => 30,
    ]);

    if ( is_wp_error($resp) ) {
        wp_send_json_error(['error' => 'request_failed', 'message' => $resp->get_error_message()]);
    }
    $code = wp_remote_retrieve_response_code($resp);
    $body = wp_remote_retrieve_body($resp);

    // Return raw API body to client for parsing.
    wp_send_json_success(['status' => $code, 'body' => $body]);
}

add_action('wp_ajax_cc_create_draft', 'cc_create_draft_handler');
function cc_create_draft_handler() {
    check_ajax_referer('cc-nonce');
    if ( ! current_user_can('manage_options') ) {
        wp_send_json_error(['error' => 'permission_denied']);
    }
    $title = isset($_POST['title']) ? sanitize_text_field(wp_unslash($_POST['title'])) : 'AI Draft';
    $content = isset($_POST['content']) ? wp_kses_post(wp_unslash($_POST['content'])) : '';

    $postarr = [
        'post_title'   => $title,
        'post_content' => $content,
        'post_status'  => 'draft',
        'post_author'  => get_current_user_id(),
        'post_type'    => 'post'
    ];

    $id = wp_insert_post($postarr, true);
    if ( is_wp_error($id) ) {
        wp_send_json_error(['error' => 'insert_failed', 'message' => $id->get_error_message()]);
    }
    wp_send_json_success(['id' => $id]);
}
