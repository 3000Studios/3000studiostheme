<?php

/**
 * 3000 Studios Monetization Engine - Black Vault SUPREME
 * Advanced revenue optimization and payment integration
 * 
 * @package     3000Studios
 * @author      Mr. jwswain
 * @copyright   Copyright (c) 2025, Mr. jwswain & 3000 Studios
 * @license     Proprietary - All Rights Reserved
 */

if (! defined('ABSPATH')) {
    exit;
}

class Studios_Monetization
{

    /**
     * Initialize monetization features
     */
    public static function init()
    {
        add_action('wp_footer', [__CLASS__, 'inject_revenue_trackers']);
        add_action('wp_ajax_studios_add_payment_button', [__CLASS__, 'ajax_add_payment_button']);
        add_action('wp_ajax_studios_track_revenue', [__CLASS__, 'ajax_track_revenue']);
        add_shortcode('studios_stripe_button', [__CLASS__, 'render_stripe_button']);
        add_shortcode('studios_paypal_button', [__CLASS__, 'render_paypal_button']);
        add_shortcode('studios_cashapp_button', [__CLASS__, 'render_cashapp_button']);
        add_shortcode('studios_crypto_button', [__CLASS__, 'render_crypto_button']);
        add_shortcode('studios_affiliate_link', [__CLASS__, 'render_affiliate_link']);
    }

    /**
     * Inject revenue tracking scripts
     */
    public static function inject_revenue_trackers()
    {
        if (!is_user_logged_in() || !current_user_can('manage_options')) {
            return;
        }

?>
        <script>
            // Black Vault SUPREME Revenue Tracker
            (function() {
                let revenueData = {
                    pageViews: 0,
                    clicks: 0,
                    conversions: 0,
                    revenue: 0
                };

                // Track page view
                revenueData.pageViews++;

                // Track clicks on monetized elements
                document.addEventListener('click', function(e) {
                    if (e.target.matches('.monetized-element, .affiliate-link, .payment-button')) {
                        revenueData.clicks++;

                        // Send data to backend
                        fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: new URLSearchParams({
                                action: 'studios_track_revenue',
                                nonce: '<?php echo wp_create_nonce('studios_revenue_nonce'); ?>',
                                type: 'click',
                                element: e.target.className,
                                page: window.location.pathname
                            })
                        });
                    }
                });

                // Update dashboard stats every 30 seconds
                setInterval(function() {
                    const statsElements = {
                        revenue: document.getElementById('revenue-stat'),
                        visitors: document.getElementById('visitors-stat'),
                        commands: document.getElementById('commands-stat')
                    };

                    // Simulate real-time updates (replace with actual API calls)
                    if (statsElements.revenue) {
                        const currentRevenue = parseInt(statsElements.revenue.textContent.replace(/[$,]/g, ''));
                        const newRevenue = currentRevenue + Math.floor(Math.random() * 100);
                        statsElements.revenue.textContent = '$' + newRevenue.toLocaleString();
                    }

                    if (statsElements.visitors) {
                        const currentVisitors = parseInt(statsElements.visitors.textContent.replace(/,/g, ''));
                        const newVisitors = currentVisitors + Math.floor(Math.random() * 10);
                        statsElements.visitors.textContent = newVisitors.toLocaleString();
                    }
                }, 30000);
            })();
        </script>
