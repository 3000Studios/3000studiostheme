<?php
/**
 * Plugin Name: Voice Admin Editor (MVP)
 * Description: Voice-driven admin editor for content and CSS. Secure: backups, capability checks, nonces. GitHub PR support (optional).
 * Version: 0.1
 * Author: 3000Studios (Copilot)
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'VOICE_EDITOR_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'VOICE_EDITOR_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

class Voice_Admin_Editor {
    public function __construct() {
        add_action( 'admin_menu', [ $this, 'admin_menu' ] );
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_assets' ] );
        add_action( 'rest_api_init', [ $this, 'register_routes' ] );
        add_action( 'wp_enqueue_scripts', [ $this, 'inject_custom_css' ], 100 );
    }

    public function admin_menu() {
        add_management_page(
            'Voice Editor',
            'Voice Editor',
            'manage_options',
            'voice-admin-editor',
            [ $this, 'page_ui' ]
        );
        add_submenu_page(
            'options-general.php',
            'Voice Editor Settings',
            'Voice Editor Settings',
            'manage_options',
            'voice-admin-editor-settings',
            [ $this, 'settings_page' ]
        );
    }

    public function enqueue_assets( $hook ) {
        if ( ! in_array( $hook, [ 'tools_page_voice-admin-editor', 'settings_page_voice-admin-editor-settings' ], true ) ) {
            return;
        }
        wp_enqueue_style( 'voice-admin-editor-css', VOICE_EDITOR_PLUGIN_URL . 'assets/css/admin-voice.css', [], '0.1' );
        wp_enqueue_script( 'voice-admin-editor-js', VOICE_EDITOR_PLUGIN_URL . 'assets/js/admin-voice.js', [ 'jquery' ], '0.1', true );
        wp_localize_script( 'voice-admin-editor-js', 'VoiceEditor', [
            'ajax_url'     => esc_url( rest_url( 'voice-editor/v1/action' ) ),
            'nonce'        => wp_create_nonce( 'voice-editor-nonce' ),
            'currentUser'  => get_current_user_id(),
            'pluginUrl'    => VOICE_EDITOR_PLUGIN_URL,
            'githubAllowed'=> defined('VOICE_EDITOR_GITHUB_TOKEN') || get_option('voice_editor_github_token', '') ? true : false,
        ] );
    }

    public function page_ui() {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( 'Insufficient permissions' );
        }
        ?>
        <div class="wrap voice-editor-wrap">
            <h1>Voice Admin Editor</h1>
            <p>Use your browser's microphone to speak commands. Examples: "edit post 123 set content to Hello world", "update css add body background red".</p>

            <div class="voice-controls">
                <button id="ve-start" class="button button-primary">Start Listening</button>
                <button id="ve-stop" class="button">Stop</button>
                <span id="ve-status">Idle</span>
            </div>

            <div class="voice-result">
                <label>Recognized text</label>
                <textarea id="ve-recognized" rows="4" placeholder="Recognized speech will appear here"></textarea>
            </div>

            <div class="voice-actions">
                <label>Select action</label>
                <select id="ve-action">
                    <option value="apply_text">Apply as text to active target</option>
                    <option value="edit_post">Edit post/page by ID</option>
                    <option value="append_css">Append to site custom CSS</option>
                    <option value="create_change_log">Create change record (do not apply)</option>
                    <option value="create_github_pr">Create GitHub PR (advanced)</option>
                </select>
                <input placeholder="Post ID (if editing post)" id="ve-post-id" type="number" />
                <button id="ve-apply" class="button button-primary">Apply</button>
            </div>

            <div id="ve-response" class="ve-response" aria-live="polite"></div>

            <h2>Backups & Audit</h2>
            <p>The plugin keeps backups of prior values (CSS, post content) before applying changes.</p>
            <button id="ve-show-backups" class="button">Show recent backups</button>
            <div id="ve-backups"></div>
        </div>
        <?php
    }

    public function settings_page() {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( 'Insufficient permissions' );
        }

        if ( isset( $_POST['voice_editor_settings_nonce'] ) && wp_verify_nonce( $_POST['voice_editor_settings_nonce'], 'voice_editor_settings' ) ) {
            if ( isset( $_POST['voice_editor_github_token'] ) ) {
                update_option( 'voice_editor_github_token', sanitize_text_field( wp_unslash( $_POST['voice_editor_github_token'] ) ) );
                echo '<div class="updated"><p>Saved.</p></div>';
            }
        }

        $token = get_option( 'voice_editor_github_token', '' );
        ?>
        <div class="wrap">
            <h1>Voice Editor Settings</h1>
            <form method="post">
                <?php wp_nonce_field( 'voice_editor_settings', 'voice_editor_settings_nonce' ); ?>
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="voice_editor_github_token">GitHub Token (optional)</label></th>
                        <td>
                            <input name="voice_editor_github_token" type="password" id="voice_editor_github_token" value="<?php echo esc_attr( $token ? '********' : '' ); ?>" class="regular-text" />
                            <p class="description">Optional: store a GitHub token here to allow the plugin to create branches/PRs. Better: define VOICE_EDITOR_GITHUB_TOKEN in wp-config.php.</p>
                        </td>
                    </tr>
                </table>
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }

    public function register_routes() {
        register_rest_route( 'voice-editor/v1', '/action', [
            'methods'             => 'POST',
            'callback'            => [ $this, 'handle_action' ],
            'permission_callback' => function () {
                return current_user_can( 'manage_options' );
            },
        ] );

        register_rest_route( 'voice-editor/v1', '/backups', [
            'methods'             => 'GET',
            'callback'            => [ $this, 'get_backups' ],
            'permission_callback' => function () {
                return current_user_can( 'manage_options' );
            },
        ] );
    }

    private function _log_action( $entry ) {
        $log = get_option( 'voice_editor_log', [] );
        array_unshift( $log, $entry );
        $log = array_slice( $log, 0, 200 );
        update_option( 'voice_editor_log', $log );
    }

    public function get_backups() {
        $backups = get_option( 'voice_editor_backups', [] );
        return rest_ensure_response( $backups );
    }

    public function handle_action( WP_REST_Request $request ) {
        $body = $request->get_json_params();
        $nonce = $body['nonce'] ?? '';
        if ( ! wp_verify_nonce( $nonce, 'voice-editor-nonce' ) ) {
            return new WP_Error( 'bad_nonce', 'Invalid nonce', [ 'status' => 403 ] );
        }
        $cmd = sanitize_text_field( $body['command'] ?? '' );
        $action = sanitize_text_field( $body['action'] ?? '' );
        $post_id = isset( $body['post_id'] ) ? intval( $body['post_id'] ) : 0;

        $result = [ 'ok' => false ];

        if ( $action === 'edit_post' && $post_id > 0 ) {
            $post = get_post( $post_id );
            if ( ! $post ) {
                return new WP_Error( 'no_post', 'Post not found', [ 'status' => 404 ] );
            }
            $old = $post->post_content;
            $this->backup( "post_{$post_id}", $old );
            $updated = wp_update_post( [
                'ID'           => $post_id,
                'post_content' => wp_kses_post( $cmd ),
            ], true );
            if ( is_wp_error( $updated ) ) {
                return $updated;
            }
            $result = [ 'ok' => true, 'msg' => 'Post updated', 'post_id' => $post_id ];
            $this->_log_action( [ 'time' => time(), 'action' => 'edit_post', 'post_id' => $post_id, 'actor' => get_current_user_id() ] );
            return rest_ensure_response( $result );
        }

        if ( $action === 'append_css' ) {
            $old = get_option( 'voice_editor_custom_css', '' );
            $this->backup( 'custom_css', $old );
            $new_css = $old . "\n/* added " . date_i18n( 'c' ) . " */\n" . $cmd;
            update_option( 'voice_editor_custom_css', $new_css );
            $result = [ 'ok' => true, 'msg' => 'CSS updated' ];
            $this->_log_action( [ 'time' => time(), 'action' => 'append_css', 'actor' => get_current_user_id() ] );
            return rest_ensure_response( $result );
        }

        if ( $action === 'create_change_log' ) {
            $logs = get_option( 'voice_editor_change_records', [] );
            $record = [
                'time'    => time(),
                'actor'   => get_current_user_id(),
                'command' => $cmd,
            ];
            array_unshift( $logs, $record );
            update_option( 'voice_editor_change_records', array_slice( $logs, 0, 500 ) );
            $result = [ 'ok' => true, 'msg' => 'Change recorded (not applied)' ];
            $this->_log_action( [ 'time' => time(), 'action' => 'create_change_log', 'actor' => get_current_user_id() ] );
            return rest_ensure_response( $result );
        }

        if ( $action === 'create_github_pr' ) {
            $token = defined( 'VOICE_EDITOR_GITHUB_TOKEN' ) ? VOICE_EDITOR_GITHUB_TOKEN : get_option( 'voice_editor_github_token', '' );
            if ( ! $token ) {
                return new WP_Error( 'no_token', 'GitHub token not configured', [ 'status' => 400 ] );
            }
            $repo = get_option( 'voice_editor_github_repo', '' );
            if ( ! $repo ) {
                return new WP_Error( 'no_repo', 'GitHub repo not configured', [ 'status' => 400 ] );
            }
            $branch = 'voice-edit/'.time();
            $file_path = 'voice-edits/' . sanitize_file_name( 'edit-' . time() ) . '.txt';
            $content = "Voice edit created by user " . get_current_user_id() . " at " . date_i18n( 'c' ) . "\n\nCommand:\n" . $cmd;
            $gh = new Voice_Editor_GitHub( $token, $repo );
            try {
                $created = $gh->create_branch_and_file_and_pr( $branch, $file_path, $content, 'Voice Editor change' );
                $this->_log_action( [ 'time' => time(), 'action' => 'create_github_pr', 'actor' => get_current_user_id(), 'pr' => $created ] );
                return rest_ensure_response( [ 'ok' => true, 'pr' => $created ] );
            } catch ( Exception $e ) {
                return new WP_Error( 'gh_error', $e->getMessage(), [ 'status' => 500 ] );
            }
        }

        return new WP_Error( 'invalid_action', 'Action not recognized', [ 'status' => 400 ] );
    }

    private function backup( $key, $value ) {
        $backups = get_option( 'voice_editor_backups', [] );
        $backups_entry = [
            'time' => time(),
            'key'  => $key,
            'value'=> $value,
            'actor'=> get_current_user_id(),
        ];
        array_unshift( $backups, $backups_entry );
        update_option( 'voice_editor_backups', array_slice( $backups, 0, 200 ) );
    }

    public function inject_custom_css() {
        $css = get_option( 'voice_editor_custom_css', '' );
        if ( $css ) {
            wp_add_inline_style( 'wp-block-library', $css );
        }
    }
}
new Voice_Admin_Editor();

