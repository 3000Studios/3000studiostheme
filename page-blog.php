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
/* Template Name: Blog */ if (! defined('ABSPATH')) {
  exit;
}
get_header(); ?>
<section class="section container">
  <h1>Blog</h1>
  <?php
  if (have_posts()): echo '<div class="cards">';
    while (have_posts()): the_post(); ?>
      <article class="card">
        <h3><?php the_title(); ?></h3>
        <div class="scrollbox"><?php the_excerpt(); ?></div>
        <a class="cta" href="<?php the_permalink(); ?>">Read</a>
      </article>
    <?php endwhile;
    echo '</div>';
    the_posts_pagination();
  else: ?>
    <p>No posts yet.</p>
  <?php endif; ?>
</section>
<?php get_footer(); ?>