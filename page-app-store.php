<?php
/**
 * Template Name: App Store
 * 3000 Studios App Store - Digital products and downloadables
 * 
 * @package 3000Studios
 * @copyright Copyright (c) 2025 3000 Studios. All Rights Reserved.
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<section class="section container" style="padding: 4rem 2rem; min-height: 80vh;">
  <div style="text-align: center; margin-bottom: 3rem;">
    <h1 style="font-size: 3rem; font-weight: 700; background: linear-gradient(135deg, #9d4edd, #00ffff); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; margin-bottom: 1rem;">
      3000 Studios App Store
    </h1>
    <p style="font-size: 1.2rem; color: #aaa; max-width: 800px; margin: 0 auto;">
      Premium apps, plugins, and digital downloads - Instant access
    </p>
  </div>

  <?php if (function_exists('woocommerce')) : ?>
    <!-- WooCommerce Digital Products -->
    <div class="apps-grid">
      <?php echo do_shortcode('[products limit="12" columns="4" category="digital" orderby="popularity" paginate="true"]'); ?>
    </div>
  <?php else : ?>
    <!-- Manual App Grid -->
    <div class="manual-apps-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 2rem; margin-top: 2rem;">
      
      <!-- App 1: AI Voice Assistant -->
      <div class="app-card" style="background: linear-gradient(135deg, rgba(157, 78, 221, 0.1), rgba(0, 255, 255, 0.1)); border: 1px solid #9d4edd; border-radius: 16px; padding: 2rem; transition: all 0.3s;">
        <div style="font-size: 3rem; margin-bottom: 1rem;">🎙️</div>
        <h3 style="color: #9d4edd; margin-bottom: 1rem; font-size: 1.5rem;">AI Voice Assistant</h3>
        <p style="color: #ccc; margin-bottom: 1.5rem; line-height: 1.6;">Voice-controlled AI assistant for hands-free coding and content creation</p>
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
          <span style="font-size: 2rem; font-weight: 700; color: #fff;">$29.99</span>
          <span style="color: #00ffff; font-size: 0.9rem;">⭐ 4.8/5</span>
        </div>
        <a href="#download" class="btn-download" style="display: block; text-align: center; background: linear-gradient(135deg, #9d4edd, #00ffff); color: #000; padding: 1rem 1.5rem; border-radius: 10px; text-decoration: none; font-weight: 700;">
          Download Now
        </a>
      </div>

      <!-- App 2: Code Generator Pro -->
      <div class="app-card" style="background: linear-gradient(135deg, rgba(0, 255, 255, 0.1), rgba(0, 255, 0, 0.1)); border: 1px solid #00ffff; border-radius: 16px; padding: 2rem; transition: all 0.3s;">
        <div style="font-size: 3rem; margin-bottom: 1rem;">⚡</div>
        <h3 style="color: #00ffff; margin-bottom: 1rem; font-size: 1.5rem;">Code Generator Pro</h3>
        <p style="color: #ccc; margin-bottom: 1.5rem; line-height: 1.6;">AI-powered code generation for PHP, JavaScript, Python, and more</p>
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
          <span style="font-size: 2rem; font-weight: 700; color: #fff;">$79.99</span>
          <span style="color: #00ffff; font-size: 0.9rem;">⭐ 4.9/5</span>
        </div>
        <a href="#download" class="btn-download" style="display: block; text-align: center; background: linear-gradient(135deg, #00ffff, #00ff00); color: #000; padding: 1rem 1.5rem; border-radius: 10px; text-decoration: none; font-weight: 700;">
          Download Now
        </a>
      </div>

      <!-- App 3: WordPress AI Plugin -->
      <div class="app-card" style="background: linear-gradient(135deg, rgba(0, 255, 0, 0.1), rgba(157, 78, 221, 0.1)); border: 1px solid #00ff00; border-radius: 16px; padding: 2rem; transition: all 0.3s;">
        <div style="font-size: 3rem; margin-bottom: 1rem;">🔌</div>
        <h3 style="color: #00ff00; margin-bottom: 1rem; font-size: 1.5rem;">WordPress AI Plugin</h3>
        <p style="color: #ccc; margin-bottom: 1.5rem; line-height: 1.6;">Complete AI integration for WordPress with auto-content generation</p>
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
          <span style="font-size: 2rem; font-weight: 700; color: #fff;">$149.99</span>
          <span style="color: #00ffff; font-size: 0.9rem;">⭐ 5.0/5</span>
        </div>
        <a href="#download" class="btn-download" style="display: block; text-align: center; background: linear-gradient(135deg, #00ff00, #9d4edd); color: #000; padding: 1rem 1.5rem; border-radius: 10px; text-decoration: none; font-weight: 700;">
          Download Now
        </a>
      </div>

      <!-- App 4: Mobile Dev Toolkit -->
      <div class="app-card" style="background: linear-gradient(135deg, rgba(255, 0, 255, 0.1), rgba(0, 255, 255, 0.1)); border: 1px solid #ff00ff; border-radius: 16px; padding: 2rem; transition: all 0.3s;">
        <div style="font-size: 3rem; margin-bottom: 1rem;">📱</div>
        <h3 style="color: #ff00ff; margin-bottom: 1rem; font-size: 1.5rem;">Mobile Dev Toolkit</h3>
        <p style="color: #ccc; margin-bottom: 1.5rem; line-height: 1.6;">Complete toolkit for mobile development with Termux integration</p>
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
          <span style="font-size: 2rem; font-weight: 700; color: #fff;">$59.99</span>
          <span style="color: #00ffff; font-size: 0.9rem;">⭐ 4.7/5</span>
        </div>
        <a href="#download" class="btn-download" style="display: block; text-align: center; background: linear-gradient(135deg, #ff00ff, #00ffff); color: #000; padding: 1rem 1.5rem; border-radius: 10px; text-decoration: none; font-weight: 700;">
          Download Now
        </a>
      </div>

    </div>

    <!-- Download Instructions -->
    <div style="margin-top: 4rem; padding: 2rem; background: rgba(157, 78, 221, 0.1); border: 1px solid #9d4edd; border-radius: 12px;">
      <h2 style="color: #9d4edd; margin-bottom: 1.5rem; text-align: center;">How It Works</h2>
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 2rem; text-align: center;">
        <div>
          <div style="font-size: 2.5rem; margin-bottom: 0.5rem;">1️⃣</div>
          <h3 style="color: #fff; margin-bottom: 0.5rem;">Choose Your App</h3>
          <p style="color: #aaa; font-size: 0.9rem;">Select from our premium collection</p>
        </div>
        <div>
          <div style="font-size: 2.5rem; margin-bottom: 0.5rem;">2️⃣</div>
          <h3 style="color: #fff; margin-bottom: 0.5rem;">Secure Payment</h3>
          <p style="color: #aaa; font-size: 0.9rem;">Pay via Stripe, PayPal, or Cash App</p>
        </div>
        <div>
          <div style="font-size: 2.5rem; margin-bottom: 0.5rem;">3️⃣</div>
          <h3 style="color: #fff; margin-bottom: 0.5rem;">Instant Download</h3>
          <p style="color: #aaa; font-size: 0.9rem;">Get immediate access to your purchase</p>
        </div>
        <div>
          <div style="font-size: 2.5rem; margin-bottom: 0.5rem;">4️⃣</div>
          <h3 style="color: #fff; margin-bottom: 0.5rem;">Free Updates</h3>
          <p style="color: #aaa; font-size: 0.9rem;">Lifetime updates included</p>
        </div>
      </div>
    </div>
  <?php endif; ?>

  <style>
    .app-card:hover {
      transform: translateY(-8px) scale(1.02);
      box-shadow: 0 15px 40px rgba(157, 78, 221, 0.4);
    }
    .btn-download:hover {
      opacity: 0.9;
      transform: scale(1.05);
      box-shadow: 0 8px 20px rgba(0, 255, 255, 0.3);
    }
  </style>
</section>

<?php get_footer(); ?>