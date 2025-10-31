<!--
/**
 * 3000 Studios Theme - Footer Template
 * 
 * @package     3000Studios
 * @author      Mr. jwswain
 * @copyright   Copyright (c) 2025, Mr. jwswain & 3000 Studios
 * @license     Proprietary - All Rights Reserved
 * 
 * This code is protected by copyright law.
 * Unauthorized use is prohibited and will be prosecuted.
 */
-->
<footer style="position:relative;padding:3rem 0;">
  <div style="max-width:1200px;margin:0 auto;text-align:center;">
    <p style="margin:0 0 1rem 0;font-size:16px;">
      © <?php echo date('Y'); ?> <strong>3000 Studios</strong> · Created by <strong>Mr. jwswain</strong>
      <br>
      <a id="footer-attack" class="footer-attack" href="mailto:J@3000studios.com" style="color:cyan;text-decoration:none;">J@3000studios.com</a>
    </p>
    
    <!-- Legal Notice -->
    <div style="margin-top:2rem;padding:1.5rem;background:rgba(255,0,0,0.1);border:2px solid rgba(255,0,0,0.5);border-radius:8px;max-width:800px;margin-left:auto;margin-right:auto;">
      <p style="margin:0 0 0.5rem 0;color:#ff4444;font-weight:bold;font-size:14px;">⚠️ COPYRIGHT PROTECTED</p>
      <p style="margin:0;font-size:12px;line-height:1.6;color:#ccc;">
        All code, designs, and functionality are proprietary property of <strong>Mr. jwswain</strong> and <strong>3000 Studios</strong>.
        <br>
        <strong style="color:#ff6666;">Unauthorized copying, modification, or distribution is STRICTLY PROHIBITED.</strong>
        <br>
        Violators will be prosecuted. © <?php echo date('Y'); ?> All Rights Reserved.
      </p>
      <p style="margin:0.5rem 0 0 0;font-size:11px;">
        <a href="<?php echo get_template_directory_uri(); ?>/LICENSE.md" target="_blank" style="color:lime;text-decoration:underline;">View Full License</a>
        |
        <strong style="color:gold;">Interested in licensing? Contact us for pricing.</strong>
      </p>
    </div>
    
    <!-- Third-Party Attributions -->
    <details style="margin-top:2rem;padding:1rem;background:rgba(100,100,100,0.1);border-radius:8px;cursor:pointer;max-width:800px;margin-left:auto;margin-right:auto;">
      <summary style="font-size:12px;color:#888;cursor:pointer;user-select:none;">📜 Third-Party Attributions & Credits</summary>
      <div style="margin-top:1rem;font-size:11px;color:#666;text-align:left;line-height:1.8;">
        <p><strong>Technologies Used:</strong></p>
        <ul style="list-style:none;padding:0;margin:0;">
          <li>• <strong>WordPress:</strong> GPL-licensed CMS platform</li>
          <li>• <strong>Web Speech API:</strong> Browser-native speech recognition (public API)</li>
          <li>• <strong>Google Fonts (Orbitron, Rajdhani):</strong> Open Font License</li>
          <li>• <strong>Pexels API:</strong> Royalty-free images (<a href="https://www.pexels.com/license/" target="_blank" style="color:cyan;">License</a>)</li>
          <li>• <strong>Unsplash API:</strong> Free images (<a href="https://unsplash.com/license" target="_blank" style="color:cyan;">License</a>)</li>
          <li>• <strong>Pixabay:</strong> Free media library (<a href="https://pixabay.com/service/license/" target="_blank" style="color:cyan;">License</a>)</li>
        </ul>
        <p style="margin-top:1rem;"><strong>Note:</strong> All third-party resources are used in compliance with their respective licenses. The custom code, AI logic, animations, and integrations are © <?php echo date('Y'); ?> Mr. jwswain & 3000 Studios.</p>
      </div>
    </details>
    
    <!-- Powered By Badge -->
    <p style="margin-top:2rem;font-size:11px;color:#555;">
      🚀 Powered by AI Technology | Built with 💚 by Mr. jwswain
    </p>
  </div>
  
  <?php wp_footer(); ?>
</footer>

<!-- Anti-Theft Protection -->
<script>
// Copyright Protection - Disable right-click and common developer shortcuts
(function() {
  'use strict';
  
  // Disable right-click
  document.addEventListener('contextmenu', function(e) {
    e.preventDefault();
    alert('⚠️ COPYRIGHT PROTECTED\n\nThis content is protected by copyright law.\n\n© <?php echo date("Y"); ?> Mr. jwswain & 3000 Studios\nAll Rights Reserved.\n\nUnauthorized copying is prohibited.');
    return false;
  });
  
  // Disable common developer shortcuts
  document.addEventListener('keydown', function(e) {
    // F12, Ctrl+Shift+I, Ctrl+Shift+J, Ctrl+U, Ctrl+S
    if (e.key === 'F12' || 
        (e.ctrlKey && e.shiftKey && (e.key === 'I' || e.key === 'J' || e.key === 'C')) ||
        (e.ctrlKey && (e.key === 'u' || e.key === 'U' || e.key === 's' || e.key === 'S'))) {
      e.preventDefault();
      console.clear();
      console.log('%c⚠️ COPYRIGHT WARNING', 'color:red;font-size:24px;font-weight:bold;');
      console.log('%cThis code is protected by copyright law.', 'color:#ff6666;font-size:16px;');
      console.log('%c© <?php echo date("Y"); ?> Mr. jwswain & 3000 Studios - All Rights Reserved', 'color:#ff9999;font-size:14px;');
      console.log('%cUnauthorized access, copying, or modification is STRICTLY PROHIBITED.', 'color:yellow;font-size:14px;font-weight:bold;');
      console.log('%cViolators will be prosecuted to the fullest extent of the law.', 'color:orange;font-size:14px;');
      return false;
    }
  });
  
  // Console warning
  console.log('%c⚠️ STOP!', 'color:red;font-size:48px;font-weight:bold;text-shadow:2px 2px 4px #000;');
  console.log('%cThis is a browser feature intended for developers.', 'color:#fff;font-size:16px;');
  console.log('%cIf someone told you to copy-paste something here, it is a scam.', 'color:yellow;font-size:14px;');
  console.log('%c━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━', 'color:#333;');
  console.log('%c© <?php echo date("Y"); ?> Mr. jwswain & 3000 Studios', 'color:lime;font-size:18px;font-weight:bold;');
  console.log('%cAll code is PROPRIETARY and COPYRIGHT PROTECTED.', 'color:cyan;font-size:14px;');
  console.log('%cUnauthorized use is PROHIBITED and will result in legal action.', 'color:orange;font-size:14px;font-weight:bold;');
  console.log('%c━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━', 'color:#333;');
})();
</script>

</body>
</html>
