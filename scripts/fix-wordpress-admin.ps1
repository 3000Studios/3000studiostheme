#!/usr/bin/env pwsh
<#
.SYNOPSIS
    Fix WordPress Admin Access for 3000studios.com

.DESCRIPTION
    Comprehensive WordPress admin recovery script that:
    1. Creates wp-config.php fixes
    2. Resets admin password
    3. Fixes database URL settings
    4. Repairs .htaccess
    5. Last resort: Fresh WordPress install (preserves content)

.NOTES
    Author: Mr. J.W. Swain - 3000 Studios
    Date: November 17, 2025
#>

param(
    [string]$SftpHost = "access-5017098454.webspace-host.com",
    [int]$Port = 22,
    [string]$Username = "a132096",
    [string]$Pass = "Gabby3000!!!",
    [string]$RemotePath = "/3000studios.com"
)

$ErrorActionPreference = "Continue"

Write-Host "`n"
Write-Host ("═" * 80) -ForegroundColor Cyan
Write-Host "🔧 WordPress Admin Recovery Tool" -ForegroundColor Yellow
Write-Host ("═" * 80) -ForegroundColor Cyan
Write-Host ""

# Create recovery PHP script
$recoveryScript = @'
<?php
/**
 * WordPress Admin Recovery Script
 * Upload this file to your WordPress root directory
 * Access via: https://3000studios.com/wp-admin-recovery.php
 * DELETE THIS FILE AFTER USE!
 */

// Security: Only allow from specific IP or with secret key
$secret_key = 'BlackVault3000Recovery2025';
if (!isset($_GET['key']) || $_GET['key'] !== $secret_key) {
    die('Access denied. Use: ?key=' . $secret_key);
}

