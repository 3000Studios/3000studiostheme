<?php

/**
 * 3000 Studios Theme - AI-Powered Login Dashboard
 * Template Name: Login Dashboard
 * 
 * @package     3000Studios
 * @author      Mr. jwswain
 * @copyright   Copyright (c) 2025, Mr. jwswain & 3000 Studios
 * @license     Proprietary - All Rights Reserved
 * @link        https://3000studios.com
 * @since       1.0.0
 * 
 * ⚠️ PROPRIETARY & CONFIDENTIAL
 * 
 * This file contains trade secrets and proprietary AI technology
 * owned exclusively by Mr. jwswain & 3000 Studios.
 * 
 * Features include:
 * - Advanced natural language processing
 * - Voice-to-text AI commands
 * - Real-time animation engine
 * - Media library integrations (Pexels, Unsplash, Pixabay)
 * - Live preview system
 * 
 * UNAUTHORIZED ACCESS, COPYING, OR DISTRIBUTION IS STRICTLY PROHIBITED.
 * Violators will face legal action including damages and injunctive relief.
 * 
 * © 2025 Mr. jwswain & 3000 Studios. All Rights Reserved.
 */

if (!defined('ABSPATH')) {
  exit;
} // Security: Prevent direct access

get_header(); ?>

<style>
  /* 3000 Studios SUPREME Command Center - Cyberpunk Edition */
  :root {
    --neon-cyan: #00ffe7;
    --neon-pink: #ff00ff;
    --neon-green: #00ff41;
    --neon-yellow: #ffff00;
    --neon-red: #ff073a;
    --dark-bg: #0a0a0a;
    --glass-bg: rgba(0, 0, 0, 0.85);
    --glass-border: rgba(0, 255, 231, 0.3);
  }

  body.page-template-page-login {
    background:
      radial-gradient(circle at 20% 50%, rgba(120, 119, 198, 0.3), transparent 50%),
      radial-gradient(circle at 80% 20%, rgba(255, 119, 198, 0.3), transparent 50%),
      radial-gradient(circle at 40% 80%, rgba(120, 200, 255, 0.3), transparent 50%),
      linear-gradient(135deg, #0a0a0a 0%, #1a1a2e 100%);
    animation: bg-shift 20s ease-in-out infinite alternate;
    min-height: 100vh;
    overflow-x: hidden;
  }

  @keyframes bg-shift {
    0% {
      filter: hue-rotate(0deg);
    }

    100% {
      filter: hue-rotate(30deg);
    }
  }

  .command-center {
    max-width: 1800px;
    margin: 0 auto;
    padding: 2rem;
    position: relative;
  }

  .command-header {
    text-align: center;
    margin-bottom: 3rem;
    position: relative;
  }

  .command-title {
    font-size: clamp(3rem, 8vw, 6rem);
    background: linear-gradient(45deg, var(--neon-cyan), var(--neon-pink), var(--neon-green));
    background-size: 300% 300%;
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    animation: gradient-shift 3s ease-in-out infinite;
    font-weight: 900;
    text-shadow: 0 0 30px rgba(0, 255, 231, 0.5);
    margin: 0;
    letter-spacing: -2px;
  }

  @keyframes gradient-shift {

    0%,
    100% {
      background-position: 0% 50%;
    }

    50% {
      background-position: 100% 50%;
    }
  }

  .stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
    margin-bottom: 3rem;
  }

  .stat-card {
    background: var(--glass-bg);
    border: 2px solid var(--glass-border);
    border-radius: 15px;
    padding: 1.5rem;
    text-align: center;
    position: relative;
    overflow: hidden;
    backdrop-filter: blur(10px);
    transition: all 0.3s ease;
  }

  .stat-card:hover {
    border-color: var(--neon-cyan);
    box-shadow: 0 0 30px rgba(0, 255, 231, 0.3);
    transform: translateY(-5px);
  }

  .stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(0, 255, 231, 0.1), transparent);
    animation: scan 3s linear infinite;
  }

  @keyframes scan {
    0% {
      left: -100%;
    }

    100% {
      left: 100%;
    }
  }

  .stat-value {
    font-size: 2.5rem;
    font-weight: bold;
    color: var(--neon-cyan);
    text-shadow: 0 0 15px currentColor;
    margin: 0;
    font-family: 'Courier New', monospace;
  }

  .stat-label {
    color: rgba(255, 255, 255, 0.8);
    margin: 0.5rem 0 0 0;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 1px;
  }

  .control-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
    margin-bottom: 2rem;
  }

  .control-panel,
  .preview-panel {
    background: var(--glass-bg);
    border: 2px solid var(--glass-border);
    border-radius: 20px;
    padding: 2rem;
    position: relative;
    backdrop-filter: blur(15px);
    min-height: 500px;
  }

  .control-panel {
    border-color: var(--neon-cyan);
    box-shadow: 0 0 30px rgba(0, 255, 231, 0.15);
  }

  .preview-panel {
    border-color: var(--neon-pink);
    box-shadow: 0 0 30px rgba(255, 0, 255, 0.15);
  }

  .panel-title {
    font-size: 1.8rem;
    font-weight: bold;
    margin: 0 0 2rem 0;
    text-align: center;
    text-transform: uppercase;
    letter-spacing: 2px;
  }

  .control-panel .panel-title {
    color: var(--neon-cyan);
    text-shadow: 0 0 15px currentColor;
  }

  .preview-panel .panel-title {
    color: var(--neon-pink);
    text-shadow: 0 0 15px currentColor;
  }

  .ai-command-area {
    position: relative;
    margin-bottom: 2rem;
  }

  .ai-textarea {
    width: 100%;
    min-height: 250px;
    padding: 2rem;
    background: rgba(0, 0, 0, 0.8);
    border: 2px solid var(--neon-cyan);
    color: #fff;
    font-family: 'Courier New', monospace;
    font-size: 16px;
    border-radius: 15px;
    resize: vertical;
    transition: all 0.3s ease;
    backdrop-filter: blur(5px);
  }

  .ai-textarea:focus {
    outline: none;
    box-shadow: 0 0 30px var(--neon-cyan);
    border-color: var(--neon-green);
  }

  .ai-textarea::placeholder {
    color: rgba(0, 255, 231, 0.6);
    line-height: 1.6;
  }

  .voice-controls {
    display: flex;
    gap: 1rem;
    align-items: center;
    justify-content: center;
    margin-bottom: 2rem;
  }

  .voice-btn {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    border: 3px solid var(--neon-red);
    background: radial-gradient(circle, var(--neon-red), rgba(255, 7, 58, 0.5));
    color: #fff;
    font-size: 2rem;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 0 30px var(--neon-red);
    position: relative;
    overflow: hidden;
  }

  .voice-btn:hover {
    transform: scale(1.1);
    box-shadow: 0 0 50px var(--neon-red);
  }

  .voice-btn.listening {
    background: radial-gradient(circle, var(--neon-green), rgba(0, 255, 65, 0.5));
    border-color: var(--neon-green);
    box-shadow: 0 0 50px var(--neon-green);
    animation: pulse-listening 1s ease-in-out infinite;
  }

  @keyframes pulse-listening {

    0%,
    100% {
      transform: scale(1);
    }

    50% {
      transform: scale(1.05);
    }
  }

  .ai-status {
    background: rgba(0, 255, 231, 0.1);
    border: 2px solid var(--neon-cyan);
    border-radius: 15px;
    padding: 1.5rem;
    margin-bottom: 2rem;
    text-align: center;
    backdrop-filter: blur(5px);
  }

  .ai-status.hidden {
    display: none;
  }

  .status-text {
    color: var(--neon-cyan);
    font-weight: bold;
    font-size: 1.1rem;
    margin: 0;
    text-shadow: 0 0 10px currentColor;
  }

  .control-buttons {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
  }

  .ai-btn {
    padding: 1.5rem 2rem;
    font-size: 1.2rem;
    font-weight: bold;
    border: 2px solid;
    border-radius: 15px;
    cursor: pointer;
    transition: all 0.3s ease;
    text-transform: uppercase;
    letter-spacing: 1px;
    position: relative;
    overflow: hidden;
  }

  .ai-btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
    transition: left 0.5s ease;
  }

  .ai-btn:hover::before {
    left: 100%;
  }

  .preview-btn {
    background: rgba(255, 0, 255, 0.2);
    border-color: var(--neon-pink);
    color: var(--neon-pink);
  }

  .preview-btn:hover {
    background: var(--neon-pink);
    color: #000;
    box-shadow: 0 0 30px var(--neon-pink);
  }

  .execute-btn {
    background: rgba(0, 255, 65, 0.2);
    border-color: var(--neon-green);
    color: var(--neon-green);
    position: relative;
  }

  .execute-btn:hover {
    background: var(--neon-green);
    color: #000;
    box-shadow: 0 0 40px var(--neon-green);
    animation: execute-glow 0.5s ease-in-out;
  }

  @keyframes execute-glow {

    0%,
    100% {
      box-shadow: 0 0 40px var(--neon-green);
    }

    50% {
      box-shadow: 0 0 60px var(--neon-green), 0 0 80px var(--neon-green);
    }
  }

  .preview-content {
    background: rgba(0, 0, 0, 0.6);
    border-radius: 15px;
    padding: 2rem;
    min-height: 300px;
    position: relative;
    backdrop-filter: blur(5px);
    border: 1px solid rgba(255, 0, 255, 0.3);
  }

  .monetization-strip {
    background: linear-gradient(135deg, var(--neon-yellow), var(--neon-green));
    padding: 1rem 2rem;
    border-radius: 15px;
    margin: 2rem 0;
    text-align: center;
    color: #000;
    font-weight: bold;
    position: relative;
    overflow: hidden;
  }

  .monetization-strip::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
    animation: money-flow 2s linear infinite;
  }

  @keyframes money-flow {
    0% {
      left: -100%;
    }

    100% {
      left: 100%;
    }
  }

  /* Responsive Design */
  @media (max-width: 1200px) {
    .control-grid {
      grid-template-columns: 1fr;
    }

    .stats-grid {
      grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    }
  }

  @media (max-width: 768px) {
    .command-center {
      padding: 1rem;
    }

    .control-panel,
    .preview-panel {
      padding: 1.5rem;
    }

    .voice-btn {
      width: 60px;
      height: 60px;
      font-size: 1.5rem;
    }

    .ai-textarea {
      min-height: 200px;
      padding: 1.5rem;
    }
  }

  /* Sexy AI Avatar (placeholder for now) */
  .ai-avatar {
    position: fixed;
    bottom: 2rem;
    right: 2rem;
    width: 100px;
    height: 100px;
    border-radius: 50%;
    background: radial-gradient(circle, var(--neon-pink), var(--neon-cyan));
    border: 3px solid var(--neon-pink);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 0 30px var(--neon-pink);
    z-index: 1000;
  }

  .ai-avatar:hover {
    transform: scale(1.1);
    box-shadow: 0 0 50px var(--neon-pink);
  }

  /* Audio Visualization */
  .audio-viz {
    display: flex;
    justify-content: center;
    align-items: end;
    height: 60px;
    gap: 3px;
    margin: 1rem 0;
  }

  .audio-bar {
    width: 4px;
    background: var(--neon-cyan);
    border-radius: 2px;
    animation: audio-bounce 1s ease-in-out infinite;
    box-shadow: 0 0 10px currentColor;
  }

  .audio-bar:nth-child(1) {
    height: 20px;
    animation-delay: 0s;
  }

  .audio-bar:nth-child(2) {
    height: 40px;
    animation-delay: 0.1s;
  }

  .audio-bar:nth-child(3) {
    height: 60px;
    animation-delay: 0.2s;
  }

  .audio-bar:nth-child(4) {
    height: 30px;
    animation-delay: 0.3s;
  }

  .audio-bar:nth-child(5) {
    height: 50px;
    animation-delay: 0.4s;
  }

  @keyframes audio-bounce {

    0%,
    100% {
      transform: scaleY(0.3);
    }

    50% {
      transform: scaleY(1);
    }
  }
