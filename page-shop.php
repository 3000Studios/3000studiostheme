
<?php
/**
 * Template Name: Shop
 * 3000 Studios Shop - Auto-products with payment integration
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
    <h1 style="font-size: 3rem; font-weight: 700; background: linear-gradient(135deg, #00ffff, #9d4edd); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; margin-bottom: 1rem;">
      3000 Studios Shop
    </h1>
    <p style="font-size: 1.2rem; color: #aaa; max-width: 800px; margin: 0 auto;">
      Premium AI tools, digital products, and exclusive content
    </p>
  </div>

  <?php if (function_exists('woocommerce')) : ?>
    <!-- WooCommerce Products -->
    <div class="products-grid">
      <?php echo do_shortcode('[products limit="12" columns="4" orderby="date" order="DESC" paginate="true"]'); ?>
    </div>
  <?php else : ?>
    <!-- Manual Product Grid (when WooCommerce is not active) -->
    <!-- NOTE: Replace #buy links with actual payment processing or use shortcodes like [studios_stripe_button amount="49.99"] -->
    <div class="manual-products-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 2rem; margin-top: 2rem;">
      
      <!-- Product 1: AI Dashboard Pro -->
      <div class="product-card" style="background: rgba(20, 20, 30, 0.8); border: 1px solid #00ffff; border-radius: 12px; padding: 2rem; transition: transform 0.3s;">
        <h3 style="color: #00ffff; margin-bottom: 1rem;">AI Dashboard Pro</h3>
        <p style="color: #ccc; margin-bottom: 1rem;">Advanced AI management and analytics dashboard</p>
        <div style="font-size: 2rem; font-weight: 700; color: #fff; margin-bottom: 1rem;">$49.99</div>
        <a href="#buy" class="btn-buy" style="display: block; text-align: center; background: linear-gradient(135deg, #00ffff, #9d4edd); color: #000; padding: 0.75rem 1.5rem; border-radius: 8px; text-decoration: none; font-weight: 600;">
          Buy Now
        </a>
      </div>

      <!-- Product 2: Theme License -->
      <div class="product-card" style="background: rgba(20, 20, 30, 0.8); border: 1px solid #9d4edd; border-radius: 12px; padding: 2rem; transition: transform 0.3s;">
        <h3 style="color: #9d4edd; margin-bottom: 1rem;">Premium Theme License</h3>
        <p style="color: #ccc; margin-bottom: 1rem;">Full 3000 Studios theme with lifetime updates</p>
        <div style="font-size: 2rem; font-weight: 700; color: #fff; margin-bottom: 1rem;">$99.99</div>
        <a href="#buy" class="btn-buy" style="display: block; text-align: center; background: linear-gradient(135deg, #9d4edd, #00ffff); color: #000; padding: 0.75rem 1.5rem; border-radius: 8px; text-decoration: none; font-weight: 600;">
          Buy Now
        </a>
      </div>

      <!-- Product 3: AI Toolkit -->
      <div class="product-card" style="background: rgba(20, 20, 30, 0.8); border: 1px solid #00ff00; border-radius: 12px; padding: 2rem; transition: transform 0.3s;">
        <h3 style="color: #00ff00; margin-bottom: 1rem;">AI Developer Toolkit</h3>
        <p style="color: #ccc; margin-bottom: 1rem;">Complete AI development tools and APIs</p>
        <div style="font-size: 2rem; font-weight: 700; color: #fff; margin-bottom: 1rem;">$199.99</div>
        <a href="#buy" class="btn-buy" style="display: block; text-align: center; background: linear-gradient(135deg, #00ff00, #00ffff); color: #000; padding: 0.75rem 1.5rem; border-radius: 8px; text-decoration: none; font-weight: 600;">
          Buy Now
        </a>
      </div>

    </div>

    <!-- Payment Methods -->
    <div style="margin-top: 4rem; text-align: center; padding: 2rem; background: rgba(0, 255, 255, 0.05); border-radius: 12px;">
      <h2 style="color: #00ffff; margin-bottom: 1.5rem;">Accepted Payment Methods</h2>
      <div style="display: flex; justify-content: center; gap: 2rem; flex-wrap: wrap; align-items: center;">
        <div style="color: #fff; font-size: 1.1rem;">💳 Stripe</div>
        <div style="color: #fff; font-size: 1.1rem;">🅿️ PayPal</div>
        <div style="color: #fff; font-size: 1.1rem;">💵 Cash App</div>
        <div style="color: #fff; font-size: 1.1rem;">🪙 Crypto</div>
      </div>
    </div>
  <?php endif; ?>

  <style>
    .product-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 30px rgba(0, 255, 255, 0.3);
    }
    .btn-buy:hover {
      opacity: 0.9;
      transform: scale(1.05);
    }
  </style>
</section>

<?php get_footer(); ?>
