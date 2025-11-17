#!/usr/bin/env pwsh
<#
.SYNOPSIS
    WordPress Quick Fix and Deployment Package Generator

.DESCRIPTION
    Creates a ready-to-deploy WordPress package with all fixes applied

.NOTES
    Author: Mr. J.W. Swain - 3000 Studios
    Date: November 17, 2025
#>

$ErrorActionPreference = "Continue"

Write-Host "`n" -NoNewline
Write-Host ("═" * 80) -ForegroundColor Cyan
Write-Host "🔧 WordPress Quick Fix & Deploy Package" -ForegroundColor Yellow
Write-Host ("═" * 80) -ForegroundColor Cyan
Write-Host ""

# Create deployment folder on desktop
$deployFolder = "$env:USERPROFILE\Desktop\WordPress-Deploy-$(Get-Date -Format 'yyyyMMdd-HHmmss')"
New-Item -ItemType Directory -Path $deployFolder -Force | Out-Null

Write-Host "✅ Created deployment folder: $deployFolder" -ForegroundColor Green
Write-Host ""

# Download WordPress
Write-Host "📥 Downloading WordPress..." -ForegroundColor Cyan
$wpZip = Join-Path $deployFolder "wordpress.zip"
try {
    Invoke-WebRequest -Uri "https://wordpress.org/latest.zip" -OutFile $wpZip -UseBasicParsing
    Write-Host "✅ WordPress downloaded" -ForegroundColor Green
} catch {
    Write-Host "❌ Failed to download WordPress: $_" -ForegroundColor Red
    exit 1
}

# Extract WordPress
Write-Host "📦 Extracting WordPress..." -ForegroundColor Cyan
try {
    Expand-Archive -Path $wpZip -DestinationPath $deployFolder -Force
    Write-Host "✅ WordPress extracted" -ForegroundColor Green
} catch {
    Write-Host "❌ Failed to extract: $_" -ForegroundColor Red
    exit 1
}

# Move WordPress files to root
$wpSource = Join-Path $deployFolder "wordpress"
if (Test-Path $wpSource) {
    Get-ChildItem -Path $wpSource | ForEach-Object {
        Move-Item -Path $_.FullName -Destination $deployFolder -Force
    }
    Remove-Item -Path $wpSource -Force
    Remove-Item -Path $wpZip -Force
    Write-Host "✅ WordPress files organized" -ForegroundColor Green
}

# Copy theme
Write-Host "🎨 Copying 3000 Studios theme..." -ForegroundColor Cyan
$themeSource = $PSScriptRoot | Split-Path -Parent
$themeName = Split-Path $themeSource -Leaf
$themeDest = Join-Path $deployFolder "wp-content\themes\$themeName"

New-Item -ItemType Directory -Path (Split-Path $themeDest -Parent) -Force | Out-Null
Copy-Item -Path $themeSource -Destination $themeDest -Recurse -Force
Write-Host "✅ Theme copied to wp-content/themes/$themeName" -ForegroundColor Green

# Create wp-config-sample with instructions
Write-Host "📝 Creating wp-config template..." -ForegroundColor Cyan
$wpConfigSample = @"
<?php
/**
 * WordPress Configuration Template
 * INSTRUCTIONS: Rename this file to wp-config.php and update the values below
 */

// ** MySQL settings - Get these from your web host ** //
define('DB_NAME', 'database_name_here');
define('DB_USER', 'username_here');
define('DB_PASSWORD', 'password_here');
define('DB_HOST', 'localhost');
define('DB_CHARSET', 'utf8mb4');
define('DB_COLLATE', '');

// ** Get your security keys from: https://api.wordpress.org/secret-key/1.1/salt/ ** //
define('AUTH_KEY',         'put your unique phrase here');
define('SECURE_AUTH_KEY',  'put your unique phrase here');
define('LOGGED_IN_KEY',    'put your unique phrase here');
define('NONCE_KEY',        'put your unique phrase here');
define('AUTH_SALT',        'put your unique phrase here');
define('SECURE_AUTH_SALT', 'put your unique phrase here');
define('LOGGED_IN_SALT',   'put your unique phrase here');
define('NONCE_SALT',       'put your unique phrase here');

// ** Table prefix ** //
`$table_prefix = 'wp_';

// ** WordPress debugging mode ** //
define('WP_DEBUG', false);

// ** Security ** //
define('DISALLOW_FILE_EDIT', true);

// ** Performance ** //
define('WP_MEMORY_LIMIT', '256M');
define('WP_MAX_MEMORY_LIMIT', '512M');

// ** HTTPS fix ** //
if (isset(`$_SERVER['HTTP_X_FORWARDED_PROTO']) && `$_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
    `$_SERVER['HTTPS'] = 'on';
}

