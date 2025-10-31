
<?php /* Template Name: Shop */ if ( ! defined('ABSPATH') ) { exit; } get_header(); ?>
<section class="section container">
  <h1>Shop</h1>
  <?php echo do_shortcode('[products limit="12" columns="4" paginate="true"]'); ?>
</section>
<?php get_footer(); ?>
