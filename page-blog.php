
<?php /* Template Name: Blog */ if ( ! defined('ABSPATH') ) { exit; } get_header(); ?>
<section class="section container">
  <h1>Blog</h1>
  <?php
    if(have_posts()): echo '<div class="cards">';
      while(have_posts()): the_post(); ?>
      <article class="card">
        <h3><?php the_title(); ?></h3>
        <div class="scrollbox"><?php the_excerpt(); ?></div>
        <a class="cta" href="<?php the_permalink(); ?>">Read</a>
      </article>
  <?php endwhile; echo '</div>'; the_posts_pagination(); else: ?>
    <p>No posts yet.</p>
  <?php endif; ?>
</section>
<?php get_footer(); ?>
