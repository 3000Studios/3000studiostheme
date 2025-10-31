
<?php /* Template Name: Login Dashboard */ if ( ! defined('ABSPATH') ) { exit; } get_header(); ?>

<style>
.quick-edit-container {
  max-width: 1400px;
  margin: 0 auto;
  padding: 2rem;
}
.edit-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 2rem;
  margin-bottom: 2rem;
}
.edit-box, .preview-box {
  background: rgba(0,255,255,0.05);
  border: 2px solid cyan;
  border-radius: 12px;
  padding: 2rem;
  min-height: 400px;
}
.preview-box {
  background: rgba(255,0,255,0.05);
  border-color: #ff00ff;
  position: relative;
  overflow: auto;
}
.ai-textarea {
  width: 100%;
  min-height: 200px;
  padding: 1.5rem;
  background: #0a0a0a;
  border: 2px solid cyan;
  color: #fff;
  font-family: 'Courier New', monospace;
  font-size: 18px;
  border-radius: 8px;
  resize: vertical;
}
.ai-textarea:focus {
  outline: none;
  box-shadow: 0 0 20px cyan;
}
@media (max-width: 768px) {
  .edit-grid { grid-template-columns: 1fr; }
}
</style>

<div class="quick-edit-container">
  <h1 style="text-align:center;color:cyan;font-size:48px;margin-bottom:2rem;">🤖 AI Quick Edit Dashboard</h1>
  
  <?php if ( is_user_logged_in() && current_user_can('edit_theme_options') ) : ?>
    
    <div class="edit-grid">
      <!-- Command Box -->
      <div class="edit-box">
        <h2 style="margin-top:0;color:cyan;text-align:center;">💬 Tell Me What You Want</h2>
        
        <form id="ai-edit-form" method="post">
          <?php wp_nonce_field('ai_edit_action', 'ai_edit_nonce'); ?>
          
          <textarea id="ai-command" name="ai_command" class="ai-textarea" placeholder="Just type naturally...

Examples:
• Change the homepage title to 'Welcome Back'
• Make the hero say 'Innovation starts here' 
• Update the main heading to say 'HELLO WORLD' in 80px font
• Change the description to 'We build the future'
• Add a subtitle that says 'Coming Soon'

