
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;600;700;800;900&family=Rajdhani:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <?php wp_head(); ?>
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
      <source src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/video/nav-bg.mp4" type="video/mp4">
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
          'fallback_cb' => function() {
            echo '<ul class="nav-menu"><li><a href="'.admin_url('nav-menus.php').'">Create Menu</a></li></ul>';
          }
        ]); 
        ?>
      </nav>
    </div>
  </div>
</header>

<main class="site-main">
