/*
 *   Copyright (c) 2025 NAME.
 *   All rights reserved.
 *   Unauthorized copying, modification, distribution, or use of this is prohibited without express written permission.
 */

<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
  <?php wp_body_open(); ?>

  <div class="preloader">
    <div class="field">
      <progress id="loading" indeterminate>loading…</progress>
    </div>
  </div>

  <header class="site-header">
    <div class="nav-wrap">
      <video class="nav-video" autoplay loop muted playsinline>
        <source src="http://3000studios.com/wp-content/uploads/2025/10/aquarium-live-wallpaper-with-sounds-Made-with-Clipchamp.mp4" type="video/mp4">
      </video>
      <div class="container">
        <div class="site-brand">
          <div class="prelogo">
            <div class="pre-rings">
              <div class="pre-ring">
                <div class="pre-sector">3</div>
                <div class="pre-sector">0</div>
                <div class="pre-sector">0</div>
                <div class="pre-sector">0</div>
              </div>
            </div>
          </div>
          <a href="<?php echo esc_url(home_url('/')); ?>">3000 Studios</a>
        </div>
        <button class="menu-toggle" aria-expanded="false" aria-label="Toggle navigation menu">
          <span class="menu-icon">☰</span>
          <span class="sr-only">Menu</span>
        </button>
        <nav class="nav">
          <?php
          wp_nav_menu([
            'theme_location' => 'primary',
            'container' => false,
            'menu_class' => 'nav-menu',
            'fallback_cb' => function () {
              // Default menu if no menu is set
              echo '<ul class="nav-menu">';
              echo '<li><a href="' . esc_url(home_url('/')) . '">Home</a></li>';
              echo '<li><a href="' . esc_url(home_url('/experience')) . '">Experience</a></li>';
              echo '<li><a href="' . esc_url(home_url('/blog')) . '">Blog</a></li>';
              echo '<li><a href="' . esc_url(home_url('/shop')) . '">Shop</a></li>';
              echo '<li><a href="' . esc_url(home_url('/ai-dashboard')) . '">AI Dashboard</a></li>';
              echo '<li><a href="' . esc_url(home_url('/contact')) . '">Contact</a></li>';
              echo '</ul>';
            }
          ]);
          ?>
        </nav>
      </div>
    </div>
  </header>

  <main class="site-main">