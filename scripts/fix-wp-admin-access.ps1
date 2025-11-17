#!/usr/bin/env pwsh
<#
.SYNOPSIS
    Fix WordPress Admin Access Issues for 3000studios.com

.DESCRIPTION
    Diagnoses and fixes common WordPress admin login problems including:
    - Theme conflicts with wp-admin
    - Database connection issues
    - .htaccess problems
    - Permission issues
    - Security lockouts

.NOTES
    Author: Mr. J.W. Swain - 3000 Studios
    Date: November 17, 2025
#>

$ErrorActionPreference = "Continue"

Write-Host "`n"
Write-Host ("═" * 80) -ForegroundColor Red
Write-Host "🔧 WordPress Admin Access Fix - 3000studios.com" -ForegroundColor Yellow
Write-Host ("═" * 80) -ForegroundColor Red
Write-Host ""

# Step 1: Create emergency wp-config patch
Write-Host "📝 Step 1: Creating emergency wp-config patch..." -ForegroundColor Cyan

$wpConfigPatch = @"
<?php
/**
 * Emergency WordPress Fix - Add this to wp-config.php
 * Place ABOVE the line: require_once ABSPATH . 'wp-settings.php';
 */

// Enable debug mode to see errors
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
@ini_set('display_errors', 0);

// Increase memory limits
define('WP_MEMORY_LIMIT', '256M');
define('WP_MAX_MEMORY_LIMIT', '512M');