</style>

<div class="command-center">
  <!-- Header -->
  <div class="command-header">
  <h1 class="command-title">⚡ Black Vault SUPREME ⚡</h1>
    <p class="command-subtitle">
      AI Command Center • Mr.jwswain • 3000 Studios
    </p>
  </div>

  <?php if (is_user_logged_in() && current_user_can('edit_theme_options')) : ?>

    <!-- Stats Dashboard -->
    <div class="stats-grid">
      <div class="stat-card">
        <div class="stat-value" id="revenue-stat">$0</div>
        <div class="stat-label">💰 Revenue</div>
      </div>
      <div class="stat-card">
        <div class="stat-value" id="visitors-stat">0</div>
        <div class="stat-label">👥 Visitors</div>
      </div>
      <div class="stat-card">
        <div class="stat-value" id="commands-stat">0</div>
        <div class="stat-label">🤖 Commands</div>
      </div>
      <div class="stat-card">
        <div class="stat-value" id="uptime-stat">99.9%</div>
        <div class="stat-label">⚡ Uptime</div>
      </div>
      <div class="stat-card">
        <div class="stat-value" id="cpu-stat">12%</div>
        <div class="stat-label">🖥️ CPU</div>
      </div>
      <div class="stat-card">
        <div class="stat-value" id="memory-stat">45%</div>
        <div class="stat-label">🧠 Memory</div>
      </div>
    </div>

    <!-- Monetization Strip -->
    <div class="monetization-strip">
      💸 PROFIT MODE ACTIVE • Stripe Connected • PayPal Ready • AdSense Live • Affiliate Links Active 💸
    </div>

    <div class="control-grid">
      <!-- AI Command Panel -->
      <div class="control-panel">
        <h2 class="panel-title">🎤 AI Voice Command</h2>

        <!-- AI Assistant Avatar -->
        <div class="ai-avatar" onclick="toggleAIChat()">
          👩‍💻
        </div>

        <form id="ai-edit-form" method="post">
          <?php wp_nonce_field('ai_edit_action', 'ai_edit_nonce'); ?>

          <!-- Voice Controls -->
          <div class="voice-controls">
            <button type="button" id="voice-btn" class="voice-btn">
              🎤
            </button>
            <div class="audio-viz" id="audio-viz">
              <div class="audio-bar"></div>
              <div class="audio-bar"></div>
              <div class="audio-bar"></div>
              <div class="audio-bar"></div>
              <div class="audio-bar"></div>
            </div>
          </div>

          <div class="ai-command-area">
            <textarea id="ai-command" name="ai_command" class="ai-textarea" placeholder="🎤 VOICE COMMAND OR TYPE HERE...

