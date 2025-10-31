
<?php /* Template Name: Live Stream */ if ( ! defined('ABSPATH') ) { exit; } get_header(); ?>
<section class="hero">
  <div class="container">
    <h1>Live Stream</h1>
    <p>Broadcast control ready. Embed your YouTube / OBS stream below.</p>
    <div style="aspect-ratio:16/9;background:#111;border-radius:16px;overflow:hidden">
      <iframe width="100%" height="100%" src="https://www.youtube.com/embed/live_stream?channel=" frameborder="0" allowfullscreen></iframe>
    </div>
  </div>
  <canvas id="particle-bg" style="position:absolute;inset:0;z-index:-2;"></canvas>
  <!-- Ghost text background effect -->
  <div style="position:absolute;inset:0;display:grid;place-items:center;pointer-events:none;opacity:.06">
    <div style="font:900 20vw Orbitron, sans-serif;letter-spacing:.1em;color:#fff">3000 STUDIOS</div>
  </div>
</section>
<?php get_footer(); ?>
