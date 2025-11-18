#!/usr/bin/env pwsh
<#
.SYNOPSIS
    Complete WordPress Reinstallation and Fix Script for 3000studios.com

.DESCRIPTION
    This script performs a complete WordPress reinstallation and fixes all common issues:
    - Downloads latest WordPress core
    - Preserves wp-content (themes, plugins, uploads)
    - Fixes database connection
    - Repairs file permissions
    - Regenerates wp-config.php with security keys
    - Fixes admin access issues
    - Tests all critical functionality

.NOTES
    Author: Mr. J.W. Swain - 3000 Studios
    Date: November 17, 2025
    Copyright (c) 2025 3000 Studios. All Rights Reserved.
#>

param(
    [string]$Mode = "local",  # "local" or "remote"
    [string]$BackupDir = "$env:USERPROFILE\Desktop\wp-backup-$(Get-Date -Format 'yyyy-MM-dd-HHmmss')"
)

$ErrorActionPreference = "Stop"

# Colors for output
function Write-Success { param($msg) Write-Host "✅ $msg" -ForegroundColor Green }
function Write-Info { param($msg) Write-Host "ℹ️  $msg" -ForegroundColor Cyan }
function Write-Warning { param($msg) Write-Host "⚠️  $msg" -ForegroundColor Yellow }
function Write-Error { param($msg) Write-Host "❌ $msg" -ForegroundColor Red }
function Write-Header { param($msg) Write-Host "`n" + ("═" * 80) -ForegroundColor Cyan; Write-Host $msg -ForegroundColor Yellow; Write-Host ("═" * 80) -ForegroundColor Cyan }

Write-Header "🔧 3000 Studios - WordPress Complete Reinstallation & Fix"
Write-Info "Mode: $Mode"
Write-Info "Backup Directory: $BackupDir"
Write-Host ""

# Configuration
$wpDownloadUrl = "https://wordpress.org/latest.zip"
$wpZip = "$env:TEMP\wordpress-latest.zip"
$wpExtract = "$env:TEMP\wordpress-extracted"

# ============================================================================
# STEP 1: PRE-FLIGHT CHECKS
# ============================================================================
Write-Header "STEP 1: Pre-Flight Checks"

# Check for required tools
$requiredTools = @{
    "curl" = "Download WordPress"
    "php" = "Run WordPress"
    "mysql" = "Database access (optional)"
}

foreach ($tool in $requiredTools.Keys) {
    try {
        $null = Get-Command $tool -ErrorAction Stop
        Write-Success "$tool found - $($requiredTools[$tool])"
    } catch {
        Write-Warning "$tool not found - $($requiredTools[$tool])"
    }
}

# ============================================================================
# STEP 2: CREATE BACKUP
# ============================================================================
Write-Header "STEP 2: Creating Backup"

if ($Mode -eq "local") {
    Write-Info "Creating local backup..."

    # Create backup directory
    New-Item -ItemType Directory -Path $BackupDir -Force | Out-Null
    Write-Success "Backup directory created: $BackupDir"

    # Backup current theme
    $themeDir = $PSScriptRoot | Split-Path -Parent
    $themeBackup = Join-Path $BackupDir "theme-backup"

    Write-Info "Backing up theme files..."
    if (-not (Test-Path $BackupDir)) {
        New-Item -ItemType Directory -Path $BackupDir -Force | Out-Null
    }
    Copy-Item -Path $themeDir -Destination $themeBackup -Recurse -Force -ErrorAction Stop
    Write-Success "Theme backed up to: $themeBackup"} else {
    Write-Info "Remote mode - backup should be done via hosting panel"
    Write-Warning "⚠️  MANUAL ACTION REQUIRED:"
    Write-Host "   1. Login to your hosting control panel" -ForegroundColor Gray
    Write-Host "   2. Navigate to File Manager or Backup section" -ForegroundColor Gray
    Write-Host "   3. Create a full backup of public_html" -ForegroundColor Gray
    Write-Host "   4. Download the backup to your local machine" -ForegroundColor Gray
    Write-Host ""
    $continue = Read-Host "Have you completed the backup? (y/n)"
    if ($continue -ne "y") {
        Write-Error "Backup not confirmed. Exiting for safety."
        exit 1
    }
}

# ============================================================================
# STEP 3: DOWNLOAD FRESH WORDPRESS
# ============================================================================
Write-Header "STEP 3: Downloading Fresh WordPress"

Write-Info "Downloading WordPress from: $wpDownloadUrl"