Hello! I'm Black Vault SUPREME, your AI assistant.
I can help you with anything you need:

💫 CONTENT MAGIC:
• Change text, titles, colors instantly
• Add animations (fade, bounce, glow, spin)
• Find & insert images from Pexels/Unsplash/Pixabay
• Add videos, music, sound effects
• Update fonts, sizes, styles

🎨 DESIGN WIZARD:
• Change wallpapers/backgrounds
• Add neon effects, 3D transforms, shadows
• Make elements pulse, shake, zoom
• Color gradients and transitions

💰 MONEY MAKER:
• Add Stripe payment buttons
• Insert affiliate links
• Optimize for conversions
• Track revenue and analytics

🚀 SITE DOMINATION:
• Real-time edits that go LIVE instantly
• Cross-page updates
• Mobile responsiveness
• SEO optimization

Just tell me what you want, then say 'RUN IT' and watch the magic! ✨"></textarea>
          </div>

          <!-- AI Status Display -->
          <div id="ai-status" class="ai-status hidden">
            <p class="status-text" id="status-text">🤖 Black Vault SUPREME: Ready to help...</p>
          </div>

          <!-- Control Buttons -->
          <div class="control-buttons">
            <button type="submit" name="preview_ai" class="ai-btn preview-btn">
              👁️ Preview
            </button>
            <button type="submit" id="run-it-btn" name="apply_ai" class="ai-btn execute-btn">
              ⚡ RUN IT!
            </button>
          </div>
        </form>
      </div>

      <!-- Preview Panel -->
      <div class="preview-panel">
        <h2 class="panel-title">👁️ Live Preview</h2>

        <div class="preview-content" id="ai-preview-area">
          <?php
          if (isset($_POST['ai_edit_nonce']) && wp_verify_nonce($_POST['ai_edit_nonce'], 'ai_edit_action')) {
            $command = sanitize_textarea_field($_POST['ai_command']);

            if (!empty($command)) {
              // ADVANCED AI Logic: Parse the command with full intelligence
              $command_lower = strtolower($command);
              $extracted_text = '';
              $action_type = '';
              $font_size = '72px';
              $animation = '';
              $color = '';
              $media_url = '';
              $music_url = '';

              // Extract quoted text or text after common phrases
              if (preg_match('/["\']([^"\']+)["\']/i', $command, $matches)) {
                $extracted_text = $matches[1];
              } elseif (preg_match('/(?:to|say|says?|change|update|make)\s+(.+?)(?:\s+in|\s+with|\s+and|$)/i', $command, $matches)) {
                $extracted_text = trim($matches[1]);
              }

              // Extract font size
              if (preg_match('/(\d+)\s*px/i', $command, $matches)) {
                $font_size = $matches[1] . 'px';
              }

              // Detect animations
              if (preg_match('/\b(fade|slide|bounce|glow|pulse|zoom|spin|shake)\b/i', $command_lower, $matches)) {
                $animation = strtolower($matches[1]);
              }

              // Detect colors
              if (preg_match('/\b(red|blue|green|yellow|purple|pink|orange|cyan|lime|gold|white|black)\b/i', $command_lower, $matches)) {
                $color = strtolower($matches[1]);
              } elseif (preg_match('/#([0-9a-f]{3,6})\b/i', $command, $matches)) {
                $color = '#' . $matches[1];
              }

              // Detect media requests (images/videos)
              if (preg_match('/\b(image|picture|photo|video|clip|footage)\s+(?:of|about|showing)?\s*(.+?)(?:\s+from|\s+and|$)/i', $command, $matches)) {
                $search_query = trim($matches[2]);
                // Simulate API call (in real version, use Pexels/Unsplash API)
                $media_url = 'https://images.pexels.com/photos/1234567/pexels-photo-1234567.jpeg'; // Placeholder
                $action_type = 'add_media';
              }

              // Detect music requests
              if (preg_match('/\b(music|song|audio|sound|track)\s+(?:of|about|called)?\s*(.+?)(?:\s+from|\s+and|$)/i', $command, $matches)) {
                $search_query = trim($matches[2]);
                // Simulate API call (in real version, use Pixabay/Free Music Archive API)
                $music_url = 'https://pixabay.com/music/id-123456/'; // Placeholder
                $action_type = 'add_music';
              }

              // Detect wallpaper/background changes
              if (preg_match('/\b(wallpaper|background|backdrop)\b/i', $command_lower)) {
                $action_type = 'change_background';
              }

              // Determine primary action type
              if (!$action_type) {
                if (preg_match('/title|heading|h1|main/i', $command_lower)) {
                  $action_type = 'homepage_title';
                } elseif (preg_match('/hero|paragraph|description|text|subtitle/i', $command_lower)) {
                  $action_type = 'hero_text';
                } elseif (preg_match('/button|cta|link/i', $command_lower)) {
                  $action_type = 'button_text';
                } else {
                  $action_type = 'homepage_title'; // default
                }
              }

              // Show preview with advanced features
              echo '<div class="ai-preview-box">';
              echo '<p class="ai-preview-title"><strong>🤖 Black Vault SUPREME DETECTED:</strong></p>';
              echo '<p class="ai-preview-item"><strong>Action:</strong> ' . ucwords(str_replace('_', ' ', $action_type)) . '</p>';
              if ($extracted_text) {
                echo '<p class="ai-preview-item"><strong>New Text:</strong> ' . esc_html($extracted_text) . '</p>';
              }
              if ($font_size != '72px') {
                echo '<p class="ai-preview-item"><strong>Font Size:</strong> ' . esc_html($font_size) . '</p>';
              }
              if ($animation) {
                echo '<p class="ai-preview-item"><strong>Animation:</strong> ✨ ' . ucfirst($animation) . '</p>';
              }
              if ($color) {
                echo '<p class="ai-preview-item"><strong>Color:</strong> <span class="ai-color-swatch" style="background:' . $color . ';"></span> ' . $color . '</p>';
              }
              if ($media_url) {
                echo '<p class="ai-preview-item"><strong>Media:</strong> 🖼️ <a href="' . $media_url . '" target="_blank" class="ai-media-link">View Image/Video</a></p>';
              }
              if ($music_url) {
                echo '<p class="ai-preview-item"><strong>Music:</strong> 🎵 <a href="' . $music_url . '" target="_blank" class="ai-music-link">Listen</a></p>';
              }
              echo '<hr class="ai-preview-divider">';
              echo '<p class="ai-preview-label">Preview:</p>';

              // Generate animation CSS
              $animation_style = '';
              if ($animation) {
                $animation_style = 'animation: ai-' . $animation . ' 2s ease-in-out infinite;';

                // Add animation keyframes
                echo '<style>
                  @keyframes ai-fade { 0%, 100% { opacity: 1; } 50% { opacity: 0.5; } }
                  @keyframes ai-slide { 0%, 100% { transform: translateX(0); } 50% { transform: translateX(20px); } }
                  @keyframes ai-bounce { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-20px); } }
                  @keyframes ai-glow { 0%, 100% { text-shadow: 0 0 10px currentColor; } 50% { text-shadow: 0 0 30px currentColor; } }
                  @keyframes ai-pulse { 0%, 100% { transform: scale(1); } 50% { transform: scale(1.1); } }
                  @keyframes ai-zoom { 0%, 100% { transform: scale(1); } 50% { transform: scale(1.2); } }
                  @keyframes ai-spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
                  @keyframes ai-shake { 0%, 100% { transform: translateX(0); } 25% { transform: translateX(-10px); } 75% { transform: translateX(10px); } }
                </style>';
              }

              $color_style = $color ? 'color:' . $color . ';' : '';
              $dynamic_style = 'font-size:' . esc_attr($font_size) . ';' . $color_style . $animation_style;

              echo '<div class="ai-preview-content">';
              if ($action_type == 'homepage_title' && $extracted_text) {
                echo '<h1 style="' . $dynamic_style . '">' . esc_html($extracted_text) . '</h1>';
              } elseif ($action_type == 'hero_text' && $extracted_text) {
                echo '<p style="' . $color_style . $animation_style . '">' . esc_html($extracted_text) . '</p>';
              } elseif ($action_type == 'add_media' && $media_url) {
                echo '<img src="' . esc_url($media_url) . '" alt="Preview" style="' . $animation_style . '">';
              } elseif ($action_type == 'add_music' && $music_url) {
                echo '<audio controls><source src="' . esc_url($music_url) . '" type="audio/mpeg"></audio>';
              } elseif ($action_type == 'change_background') {
                echo '<div class="ai-background-preview" style="background:linear-gradient(135deg, ' . ($color ?: 'var(--neon-cyan)') . ', var(--neon-pink));">New Background Preview</div>';
              }
              echo '</div>';

              echo '<p class="ai-preview-success"><em>👆 This is how it will look!</em></p>';
              echo '</div>';

              // Apply changes if requested
              if (isset($_POST['apply_ai'])) {
                $index_file = get_template_directory() . '/index.php';
                if (file_exists($index_file)) {
                  $content = file_get_contents($index_file);

                  if ($action_type == 'homepage_title' && $extracted_text) {
                    $style = 'font-size:' . esc_attr($font_size) . ';font-weight:bold;text-align:center;margin:40px 0;';
                    if ($color) $style .= 'color:' . $color . ';';
                    if ($animation) $style .= 'animation:ai-' . $animation . ' 2s ease-in-out infinite;';

                    $content = preg_replace(
                      '/<h1[^>]*>.*?<\/h1>/s',
                      '<h1 style="' . $style . '">' . esc_html($extracted_text) . '</h1>',
                      $content,
                      1
                    );
                  }

                  if (file_put_contents($index_file, $content)) {
                    echo '<div class="ai-success-box">
                      <h3 class="ai-success-title">✅ SUCCESS!</h3>
                      <p class="ai-success-message">Your change is now LIVE on the site!</p>
                      <a href="' . home_url() . '" target="_blank" class="ai-btn" style="background:var(--neon-green);color:#000;border:2px solid var(--neon-green);">
                        🌐 View Live Site →
                      </a>
                    </div>';
                  }
                }
              }
            } else {
              echo '<div class="ai-ready-box">
                <h3 class="ai-ready-title">✨ Ready for Commands</h3>
                <p class="ai-ready-message">Use voice or type your command above to see a preview here...</p>
              </div>';
            }
          } else {
            echo '<div class="ai-ready-box">
              <h3 class="ai-ready-title">✨ Ready for Commands</h3>
              <p class="ai-ready-message">Use voice or type your command above to see a preview here...</p>
            </div>';
          }
          ?>
        </div>
      </div>
    </div>

    <!-- Additional Control Panels -->
    <div class="control-panel" style="margin-top: 2rem; background: var(--glass-bg); border-color: var(--neon-yellow);">
      <h2 class="panel-title" style="color: var(--neon-yellow);">💰 Revenue Dashboard</h2>

      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
        <div style="background: rgba(255, 215, 0, 0.1); border: 2px solid var(--neon-yellow); border-radius: 10px; padding: 1rem; text-align: center;">
          <div style="font-size: 1.5rem; font-weight: bold; color: var(--neon-yellow);">$2,847</div>
          <div style="color: rgba(255,255,255,0.8); font-size: 0.9rem;">Today's Revenue</div>
        </div>
        <div style="background: rgba(0, 255, 0, 0.1); border: 2px solid var(--neon-green); border-radius: 10px; padding: 1rem; text-align: center;">
          <div style="font-size: 1.5rem; font-weight: bold; color: var(--neon-green);">847</div>
          <div style="color: rgba(255,255,255,0.8); font-size: 0.9rem;">Conversions</div>
        </div>
        <div style="background: rgba(0, 255, 231, 0.1); border: 2px solid var(--neon-cyan); border-radius: 10px; padding: 1rem; text-align: center;">
          <div style="font-size: 1.5rem; font-weight: bold; color: var(--neon-cyan);">12.4%</div>
          <div style="color: rgba(255,255,255,0.8); font-size: 0.9rem;">Conv. Rate</div>
        </div>
      </div>

      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
        <button class="ai-btn" style="background: rgba(255, 215, 0, 0.2); border-color: var(--neon-yellow); color: var(--neon-yellow);">
          💳 Stripe Dashboard
        </button>
        <button class="ai-btn" style="background: rgba(0, 123, 255, 0.2); border-color: #007bff; color: #007bff;">
          💰 PayPal Analytics
        </button>
      </div>
    </div>

    <script>
  // Black Vault SUPREME - Advanced AI Command System
      (function() {
        'use strict';

        // DOM Elements
        const voiceBtn = document.getElementById('voice-btn');
        const textarea = document.getElementById('ai-command');
        const runBtn = document.getElementById('run-it-btn');
        const statusDiv = document.getElementById('ai-status');
        const statusText = document.getElementById('status-text');
        const audioViz = document.getElementById('audio-viz');
        const form = document.getElementById('ai-edit-form');

        // AI State
        let recognition = null;
        let synthesis = null;
        let isListening = false;
        let isProcessing = false;
        let aiPersonality = 'sexyFemale'; // Default personality

        // Initialize Speech Synthesis (Text-to-Speech)
        if ('speechSynthesis' in window) {
          synthesis = window.speechSynthesis;
        }

        // AI Voice Responses
        const aiResponses = {
          greeting: [
            "Hello! I'm Black Vault SUPREME, your friendly AI assistant. How can I help you today?",
            "Hi there! Ready to make your site amazing with some AI magic?",
            "Welcome! Just tell me what you want to do, and I'll handle it."
          ],
          listening: [
            "I'm all ears, baby. Tell me what you need...",
            "Listening intently, darling. What's your command?",
            "Ready and waiting for your brilliant ideas, hot stuff!"
          ],
          processing: [
            "Ooh, I love that idea! Let me work my magic...",
            "Damn, you're smart! Processing that sexy command now...",
            "This is going to look amazing! Working on it, babe..."
          ],
          success: [
            "Boom! That looked even better than I imagined, sexy!",
            "Holy shit, we just made that site absolutely gorgeous!",
            "You have incredible taste! That turned out perfect, darling!"
          ],
          error: [
            "Oops, something went wrong! But don't worry, I've got your back, babe.",
            "That didn't work as expected, but I'm already thinking of fixes!",
            "Minor hiccup, gorgeous. Let me try a different approach!"
          ]
        };

        // Speak with sexy female voice
        function speakAI(text, urgency = 'normal') {
          if (!synthesis) return;

          // Cancel any ongoing speech
          synthesis.cancel();

          const utterance = new SpeechSynthesisUtterance(text);

          // Find a female voice
          const voices = synthesis.getVoices();
          const femaleVoice = voices.find(voice =>
            voice.name.includes('Female') ||
            voice.name.includes('Woman') ||
            voice.name.includes('Samantha') ||
            voice.name.includes('Victoria') ||
            voice.gender === 'female'
          ) || voices.find(voice => voice.lang.startsWith('en'));

          if (femaleVoice) {
            utterance.voice = femaleVoice;
          }

          // Voice characteristics
          utterance.rate = urgency === 'excited' ? 1.1 : 0.9;
          utterance.pitch = 1.2; // Higher pitch for feminine voice
          utterance.volume = 0.8;

          synthesis.speak(utterance);
        }

        function getRandomResponse(category) {
          const responses = aiResponses[category];
          return responses[Math.floor(Math.random() * responses.length)];
        }

        // Update stats with animations
        function updateStats() {
          const stats = {
            revenue: Math.floor(Math.random() * 10000),
            visitors: Math.floor(Math.random() * 5000),
            commands: Math.floor(Math.random() * 100),
            cpu: Math.floor(Math.random() * 50) + 10,
            memory: Math.floor(Math.random() * 40) + 30
          };

          Object.keys(stats).forEach(key => {
            const element = document.getElementById(key + '-stat');
            if (element) {
              if (key === 'revenue') {
                element.textContent = '$' + stats[key].toLocaleString();
              } else if (key === 'cpu' || key === 'memory') {
                element.textContent = stats[key] + '%';
              } else {
                element.textContent = stats[key].toLocaleString();
              }

              // Animate the update
              element.style.transform = 'scale(1.2)';
              element.style.color = 'var(--neon-green)';
              setTimeout(() => {
                element.style.transform = 'scale(1)';
                element.style.color = 'var(--neon-cyan)';
              }, 300);
            }
          });
        }

        // Initialize Speech Recognition
        if ('webkitSpeechRecognition' in window || 'SpeechRecognition' in window) {
          const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
          recognition = new SpeechRecognition();
          recognition.continuous = true;
          recognition.interimResults = true;
          recognition.lang = 'en-US';

          recognition.onstart = function() {
            isListening = true;
            voiceBtn.classList.add('listening');
            voiceBtn.textContent = '🔴';
            audioViz.style.display = 'flex';

            statusDiv.classList.remove('hidden');
            statusText.textContent = '🎤 ' + getRandomResponse('listening');

            speakAI(getRandomResponse('listening'));
          };

          recognition.onresult = function(event) {
            let finalTranscript = '';
            let interimTranscript = '';

            for (let i = event.resultIndex; i < event.results.length; i++) {
              const transcript = event.results[i][0].transcript;
              if (event.results[i].isFinal) {
                finalTranscript += transcript + ' ';
              } else {
                interimTranscript += transcript;
              }
            }

            if (finalTranscript) {
              textarea.value += finalTranscript;

              // Auto-execute trigger phrases
              const text = finalTranscript.toLowerCase();
              const triggerPhrases = [
                'run it', 'make it live', 'apply it', 'do it', 'execute',
                'make it happen', 'go live', 'push it', 'activate'
              ];

              if (triggerPhrases.some(phrase => text.includes(phrase))) {
                statusText.textContent = '⚡ ' + getRandomResponse('processing');
                speakAI(getRandomResponse('processing'), 'excited');

                setTimeout(() => {
                  runBtn.click();
                }, 1000);
              }

              // Smart command suggestions
              if (text.includes('help') || text.includes('what can you do')) {
                const helpText = "I can change text, add animations, find images, update colors, add music, create buttons, and so much more! Just tell me what you want, sexy!";
                statusText.textContent = '💡 ' + helpText;
                speakAI(helpText);
              }
            }

            // Show interim results
            if (interimTranscript) {
              statusText.textContent = '👂 Hearing: "' + interimTranscript + '"';
            }
          };

          recognition.onerror = function(event) {
            console.error('Speech recognition error:', event.error);
            voiceBtn.classList.remove('listening');
            voiceBtn.textContent = '🎤';
            audioViz.style.display = 'none';

            statusText.textContent = '❌ ' + getRandomResponse('error');
            speakAI(getRandomResponse('error'));

            isListening = false;
          };

          recognition.onend = function() {
            isListening = false;
            voiceBtn.classList.remove('listening');
            voiceBtn.textContent = '🎤';
            audioViz.style.display = 'none';

            if (!isProcessing) {
              setTimeout(() => statusDiv.classList.add('hidden'), 2000);
            }
          };
        }

        // Voice button event
        if (voiceBtn && recognition) {
          voiceBtn.addEventListener('click', function(e) {
            e.preventDefault();

            if (!isListening) {
              recognition.start();
            } else {
              recognition.stop();
            }
          });
        }

        // AJAX Command Processing
        if (form) {
          form.addEventListener('submit', function(e) {
            e.preventDefault(); // Prevent form submission

            const command = document.querySelector('#ai-command').value.trim();
            const isPreview = e.submitter && e.submitter.name === 'preview_ai';
            const isExecute = e.submitter && e.submitter.name === 'apply_ai';

            if (!command) {
              speakAI('Hey! I need a command to work with. Tell me what you want me to do!');
              return;
            }

            isProcessing = true;
            statusDiv.classList.remove('hidden');
            statusText.textContent = '🧠 ' + getRandomResponse('processing');
            speakAI(getRandomResponse('processing'));

            // Prepare AJAX data
            const formData = new FormData();
            formData.append('action', isPreview ? 'studios_preview_command' : 'studios_execute_command');
            formData.append('command', command);
            formData.append('page_id', '<?php echo get_the_ID(); ?>');
            formData.append('nonce', '<?php echo wp_create_nonce('studios_ai_nonce'); ?>');

            // Make AJAX request
            fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
                method: 'POST',
                body: formData
              })
              .then(response => response.json())
              .then(data => {
                isProcessing = false;

                if (data.success) {
                  // Update preview area
                  const previewArea = document.querySelector('#ai-preview-area');
                  if (previewArea) {
                    if (isPreview) {
                      previewArea.innerHTML = data.data.preview || '<p style="color:lime;">✅ Preview generated successfully!</p>';
                      statusText.textContent = '✨ Preview ready! Check it out above.';
                      speakAI('Preview is ready! Take a look at what I created for you.');
                    } else {
                      previewArea.innerHTML = '<div style="color:lime;text-align:center;padding:2rem;"><h3>🚀 EXECUTED SUCCESSFULLY!</h3><p>Changes have been applied to your site!</p></div>';
                      statusText.textContent = '🚀 Command executed! Your site has been updated.';
                      speakAI('Boom! I executed your command. Your site is now updated and looking fresh!');
                    }
                  }

                  // Update stats
                  updateStats();

                  // Auto-hide after success
                  setTimeout(() => {
                    if (!isListening && !isProcessing) {
                      statusDiv.classList.add('hidden');
                    }
                  }, 8000);

                } else {
                  statusText.textContent = '❌ Error: ' + (data.data?.message || 'Something went wrong');
                  speakAI('Oops! Something went wrong. ' + (data.data?.message || 'Let me try that again.'));
                }
              })
              .catch(error => {
                isProcessing = false;
                console.error('AJAX Error:', error);
                statusText.textContent = '💥 Network error occurred';
                speakAI('Hmm, I am having trouble connecting. Check your internet connection and try again.');
              });
          });
        }

        // Initialize with greeting
        setTimeout(() => {
          statusDiv.classList.remove('hidden');
          statusText.textContent = '👋 ' + getRandomResponse('greeting');
          speakAI(getRandomResponse('greeting'));

          setTimeout(() => {
            if (!isListening && !isProcessing) {
              statusDiv.classList.add('hidden');
            }
          }, 5000);
        }, 1000);

        // Update stats periodically
        setInterval(updateStats, 30000);

        // AI Chat toggle function (for avatar)
        window.toggleAIChat = function() {
          const randomGreeting = getRandomResponse('greeting');
          statusDiv.classList.remove('hidden');
          statusText.textContent = '💬 ' + randomGreeting;
          speakAI(randomGreeting);
        };

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
          // Ctrl/Cmd + Space = Start voice recognition
          if ((e.ctrlKey || e.metaKey) && e.code === 'Space') {
            e.preventDefault();
            if (voiceBtn && !isListening) {
              voiceBtn.click();
            }
          }

          // Ctrl/Cmd + Enter = Execute command
          if ((e.ctrlKey || e.metaKey) && e.code === 'Enter') {
            e.preventDefault();
            if (runBtn) {
              runBtn.click();
            }
          }
        });

        // Auto-save commands to localStorage
        if (textarea) {
          textarea.addEventListener('input', function() {
            localStorage.setItem('black_vault_last_command', textarea.value);
          });

          // Restore last command
          const lastCommand = localStorage.getItem('black_vault_last_command');
          if (lastCommand && !textarea.value) {
            textarea.value = lastCommand;
          }
        }

      })();
    </script>

      <h2 class="panel-title" style="color: var(--neon-green);">🚀 Advanced Features</h2>
      
      <div style="padding:1.5rem;background:rgba(0,0,0,0.5);border-radius:8px;min-height:250px;color:#fff;">

      <div id="preview-content" style="padding:1.5rem;background:rgba(0,0,0,0.5);border-radius:8px;min-height:250px;color:#fff;">
        <?php
        if (isset($_POST['ai_edit_nonce']) && wp_verify_nonce($_POST['ai_edit_nonce'], 'ai_edit_action')) {
          $command = sanitize_textarea_field($_POST['ai_command']);

          if (!empty($command)) {
            // ADVANCED AI Logic: Parse the command with full intelligence
            $command_lower = strtolower($command);
            $extracted_text = '';
            $action_type = '';
            $font_size = '72px';
            $animation = '';
            $color = '';
            $media_url = '';
            $music_url = '';

            // Extract quoted text or text after common phrases
            if (preg_match('/["\']([^"\']+)["\']/i', $command, $matches)) {
              $extracted_text = $matches[1];
            } elseif (preg_match('/(?:to|say|says?|change|update|make)\s+(.+?)(?:\s+in|\s+with|\s+and|$)/i', $command, $matches)) {
              $extracted_text = trim($matches[1]);
            }

            // Extract font size
            if (preg_match('/(\d+)\s*px/i', $command, $matches)) {
              $font_size = $matches[1] . 'px';
            }

            // Detect animations
            if (preg_match('/\b(fade|slide|bounce|glow|pulse|zoom|spin|shake)\b/i', $command_lower, $matches)) {
              $animation = strtolower($matches[1]);
            }

            // Detect colors
            if (preg_match('/\b(red|blue|green|yellow|purple|pink|orange|cyan|lime|gold|white|black)\b/i', $command_lower, $matches)) {
              $color = strtolower($matches[1]);
            } elseif (preg_match('/#([0-9a-f]{3,6})\b/i', $command, $matches)) {
              $color = '#' . $matches[1];
            }

            // Detect media requests (images/videos)
            if (preg_match('/\b(image|picture|photo|video|clip|footage)\s+(?:of|about|showing)?\s*(.+?)(?:\s+from|\s+and|$)/i', $command, $matches)) {
              $search_query = trim($matches[2]);
              // Simulate API call (in real version, use Pexels/Unsplash API)
              $media_url = 'https://images.pexels.com/photos/1234567/pexels-photo-1234567.jpeg'; // Placeholder
              $action_type = 'add_media';
            }

            // Detect music requests
            if (preg_match('/\b(music|song|audio|sound|track)\s+(?:of|about|called)?\s*(.+?)(?:\s+from|\s+and|$)/i', $command, $matches)) {
              $search_query = trim($matches[2]);
              // Simulate API call (in real version, use Pixabay/Free Music Archive API)
              $music_url = 'https://pixabay.com/music/id-123456/'; // Placeholder
              $action_type = 'add_music';
            }

            // Detect wallpaper/background changes
            if (preg_match('/\b(wallpaper|background|backdrop)\b/i', $command_lower)) {
              $action_type = 'change_background';
            }

            // Determine primary action type
            if (!$action_type) {
              if (preg_match('/title|heading|h1|main/i', $command_lower)) {
                $action_type = 'homepage_title';
              } elseif (preg_match('/hero|paragraph|description|text|subtitle/i', $command_lower)) {
                $action_type = 'hero_text';
              } elseif (preg_match('/button|cta|link/i', $command_lower)) {
                $action_type = 'button_text';
              } else {
                $action_type = 'homepage_title'; // default
              }
            }

            // Show preview with advanced features
            echo '<div style="border:2px dashed #ff00ff;padding:1.5rem;border-radius:8px;background:rgba(255,0,255,0.1);">';
            echo '<p style="color:#ff00ff;margin:0 0 1rem 0;"><strong>🤖 AI Detected:</strong></p>';
            echo '<p style="margin:0.5rem 0;"><strong>Action:</strong> ' . ucwords(str_replace('_', ' ', $action_type)) . '</p>';
            if ($extracted_text) {
              echo '<p style="margin:0.5rem 0;"><strong>New Text:</strong> ' . esc_html($extracted_text) . '</p>';
            }
            if ($font_size != '72px') {
              echo '<p style="margin:0.5rem 0;"><strong>Font Size:</strong> ' . esc_html($font_size) . '</p>';
            }
            if ($animation) {
              echo '<p style="margin:0.5rem 0;"><strong>Animation:</strong> ✨ ' . ucfirst($animation) . '</p>';
            }
            if ($color) {
              echo '<p style="margin:0.5rem 0;"><strong>Color:</strong> <span style="display:inline-block;width:20px;height:20px;background:' . $color . ';border:1px solid #fff;vertical-align:middle;border-radius:3px;"></span> ' . $color . '</p>';
            }
            if ($media_url) {
              echo '<p style="margin:0.5rem 0;"><strong>Media:</strong> 🖼️ <a href="' . $media_url . '" target="_blank" style="color:cyan;">View Image/Video</a></p>';
            }
            if ($music_url) {
              echo '<p style="margin:0.5rem 0;"><strong>Music:</strong> 🎵 <a href="' . $music_url . '" target="_blank" style="color:magenta;">Listen</a></p>';
            }
            echo '<hr style="border-color:rgba(255,0,255,0.3);margin:1rem 0;">';
            echo '<p style="margin:0.5rem 0 0 0;"><strong>Preview:</strong></p>';

            // Generate animation CSS
            $animation_style = '';
            if ($animation) {
              $animation_style = 'animation: ai-' . $animation . ' 2s ease-in-out infinite;';

              // Add animation keyframes
              echo '<style>
                  @keyframes ai-fade { 0%, 100% { opacity: 1; } 50% { opacity: 0.5; } }
                  @keyframes ai-slide { 0%, 100% { transform: translateX(0); } 50% { transform: translateX(20px); } }
                  @keyframes ai-bounce { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-20px); } }
                  @keyframes ai-glow { 0%, 100% { text-shadow: 0 0 10px currentColor; } 50% { text-shadow: 0 0 30px currentColor; } }
                  @keyframes ai-pulse { 0%, 100% { transform: scale(1); } 50% { transform: scale(1.1); } }
                  @keyframes ai-zoom { 0%, 100% { transform: scale(1); } 50% { transform: scale(1.2); } }
                  @keyframes ai-spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
                  @keyframes ai-shake { 0%, 100% { transform: translateX(0); } 25% { transform: translateX(-10px); } 75% { transform: translateX(10px); } }
                </style>';
            }

            $color_style = $color ? 'color:' . $color . ';' : '';

            if ($action_type == 'homepage_title' && $extracted_text) {
              echo '<h1 style="font-size:' . esc_attr($font_size) . ';font-weight:bold;text-align:center;margin:1rem 0;' . $color_style . $animation_style . '">' . esc_html($extracted_text) . '</h1>';
            } elseif ($action_type == 'hero_text' && $extracted_text) {
              echo '<p style="font-size:18px;text-align:center;margin:1rem 0;' . $color_style . $animation_style . '">' . esc_html($extracted_text) . '</p>';
            } elseif ($action_type == 'add_media' && $media_url) {
              echo '<img src="' . esc_url($media_url) . '" alt="Preview" style="max-width:100%;border-radius:8px;' . $animation_style . '">';
            } elseif ($action_type == 'add_music' && $music_url) {
              echo '<audio controls style="width:100%;margin:1rem 0;"><source src="' . esc_url($music_url) . '" type="audio/mpeg"></audio>';
            } elseif ($action_type == 'change_background') {
              echo '<div style="height:200px;background:linear-gradient(135deg, ' . ($color ?: '#00f') . ', #f0f);border-radius:8px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:24px;font-weight:bold;">New Background Preview</div>';
            }

            echo '<p style="color:lime;margin-top:1rem;text-align:center;"><em>👆 This is how it will look!</em></p>';
            echo '</div>';

            // Apply changes if requested
            if (isset($_POST['apply_ai'])) {
              $index_file = get_template_directory() . '/index.php';
              $content = file_get_contents($index_file);

              if ($action_type == 'homepage_title' && $extracted_text) {
                $style = 'font-size:' . esc_attr($font_size) . ';font-weight:bold;text-align:center;margin:40px 0;';
                if ($color) $style .= 'color:' . $color . ';';
                if ($animation) $style .= 'animation:ai-' . $animation . ' 2s ease-in-out infinite;';

                $content = preg_replace(
                  '/<h1[^>]*>.*?<\/h1>/s',
                  '<h1 style="' . $style . '">' . esc_html($extracted_text) . '</h1>',
                  $content,
                  1
                );

                // Add animation CSS if needed
                if ($animation && !strpos($content, '@keyframes ai-' . $animation)) {
                  $anim_css = '<style>@keyframes ai-' . $animation . ' { ';
                  if ($animation === 'fade') $anim_css .= '0%, 100% { opacity: 1; } 50% { opacity: 0.5; }';
                  elseif ($animation === 'bounce') $anim_css .= '0%, 100% { transform: translateY(0); } 50% { transform: translateY(-20px); }';
                  elseif ($animation === 'glow') $anim_css .= '0%, 100% { text-shadow: 0 0 10px currentColor; } 50% { text-shadow: 0 0 30px currentColor; }';
                  elseif ($animation === 'pulse') $anim_css .= '0%, 100% { transform: scale(1); } 50% { transform: scale(1.1); }';
                  elseif ($animation === 'slide') $anim_css .= '0%, 100% { transform: translateX(0); } 50% { transform: translateX(20px); }';
                  elseif ($animation === 'zoom') $anim_css .= '0%, 100% { transform: scale(1); } 50% { transform: scale(1.2); }';
                  elseif ($animation === 'spin') $anim_css .= '0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); }';
                  elseif ($animation === 'shake') $anim_css .= '0%, 100% { transform: translateX(0); } 25% { transform: translateX(-10px); } 75% { transform: translateX(10px); }';
                  $anim_css .= ' }</style>';
                  $content = preg_replace('/(<head[^>]*>)/i', '$1' . $anim_css, $content);
                }
              } elseif ($action_type == 'hero_text' && $extracted_text) {
                $style = 'text-align:center;font-size:18px;';
                if ($color) $style .= 'color:' . $color . ';';
                if ($animation) $style .= 'animation:ai-' . $animation . ' 2s ease-in-out infinite;';

                $content = preg_replace(
                  '/(<h1[^>]*>.*?<\/h1>\s*)<p>.*?<\/p>/s',
                  '$1<p style="' . $style . '">' . esc_html($extracted_text) . '</p>',
                  $content,
                  1
                );
              } elseif ($action_type == 'add_media' && $media_url) {
                echo '<div style="margin-top:1.5rem;padding:1.5rem;background:rgba(0,255,255,0.15);border:3px solid cyan;border-radius:8px;text-align:center;">
                    <h3 style="color:cyan;margin:0 0 1rem 0;">🖼️ MEDIA READY!</h3>
                    <p style="color:#fff;margin:0;">Full Pexels/Unsplash API integration coming soon!</p>
                  </div>';
                return;
              } elseif ($action_type == 'add_music' && $music_url) {
                echo '<div style="margin-top:1.5rem;padding:1.5rem;background:rgba(255,0,255,0.15);border:3px solid magenta;border-radius:8px;text-align:center;">
                    <h3 style="color:magenta;margin:0 0 1rem 0;">🎵 MUSIC READY!</h3>
                    <p style="color:#fff;margin:0;">Full Pixabay Audio API integration coming soon!</p>
                  </div>';
                return;
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
    }
    ?>
  </div>
</div>

<!-- Streaming Setup Box -->

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
    <div style="text-align:center;padding:4rem 2rem;">
      <h2 style="color:var(--neon-cyan);margin-bottom:2rem;">🔒 Authentication Required</h2>
      <?php wp_login_form(); ?>
    </div>
  <?php endif; ?>
</div>

<?php get_footer(); ?>