I understand what you mean!"></textarea>
          
          <div style="display:flex;gap:1rem;margin-top:1.5rem;">
            <button type="submit" name="preview_ai" class="cta" style="flex:1;background:#ff00ff;border-color:#ff00ff;font-size:18px;padding:1rem;">
              👁️ Preview
            </button>
            <button type="submit" name="apply_ai" class="cta" style="flex:1;background:cyan;color:#000;border-color:cyan;font-size:18px;padding:1rem;font-weight:bold;">
              🚀 Make It Live!
            </button>
          </div>
        </form>
      </div>

      <!-- Preview Box -->
      <div class="preview-box">
        <h2 style="margin-top:0;color:#ff00ff;text-align:center;">👁️ Live Preview</h2>
        
        <div id="preview-content" style="padding:1.5rem;background:rgba(0,0,0,0.5);border-radius:8px;min-height:250px;color:#fff;">
          <?php
          if (isset($_POST['ai_edit_nonce']) && wp_verify_nonce($_POST['ai_edit_nonce'], 'ai_edit_action')) {
            $command = sanitize_textarea_field($_POST['ai_command']);
            
            if (!empty($command)) {
              // AI Logic: Parse the command intelligently
              $command_lower = strtolower($command);
              $extracted_text = '';
              $action_type = '';
              $font_size = '72px';
              
              // Extract quoted text or text after common phrases
              if (preg_match('/["\']([^"\']+)["\']/i', $command, $matches)) {
                $extracted_text = $matches[1];
              } elseif (preg_match('/(?:to|say|says?)\s+(.+?)(?:\s+in|$)/i', $command, $matches)) {
                $extracted_text = trim($matches[1]);
              }
              
              // Extract font size if mentioned
              if (preg_match('/(\d+)\s*px/i', $command, $matches)) {
                $font_size = $matches[1] . 'px';
              }
              
              // Determine action type from keywords
              if (preg_match('/title|heading|h1|main/i', $command_lower)) {
                $action_type = 'homepage_title';
              } elseif (preg_match('/hero|paragraph|description|text|subtitle/i', $command_lower)) {
                $action_type = 'hero_text';
              } elseif (preg_match('/button|cta|link/i', $command_lower)) {
                $action_type = 'button_text';
              } else {
                $action_type = 'homepage_title'; // default
              }
              
              // Show preview
              echo '<div style="border:2px dashed #ff00ff;padding:1.5rem;border-radius:8px;background:rgba(255,0,255,0.1);">';
              echo '<p style="color:#ff00ff;margin:0 0 1rem 0;"><strong>🤖 AI Detected:</strong></p>';
              echo '<p style="margin:0.5rem 0;"><strong>Action:</strong> ' . ucwords(str_replace('_', ' ', $action_type)) . '</p>';
              echo '<p style="margin:0.5rem 0;"><strong>New Text:</strong> ' . esc_html($extracted_text) . '</p>';
              echo '<p style="margin:0.5rem 0;"><strong>Font Size:</strong> ' . esc_html($font_size) . '</p>';
              echo '<hr style="border-color:rgba(255,0,255,0.3);margin:1rem 0;">';
              echo '<p style="margin:0.5rem 0 0 0;"><strong>Preview:</strong></p>';
              
              if ($action_type == 'homepage_title') {
                echo '<h1 style="font-size:' . esc_attr($font_size) . ';font-weight:bold;text-align:center;margin:1rem 0;">' . esc_html($extracted_text) . '</h1>';
              } else {
                echo '<p style="font-size:18px;text-align:center;margin:1rem 0;">' . esc_html($extracted_text) . '</p>';
              }
              
              echo '<p style="color:lime;margin-top:1rem;text-align:center;"><em>👆 This is how it will look!</em></p>';
              echo '</div>';
              
              // Apply changes if requested
              if (isset($_POST['apply_ai'])) {
                $index_file = get_template_directory() . '/index.php';
                $content = file_get_contents($index_file);
                
                if ($action_type == 'homepage_title') {
                  $content = preg_replace(
                    '/<h1[^>]*>.*?<\/h1>/s',
                    '<h1 style="font-size:' . esc_attr($font_size) . ';font-weight:bold;text-align:center;margin:40px 0;">' . esc_html($extracted_text) . '</h1>',
                    $content,
                    1
                  );
                } elseif ($action_type == 'hero_text') {
                  $content = preg_replace(
                    '/(<h1[^>]*>.*?<\/h1>\s*)<p>.*?<\/p>/s',
                    '$1<p style="text-align:center;font-size:18px;">' . esc_html($extracted_text) . '</p>',
                    $content,
                    1
                  );
                }
                
                if (file_put_contents($index_file, $content)) {
                  echo '<div style="margin-top:1.5rem;padding:1.5rem;background:rgba(0,255,0,0.15);border:3px solid lime;border-radius:8px;text-align:center;">
                    <h3 style="color:lime;margin:0 0 1rem 0;">✅ SUCCESS!</h3>
                    <p style="color:#fff;margin:0 0 1rem 0;">Your change is now LIVE on the site!</p>
                    <a href="' . home_url() . '" target="_blank" class="cta" style="background:lime;color:#000;display:inline-block;padding:0.75rem 2rem;text-decoration:none;border-radius:8px;font-weight:bold;">
                      🌐 View Live Site →
                    </a>
                  </div>';
                }
              }
            } else {
              echo '<p style="text-align:center;color:#888;padding:2rem;">Type a command to see a preview here...</p>';
            }
          } else {
            echo '<p style="text-align:center;color:#888;padding:2rem;">Type a command to see a preview here...</p>';
          }
          ?>
        </div>
      </div>
    </div>
        </div>

    <!-- Streaming Setup Box -->
    <div class="edit-box" style="background:rgba(255,215,0,0.05);border-color:gold;margin-top:2rem;">
      <h2 style="margin-top:0;color:gold;text-align:center;">📺 Live Streaming Control</h2>
      
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:2rem;">
        <div>
          <label style="display:block;margin-bottom:0.5rem;color:gold;font-weight:bold;">Stream Key:</label>
          <input type="text" value="sk-live-xxxx-xxxx-xxxx" readonly style="width:100%;padding:1rem;background:#0a0a0a;border:2px solid gold;color:#fff;border-radius:8px;font-family:monospace;">
          <button class="cta" style="margin-top:1rem;background:gold;color:#000;width:100%;">🔑 Generate New Key</button>
        </div>
        
        <div>
          <label style="display:block;margin-bottom:0.5rem;color:gold;font-weight:bold;">Stream Status:</label>
          <div style="padding:1rem;background:rgba(255,0,0,0.1);border:2px solid red;border-radius:8px;text-align:center;">
            <p style="color:red;font-size:24px;margin:0;font-weight:bold;">⭕ OFFLINE</p>
          </div>
          <button class="cta" style="margin-top:1rem;background:lime;color:#000;width:100%;font-weight:bold;">🔴 GO LIVE</button>
        </div>
      </div>
      
      <div style="margin-top:2rem;padding:1.5rem;background:rgba(0,0,0,0.5);border-radius:8px;">
        <h3 style="color:gold;margin-top:0;">Quick Actions:</h3>
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;">
          <button class="cta" style="background:rgba(255,215,0,0.2);border-color:gold;color:gold;">📊 Analytics</button>
          <button class="cta" style="background:rgba(255,215,0,0.2);border-color:gold;color:gold;">💬 Chat Overlay</button>
          <button class="cta" style="background:rgba(255,215,0,0.2);border-color:gold;color:gold;">⚙️ Settings</button>
        </div>
      </div>
      
      <p style="text-align:center;color:#888;margin-top:1.5rem;font-size:14px;">
        <em>Stream control will be connected in the next update</em>
      </p>
    </div>
  <?php else: ?>
    <?php wp_login_form(); ?>
  <?php endif; ?>
</section>
<?php get_footer(); ?>
