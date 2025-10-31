
<?php /* Template Name: App Store */ if ( ! defined('ABSPATH') ) { exit; } get_header(); ?>
<section class="section container">
  <h1>App Store</h1>
  <p>List your apps and digital products. (WooCommerce product type: downloadable)</p>
  <?php echo do_shortcode('[products limit="12" columns="4" visibility="featured"]'); ?>
</section>
<?php get_footer(); ?>