// Fix HTTPS if behind proxy
if (isset(`$_SERVER['HTTP_X_FORWARDED_PROTO']) && `$_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
    `$_SERVER['HTTPS'] = 'on';
}

// Auto-repair database
define('WP_ALLOW_REPAIR', true);

// Disable file editing from dashboard (security)
define('DISALLOW_FILE_EDIT', true);

// If you still can't login, uncomment these lines:
// define('RELOCATE', true);
// define('WP_HOME', 'https://3000studios.com');
// define('WP_SITEURL', 'https://3000studios.com');
"@

$desktopPath = [Environment]::GetFolderPath("Desktop")
$patchFile = Join-Path $desktopPath "wp-config-emergency-patch.txt"
$wpConfigPatch | Out-File -FilePath $patchFile -Encoding UTF8
Write-Host "  ✅ Created: $patchFile" -ForegroundColor Green

# Step 2: Create .htaccess fix
Write-Host "`n📝 Step 2: Creating .htaccess fix..." -ForegroundColor Cyan

$htaccessFix = @"
# BEGIN WordPress
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteBase /
RewriteRule ^index\.php$ - [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . /index.php [L]
</IfModule>
# END WordPress

# Security Headers
<IfModule mod_headers.c>
    Header set X-Content-Type-Options "nosniff"
    Header set X-Frame-Options "SAMEORIGIN"
</IfModule>

# Protect wp-config.php
<Files wp-config.php>
    order allow,deny
    deny from all
</Files>

# Increase PHP limits
php_value upload_max_filesize 64M
php_value post_max_size 64M
php_value max_execution_time 300
php_value max_input_time 300
php_value memory_limit 256M
"@

$htaccessFile = Join-Path $desktopPath "htaccess-fix.txt"
$htaccessFix | Out-File -FilePath $htaccessFile -Encoding UTF8
Write-Host "  ✅ Created: $htaccessFile" -ForegroundColor Green
Write-Host "     Rename to: .htaccess when uploading" -ForegroundColor Gray

# Step 3: Create theme emergency disable plugin
Write-Host "`n📝 Step 3: Creating emergency theme disable plugin..." -ForegroundColor Cyan

$emergencyPlugin = @"
<?php
/*
Plugin Name: Emergency Theme Disable
Description: Switches to default WordPress theme if admin access fails
Version: 1.0
Author: 3000 Studios
*/

// This plugin will switch to a default theme if wp-admin fails to load
add_action('admin_init', function() {
    if (!current_user_can('manage_options')) {
        return;
    }
    
    // Check if we're having theme issues
    if (isset(`$_GET['emergency_theme_reset'])) {
        // Switch to Twenty Twenty-Three or any available default theme
        `$default_themes = ['twentytwentythree', 'twentytwentytwo', 'twentytwentyone'];
        foreach (`$default_themes as `$theme_slug) {
            if (wp_get_theme(`$theme_slug)->exists()) {
                switch_theme(`$theme_slug);
                wp_redirect(admin_url());
                exit;
            }
        }
    }
});

// Add notice in admin
add_action('admin_notices', function() {
    if (!current_user_can('manage_options')) {
        return;
    }
    ?>
    <div class="notice notice-warning">
        <p><strong>Emergency Theme Reset Available:</strong> If you're experiencing issues, 
        <a href="<?php echo admin_url('?emergency_theme_reset=1'); ?>">click here to switch to a default theme</a>.</p>
    </div>
    <?php
});
"@

$pluginDir = Join-Path $desktopPath "emergency-theme-disable"
New-Item -ItemType Directory -Path $pluginDir -Force | Out-Null
$pluginFile = Join-Path $pluginDir "emergency-theme-disable.php"
$emergencyPlugin | Out-File -FilePath $pluginFile -Encoding UTF8
Compress-Archive -Path $pluginDir -DestinationPath (Join-Path $desktopPath "emergency-theme-disable.zip") -Force
Write-Host "  ✅ Created: emergency-theme-disable.zip" -ForegroundColor Green

# Step 4: Create diagnostic instructions
Write-Host "`n📝 Step 4: Creating diagnostic instructions..." -ForegroundColor Cyan

$instructions = @"
═══════════════════════════════════════════════════════════════════
🚨 WORDPRESS ADMIN ACCESS FIX - 3000STUDIOS.COM
═══════════════════════════════════════════════════════════════════

🎯 PROBLEM: Cannot access wp-admin dashboard
🎯 SOLUTION: Follow these steps in order

═══════════════════════════════════════════════════════════════════
METHOD 1: QUICK FIX VIA CPANEL FILE MANAGER (5 MINUTES)
═══════════════════════════════════════════════════════════════════

STEP 1: Check if site is up
──────────────────────────────────────────────────────────────────
  • Visit: https://3000studios.com
  • If site loads, theme is OK
  • If blank/error, theme has issues

STEP 2: Access cPanel File Manager
──────────────────────────────────────────────────────────────────
  • Login to your hosting control panel
  • Go to: File Manager
  • Navigate to: public_html

STEP 3: Edit wp-config.php
──────────────────────────────────────────────────────────────────
  • Right-click wp-config.php → Edit
  • Find this line:
    require_once ABSPATH . 'wp-settings.php';
  
  • ADD ABOVE IT (copy from wp-config-emergency-patch.txt):
    define('WP_DEBUG', true);
    define('WP_DEBUG_LOG', true);
    define('WP_MEMORY_LIMIT', '256M');
    define('RELOCATE', true);
    define('WP_HOME', 'https://3000studios.com');
    define('WP_SITEURL', 'https://3000studios.com');
  
  • Save the file
  • Try logging in again

STEP 4: Check .htaccess
──────────────────────────────────────────────────────────────────
  • In File Manager, find .htaccess in public_html
  • Right-click → Edit
  • Replace ALL content with content from: htaccess-fix.txt
  • Save
  • Try wp-admin again

STEP 5: Check debug.log
──────────────────────────────────────────────────────────────────
  • Navigate to: wp-content/debug.log
  • Download and open it
  • Look for errors mentioning your theme
  • Send errors to: mr.jwswain@gmail.com

═══════════════════════════════════════════════════════════════════
METHOD 2: EMERGENCY THEME RESET (10 MINUTES)
═══════════════════════════════════════════════════════════════════

If theme is causing the issue:

STEP 1: Install Emergency Plugin
──────────────────────────────────────────────────────────────────
  • Upload emergency-theme-disable.zip via:
    - cPanel File Manager → wp-content/plugins/
    - Extract the ZIP
  
  OR try to login and go to:
    - Plugins → Add New → Upload Plugin
    - Upload emergency-theme-disable.zip
    - Activate

STEP 2: Reset Theme via FTP
──────────────────────────────────────────────────────────────────
  • Connect via FTP (FileZilla)
  • Navigate to: /wp-content/themes/
  • Rename folder: 3000studios → 3000studios-backup
  • WordPress will auto-switch to default theme
  • Try wp-admin login
  • If successful, upload fresh 3000studios theme

STEP 3: Database Repair
──────────────────────────────────────────────────────────────────
  • Visit: https://3000studios.com/wp-admin/maint/repair.php
  • Click "Repair Database"
  • Try login again

═══════════════════════════════════════════════════════════════════
METHOD 3: RESET PASSWORD (IF LOGIN FAILED)
═══════════════════════════════════════════════════════════════════

STEP 1: Use "Lost Password"
──────────────────────────────────────────────────────────────────
  • Go to: https://3000studios.com/wp-login.php
  • Click "Lost your password?"
  • Enter your email
  • Check email for reset link

STEP 2: Reset via phpMyAdmin (if email fails)
──────────────────────────────────────────────────────────────────
  • Login to cPanel → phpMyAdmin
  • Select your WordPress database
  • Find table: wp_users
  • Click "Browse"
  • Find your username
  • Click "Edit"
  • In user_pass field, enter:
    MD5('your-new-password')
  • Save
  • Try login with new password

═══════════════════════════════════════════════════════════════════
METHOD 4: COMPLETE FRESH START (30 MINUTES)
═══════════════════════════════════════════════════════════════════

If nothing else works:

STEP 1: Backup Current Site
──────────────────────────────────────────────────────────────────
  • cPanel → File Manager → public_html
  • Select All → Compress → Download
  • Save to your computer

STEP 2: Export Database
──────────────────────────────────────────────────────────────────
  • cPanel → phpMyAdmin
  • Select WordPress database
  • Click "Export"
  • Download SQL file

STEP 3: Fresh WordPress Install
──────────────────────────────────────────────────────────────────
  • cPanel → WordPress Manager (or Softaculous)
  • Choose "Fresh Install"
  • Install to: public_html
  • Complete installation wizard

STEP 4: Restore Theme
──────────────────────────────────────────────────────────────────
  • Upload 3000studios theme via:
    Appearance → Themes → Add New → Upload
  • Activate theme

═══════════════════════════════════════════════════════════════════
🔍 DIAGNOSTIC CHECKLIST
═══════════════════════════════════════════════════════════════════

□ Can you access https://3000studios.com (homepage)?
□ Does wp-admin redirect or show error?
□ What error message do you see (if any)?
□ Have you recently changed hosting settings?
□ Have you recently updated WordPress/themes/plugins?
□ Can you access cPanel?
□ Can you access FTP?
□ Do you have database access (phpMyAdmin)?

═══════════════════════════════════════════════════════════════════
📞 NEED HELP?
═══════════════════════════════════════════════════════════════════

Email: mr.jwswain@gmail.com

Include:
  • URL you're trying to access
  • Error message (screenshot if possible)
  • What you've tried so far
  • Content of wp-content/debug.log (if accessible)

═══════════════════════════════════════════════════════════════════
© 2025 Mr. J.W. Swain - 3000 Studios. All Rights Reserved.
═══════════════════════════════════════════════════════════════════
"@

$instructionsFile = Join-Path $desktopPath "WP-ADMIN-FIX-INSTRUCTIONS.txt"
$instructions | Out-File -FilePath $instructionsFile -Encoding UTF8
Write-Host "  ✅ Created: WP-ADMIN-FIX-INSTRUCTIONS.txt" -ForegroundColor Green

# Summary
Write-Host ""
Write-Host ("═" * 80) -ForegroundColor Green
Write-Host "✅ WORDPRESS ADMIN FIX KIT CREATED!" -ForegroundColor Green
Write-Host ("═" * 80) -ForegroundColor Green
Write-Host ""
Write-Host "📁 Files created on your Desktop:" -ForegroundColor Yellow
Write-Host "   1. WP-ADMIN-FIX-INSTRUCTIONS.txt - Full diagnostic guide" -ForegroundColor White
Write-Host "   2. wp-config-emergency-patch.txt - Add to wp-config.php" -ForegroundColor White
Write-Host "   3. htaccess-fix.txt - Replace .htaccess with this" -ForegroundColor White
Write-Host "   4. emergency-theme-disable.zip - Plugin to reset theme" -ForegroundColor White
Write-Host ""
Write-Host "🚀 QUICKEST FIX:" -ForegroundColor Cyan
Write-Host "   1. Login to cPanel File Manager" -ForegroundColor White
Write-Host "   2. Edit wp-config.php" -ForegroundColor White
Write-Host "   3. Add content from wp-config-emergency-patch.txt" -ForegroundColor White
Write-Host "   4. Save and try wp-admin again" -ForegroundColor White
Write-Host ""
Write-Host "📖 READ: WP-ADMIN-FIX-INSTRUCTIONS.txt for full guide" -ForegroundColor Yellow
Write-Host ""
Write-Host ("═" * 80) -ForegroundColor Cyan

# Open desktop folder
if ($IsWindows -or $env:OS -like "*Windows*") {
    Start-Process "explorer.exe" -ArgumentList $desktopPath
}

Write-Host ""
