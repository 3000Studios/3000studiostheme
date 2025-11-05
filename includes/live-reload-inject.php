<?php

/**
 * Copyright (c) 2025 Mr. jwswain - 3000 Studios. All Rights Reserved.
 * Black Vault SUPREME Live Reload Injection
 */

if (!defined('ABSPATH')) exit;

/**
 * Inject live reload script into footer
 */
function studios_inject_live_reload()
{
    // Only in development mode - NEVER in production
    if (!defined('WP_DEBUG') || !WP_DEBUG) {
        return;
    }

    // Additional safety check - only on localhost
    $is_local = (
        isset($_SERVER['HTTP_HOST']) && (
            strpos($_SERVER['HTTP_HOST'], 'localhost') !== false ||
            strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false ||
            strpos($_SERVER['HTTP_HOST'], '.local') !== false
        )
    );

    if (!$is_local) {
        return;
    }

    // Check if live reload is enabled via option or constant
    $live_reload_enabled = get_option('studios_live_reload_enabled', false);
    if (defined('STUDIOS_LIVE_RELOAD')) {
        $live_reload_enabled = STUDIOS_LIVE_RELOAD;
    }

    if (!$live_reload_enabled) {
        return;
    }

?>
    <script id="studios-live-reload">
        (function() {
            'use strict';

            const WEBSOCKET_URL = 'ws://localhost:3002';
            const RECONNECT_DELAY = 3000;
            const CHECK_INTERVAL = 5000;

            console.log('%c⚡ 3000 Studios Live Reload Active', 'color: #00ffe7; font-weight: bold; font-size: 14px;');

            let ws = null;
            let reconnectTimer = null;
            let checkTimer = null;
            let lastVersion = null;

            // WebSocket connection
            function connect() {
                try {
                    ws = new WebSocket(WEBSOCKET_URL);

                    ws.onopen = function() {
                        console.log('%c✅ Live reload connected', 'color: #00ff66;');
                        clearTimeout(reconnectTimer);

                        // Start checking for version changes
                        startVersionCheck();
                    };

                    ws.onmessage = function(event) {
                        try {
                            const data = JSON.parse(event.data);

                            if (data.type === 'reload') {
                                console.log('%c🔄 Theme updated - Reloading...', 'color: #f5c84b; font-weight: bold;');
                                showReloadNotification(data.message);
                                setTimeout(() => location.reload(), 500);
                            }
                        } catch (e) {
                            console.error('Live reload message error:', e);
                        }
                    };

                    ws.onerror = function(error) {
                        console.warn('Live reload connection error:', error);
                    };

                    ws.onclose = function() {
                        console.log('%c❌ Live reload disconnected', 'color: #ff6b6b;');
                        stopVersionCheck();
                        scheduleReconnect();
                    };

                } catch (e) {
                    console.warn('Could not connect to live reload server:', e);
                    scheduleReconnect();
                }
            }

            function scheduleReconnect() {
                clearTimeout(reconnectTimer);
                reconnectTimer = setTimeout(() => {
                    console.log('🔄 Attempting to reconnect...');
                    connect();
                }, RECONNECT_DELAY);
            }

            // Fallback: Check version via HTTP
            function startVersionCheck() {
                checkTimer = setInterval(checkVersion, CHECK_INTERVAL);
                checkVersion(); // Check immediately
            }

            function stopVersionCheck() {
                if (checkTimer) {
                    clearInterval(checkTimer);
                    checkTimer = null;
                }
            }

            async function checkVersion() {
                try {
                    const response = await fetch(window.location.href, {
                        method: 'HEAD',
                        cache: 'no-cache'
                    });

                    const etag = response.headers.get('ETag');
                    const lastModified = response.headers.get('Last-Modified');
                    const version = etag || lastModified;

                    if (lastVersion === null) {
                        lastVersion = version;
                    } else if (version !== lastVersion) {
                        console.log('%c🔄 New version detected - Reloading...', 'color: #f5c84b; font-weight: bold;');
                        showReloadNotification('Theme updated!');
                        setTimeout(() => location.reload(), 500);
                    }
                } catch (e) {
                    // Silently fail - server might be down
                }
            }

            // Visual notification
            function showReloadNotification(message) {
                const notification = document.createElement('div');
                notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: linear-gradient(135deg, #00ffe7, #00ff66);
                color: #000;
                padding: 1rem 1.5rem;
                border-radius: 12px;
                font-family: 'Orbitron', sans-serif;
                font-weight: bold;
                z-index: 999999;
                box-shadow: 0 4px 20px rgba(0, 255, 231, 0.5);
                animation: slideIn 0.3s ease-out;
            `;
                notification.textContent = '⚡ ' + message;

                // Add animation
                const style = document.createElement('style');
                style.textContent = `
                @keyframes slideIn {
                    from { transform: translateX(400px); opacity: 0; }
                    to { transform: translateX(0); opacity: 1; }
                }
            `;
                document.head.appendChild(style);

                document.body.appendChild(notification);
            }

            // Start connection
            connect();

            // Cleanup on page unload
            window.addEventListener('beforeunload', function() {
                if (ws) {
                    ws.close();
                }
                clearTimeout(reconnectTimer);
                stopVersionCheck();
            });

        })();
    </script>
<?php
}
add_action('wp_footer', 'studios_inject_live_reload', 999);

/**
 * Add version query string to bust cache
 */
function studios_cache_buster($src)
{
    if (!defined('WP_DEBUG') || !WP_DEBUG) {
        return $src;
    }

    // Only bust cache for theme files
    $theme_url = get_stylesheet_directory_uri();
    if (strpos($src, $theme_url) !== false) {
        $version = filemtime(get_stylesheet_directory() . '/style.css');
        $separator = (strpos($src, '?') === false) ? '?' : '&';
        return $src . $separator . 'v=' . $version;
    }

    return $src;
}
add_filter('style_loader_src', 'studios_cache_buster', 999);
add_filter('script_loader_src', 'studios_cache_buster', 999);
