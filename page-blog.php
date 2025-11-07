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
  <h1>Our Blog</h1>
  <p>Exploring the frontiers of Artificial Intelligence, technology, and creative engineering. Your source for insights from the 3000 Studios team.</p>
  
  <div class="blog-grid">
    <article class="blog-post featured">
      <img src="https://images.pexels.com/photos/3861969/pexels-photo-3861969.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="AI in action" class="featured-image">
      <div class="post-content">
        <h2 class="post-title">The Rise of Generative AI: Reshaping Creative Industries</h2>
        <p class="post-excerpt">Generative AI is no longer science fiction. From creating stunning digital art to composing music and writing code, we explore how these powerful algorithms are augmenting human creativity and what it means for the future of content creation.</p>
        <a href="#" class="cta">Read Full Story</a>
      </div>
    </article>

    <article class="blog-post">
      <div class="post-content">
        <h3 class="post-title">Neural Networks Explained: A Beginner's Guide</h3>
        <p class="post-excerpt">Dive into the core concepts of neural networks. This guide breaks down the fundamentals of deep learning, from neurons and layers to activation functions, in an easy-to-understand format.</p>
        <a href="#" class="cta">Learn More</a>
      </div>
    </article>

    <article class="blog-post">
      <div class="post-content">
        <h3 class="post-title">The Ethics of AI: Navigating Bias and Fairness</h3>
        <p class="post-excerpt">As AI systems become more integrated into our lives, ensuring they are fair and unbiased is critical. We discuss the challenges and solutions in building ethical AI that benefits everyone.</p>
        <a href="#" class="cta">Join the Discussion</a>
      </div>
    </article>
    
    <article class="blog-post">
      <div class="post-content">
        <h3 class="post-title">Quantum Computing and AI: The Next Frontier</h3>
        <p class="post-excerpt">What happens when you combine the power of quantum computing with artificial intelligence? A look into the potential breakthroughs and paradigm shifts this synergy could bring to machine learning and beyond.</p>
        <a href="#" class="cta">Explore the Future</a>
      </div>
    </article>
  </div>

  <?php
  // You can keep your existing loop for dynamic posts below this static section
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
    <p>No dynamic posts yet.</p>
  <?php endif; ?>
</section>

<style>
.blog-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
    margin-top: 2rem;
}
.blog-post {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid var(--neon-cyan);
    border-radius: 10px;
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.blog-post:hover {
    transform: translateY(-5px);
    box-shadow: 0 0 15px var(--neon-cyan), 0 0 25px var(--neon-cyan);
}
.blog-post.featured {
    grid-column: 1 / -1;
    display: flex;
    align-items: center;
    gap: 2rem;
}
.featured-image {
    width: 50%;
    height: 100%;
    object-fit: cover;
}
.post-content {
    padding: 1.5rem;
}
.post-title {
    font-size: 1.5rem;
    color: var(--neon-cyan);
    margin-bottom: 0.5rem;
}
.blog-post.featured .post-title {
    font-size: 2rem;
}
.post-excerpt {
    font-size: 1rem;
    color: var(--light-text);
    line-height: 1.6;
}
.blog-post .cta {
    margin-top: 1rem;
    display: inline-block;
}
@media (max-width: 768px) {
    .blog-post.featured {
        flex-direction: column;
    }
    .featured-image {
        width: 100%;
        height: 200px;
    }
}
</style>

<?php get_footer(); ?>