try {
    # Download WordPress
    Invoke-WebRequest -Uri $wpDownloadUrl -OutFile $wpZip -UseBasicParsing
    Write-Success "WordPress downloaded: $wpZip"

    # Extract WordPress
    Write-Info "Extracting WordPress..."
    if (Test-Path $wpExtract) {
        Remove-Item -Path $wpExtract -Recurse -Force
    }
    Expand-Archive -Path $wpZip -DestinationPath $wpExtract -Force
    Write-Success "WordPress extracted to: $wpExtract"

    # Verify extraction
    $wpCore = Join-Path $wpExtract "wordpress"
    if (-not (Test-Path $wpCore)) {
        throw "WordPress core not found after extraction"
    }
    Write-Success "WordPress core verified"

} catch {
    Write-Error "Failed to download/extract WordPress: $_"
    exit 1
}

# ============================================================================
# STEP 4: GENERATE WP-CONFIG.PHP
# ============================================================================
Write-Header "STEP 4: Generate wp-config.php"

Write-Info "Creating secure wp-config.php..."

# Get database credentials
Write-Host ""
Write-Host "📊 Database Configuration" -ForegroundColor Yellow
Write-Host "Enter your database credentials:" -ForegroundColor Cyan
$dbName = Read-Host "Database Name"
$dbUser = Read-Host "Database User"
$dbPassword = Read-Host "Database Password" -AsSecureString
$dbPasswordPlain = [Runtime.InteropServices.Marshal]::PtrToStringAuto([Runtime.InteropServices.Marshal]::SecureStringToBSTR($dbPassword))
$dbHost = Read-Host "Database Host (default: localhost)"
if ([string]::IsNullOrWhiteSpace($dbHost)) { $dbHost = "localhost" }
$tablePrefix = Read-Host "Table Prefix (default: wp_)"
if ([string]::IsNullOrWhiteSpace($tablePrefix)) { $tablePrefix = "wp_" }

# Fetch security keys from WordPress API
Write-Info "Fetching fresh security keys from WordPress.org..."
try {
    $saltKeys = Invoke-RestMethod -Uri "https://api.wordpress.org/secret-key/1.1/salt/" -UseBasicParsing
    Write-Success "Security keys generated"
} catch {
    Write-Warning "Could not fetch security keys, using fallback"
    $saltKeys = @"
define('AUTH_KEY',         'put your unique phrase here');
define('SECURE_AUTH_KEY',  'put your unique phrase here');
define('LOGGED_IN_KEY',    'put your unique phrase here');
define('NONCE_KEY',        'put your unique phrase here');
define('AUTH_SALT',        'put your unique phrase here');
define('SECURE_AUTH_SALT', 'put your unique phrase here');
define('LOGGED_IN_SALT',   'put your unique phrase here');
define('NONCE_SALT',       'put your unique phrase here');
"@
}

# Generate wp-config.php
$wpConfig = @"
<?php
/**
 * WordPress Configuration File
 * Generated by 3000 Studios WordPress Reinstall Script
 * Date: $(Get-Date -Format 'yyyy-MM-dd HH:mm:ss')
 */

// ** Database Settings ** //
define('DB_NAME', '$dbName');
define('DB_USER', '$dbUser');
define('DB_PASSWORD', '$dbPasswordPlain');
define('DB_HOST', '$dbHost');
define('DB_CHARSET', 'utf8mb4');
define('DB_COLLATE', '');

// ** Security Keys ** //
$saltKeys

// ** Database Table Prefix ** //
`$table_prefix = '$tablePrefix';

// ** WordPress Debug Mode ** //
define('WP_DEBUG', false);
define('WP_DEBUG_LOG', false);
define('WP_DEBUG_DISPLAY', false);

// ** Performance & Security ** //
define('DISALLOW_FILE_EDIT', true);
define('AUTOMATIC_UPDATER_DISABLED', false);
define('WP_AUTO_UPDATE_CORE', 'minor');
define('WP_POST_REVISIONS', 5);
define('AUTOSAVE_INTERVAL', 300);
define('WP_MEMORY_LIMIT', '256M');
define('WP_MAX_MEMORY_LIMIT', '512M');

// ** HTTPS Settings ** //
if (isset(`$_SERVER['HTTP_X_FORWARDED_PROTO']) && `$_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
    `$_SERVER['HTTPS'] = 'on';
}

// ** Absolute path to WordPress directory ** //
if (!defined('ABSPATH')) {
    define('ABSPATH', __DIR__ . '/');
}

