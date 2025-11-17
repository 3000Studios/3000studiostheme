#!/usr/bin/env pwsh
<#
.SYNOPSIS
    Deploy 3000 Studios Theme to IONOS via SFTP

.DESCRIPTION
    Uploads the WordPress theme to 3000studios.com using SFTP/SSH

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
Write-Host "🚀 3000 Studios Theme - IONOS SFTP Deployment" -ForegroundColor Yellow
Write-Host ("═" * 80) -ForegroundColor Cyan
Write-Host ""

# Check if WinSCP is installed
$winscpPaths = @(
    "C:\Program Files (x86)\WinSCP\WinSCP.com",
    "C:\Program Files\WinSCP\WinSCP.com",
    "$env:LOCALAPPDATA\Programs\WinSCP\WinSCP.com",
    "$env:ProgramFiles\WinSCP\WinSCP.com",
    "${env:ProgramFiles(x86)}\WinSCP\WinSCP.com"
)

$winscpPath = $winscpPaths | Where-Object { Test-Path $_ } | Select-Object -First 1
$winscpInstalled = $null -ne $winscpPath

if ($winscpInstalled) {
    Write-Host "✅ WinSCP found at: $winscpPath" -ForegroundColor Green
}

if (-not $winscpInstalled) {
    Write-Host "⚠️  WinSCP not found. Using alternative method..." -ForegroundColor Yellow
    Write-Host ""
    Write-Host "📦 Creating deployment package..." -ForegroundColor Cyan
    
    $repoRoot = $PSScriptRoot | Split-Path -Parent
    $deployZip = Join-Path $env:TEMP "3000studios-theme-deploy.zip"
    
    # Clean up old zip
    if (Test-Path $deployZip) {
        Remove-Item $deployZip -Force
    }
    
    # Files to exclude
    $excludes = @(
        '.git',
        '.github',
        'node_modules',
        '.vscode',
        '*.backup.*',
        '*.log',
        'ai-dashboard-deploy',
        'scripts',
        'reports',
        'webhook-server',
        'mobile-client',
        'wp-content',
        '*.md',
        '*.zip'
    )
    
    # Create temp directory for clean theme
    $tempDir = Join-Path $env:TEMP "3000studios-clean"
    if (Test-Path $tempDir) {
        Remove-Item -Recurse -Force $tempDir
    }
    New-Item -ItemType Directory -Path $tempDir | Out-Null
    
    # Copy theme files
    Write-Host "📁 Copying theme files..." -ForegroundColor Cyan
    Get-ChildItem -Path $repoRoot -Exclude $excludes | Where-Object {
        $item = $_
        -not ($excludes | Where-Object { $item.Name -like $_ })
    } | ForEach-Object {
        Copy-Item -Path $_.FullName -Destination $tempDir -Recurse -Force
    }
    
    # Create ZIP
    Write-Host "📦 Creating ZIP package..." -ForegroundColor Cyan
    Compress-Archive -Path "$tempDir\*" -DestinationPath $deployZip -Force
    
    $zipSize = (Get-Item $deployZip).Length / 1MB
    Write-Host "✅ Package created: $([math]::Round($zipSize, 2)) MB" -ForegroundColor Green
    
    # Clean up temp
    Remove-Item -Recurse -Force $tempDir
    
    Write-Host ""
    Write-Host ("═" * 80) -ForegroundColor Yellow
    Write-Host "📋 MANUAL UPLOAD INSTRUCTIONS" -ForegroundColor Yellow
    Write-Host ("═" * 80) -ForegroundColor Yellow
    Write-Host ""
    Write-Host "Since WinSCP is not installed, please upload manually:" -ForegroundColor White
    Write-Host ""
    Write-Host "OPTION 1: FileZilla (Recommended)" -ForegroundColor Cyan
    Write-Host "  1. Download FileZilla: https://filezilla-project.org/download.php?type=client" -ForegroundColor Gray
    Write-Host "  2. Install and open FileZilla" -ForegroundColor Gray
    Write-Host "  3. Connect with these credentials:" -ForegroundColor Gray
    Write-Host "     Host: sftp://$SftpHost" -ForegroundColor White
    Write-Host "     Username: $Username" -ForegroundColor White
    Write-Host "     Password: $Pass" -ForegroundColor White
    Write-Host "     Port: $Port" -ForegroundColor White
    Write-Host "  4. Navigate to: $RemotePath/wp-content/themes/" -ForegroundColor Gray
    Write-Host "  5. Upload theme folder OR extract ZIP" -ForegroundColor Gray
    Write-Host ""
    Write-Host "OPTION 2: WinSCP (Windows SFTP Client)" -ForegroundColor Cyan
    Write-Host "  1. Download WinSCP: https://winscp.net/eng/download.php" -ForegroundColor Gray
    Write-Host "  2. Install and run this script again" -ForegroundColor Gray
    Write-Host ""
    Write-Host "OPTION 3: IONOS Web Interface" -ForegroundColor Cyan
    Write-Host "  1. Login to: https://login.ionos.com" -ForegroundColor Gray
    Write-Host "  2. Go to: Hosting → File Manager" -ForegroundColor Gray
    Write-Host "  3. Navigate to: $RemotePath/wp-content/themes/" -ForegroundColor Gray
    Write-Host "  4. Upload and extract: $deployZip" -ForegroundColor Gray
    Write-Host ""
    Write-Host "📦 Deployment package ready at:" -ForegroundColor Green
    Write-Host "   $deployZip" -ForegroundColor White
    Write-Host ""
    Write-Host ("═" * 80) -ForegroundColor Cyan
    
    # Open folder
    Start-Process "explorer.exe" -ArgumentList "/select,`"$deployZip`""
    
    exit 0
}

# WinSCP deployment
Write-Host "✅ WinSCP found - proceeding with automatic deployment..." -ForegroundColor Green
Write-Host ""

# Create WinSCP script
$escapedPass = $Pass -replace '!', '%21'
$winscp_script = @"
option batch abort
option confirm off
open sftp://${Username}:${escapedPass}@${SftpHost}:${Port}/ -hostkey="ssh-ed25519 255 1gx2w8Rtv3wCgi7Jh8myf/KVd72cRQbow03UP8P095Q"
cd ${RemotePath}/wp-content/themes/
synchronize remote -delete -criteria=time "$($PSScriptRoot | Split-Path -Parent)" ./3000studios/
exit
"@

$scriptFile = Join-Path $env:TEMP "winscp_deploy.txt"
$winscp_script | Out-File -FilePath $scriptFile -Encoding ASCII

Write-Host "🔄 Uploading theme via SFTP..." -ForegroundColor Cyan
Write-Host ""

try {
    & $winscpPath /script=$scriptFile /log="$env:TEMP\winscp_log.txt" 2>&1 | Out-Null
    
    if ($LASTEXITCODE -eq 0) {
        Write-Host "✅ Deployment successful!" -ForegroundColor Green
        Write-Host ""
        Write-Host "🌐 Your theme has been uploaded to:" -ForegroundColor Cyan
        Write-Host "   https://3000studios.com" -ForegroundColor White
        Write-Host ""
        Write-Host "🔄 Next steps:" -ForegroundColor Yellow
        Write-Host "   1. Visit: https://3000studios.com" -ForegroundColor White
        Write-Host "   2. Clear browser cache (Ctrl+Shift+R)" -ForegroundColor White
        Write-Host "   3. Check if 'UNDER DEVELOPMENT' shows" -ForegroundColor White
        Write-Host "   4. Try wp-admin login" -ForegroundColor White
    }
    else {
        Write-Host "❌ Deployment failed!" -ForegroundColor Red
        Write-Host ""
        Write-Host "Check log file:" -ForegroundColor Yellow
        Write-Host "   $env:TEMP\winscp_log.txt" -ForegroundColor Gray
    }
}
catch {
    Write-Host "❌ Error: $_" -ForegroundColor Red
}
finally {
    Remove-Item $scriptFile -Force -ErrorAction SilentlyContinue
}

Write-Host ""
Write-Host ("═" * 80) -ForegroundColor Cyan
Write-Host "© 2025 3000 Studios - Deployment Complete" -ForegroundColor Cyan
Write-Host ("═" * 80) -ForegroundColor Cyan
Write-Host ""