class Voice_Editor_GitHub {
    private $token;
    private $repo;

    public function __construct( $token, $repo ) {
        $this->token = $token;
        $this->repo  = $repo;
    }

    private function gh_request( $method, $path, $body = null ) {
        $url = "https://api.github.com/repos/{$this->repo}{$path}";
        $args = [
            'method'  => strtoupper( $method ),
            'headers' => [
                'Authorization' => 'token ' . $this->token,
                'User-Agent'    => 'voice-admin-editor/0.1',
                'Accept'        => 'application/vnd.github.v3+json',
            ],
        ];
        if ( $body !== null ) {
            $args['body'] = wp_json_encode( $body );
            $args['headers']['Content-Type'] = 'application/json';
        }
        $response = wp_remote_request( $url, $args );
        $code = wp_remote_retrieve_response_code( $response );
        $body = wp_remote_retrieve_body( $response );
        $data = json_decode( $body, true );
        if ( $code < 200 || $code >= 300 ) {
            throw new Exception( "GitHub API error: {$code} - " . ( is_array( $data ) ? json_encode( $data ) : $body ) );
        }
        return $data;
    }

    public function create_branch_and_file_and_pr( $branch, $file_path, $content, $commit_message ) {
        $repoData = $this->gh_request( 'GET', '' );
        $defaultBranch = $repoData['default_branch'] ?? 'main';
        $ref = $this->gh_request( 'GET', "/git/ref/heads/{$defaultBranch}" );
        $sha = $ref['object']['sha'];

        $this->gh_request( 'POST', '/git/refs', [
            'ref' => 'refs/heads/' . $branch,
            'sha' => $sha,
        ] );

        $this->gh_request( 'PUT', "/contents/{$file_path}", [
            'message' => $commit_message,
            'content' => base64_encode( $content ),
            'branch'  => $branch,
        ] );

        $pr = $this->gh_request( 'POST', '/pulls', [
            'title' => $commit_message,
            'head'  => $branch,
            'base'  => $defaultBranch,
            'body'  => 'Voice Editor generated change',
        ] );
        return $pr;
    }
}