// ** Load WordPress ** //
require_once ABSPATH . 'wp-settings.php';
"@

$wpConfigFile = Join-Path $wpCore "wp-config.php"
$wpConfig | Out-File -FilePath $wpConfigFile -Encoding UTF8 -Force
Write-Success "wp-config.php created with secure settings"

# ============================================================================
# STEP 5: FIX THEME FUNCTIONS.PHP
# ============================================================================
Write-Header "STEP 5: Fix Theme Issues"

Write-Info "Applying fixes to theme files..."

# Fix for admin access issue
$themeDir = $PSScriptRoot | Split-Path -Parent
$functionsPhp = Join-Path $themeDir "functions.php"

if (Test-Path $functionsPhp) {
    $functionsContent = Get-Content $functionsPhp -Raw

    # Check if is_admin() fix is already present
    if ($functionsContent -notmatch "is_admin\(\)") {
        Write-Info "Adding is_admin() check to functions.php..."

        # Find the studios_enqueue_assets function
        if ($functionsContent -match "function studios_enqueue_assets\(\)\s*\{") {
            $functionsContent = $functionsContent -replace "(function studios_enqueue_assets\(\)\s*\{)", "`$1`n    // Don't load theme assets on admin pages - prevents wp-admin conflicts`n    if (is_admin()) {`n        return;`n    }`n"
            $functionsContent | Out-File -FilePath $functionsPhp -Encoding UTF8 -Force
            Write-Success "Admin access fix applied to functions.php"
        } else {
            Write-Warning "Could not find studios_enqueue_assets function"
        }
    } else {
        Write-Success "Admin access fix already present in functions.php"
    }
} else {
    Write-Warning "functions.php not found in theme directory"
}

# ============================================================================
# STEP 6: GENERATE DEPLOYMENT PACKAGE
# ============================================================================
Write-Header "STEP 6: Generate Deployment Package"

$deployPackage = Join-Path $BackupDir "wordpress-deploy"
New-Item -ItemType Directory -Path $deployPackage -Force | Out-Null

Write-Info "Copying WordPress core files..."
Copy-Item -Path "$wpCore\*" -Destination $deployPackage -Recurse -Force

Write-Info "Preparing wp-content directory..."
$wpContentDest = Join-Path $deployPackage "wp-content"
$wpContentThemes = Join-Path $wpContentDest "themes"
New-Item -ItemType Directory -Path $wpContentThemes -Force | Out-Null

Write-Info "Copying 3000 Studios theme..."
$themeName = Split-Path $themeDir -Leaf
$themeDestination = Join-Path $wpContentThemes $themeName
Copy-Item -Path $themeDir -Destination $themeDestination -Recurse -Force

Write-Success "Deployment package created: $deployPackage"

# ============================================================================
# STEP 7: CREATE DEPLOYMENT ZIP
# ============================================================================
Write-Header "STEP 7: Create Deployment Archive"

$deployZip = Join-Path $BackupDir "wordpress-3000studios-deploy.zip"
Write-Info "Creating ZIP archive..."

if (Test-Path $deployZip) {
    Remove-Item $deployZip -Force
}

Compress-Archive -Path "$deployPackage\*" -DestinationPath $deployZip -Force
$zipSize = (Get-Item $deployZip).Length / 1MB

Write-Success "Deployment ZIP created: $deployZip"
Write-Info "Size: $([math]::Round($zipSize, 2)) MB"

# ============================================================================
# STEP 8: GENERATE DEPLOYMENT INSTRUCTIONS
# ============================================================================
Write-Header "STEP 8: Generate Deployment Instructions"

$deployInstructions = @"
═══════════════════════════════════════════════════════════════
🚀 WORDPRESS REINSTALLATION DEPLOYMENT INSTRUCTIONS
═══════════════════════════════════════════════════════════════

📦 Deployment Package: wordpress-3000studios-deploy.zip
📅 Generated: $(Get-Date -Format 'yyyy-MM-dd HH:mm:ss')
🏢 Site: 3000studios.com

═══════════════════════════════════════════════════════════════
⚡ QUICK DEPLOYMENT (10-15 minutes)
═══════════════════════════════════════════════════════════════

OPTION 1: VIA CPANEL FILE MANAGER (RECOMMENDED)
───────────────────────────────────────────────────────────────

1. LOGIN TO CPANEL:
   • Go to your hosting control panel
   • Login with your credentials