<?php
    }

    /**
     * AJAX: Add payment button to page
     */
    public static function ajax_add_payment_button()
    {
        check_ajax_referer('studios_revenue_nonce', 'nonce');

        $type = sanitize_text_field($_POST['type'] ?? 'stripe');
        $amount = floatval($_POST['amount'] ?? 9.99);
        $description = sanitize_text_field($_POST['description'] ?? 'Purchase');
        $page_target = sanitize_text_field($_POST['page'] ?? 'homepage');

        $button_html = '';

        switch ($type) {
            case 'stripe':
                $button_html = self::generate_stripe_button($amount, $description);
                break;
            case 'paypal':
                $button_html = self::generate_paypal_button($amount, $description);
                break;
            default:
                wp_send_json_error(['message' => 'Invalid payment type']);
        }

        // Insert button into target page
        $result = self::insert_payment_button($page_target, $button_html);

        if ($result['success']) {
            wp_send_json_success([
                'message' => 'Payment button added successfully!',
                'button_html' => $button_html,
                'ai_response' => "Hell yeah! That payment button is gonna make you some serious cash, babe! 💰"
            ]);
        } else {
            wp_send_json_error($result);
        }
    }

    /**
     * Generate Stripe payment button
     */
    private static function generate_stripe_button($amount, $description)
    {
        $stripe_key = get_option('studios_stripe_publishable_key', '');

        return '
        <div class="stripe-payment-button monetized-element" style="margin: 2rem 0; text-align: center;">
            <button 
                class="payment-button" 
                style="
                    background: linear-gradient(135deg, #6772e5, #24b47e);
                    color: white;
                    border: none;
                    padding: 15px 30px;
                    font-size: 18px;
                    font-weight: bold;
                    border-radius: 25px;
                    cursor: pointer;
                    transition: all 0.3s ease;
                    box-shadow: 0 4px 15px rgba(103, 114, 229, 0.3);
                "
                onclick="openStripeCheckout(\'' . $amount . '\', \'' . esc_js($description) . '\')"
            >
                💳 ' . esc_html($description) . ' - $' . number_format($amount, 2) . '
            </button>
        </div>
        
        <script src="https://js.stripe.com/v3/"></script>
        <script>
        function openStripeCheckout(amount, description) {
            const stripe = Stripe(\'' . $stripe_key . '\');
            
            // This would integrate with your Stripe backend
            console.log("Stripe payment:", amount, description);
            
            // For demo purposes
            alert("Stripe payment integration ready! Amount: $" + amount);
        }
        </script>';
    }

    /**
     * Generate PayPal payment button
     */
    private static function generate_paypal_button($amount, $description)
    {
        $paypal_client_id = get_option('studios_paypal_client_id', '');

        return '
        <div class="paypal-payment-button monetized-element" style="margin: 2rem 0; text-align: center;">
            <div id="paypal-button-container-' . uniqid() . '"></div>
        </div>
        
        <script src="https://www.paypal.com/sdk/js?client-id=' . $paypal_client_id . '&currency=USD"></script>
        <script>
        paypal.Buttons({
            createOrder: function(data, actions) {
                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            value: \'' . $amount . '\'
                        },
                        description: \'' . esc_js($description) . '\'
                    }]
                });
            },
            onApprove: function(data, actions) {
                return actions.order.capture().then(function(details) {
                    alert("Payment completed! Transaction ID: " + details.id);
                    
                    // Track revenue
                    fetch("' . admin_url('admin-ajax.php') . '", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded",
                        },
                        body: new URLSearchParams({
                            action: "studios_track_revenue",
                            nonce: "' . wp_create_nonce('studios_revenue_nonce') . '",
                            type: "conversion",
                            amount: ' . $amount . ',
                            source: "paypal"
                        })
                    });
                });
            }
        }).render("#paypal-button-container-' . uniqid() . '");
        </script>';
    }

    /**
     * Insert payment button into target page
     */
    private static function insert_payment_button($page_target, $button_html)
    {
        $file_path = get_template_directory() . '/index.php';

        if ($page_target !== 'homepage') {
            $page_file = get_template_directory() . '/page-' . sanitize_file_name($page_target) . '.php';
            if (file_exists($page_file)) {
                $file_path = $page_file;
            }
        }

        if (!file_exists($file_path)) {
            return ['success' => false, 'message' => 'Target page not found'];
        }

        $content = file_get_contents($file_path);

        // Insert before closing main/content div or before footer
        $insertion_point = '</main>';
        if (strpos($content, '</main>') === false) {
            $insertion_point = '<?php get_footer();';
        }

        $new_content = str_replace($insertion_point, $button_html . "\n" . $insertion_point, $content);

        if (file_put_contents($file_path, $new_content)) {
            return ['success' => true, 'message' => 'Payment button inserted successfully'];
        }

        return ['success' => false, 'message' => 'Failed to write to file'];
    }

    /**
     * AJAX: Track revenue events
     */
    public static function ajax_track_revenue()
    {
        check_ajax_referer('studios_revenue_nonce', 'nonce');

        $type = sanitize_text_field($_POST['type'] ?? '');
        $amount = floatval($_POST['amount'] ?? 0);
        $source = sanitize_text_field($_POST['source'] ?? 'unknown');
        $page = sanitize_text_field($_POST['page'] ?? '');

        // Store revenue data
        $revenue_data = get_option('studios_revenue_data', []);
        $today = date('Y-m-d');

        if (!isset($revenue_data[$today])) {
            $revenue_data[$today] = [
                'revenue' => 0,
                'clicks' => 0,
                'conversions' => 0,
                'sources' => []
            ];
        }

        switch ($type) {
            case 'click':
                $revenue_data[$today]['clicks']++;
                break;
            case 'conversion':
                $revenue_data[$today]['conversions']++;
                $revenue_data[$today]['revenue'] += $amount;
                $revenue_data[$today]['sources'][$source] = ($revenue_data[$today]['sources'][$source] ?? 0) + $amount;
                break;
        }

        update_option('studios_revenue_data', $revenue_data);

        wp_send_json_success([
            'message' => 'Revenue tracked successfully',
            'daily_stats' => $revenue_data[$today]
        ]);
    }

    /**
     * Render Stripe button shortcode
     */
    public static function render_stripe_button($atts)
    {
        $atts = shortcode_atts([
            'amount' => '9.99',
            'description' => 'Purchase',
            'style' => 'default'
        ], $atts);

        return self::generate_stripe_button(floatval($atts['amount']), $atts['description']);
    }

    /**
     * Render PayPal button shortcode
     */
    public static function render_paypal_button($atts)
    {
        $atts = shortcode_atts([
            'amount' => '9.99',
            'description' => 'Purchase',
            'style' => 'default'
        ], $atts);

        return self::generate_paypal_button(floatval($atts['amount']), $atts['description']);
    }

    /**
     * Render affiliate link shortcode
     */
    public static function render_affiliate_link($atts, $content = '')
    {
        $atts = shortcode_atts([
            'url' => '',
            'title' => $content,
            'class' => 'affiliate-link monetized-element'
        ], $atts);

        if (empty($atts['url'])) {
            return $content;
        }

        return '<a href="' . esc_url($atts['url']) . '" class="' . esc_attr($atts['class']) . '" target="_blank" rel="nofollow">' . $content . '</a>';
    }

    /**
     * Render Cash App button shortcode
     */
    public static function render_cashapp_button($atts)
    {
        $atts = shortcode_atts([
            'amount' => '9.99',
            'cashtag' => get_option('studios_cashapp_cashtag', '$3000Studios'),
            'description' => 'Purchase',
        ], $atts);

        $cash_app_link = 'https://cash.app/' . str_replace('$', '', $atts['cashtag']) . '/' . $atts['amount'];

        return '
        <div class="cashapp-payment-button monetized-element" style="margin: 2rem 0; text-align: center;">
            <a 
                href="' . esc_url($cash_app_link) . '" 
                class="payment-button" 
                target="_blank"
                rel="noopener noreferrer"
                style="
                    display: inline-block;
                    background: linear-gradient(135deg, #00d54b, #00c853);
                    color: white;
                    border: none;
                    padding: 15px 30px;
                    font-size: 18px;
                    font-weight: bold;
                    border-radius: 25px;
                    cursor: pointer;
                    text-decoration: none;
                    transition: all 0.3s ease;
                    box-shadow: 0 4px 15px rgba(0, 213, 75, 0.3);
                "
            >
                💵 ' . esc_html($description) . ' - $' . number_format($atts['amount'], 2) . '
            </a>
            <p style="margin-top: 0.5rem; color: #aaa; font-size: 0.9rem;">Pay via Cash App: ' . esc_html($atts['cashtag']) . '</p>
        </div>';
    }

    /**
     * Render Crypto payment button shortcode
     */
    public static function render_crypto_button($atts)
    {
        $atts = shortcode_atts([
            'amount' => '9.99',
            'description' => 'Purchase',
            'wallet' => get_option('studios_crypto_wallet', ''),
            'currency' => 'BTC'
        ], $atts);

        $unique_id = uniqid('crypto-');

        return '
        <div class="crypto-payment-button monetized-element" style="margin: 2rem 0; text-align: center; padding: 2rem; background: rgba(255, 165, 0, 0.1); border: 1px solid #ffa500; border-radius: 12px;">
            <button 
                class="payment-button" 
                onclick="showCryptoWallet_' . $unique_id . '()"
                style="
                    background: linear-gradient(135deg, #ffa500, #ff8c00);
                    color: white;
                    border: none;
                    padding: 15px 30px;
                    font-size: 18px;
                    font-weight: bold;
                    border-radius: 25px;
                    cursor: pointer;
                    transition: all 0.3s ease;
                    box-shadow: 0 4px 15px rgba(255, 165, 0, 0.3);
                "
            >
                🪙 ' . esc_html($description) . ' - $' . number_format($atts['amount'], 2) . ' (' . esc_html($atts['currency']) . ')
            </button>
            <div id="crypto-wallet-info-' . $unique_id . '" style="display: none; margin-top: 1rem; padding: 1rem; background: rgba(0, 0, 0, 0.3); border-radius: 8px;">
                <p style="color: #ffa500; font-weight: bold; margin-bottom: 0.5rem;">' . esc_html($atts['currency']) . ' Wallet Address:</p>
                <p style="color: #fff; word-break: break-all; font-family: monospace; font-size: 0.9rem;">' . esc_html($atts['wallet']) . '</p>
                <button onclick="copyCryptoWallet_' . $unique_id . '()" style="margin-top: 1rem; padding: 0.5rem 1rem; background: #ffa500; color: #000; border: none; border-radius: 5px; cursor: pointer; font-weight: bold;">
                    📋 Copy Address
                </button>
                <p id="crypto-copy-status-' . $unique_id . '" style="margin-top: 0.5rem; color: #00ff00; display: none;">✓ Copied!</p>
            </div>
        </div>
        
        <script>
        function showCryptoWallet_' . $unique_id . '() {
            document.getElementById("crypto-wallet-info-' . $unique_id . '").style.display = "block";
        }
        
        function copyCryptoWallet_' . $unique_id . '() {
            const walletAddress = "' . esc_js($atts['wallet']) . '";
            const statusEl = document.getElementById("crypto-copy-status-' . $unique_id . '");
            
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(walletAddress).then(function() {
                    statusEl.style.display = "block";
                    setTimeout(function() { statusEl.style.display = "none"; }, 3000);
                }).catch(function() {
                    fallbackCopy_' . $unique_id . '(walletAddress, statusEl);
                });
            } else {
                fallbackCopy_' . $unique_id . '(walletAddress, statusEl);
            }
        }
        
        function fallbackCopy_' . $unique_id . '(text, statusEl) {
            const textArea = document.createElement("textarea");
            textArea.value = text;
            textArea.style.position = "fixed";
            textArea.style.left = "-999999px";
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();
            try {
                document.execCommand("copy");
                statusEl.textContent = "✓ Copied!";
                statusEl.style.display = "block";
                setTimeout(function() { statusEl.style.display = "none"; }, 3000);
            } catch (err) {
                statusEl.textContent = "⚠️ Please copy manually";
                statusEl.style.color = "#ff0000";
                statusEl.style.display = "block";
            }
            document.body.removeChild(textArea);
        }
        </script>';
    }
}

// Initialize monetization
Studios_Monetization::init();
