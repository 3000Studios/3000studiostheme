
<?php /* Template Name: Contact Us */ if ( ! defined('ABSPATH') ) { exit; } get_header(); ?>
<section class="section container">
  <h1>Contact Us</h1>
  <p>Email: <a href="mailto:mr.jwswain@gmail.com">mr.jwswain@gmail.com</a></p>
  <?php echo do_shortcode('[contact-form-7 id="contact" title="Contact"]'); ?>
</section>
<?php get_footer(); ?>