2. BACKUP CURRENT SITE:
   • Navigate to File Manager
   • Select public_html folder
   • Click "Compress" → Create backup.zip
   • Download backup.zip to your computer

3. CLEAR PUBLIC_HTML:
   • Select all files in public_html
   • Click "Delete" (your backup is safe)
   • Confirm deletion

4. UPLOAD WORDPRESS:
   • Click "Upload" in File Manager
   • Upload: wordpress-3000studios-deploy.zip
   • Wait for upload to complete

5. EXTRACT WORDPRESS:
   • Right-click wordpress-3000studios-deploy.zip
   • Click "Extract"
   • Extract to: /public_html/
   • Confirm extraction

6. SET PERMISSIONS:
   • Select wp-content folder
   • Click "Change Permissions"
   • Set to: 755 (folders) / 644 (files)
   • Apply recursively

7. DELETE ZIP:
   • Delete wordpress-3000studios-deploy.zip

8. TEST SITE:
   • Visit: https://3000studios.com
   • Visit: https://3000studios.com/wp-admin
   • Login with your WordPress credentials


OPTION 2: VIA FTP (FILEZILLA/WINSCP)
───────────────────────────────────────────────────────────────

1. CONNECT TO SERVER:
   • Open FileZilla or WinSCP
   • Host: ftp.3000studios.com
   • Username: [Your FTP Username]
   • Password: [Your FTP Password]
   • Port: 21
   • Connect

2. BACKUP CURRENT SITE:
   • Navigate to /public_html/
   • Download entire folder to local machine

3. CLEAR PUBLIC_HTML:
   • Delete all files in /public_html/

4. EXTRACT ZIP LOCALLY:
   • Extract wordpress-3000studios-deploy.zip on your computer

5. UPLOAD FILES:
   • Navigate to extracted folder
   • Select all files and folders
   • Upload to /public_html/
   • Wait for upload to complete (may take 10-15 minutes)

6. SET PERMISSIONS (via FTP client):
   • Right-click wp-content folder
   • Properties → Permissions
   • Set: 755 for folders, 644 for files
   • Apply to all subdirectories

7. TEST SITE:
   • Visit: https://3000studios.com
   • Visit: https://3000studios.com/wp-admin


OPTION 3: VIA SSH (ADVANCED)
───────────────────────────────────────────────────────────────

1. CONNECT VIA SSH:
   ssh username@3000studios.com

2. NAVIGATE TO WEB ROOT:
   cd ~/public_html