/** Absolute path to the WordPress directory. */
if (!defined('ABSPATH')) {
    define('ABSPATH', __DIR__ . '/');
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
"@

$wpConfigFile = Join-Path $deployFolder "wp-config-TEMPLATE.php"
$wpConfigSample | Out-File -FilePath $wpConfigFile -Encoding UTF8 -Force
Write-Host "✅ wp-config template created" -ForegroundColor Green

# Create deployment instructions
$instructions = @"
═══════════════════════════════════════════════════════════════
🚀 WORDPRESS DEPLOYMENT FOR 3000STUDIOS.COM
═══════════════════════════════════════════════════════════════

📦 WHAT'S INCLUDED:
  ✓ Fresh WordPress Core (latest version)
  ✓ 3000 Studios Theme (with admin fixes)
  ✓ AI Dashboard template
  ✓ wp-config template

═══════════════════════════════════════════════════════════════
⚡ QUICK DEPLOY (15 MINUTES)
═══════════════════════════════════════════════════════════════

STEP 1: BACKUP CURRENT SITE
──────────────────────────────────────────────────────────────
  • Login to your hosting control panel (cPanel)
  • Go to File Manager → public_html
  • Select all files → Compress → Download
  • Save backup to your computer

STEP 2: PREPARE WP-CONFIG
──────────────────────────────────────────────────────────────
  • Rename: wp-config-TEMPLATE.php → wp-config.php
  • Edit wp-config.php with your database details:
    - DB_NAME: Your database name
    - DB_USER: Your database username
    - DB_PASSWORD: Your database password
    - DB_HOST: Usually 'localhost'
  • Get fresh security keys from:
    https://api.wordpress.org/secret-key/1.1/salt/
  • Replace the security key section in wp-config.php
  • Save the file

STEP 3: UPLOAD TO SERVER
──────────────────────────────────────────────────────────────
  VIA CPANEL:
    1. Login to cPanel File Manager
    2. Navigate to public_html
    3. DELETE all files in public_html (backup exists!)
    4. Upload ALL files from this folder
    5. Wait for upload to complete

  VIA FTP (FileZilla):
    1. Connect to your server
    2. Navigate to public_html
    3. Delete all existing files
    4. Upload all files from this folder
    5. Wait for upload (10-15 minutes)

STEP 4: SET PERMISSIONS
──────────────────────────────────────────────────────────────
  In cPanel File Manager or FTP:
    • wp-content folder: 755
    • wp-config.php: 600
    • All other files: 644

STEP 5: TEST SITE
──────────────────────────────────────────────────────────────
  ✓ Visit: https://3000studios.com
  ✓ Visit: https://3000studios.com/wp-admin
  ✓ Login with your WordPress credentials
  ✓ Check theme is active: Appearance → Themes

STEP 6: ACTIVATE THEME & FEATURES
──────────────────────────────────────────────────────────────
  1. In WordPress Admin:
     • Appearance → Themes
     • Activate "3000 Studios Theme"

  2. Create AI Dashboard page:
     • Pages → Add New
     • Title: "AI Dashboard"
     • Template: Select "AI Dashboard V2"
     • Publish
     • Access at: /ai-dashboard

═══════════════════════════════════════════════════════════════
🐛 TROUBLESHOOTING
═══════════════════════════════════════════════════════════════

ERROR: "Error establishing database connection"
FIX: Check wp-config.php database credentials

ERROR: White screen
FIX: Enable debug in wp-config.php:
     define('WP_DEBUG', true);
     define('WP_DEBUG_LOG', true);

ERROR: Can't access wp-admin
FIX:
  • Clear browser cache
  • Try incognito mode
  • Check theme is not conflicting

ERROR: 404 on pages
FIX:
  • Login to wp-admin
  • Settings → Permalinks → Save Changes

═══════════════════════════════════════════════════════════════
📞 SUPPORT
═══════════════════════════════════════════════════════════════

Email: mr.jwswain@gmail.com
Site: https://3000studios.com

═══════════════════════════════════════════════════════════════
© 2025 Mr. J.W. Swain - 3000 Studios. All Rights Reserved.
═══════════════════════════════════════════════════════════════
"@

$instructionsFile = Join-Path $deployFolder "README-DEPLOY.txt"
$instructions | Out-File -FilePath $instructionsFile -Encoding UTF8 -Force
Write-Host "✅ Deployment instructions created" -ForegroundColor Green

# Create ZIP package
Write-Host ""
Write-Host "📦 Creating deployment ZIP..." -ForegroundColor Cyan
$zipFile = "$env:USERPROFILE\Desktop\WordPress-3000Studios-Deploy.zip"
if (Test-Path $zipFile) {
    Remove-Item $zipFile -Force
}

try {
    Compress-Archive -Path "$deployFolder\*" -DestinationPath $zipFile -Force
    $zipSize = (Get-Item $zipFile).Length / 1MB
    Write-Host "✅ ZIP created: $zipFile ($([math]::Round($zipSize, 2)) MB)" -ForegroundColor Green
} catch {
    Write-Host "⚠️  Could not create ZIP: $_" -ForegroundColor Yellow
    Write-Host "   Manual ZIP: Compress the folder: $deployFolder" -ForegroundColor Gray
}

# Summary
Write-Host ""
Write-Host ("═" * 80) -ForegroundColor Green
Write-Host "✅ DEPLOYMENT PACKAGE READY!" -ForegroundColor Green
Write-Host ("═" * 80) -ForegroundColor Green
Write-Host ""
Write-Host "📁 Folder: $deployFolder" -ForegroundColor White
if (Test-Path $zipFile) {
    Write-Host "📦 ZIP: $zipFile" -ForegroundColor White
}
Write-Host "📖 Instructions: README-DEPLOY.txt" -ForegroundColor White
Write-Host ""
Write-Host "🚀 NEXT STEPS:" -ForegroundColor Cyan
Write-Host "   1. Edit wp-config-TEMPLATE.php with your database details" -ForegroundColor White
Write-Host "   2. Rename to wp-config.php" -ForegroundColor White
Write-Host "   3. Upload all files to your server" -ForegroundColor White
Write-Host "   4. Visit https://3000studios.com" -ForegroundColor White
Write-Host ""
Write-Host ("═" * 80) -ForegroundColor Cyan

# Open folder
if ($IsWindows -or $env:OS -like "*Windows*") {
    Start-Process "explorer.exe" -ArgumentList $deployFolder
}
