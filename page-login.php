
<?php /* Template Name: Login Dashboard */ if ( ! defined('ABSPATH') ) { exit; } get_header(); ?>
<section class="section container">
  <h1>🚀 3000 Studios Quick Edit</h1>
  <?php if ( is_user_logged_in() && current_user_can('edit_theme_options') ) : ?>
    <div class="card" style="padding:2rem;background:rgba(0,255,255,0.05);border:2px solid cyan;">
      <h2 style="margin-top:0;color:cyan;">Type Your Command</h2>
      
      <form id="quick-edit-form" method="post" style="margin-top:1.5rem;">
        <?php wp_nonce_field('quick_edit_action', 'quick_edit_nonce'); ?>
        
        <label style="display:block;margin-bottom:0.5rem;font-weight:bold;color:#fff;">What do you want to change?</label>
        <textarea id="edit-command" name="edit_command" style="width:100%;min-height:120px;padding:1rem;background:#1a1a1a;border:1px solid cyan;color:#fff;font-family:monospace;font-size:16px;border-radius:8px;" placeholder="Examples:
- Change homepage title to 'Welcome to 3000 Studios'
- Update hero text to say 'Innovation starts here'
- Make the main heading say 'HEY WE DID IT' in 72px font"></textarea>
        
        <div style="margin-top:1rem;">
          <label style="display:block;margin-bottom:0.5rem;font-weight:bold;color:#fff;">Choose Action:</label>
          <select name="action_type" style="padding:0.75rem;background:#1a1a1a;border:1px solid cyan;color:#fff;border-radius:8px;width:100%;">
            <option value="homepage_title">Update Homepage Title (H1)</option>
            <option value="hero_text">Update Hero Paragraph</option>
            <option value="custom">Custom CSS/HTML Change</option>
          </select>
        </div>
        
        <div style="display:flex;gap:1rem;margin-top:1.5rem;">
          <button type="submit" name="preview" class="cta" style="flex:1;background:#ff00ff;border-color:#ff00ff;">
            👁️ Preview
          </button>
          <button type="submit" name="apply" class="cta" style="flex:1;background:cyan;color:#000;border-color:cyan;">
            ✅ Apply Live
          </button>
        </div>
      </form>

      <?php
      // Handle form submission
      if (isset($_POST['quick_edit_nonce']) && wp_verify_nonce($_POST['quick_edit_nonce'], 'quick_edit_action')) {
        $command = sanitize_textarea_field($_POST['edit_command']);
        $action_type = sanitize_text_field($_POST['action_type']);
        
        if (isset($_POST['apply']) && !empty($command)) {
          $index_file = get_template_directory() . '/index.php';
          $content = file_get_contents($index_file);
          
          switch($action_type) {
            case 'homepage_title':
              // Update the H1 tag
              $content = preg_replace(
                '/<h1[^>]*>.*?<\/h1>/s',
                '<h1 style="font-size:72px;font-weight:bold;text-align:center;margin:40px 0;">' . esc_html($command) . '</h1>',
                $content,
                1
              );
              break;
              
            case 'hero_text':
              // Update the paragraph after H1
              $content = preg_replace(
                '/(<h1[^>]*>.*?<\/h1>\s*)<p>.*?<\/p>/s',
                '$1<p>' . esc_html($command) . '</p>',
                $content,
                1
              );
              break;
          }
          
          if (file_put_contents($index_file, $content)) {
            echo '<div style="margin-top:1.5rem;padding:1rem;background:rgba(0,255,0,0.1);border:2px solid lime;border-radius:8px;color:lime;">
              <strong>✅ SUCCESS!</strong> Your change has been applied to the live site!<br>
              <small>Refresh the homepage to see: <a href="' . home_url() . '" style="color:cyan;">Visit Homepage →</a></small>
            </div>';
          } else {
            echo '<div style="margin-top:1.5rem;padding:1rem;background:rgba(255,0,0,0.1);border:2px solid red;border-radius:8px;color:red;">
              <strong>❌ ERROR:</strong> Could not write to file. Check file permissions.
            </div>';
          }
        } elseif (isset($_POST['preview'])) {
          echo '<div style="margin-top:1.5rem;padding:1rem;background:rgba(255,255,0,0.1);border:2px solid yellow;border-radius:8px;color:yellow;">
            <strong>👁️ PREVIEW:</strong><br>
            Your command: <em>' . esc_html($command) . '</em><br>
            <small>Click "Apply Live" to make this change permanent.</small>
          </div>';
        }
      }
      ?>
    <div class="card" style="margin-top:1rem">
      <h3>Stream Control</h3>
      <p>Embed key + Go Live controls will be added in Phase 2.</p>
    </div>
  <?php else: ?>
    <?php wp_login_form(); ?>
  <?php endif; ?>
</section>
<?php get_footer(); ?>