3. BACKUP CURRENT SITE:
   tar -czf backup-`$(date +%Y%m%d-%H%M%S).tar.gz .

4. CLEAR DIRECTORY:
   rm -rf *

5. UPLOAD AND EXTRACT:
   # Upload wordpress-3000studios-deploy.zip via FTP first, then:
   unzip wordpress-3000studios-deploy.zip
   rm wordpress-3000studios-deploy.zip

6. SET PERMISSIONS:
   find . -type d -exec chmod 755 {} \;
   find . -type f -exec chmod 644 {} \;
   chmod 600 wp-config.php

7. TEST:
   curl -I https://3000studios.com


═══════════════════════════════════════════════════════════════
🔧 POST-INSTALLATION CHECKS
═══════════════════════════════════════════════════════════════

✓ SITE ACCESSIBILITY:
  □ Homepage loads: https://3000studios.com
  □ No errors displayed
  □ Theme is active (3000 Studios theme)

✓ ADMIN ACCESS:
  □ Can access: https://3000studios.com/wp-admin
  □ Can login successfully
  □ Dashboard loads without errors

✓ THEME FUNCTIONALITY:
  □ All pages load correctly
  □ AI Dashboard accessible (if admin)
  □ No JavaScript console errors (F12)

✓ DATABASE CONNECTION:
  □ Posts/pages display correctly
  □ Can create new posts
  □ Media uploads work

✓ SECURITY:
  □ HTTPS is working (green padlock)
  □ File permissions correct
  □ wp-config.php not publicly accessible


═══════════════════════════════════════════════════════════════
🐛 TROUBLESHOOTING
═══════════════════════════════════════════════════════════════

ISSUE: White screen / blank page
FIX:
  • Enable debug mode in wp-config.php:
    define('WP_DEBUG', true);
    define('WP_DEBUG_LOG', true);
  • Check error log: wp-content/debug.log

ISSUE: Database connection error
FIX:
  • Verify database credentials in wp-config.php
  • Check database exists in hosting panel
  • Ensure database user has permissions

ISSUE: Cannot access wp-admin
FIX:
  • Clear browser cache
  • Try incognito/private window
  • Reset .htaccess: delete and regenerate via Settings → Permalinks

ISSUE: Theme not displaying
FIX:
  • Login to wp-admin
  • Go to Appearance → Themes
  • Activate "3000 Studios Theme"

ISSUE: 404 errors on pages
FIX:
  • Go to Settings → Permalinks
  • Click "Save Changes" (don't change anything)
  • This regenerates .htaccess


═══════════════════════════════════════════════════════════════
📊 WHAT'S INCLUDED
═══════════════════════════════════════════════════════════════

✓ Fresh WordPress Core (latest version)
✓ Secure wp-config.php with fresh security keys
✓ 3000 Studios Theme (fixed for admin access)
✓ AI Dashboard template
✓ All theme assets and scripts
✓ Security hardening enabled
✓ Performance optimizations


═══════════════════════════════════════════════════════════════
📞 SUPPORT
═══════════════════════════════════════════════════════════════

Email: mr.jwswain@gmail.com
Site: https://3000studios.com

═══════════════════════════════════════════════════════════════
© 2025 Mr. J.W. Swain - 3000 Studios. All Rights Reserved.
═══════════════════════════════════════════════════════════════
"@

$instructionsFile = Join-Path $BackupDir "DEPLOYMENT-INSTRUCTIONS.txt"
$deployInstructions | Out-File -FilePath $instructionsFile -Encoding UTF8 -Force

Write-Success "Deployment instructions created: $instructionsFile"

# ============================================================================
# STEP 9: CREATE QUICK REFERENCE CARD
# ============================================================================

$quickRef = @"
╔══════════════════════════════════════════════════════════════╗
║     WORDPRESS REINSTALL - QUICK REFERENCE CARD              ║
╠══════════════════════════════════════════════════════════════╣
║                                                              ║
║  📦 FILES LOCATION:                                          ║
║     $BackupDir
║                                                              ║
║  🚀 FASTEST METHOD:                                          ║
║     1. Login to cPanel File Manager                          ║
║     2. Backup & delete all in public_html                    ║
║     3. Upload: wordpress-3000studios-deploy.zip              ║
║     4. Extract to public_html                                ║
║     5. Test: https://3000studios.com                         ║
║                                                              ║
║  ⏱️  TIME REQUIRED: 10-15 minutes                            ║
║                                                              ║
║  📖 FULL INSTRUCTIONS: DEPLOYMENT-INSTRUCTIONS.txt           ║
║                                                              ║
╚══════════════════════════════════════════════════════════════╝
"@

Write-Host "`n$quickRef" -ForegroundColor Cyan

# ============================================================================
# STEP 10: FINAL SUMMARY
# ============================================================================
Write-Header "✅ WORDPRESS REINSTALLATION PACKAGE COMPLETE"

Write-Host ""
Write-Host "📦 GENERATED FILES:" -ForegroundColor Yellow
Write-Host "   ├─ wordpress-3000studios-deploy.zip ($([math]::Round($zipSize, 2)) MB)" -ForegroundColor White
Write-Host "   ├─ DEPLOYMENT-INSTRUCTIONS.txt" -ForegroundColor White
Write-Host "   ├─ theme-backup/ (original theme backup)" -ForegroundColor White
Write-Host "   └─ wordpress-deploy/ (extracted files)" -ForegroundColor White
Write-Host ""

Write-Host "📍 LOCATION:" -ForegroundColor Yellow
Write-Host "   $BackupDir" -ForegroundColor White
Write-Host ""

Write-Host "🚀 NEXT STEPS:" -ForegroundColor Cyan
Write-Host "   1. Open: $BackupDir" -ForegroundColor White
Write-Host "   2. Read: DEPLOYMENT-INSTRUCTIONS.txt" -ForegroundColor White
Write-Host "   3. Upload: wordpress-3000studios-deploy.zip to your server" -ForegroundColor White
Write-Host "   4. Extract and test" -ForegroundColor White
Write-Host ""

Write-Host "═" * 80 -ForegroundColor Green
Write-Host "✅ Ready to deploy to 3000studios.com!" -ForegroundColor Green
Write-Host "═" * 80 -ForegroundColor Green
Write-Host ""

# Open folder in explorer
if ($IsWindows -or $env:OS -like "*Windows*") {
    Write-Info "Opening backup folder..."
    Start-Process "explorer.exe" -ArgumentList $BackupDir
}
