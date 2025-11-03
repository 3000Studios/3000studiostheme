<!--
/**
 * 3000 Studios Theme - Header Template
 * 
 * @package     3000Studios
 * @author      Mr. jwswain
 * @copyright   Copyright (c) 2025, Mr. jwswain & 3000 Studios
 * @license     Proprietary - All Rights Reserved
 * @link        https://3000studios.com
 * @since       1.0.0
 * 
 * WARNING: This code is protected by copyright law.
 * Unauthorized copying, modification, or distribution is STRICTLY PROHIBITED.
 * Violators will be prosecuted to the fullest extent of the law.
 * 
 * For licensing inquiries: https://3000studios.com/contact
 */
-->
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- SEO Meta Tags -->
  <meta name="description" content="<?php echo esc_attr(get_bloginfo('description')); ?> - Powered by 3000 Studios AI Technology">
  <meta name="author" content="Mr. jwswain - 3000 Studios">
  <meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">

  <!-- Open Graph / Facebook -->
  <meta property="og:type" content="website">
  <meta property="og:url" content="<?php echo esc_url(home_url('/')); ?>">
  <meta property="og:title" content="<?php wp_title('|', true, 'right'); ?><?php bloginfo('name'); ?>">
  <meta property="og:description" content="<?php echo esc_attr(get_bloginfo('description')); ?>">
  <meta property="og:site_name" content="<?php bloginfo('name'); ?>">

  <!-- Twitter -->
  <meta property="twitter:card" content="summary_large_image">
  <meta property="twitter:url" content="<?php echo esc_url(home_url('/')); ?>">
  <meta property="twitter:title" content="<?php wp_title('|', true, 'right'); ?><?php bloginfo('name'); ?>">
  <meta property="twitter:description" content="<?php echo esc_attr(get_bloginfo('description')); ?>">

  <!-- Copyright & Legal -->
  <meta name="copyright" content="© <?php echo date('Y'); ?> Mr. jwswain & 3000 Studios. All Rights Reserved.">
  <meta name="dcterms.rights" content="© <?php echo date('Y'); ?> Mr. jwswain & 3000 Studios. All Rights Reserved.">

  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;600;700;800;900&family=Rajdhani:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <!-- Structured Data / JSON-LD for SEO -->
  <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "WebSite",
      "name": "<?php bloginfo('name'); ?>",
      "url": "<?php echo esc_url(home_url('/')); ?>",
      "description": "<?php echo esc_attr(get_bloginfo('description')); ?>",
      "creator": {
        "@type": "Person",
        "name": "Mr. jwswain",
        "url": "https://3000studios.com"
      },
      "publisher": {
        "@type": "Organization",
        "name": "3000 Studios",
        "url": "https://3000studios.com",
        "logo": {
          "@type": "ImageObject",
          "url": "<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/logo.png"
        }
      },
      "copyrightYear": "<?php echo date('Y'); ?>",
      "copyrightHolder": {
        "@type": "Organization",
        "name": "3000 Studios",
        "founder": {
          "@type": "Person",
          "name": "Mr. jwswain"
        }
      }
    }
  </script>

  <!-- Performance Optimization -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="dns-prefetch" href="//fonts.googleapis.com">

  <?php wp_head(); ?>

  <!-- No-JS fallback to ensure preloader never blocks rendering -->
  <noscript>
    <style>
      .preloader{display:none !important}
    </style>
  </noscript>

  <!-- KILL BLACK SCREEN IMMEDIATELY -->
  <script>
    (function() {
      // Hide preloader as soon as possible
      const hidePreloader = () => {
        const preloader = document.querySelector('.preloader');
        if (preloader) {
          preloader.style.display = 'none';
          console.log('🔥 PRELOADER KILLED IMMEDIATELY!');
        }
      };

      // Try multiple times to catch the preloader
      hidePreloader();
      document.addEventListener('DOMContentLoaded', hidePreloader);
      setTimeout(hidePreloader, 100);
      setTimeout(hidePreloader, 500);
    })();
  </script>
</head>

<body <?php body_class('has-perimeter'); ?>>
  <?php wp_body_open(); ?>

  <div class="preloader">
    <div class="pre-rings">
      <div class="pre-ring">
        <div class="pre-sector">3</div>
        <div class="pre-sector">0</div>
        <div class="pre-sector">0</div>
        <div class="pre-sector">0</div>
      </div>
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
        <nav class="nav">
          <?php
          wp_nav_menu([
            'theme_location' => 'primary',
            'container' => false,
            'menu_class' => 'nav-menu',
            'fallback_cb' => function () {
              echo '<ul class="nav-menu"><li><a href="' . admin_url('nav-menus.php') . '">Create Menu</a></li></ul>';
            }
          ]);
          ?>
        </nav>
      </div>
    </div>
  </header>

  <main class="site-main">