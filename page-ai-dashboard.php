<?php
/**
 * 3000 Studios Theme - Advanced AI Dashboard V2
 * Template Name: AI Dashboard V2
 * 
 * @package     3000Studios
 * @author      Mr. jwswain
 * @copyright   Copyright (c) 2025, Mr. jwswain & 3000 Studios
 * @license     Proprietary - All Rights Reserved
 */

if ( ! defined('ABSPATH') ) { exit; }

// Load AI systems
require_once get_template_directory() . '/includes/ai-learning.php';
require_once get_template_directory() . '/includes/wp-intelligence.php';

// Initialize database on first load
studios_ai_create_tables();

get_header(); 
?>

<style>
:root {
  --primary: #00ffe7;
  --secondary: #ff00ff;
  --success: #00ff00;
  --warning: #ffaa00;
  --danger: #ff4444;
  --dark: #0a0a0a;
  --glass: rgba(255, 255, 255, 0.05);
}

.ai-dashboard {
  max-width: 1600px;
  margin: 0 auto;
  padding: 2rem;
  min-height: 100vh;
}

/* Modern Glass Effect */
.glass-card {
  background: var(--glass);
  backdrop-filter: blur(20px);
  border: 1px solid rgba(255, 255, 255, 0.1);
  border-radius: 16px;
  padding: 2rem;
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
  transition: all 0.3s ease;
}

.glass-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 12px 48px rgba(0, 255, 231, 0.2);
}

/* Header */
.dashboard-header {
  text-align: center;
  margin-bottom: 3rem;
  position: relative;
}

.dashboard-title {
  font-size: 56px;
  font-weight: 900;
  background: linear-gradient(135deg, var(--primary), var(--secondary));
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  margin: 0;
  animation: glow 3s ease-in-out infinite;
}

@keyframes glow {
  0%, 100% { filter: drop-shadow(0 0 20px var(--primary)); }
  50% { filter: drop-shadow(0 0 40px var(--secondary)); }
}

/* Main Grid Layout */
.dashboard-grid {
  display: grid;
  grid-template-columns: 2fr 1fr;
  gap: 2rem;
  margin-bottom: 2rem;
}