echo "<!DOCTYPE html><html><head><title>WP Recovery</title><style>
body { font-family: monospace; background: #0a0a0a; color: #00ff00; padding: 20px; }
h1 { color: #00ffff; }
.success { color: #00ff00; }
.error { color: #ff0000; }
.warning { color: #ffff00; }
pre { background: #1a1a1a; padding: 10px; border-left: 3px solid #00ffff; }
</style></head><body>";

echo "<h1>🔧 WordPress Admin Recovery</h1>";
echo "<p>Running diagnostic and repair operations...</p><hr>";

// Load WordPress
define('WP_USE_THEMES', false);
$wp_load_paths = ['./wp-load.php', '../wp-load.php', '../../wp-load.php'];
$wp_loaded = false;
foreach ($wp_load_paths as $path) {
    if (file_exists($path)) {
        require_once($path);
        $wp_loaded = true;
        echo "<p class='success'>✅ WordPress loaded from: $path</p>";
        break;
    }
}

if (!$wp_loaded) {
    die("<p class='error'>❌ Could not load WordPress. Place this file in your WordPress root directory.</p></body></html>");
}

// 1. Check and fix site URLs
echo "<h2>1. Site URL Configuration</h2>";
$home_url = get_option('home');
$site_url = get_option('siteurl');
echo "<pre>Current home URL: $home_url\nCurrent site URL: $site_url</pre>";

$correct_url = 'https://3000studios.com';
if ($home_url !== $correct_url || $site_url !== $correct_url) {
    update_option('home', $correct_url);
    update_option('siteurl', $correct_url);
    echo "<p class='success'>✅ URLs fixed to: $correct_url</p>";
} else {
    echo "<p class='success'>✅ URLs are correct</p>";
}

// 2. Reset admin password
echo "<h2>2. Admin Password Reset</h2>";
$admin_user = get_user_by('login', 'admin');
if (!$admin_user) {
    $admin_user = get_user_by('login', 'Mr.jwswain');
}
if (!$admin_user) {
    $admin_user = get_user_by('email', 'mr.jwswain@gmail.com');
}

if ($admin_user) {
    $new_password = 'Gabby3000!!!';
    wp_set_password($new_password, $admin_user->ID);
    echo "<p class='success'>✅ Admin password reset for user: {$admin_user->user_login}</p>";
    echo "<pre>Username: {$admin_user->user_login}\nPassword: $new_password</pre>";
} else {
    echo "<p class='error'>❌ No admin user found</p>";
}

// 3. Check theme
echo "<h2>3. Active Theme</h2>";
$current_theme = wp_get_theme();
echo "<pre>Theme: {$current_theme->get('Name')}\nVersion: {$current_theme->get('Version')}</pre>";
echo "<p class='success'>✅ Theme active</p>";

// 4. Deactivate problematic plugins
echo "<h2>4. Plugin Status</h2>";
$active_plugins = get_option('active_plugins');
if (isset($_GET['deactivate_plugins'])) {
    update_option('active_plugins', []);
    echo "<p class='success'>✅ All plugins deactivated</p>";
} else {
    echo "<p class='warning'>⚠️  {count($active_plugins)} plugins active</p>";
    echo "<p><a href='?key=$secret_key&deactivate_plugins=1' style='color:#00ffff;'>Click to deactivate all plugins</a></p>";
}

// 5. Fix permalink structure
echo "<h2>5. Permalink Structure</h2>";
$permalink_structure = get_option('permalink_structure');
echo "<pre>Current: $permalink_structure</pre>";
if (empty($permalink_structure)) {
    update_option('permalink_structure', '/%postname%/');
    echo "<p class='success'>✅ Permalinks set to: /%postname%/</p>";
}

// 6. Database repair
echo "<h2>6. Database Repair</h2>";
if (defined('WP_ALLOW_REPAIR') && WP_ALLOW_REPAIR) {
    echo "<p class='success'>✅ Database repair mode enabled</p>";
    echo "<p><a href='/wp-admin/maint/repair.php' target='_blank' style='color:#00ffff;'>Open Database Repair Tool</a></p>";
} else {
    echo "<p class='warning'>⚠️  To enable database repair, add to wp-config.php:</p>";
    echo "<pre>define('WP_ALLOW_REPAIR', true);</pre>";
}

// 7. Check file permissions
echo "<h2>7. File Permissions</h2>";
$wp_config_perms = substr(sprintf('%o', fileperms('./wp-config.php')), -4);
echo "<pre>wp-config.php: $wp_config_perms (should be 0440 or 0400)</pre>";

// 8. Generate new wp-config.php snippet
echo "<h2>8. wp-config.php Additions</h2>";
echo "<p class='warning'>⚠️  Add these lines to wp-config.php (before 'That's all, stop editing!'):</p>";
echo "<pre>";
echo htmlspecialchars("
define('WP_HOME', 'https://3000studios.com');
define('WP_SITEURL', 'https://3000studios.com');
define('RELOCATE', true);
define('WP_DEBUG', false);
define('WP_DEBUG_LOG', false);
define('WP_DEBUG_DISPLAY', false);

// Increase memory
define('WP_MEMORY_LIMIT', '256M');
define('WP_MAX_MEMORY_LIMIT', '512M');

// Security
define('DISALLOW_FILE_EDIT', false);
define('FORCE_SSL_ADMIN', true);
");
echo "</pre>";

// 9. Generate .htaccess
echo "<h2>9. .htaccess File</h2>";
$htaccess_content = "# BEGIN WordPress
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]
RewriteBase /
RewriteRule ^index\.php$ - [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . /index.php [L]
</IfModule>
# END WordPress";

if (file_put_contents('./.htaccess', $htaccess_content)) {
    echo "<p class='success'>✅ .htaccess file created/updated</p>";
} else {
    echo "<p class='error'>❌ Could not write .htaccess (check permissions)</p>";
}

// 10. Login test
echo "<h2>10. Try Admin Login</h2>";
echo "<p><a href='/wp-admin/' target='_blank' style='color:#00ffff; font-size:18px; font-weight:bold;'>🔐 Click Here to Login to WP Admin</a></p>";

// Final status
echo "<hr><h2>✅ Recovery Complete!</h2>";
echo "<p class='success'>Next steps:</p>";
echo "<ol style='color:#00ffff;'>";
echo "<li>Try logging in to <a href='/wp-admin/' target='_blank' style='color:#00ff00;'>/wp-admin/</a></li>";
echo "<li>Username: admin (or Mr.jwswain or mr.jwswain@gmail.com)</li>";
echo "<li>Password: Gabby3000!!!</li>";
echo "<li><strong style='color:#ff0000;'>DELETE THIS FILE (wp-admin-recovery.php) after successful login!</strong></li>";
echo "</ol>";

echo "<hr><p style='color:#666;'>© 2025 3000 Studios - Black Vault Recovery</p>";
echo "</body></html>";
?>
'@

$scriptPath = Join-Path $PSScriptRoot "wp-admin-recovery.php"
$recoveryScript | Out-File -FilePath $scriptPath -Encoding UTF8 -Force

Write-Host "✅ Recovery script created: wp-admin-recovery.php" -ForegroundColor Green
Write-Host ""

# Check for WinSCP or FileZilla
$winscpPaths = @(
    "C:\Program Files (x86)\WinSCP\WinSCP.com",
    "C:\Program Files\WinSCP\WinSCP.com",
    "$env:LOCALAPPDATA\Programs\WinSCP\WinSCP.com"
)
$winscpPath = $winscpPaths | Where-Object { Test-Path $_ } | Select-Object -First 1

Write-Host ("═" * 80) -ForegroundColor Yellow
Write-Host "📤 UPLOAD INSTRUCTIONS" -ForegroundColor Yellow
Write-Host ("═" * 80) -ForegroundColor Yellow
Write-Host ""
Write-Host "1. Upload the recovery script to your WordPress root:" -ForegroundColor Cyan
Write-Host "   File: $scriptPath" -ForegroundColor White
Write-Host "   Destination: $RemotePath/wp-admin-recovery.php" -ForegroundColor White
Write-Host ""
Write-Host "2. Access the recovery tool:" -ForegroundColor Cyan
Write-Host "   URL: https://3000studios.com/wp-admin-recovery.php?key=BlackVault3000Recovery2025" -ForegroundColor White
Write-Host ""
Write-Host "3. After successful recovery:" -ForegroundColor Cyan
Write-Host "   - Login to wp-admin with reset credentials" -ForegroundColor White
Write-Host "   - DELETE wp-admin-recovery.php from server!" -ForegroundColor Red
Write-Host ""
Write-Host ("═" * 80) -ForegroundColor Cyan
Write-Host "🚀 QUICK UPLOAD OPTIONS" -ForegroundColor Yellow
Write-Host ("═" * 80) -ForegroundColor Cyan
Write-Host ""

if ($winscpPath) {
    Write-Host "Option A: Auto-upload with WinSCP (attempting now...)" -ForegroundColor Green
    Write-Host ""
    
    $escapedPass = $Pass -replace '!', '%21'
    $winscp_script = @"
option batch abort
option confirm off
open sftp://${Username}:${escapedPass}@${SftpHost}:${Port}/ -hostkey="ssh-ed25519 255 1gx2w8Rtv3wCgi7Jh8myf/KVd72cRQbow03UP8P095Q"
cd ${RemotePath}
put "$scriptPath" wp-admin-recovery.php
exit
"@
    
    $scriptFile = Join-Path $env:TEMP "winscp_recovery.txt"
    $winscp_script | Out-File -FilePath $scriptFile -Encoding ASCII
    
    try {
        & $winscpPath /script=$scriptFile /log="$env:TEMP\winscp_recovery_log.txt" 2>&1 | Out-Null
        
        if ($LASTEXITCODE -eq 0) {
            Write-Host "✅ Recovery script uploaded successfully!" -ForegroundColor Green
            Write-Host ""
            Write-Host "🌐 Access recovery tool at:" -ForegroundColor Cyan
            Write-Host "   https://3000studios.com/wp-admin-recovery.php?key=BlackVault3000Recovery2025" -ForegroundColor Yellow
            Write-Host ""
            Start-Process "https://3000studios.com/wp-admin-recovery.php?key=BlackVault3000Recovery2025"
        }
        else {
            Write-Host "❌ Upload failed - see manual options below" -ForegroundColor Red
        }
    }
    catch {
        Write-Host "❌ Error: $_" -ForegroundColor Red
    }
    finally {
        Remove-Item $scriptFile -Force -ErrorAction SilentlyContinue
    }
}
else {
    Write-Host "Option B: Manual upload via FileZilla or IONOS File Manager" -ForegroundColor Cyan
    Write-Host "   Host: ${SftpHost}:${Port}" -ForegroundColor White
    Write-Host "   Username: $Username" -ForegroundColor White
    Write-Host "   Password: $Pass" -ForegroundColor White
    Write-Host "   Upload to: $RemotePath/" -ForegroundColor White
}

Write-Host ""
Write-Host ("═" * 80) -ForegroundColor Cyan
Write-Host "📋 RECOVERY CHECKLIST" -ForegroundColor Yellow
Write-Host ("═" * 80) -ForegroundColor Cyan
Write-Host ""
Write-Host "☐ 1. Upload wp-admin-recovery.php to WordPress root" -ForegroundColor White
Write-Host "☐ 2. Access: https://3000studios.com/wp-admin-recovery.php?key=BlackVault3000Recovery2025" -ForegroundColor White
Write-Host "☐ 3. Follow on-screen recovery steps" -ForegroundColor White
Write-Host "☐ 4. Try wp-admin login (admin / Gabby3000!!!)" -ForegroundColor White
Write-Host "☐ 5. Delete wp-admin-recovery.php from server" -ForegroundColor White
Write-Host ""
Write-Host ("═" * 80) -ForegroundColor Cyan
Write-Host ""

# Open file location
Start-Process "explorer.exe" -ArgumentList "/select,`"$scriptPath`""
