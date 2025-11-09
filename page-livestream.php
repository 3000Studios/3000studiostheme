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
/* Template Name: Live Stream */ if ( ! defined('ABSPATH') ) { exit; } get_header(); ?>
<section class="hero">
  <div class="container">
    <h1>Live Stream</h1>
    <p>Broadcast control ready. Embed your YouTube / OBS stream below.</p>
    <div class="livestream-video-container">
      <iframe width="100%" height="100%" src="https://www.youtube.com/embed/live_stream?channel=" frameborder="0" allowfullscreen></iframe>
    </div>
  </div>
  <canvas id="particle-bg" class="particle-bg"></canvas>
  <!-- Ghost text background effect -->
  <div class="ghost-text-bg">
    <div class="ghost-text">3000 STUDIOS</div>
  </div>
</section>
<?php get_footer(); ?>
