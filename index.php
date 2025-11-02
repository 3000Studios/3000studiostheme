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

/* Template: Home */ if (! defined('ABSPATH')) {
  exit;
}
get_header();
// Add home class to body for JavaScript detection
add_action('wp_footer', function () {
  echo '<script>document.body.classList.add("home");</script>';
});
?>

<video autoplay loop muted playsinline style="position:fixed;inset:0;width:100%;height:100%;object-fit:cover;z-index:-3;opacity:.45;">
  <source src="http://3000studios.com/wp-content/uploads/2025/10/aquarium-live-wallpaper-with-sounds-Made-with-Clipchamp.mp4" type="video/mp4">
</video>

<section class="hero">
  <canvas id="particle-bg" style="position:absolute;inset:0;z-index:-2;"></canvas>
  <div class="container">
    <h1 style="font-size:72px;font-weight:bold;text-align:center;margin:40px 0;">HEY WE DID IT!</h1>
    <p>Code, creativity, and innovation collide here. Dive into the fusion of art, AI, and engineering.</p>
    <a class="cta" href="<?php echo esc_url(function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : home_url('/')); ?>">Enter the Experience</a>
  </div>
</section>

<section class="section container">
  <h2>Featured Projects</h2>
  <div class="cards">
    <?php
    $q = new WP_Query(['posts_per_page' => 3]);
    if ($q->have_posts()):
      while ($q->have_posts()): $q->the_post(); ?>
        <div class="card">
          <h3><?php the_title(); ?></h3>
          <div style="max-height:200px;overflow:auto;"><?php the_excerpt(); ?></div>
          <a class="cta" href="<?php the_permalink(); ?>">Read More</a>
        </div>
      <?php endwhile;
      wp_reset_postdata();
    else: ?>
      <div class="card">
        <h3>Getting Started</h3>
        <p>Create your first post to see it here.</p>
      </div>
    <?php endif; ?>
  </div>
</section>

<section class="section container">
  <h2>Media Gallery</h2>
  <div class="slider" id="media-slider">
    <div class="slide"><img src="<?php echo esc_url(get_template_directory_uri() . '/assets/placeholder.png'); ?>" alt=""></div>
    <div class="slide"><img src="<?php echo esc_url(get_template_directory_uri() . '/assets/placeholder.png'); ?>" alt=""></div>
    <div class="slide"><img src="<?php echo esc_url(get_template_directory_uri() . '/assets/placeholder.png'); ?>" alt=""></div>
  </div>
</section>

<section id="game" class="section container">
  <h2>Arcade</h2>
  <canvas id="mini-game" width="640" height="360" style="width:100%;max-width:800px;margin:0 auto;background:#0a0a0a;border-radius:16px;border:1px solid rgba(255,255,255,.08)"></canvas>
</section>

<section class="section container">
  <h2>Top Crypto Movers</h2>
  <div id="crypto-ticker" class="small">Loading data…</div>
</section>

<?php get_footer(); ?>