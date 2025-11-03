<?php
/*
 *   Copyright (c) 2025 NAME.
 *   All rights reserved.
 *   Unauthorized copying, modification, distribution, or use of this is prohibited without express written permission.
 */

/*!
 * 3000 Studios Theme
 * Copyright © 2025 3000 Studios. All rights reserved.
 */
/* Template Name: Contact Us */ if (! defined('ABSPATH')) {
  exit;
}
get_header(); ?>
<section class="section container">
  <h1>Contact Us</h1>
  <p>Email: <a href="mailto:mr.jwswain@gmail.com">mr.jwswain@gmail.com</a></p>
  <?php echo do_shortcode('[contact-form-7 id="contact" title="Contact"]'); ?>
</section>
<?php get_footer(); ?>