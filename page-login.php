
<?php /* Template Name: Login Dashboard */ if ( ! defined('ABSPATH') ) { exit; } get_header(); ?>
<section class="section container">
  <h1>Admin Dashboard (Phase 1)</h1>
  <?php if ( is_user_logged_in() ) : ?>
    <form id="live-update" class="card" style="padding:1.25rem">
      <label>What would you like to change today?</label>
      <textarea style="width:100%;min-height:140px" placeholder="Describe the change..."></textarea>
      <div style="display:flex;gap:10px;margin-top:10px">
        <button class="cta" type="button" id="preview-change">Preview</button>
        <button class="cta" type="button" id="commit-change">Commit</button>
      </div>
      <small>Phase 2 connects this to an authenticated backend for safe live edits.</small>
    </form>
    <div class="card" style="margin-top:1rem">
      <h3>Stream Control</h3>
      <p>Embed key + Go Live controls will be added in Phase 2.</p>
    </div>
  <?php else: ?>
    <?php wp_login_form(); ?>
  <?php endif; ?>
</section>
<?php get_footer(); ?>