/* Command Input Section */
.command-section {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.input-container {
  position: relative;
}

.ai-input {
  width: 100%;
  min-height: 180px;
  padding: 1.5rem;
  background: var(--dark);
  border: 2px solid var(--primary);
  color: #fff;
  font-size: 18px;
  font-family: 'Segoe UI', system-ui, sans-serif;
  border-radius: 12px;
  resize: vertical;
  transition: all 0.3s ease;
}

.ai-input:focus {
  outline: none;
  border-color: var(--secondary);
  box-shadow: 0 0 30px rgba(0, 255, 231, 0.3);
}

.voice-btn {
  position: absolute;
  top: 1rem;
  right: 1rem;
  width: 56px;
  height: 56px;
  border-radius: 50%;
  background: linear-gradient(135deg, #ff0080, #ff0000);
  border: none;
  cursor: pointer;
  font-size: 24px;
  color: #fff;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 4px 16px rgba(255, 0, 0, 0.4);
  transition: all 0.3s ease;
  z-index: 10;
}

.voice-btn:hover {
  transform: scale(1.1);
  box-shadow: 0 6px 24px rgba(255, 0, 0, 0.6);
}

.voice-btn.listening {
  animation: pulse-red 1s infinite;
  background: linear-gradient(135deg, #00ff00, #00ff80);
}

@keyframes pulse-red {
  0%, 100% { box-shadow: 0 0 20px rgba(0, 255, 0, 0.6); }
  50% { box-shadow: 0 0 40px rgba(0, 255, 0, 1); }
}

/* Quick Actions */
.quick-actions {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
  gap: 1rem;
}

.action-btn {
  padding: 1rem;
  background: linear-gradient(135deg, var(--primary), var(--secondary));
  border: none;
  border-radius: 12px;
  color: #000;
  font-weight: 700;
  font-size: 14px;
  cursor: pointer;
  transition: all 0.3s ease;
  text-align: center;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 0.5rem;
}

.action-btn:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 24px rgba(0, 255, 231, 0.5);
}

.action-btn .icon {
  font-size: 24px;
}

/* Page Selector */
.page-selector {
  display: flex;
  gap: 1rem;
  align-items: center;
}

.page-select {
  flex: 1;
  padding: 1rem 1.5rem;
  background: var(--dark);
  border: 2px solid var(--primary);
  color: #fff;
  font-size: 16px;
  border-radius: 12px;
  cursor: pointer;
}

/* AI Status Panel */
.ai-status {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.status-card {
  padding: 1.5rem;
  border-radius: 12px;
  border-left: 4px solid;
}

.status-card.learning {
  background: rgba(0, 255, 231, 0.1);
  border-color: var(--primary);
}

.status-card.ready {
  background: rgba(0, 255, 0, 0.1);
  border-color: var(--success);
}

.status-card.processing {
  background: rgba(255, 170, 0, 0.1);
  border-color: var(--warning);
  animation: pulse-warning 2s infinite;
}

@keyframes pulse-warning {
  0%, 100% { opacity: 1; }
  50% { opacity: 0.6; }
}

/* Preview Panel */
.preview-panel {
  padding: 1.5rem;
  background: var(--dark);
  border-radius: 12px;
  border: 2px solid var(--secondary);
  min-height: 300px;
  max-height: 500px;
  overflow-y: auto;
}

.preview-title {
  color: var(--secondary);
  font-size: 20px;
  margin: 0 0 1rem 0;
  font-weight: 700;
}

/* AI Suggestions */
.suggestions {
  display: flex;
  flex-wrap: wrap;
  gap: 0.75rem;
  margin-top: 1rem;
}

.suggestion-chip {
  padding: 0.5rem 1rem;
  background: rgba(0, 255, 231, 0.2);
  border: 1px solid var(--primary);
  border-radius: 20px;
  color: var(--primary);
  font-size: 13px;
  cursor: pointer;
  transition: all 0.2s ease;
}

.suggestion-chip:hover {
  background: var(--primary);
  color: #000;
  transform: scale(1.05);
}

/* Learning Stats */
.learning-stats {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 1rem;
  margin-top: 1rem;
}

.stat-box {
  text-align: center;
  padding: 1rem;
  background: rgba(0, 0, 0, 0.3);
  border-radius: 8px;
}

.stat-value {
  font-size: 32px;
  font-weight: 900;
  color: var(--primary);
  display: block;
}

.stat-label {
  font-size: 12px;
  color: #888;
  text-transform: uppercase;
}

/* Action Buttons */
.action-buttons {
  display: flex;
  gap: 1rem;
  margin-top: 1.5rem;
}

.btn-execute {
  flex: 1;
  padding: 1.25rem 2rem;
  background: linear-gradient(135deg, #00ff00, #00ff80);
  border: none;
  border-radius: 12px;
  color: #000;
  font-size: 20px;
  font-weight: 900;
  cursor: pointer;
  transition: all 0.3s ease;
  text-transform: uppercase;
  letter-spacing: 1px;
}

.btn-execute:hover {
  transform: translateY(-4px);
  box-shadow: 0 12px 32px rgba(0, 255, 0, 0.6);
}

.btn-preview {
  padding: 1.25rem 2rem;
  background: rgba(255, 0, 255, 0.2);
  border: 2px solid var(--secondary);
  border-radius: 12px;
  color: var(--secondary);
  font-size: 18px;
  font-weight: 700;
  cursor: pointer;
  transition: all 0.3s ease;
}

.btn-preview:hover {
  background: var(--secondary);
  color: #000;
}

/* Responsive */
@media (max-width: 1200px) {
  .dashboard-grid {
    grid-template-columns: 1fr;
  }
}

@media (max-width: 768px) {
  .dashboard-title {
    font-size: 36px;
  }
  .quick-actions {
    grid-template-columns: repeat(2, 1fr);
  }
}

/* Scrollbar styling */
::-webkit-scrollbar {
  width: 8px;
}

::-webkit-scrollbar-track {
  background: rgba(0, 0, 0, 0.3);
  border-radius: 4px;
}

::-webkit-scrollbar-thumb {
  background: var(--primary);
  border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
  background: var(--secondary);
}
</style>

<div class="ai-dashboard">
  <!-- Header -->
  <div class="dashboard-header">
    <h1 class="dashboard-title">🧠 AI Command Center</h1>
    <p class="dashboard-subtitle">Advanced Natural Language Website Editor</p>
  </div>

  <?php if ( is_user_logged_in() && current_user_can('edit_theme_options') ) : ?>

  <div class="dashboard-grid">
    <!-- Left Column: Command Input -->
    <div class="command-section">
      <!-- Command Input -->
      <div class="glass-card">
        <form id="ai-form" method="post">
          <?php wp_nonce_field('ai_command', 'ai_nonce'); ?>
          
          <div class="input-container">
            <textarea 
              id="ai-command" 
              name="ai_command"
              class="ai-input" 
              placeholder="Tell me what you want to change...

Examples:
• 'Change the contact page title to Welcome'
• 'Make the about page hero text blue with fade animation'
• 'Add a glow effect to buttons on homepage'
• 'Find an image of a sunset and add it to blog page'

I understand WordPress, pages, layouts, grids, colors, animations, and more!"
            ></textarea>
            
            <button type="button" id="voice-btn" class="voice-btn" title="Voice Input">
              🎤
            </button>
          </div>

          <!-- Page Selector -->
          <div class="page-selector">
            <label class="form-label-primary">Target Page:</label>
            <select id="target-page" name="target_page" class="page-select">
              <option value="homepage">🏠 Homepage</option>
              <?php
              $pages = get_pages(['sort_column' => 'post_title']);
              foreach ($pages as $page) {
                echo '<option value="' . esc_attr($page->post_name) . '">' . esc_html($page->post_title) . '</option>';
              }
              ?>
            </select>
          </div>

          <!-- Quick Actions -->
          <div class="quick-actions">
            <button type="button" class="action-btn" onclick="insertCommand('Change title')">
              <span class="icon">📝</span>
              <span>Change Text</span>
            </button>
            <button type="button" class="action-btn" onclick="insertCommand('Add animation')">
              <span class="icon">✨</span>
              <span>Add Animation</span>
            </button>
            <button type="button" class="action-btn" onclick="insertCommand('Change color')">
              <span class="icon">🎨</span>
              <span>Change Color</span>
            </button>
            <button type="button" class="action-btn" onclick="insertCommand('Find image')">
              <span class="icon">🖼️</span>
              <span>Add Media</span>
            </button>
            <button type="button" class="action-btn" onclick="insertCommand('Adjust layout')">
              <span class="icon">📐</span>
              <span>Layout</span>
            </button>
            <button type="button" class="action-btn" onclick="insertCommand('Add music')">
              <span class="icon">🎵</span>
              <span>Add Audio</span>
            </button>
          </div>

          <!-- Action Buttons -->
          <div class="action-buttons">
            <button type="button" id="btn-preview" class="btn-preview">
              👁️ Preview Changes
            </button>
            <button type="button" id="btn-execute" class="btn-execute">
              ⚡ Execute Now
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Right Column: AI Status & Preview -->
    <div class="ai-status">
      <!-- AI Status Card -->
      <div class="glass-card">
        <div id="ai-status-display" class="status-card ready ai-status-ready">
          <h3>🟢 AI Ready</h3>
          <p>Waiting for your command...</p>
        </div>

        <!-- AI Suggestions -->
        <div class="suggestions-container">
          <p class="suggestions-title">💡 Quick Suggestions:</p>
          <div class="suggestions">
            <span class="suggestion-chip" onclick="insertCommand('Make homepage title bigger')">Bigger Title</span>
            <span class="suggestion-chip" onclick="insertCommand('Add bounce animation')">Add Bounce</span>
            <span class="suggestion-chip" onclick="insertCommand('Change to cyan color')">Cyan Color</span>
            <span class="suggestion-chip" onclick="insertCommand('Add glow effect')">Glow Effect</span>
          </div>
        </div>

        <!-- Learning Stats -->
        <div class="learning-stats">
          <div class="stat-box">
            <span class="stat-value" id="commands-count">0</span>
            <span class="stat-label">Commands Learned</span>
          </div>
          <div class="stat-box">
            <span class="stat-value" id="success-rate">100%</span>
            <span class="stat-label">Success Rate</span>
          </div>
        </div>
      </div>

      <!-- Preview Panel -->
      <div class="glass-card">
        <div class="preview-panel" id="preview-area">
          <h3 class="preview-title">🔮 Live Preview</h3>
          <p class="empty-state">
            Enter a command and click "Preview Changes" to see a live preview here
          </p>
        </div>
      </div>
    </div>
  </div>

  <?php else: ?>
    <div class="glass-card access-denied-container">
      <h2 class="access-denied-title">🔒 Access Denied</h2>
      <p class="access-denied-message">Please log in with administrator privileges to access the AI Dashboard.</p>
      <?php wp_login_form(); ?>
    </div>
  <?php endif; ?>
</div>

<script>
// Voice Recognition
(function() {
  const voiceBtn = document.getElementById('voice-btn');
  const commandInput = document.getElementById('ai-command');
  const statusDisplay = document.getElementById('ai-status-display');
  
  if (!voiceBtn || !commandInput) return;
  
  let recognition = null;
  let isListening = false;
  
  if ('webkitSpeechRecognition' in window) {
    recognition = new webkitSpeechRecognition();
    recognition.continuous = true;
    recognition.interimResults = true;
    recognition.lang = 'en-US';
    
    recognition.onresult = function(event) {
      let transcript = '';
      for (let i = event.resultIndex; i < event.results.length; i++) {
        transcript += event.results[i][0].transcript;
      }
      commandInput.value = transcript;
      
      // Auto-execute on "run it", "do it", "execute"
      if (/\b(run it|do it|execute|make it live)\b/i.test(transcript)) {
        recognition.stop();
        document.querySelector('.btn-execute').click();
      }
    };
    
    recognition.onstart = function() {
      isListening = true;
      voiceBtn.classList.add('listening');
      voiceBtn.textContent = '🔴';
      updateStatus('learning', '🎤 Listening...', 'Speak your command clearly');
    };
    
    recognition.onend = function() {
      isListening = false;
      voiceBtn.classList.remove('listening');
      voiceBtn.textContent = '🎤';
      updateStatus('ready', '🟢 AI Ready', 'Voice input stopped');
    };
    
    recognition.onerror = function(event) {
      console.error('Speech recognition error:', event.error);
      updateStatus('ready', '⚠️ Voice Error', 'Please try again');
    };
  }
  
  voiceBtn.addEventListener('click', function() {
    if (!recognition) {
      alert('Voice recognition not supported in this browser');
      return;
    }
    
    if (isListening) {
      recognition.stop();
    } else {
      recognition.start();
    }
  });
  
  function updateStatus(type, title, message) {
    statusDisplay.className = 'status-card ' + type;
    statusDisplay.innerHTML = `
      <h3 class="toast-title">${title}</h3>
      <p class="toast-message">${message}</p>
    `;
  }
  
  // Preview button - REAL AJAX CALL
  document.getElementById('btn-preview')?.addEventListener('click', async function() {
    const command = commandInput.value;
    const pageId = document.getElementById('target-page')?.value || 0;
    
    if (!command.trim()) {
      alert('Please enter a command first');
      return;
    }
    
    updateStatus('processing', '🔄 Processing...', 'AI analyzing your command');
    
    try {
      const response = await fetch('<?php echo admin_url("admin-ajax.php"); ?>', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams({
          action: 'studios_preview_command',
          nonce: '<?php echo wp_create_nonce("studios_ai_nonce"); ?>',
          command: command,
          page_id: pageId
        })
      });
      
      const data = await response.json();
      
      if (data.success) {
        const preview = document.getElementById('preview-area');
        preview.innerHTML = `
          <h3 class="preview-title">🔮 AI Analysis</h3>
          <div class="command-parsed-box">
            <p class="command-parsed-title"><strong>✓ Command Parsed</strong></p>
            <p class="command-parsed-item"><strong>Action:</strong> ${data.data.parsed.action || 'unknown'}</p>
            <p class="command-parsed-item"><strong>Target:</strong> ${data.data.parsed.target || 'N/A'}</p>
            <p class="command-parsed-item"><strong>Confidence:</strong> ${Math.round((data.data.parsed.confidence || 0) * 100)}%</p>
          </div>
          <div class="page-structure-box">
            <p class="page-structure-title"><strong>Page Structure:</strong></p>
            <p class="page-structure-info">
              ${data.data.page_structure.layout_type || 'standard'} layout
              ${data.data.page_structure.has_blocks ? '• Gutenberg blocks detected' : ''}
              ${data.data.page_structure.has_grid ? '• CSS Grid' : ''}
              ${data.data.page_structure.has_flex ? '• Flexbox' : ''}
            </p>
          </div>
          <p class="execute-ready-message">✓ Ready to execute. Click "Execute Now" to apply.</p>
        `;
        updateStatus('ready', '✅ Preview Ready', 'AI understood your request');
      } else {
        throw new Error(data.data?.message || 'Preview failed');
      }
    } catch (error) {
      console.error('Preview error:', error);
      updateStatus('ready', '⚠️ Preview Error', error.message);
      alert('Preview failed: ' + error.message);
    }
  });
  
  // Execute button - REAL AJAX CALL
  document.getElementById('btn-execute')?.addEventListener('click', async function(e) {
    e.preventDefault(); // Prevent any default behavior
    
    const command = commandInput.value;
    const pageId = document.getElementById('target-page')?.value || 0;
    
    if (!command.trim()) {
      alert('Please enter a command first');
      return;
    }
    
    if (!confirm('Are you sure you want to execute this command? A backup will be created.')) {
      return;
    }
    
    updateStatus('processing', '⚡ Executing...', 'AI making changes to your site');
    
    try {
      const response = await fetch('<?php echo admin_url("admin-ajax.php"); ?>', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams({
          action: 'studios_execute_command',
          nonce: '<?php echo wp_create_nonce("studios_ai_nonce"); ?>',
          command: command,
          page_id: pageId
        })
      });
      
      const data = await response.json();
      
      if (data.success && data.data.result.success) {
        updateStatus('ready', '✅ Success!', `Command executed in ${data.data.execution_time}s`);
        alert('✓ Changes applied successfully!\n\n' + (data.data.result.message || 'Command executed'));
        
        // Reload stats
        loadStats();
        
        // Clear input
        commandInput.value = '';
        document.getElementById('preview-area').innerHTML = `
          <h3 class="preview-title">🔮 Live Preview</h3>
          <p class="empty-state">Enter another command to continue editing</p>
        `;
      } else {
        throw new Error(data.data?.result?.message || data.data?.message || 'Execution failed');
      }
    } catch (error) {
      console.error('Execution error:', error);
      updateStatus('ready', '❌ Execution Failed', error.message);
      alert('Execution failed: ' + error.message);
    }
  });
})();

// Helper: Insert command template
function insertCommand(template) {
  const input = document.getElementById('ai-command');
  input.value = template + ' ';
  input.focus();
}

// Load learning stats from database
async function loadStats() {
  try {
    const response = await fetch('<?php echo admin_url("admin-ajax.php"); ?>', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
      },
      body: new URLSearchParams({
        action: 'studios_get_stats'
      })
    });
    
    const data = await response.json();
    
    if (data.success) {
      document.getElementById('commands-count').textContent = data.data.total_commands || '0';
      document.getElementById('success-rate').textContent = (data.data.success_rate || 0) + '%';
      
      // Update suggestions if patterns exist
      if (data.data.top_patterns && data.data.top_patterns.length > 0) {
        const suggestionsDiv = document.querySelector('.suggestion-chips');
        if (suggestionsDiv) {
          suggestionsDiv.innerHTML = '<p class="suggestions-title">🎯 Smart Suggestions:</p>';
          data.data.top_patterns.slice(0, 3).forEach(pattern => {
            const chip = document.createElement('button');
            chip.className = 'suggestion-chip';
            chip.textContent = `${pattern.action_type} (${Math.round(pattern.confidence_score * 100)}% confidence)`;
            chip.onclick = () => insertCommand(pattern.action_type);
            suggestionsDiv.appendChild(chip);
          });
        }
      }
    }
  } catch (error) {
    console.error('Failed to load stats:', error);
  }
}

// Load stats on page load
window.addEventListener('load', loadStats);
</script>

<?php get_footer(); ?>
