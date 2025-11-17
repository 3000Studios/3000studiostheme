#!/usr/bin/env pwsh
<#
.SYNOPSIS
    Deploy AI Dashboard to 3000studios.com WordPress site

.DESCRIPTION
    This script packages and prepares the AI Dashboard for deployment to your WordPress site.
    Includes all necessary files and generates deployment instructions.

.NOTES
    Author: Mr. J.W. Swain - 3000 Studios
    Date: November 16, 2025
    Copyright (c) 2025 3000 Studios. All Rights Reserved.
#>

$ErrorActionPreference = "Stop"

Write-Host "🧠 AI Dashboard Deployment Packager" -ForegroundColor Cyan
Write-Host "=" * 60 -ForegroundColor Cyan
Write-Host ""

# Configuration
$repoRoot = $PSScriptRoot | Split-Path -Parent
$deployDir = Join-Path $repoRoot "ai-dashboard-deploy"
$zipFile = Join-Path $repoRoot "ai-dashboard-deployment.zip"

# Files to include in deployment
$deployFiles = @(
    "page-ai-dashboard.php",
    "includes/ai-learning.php",
    "includes/wp-intelligence.php",
    "AI-DASHBOARD-DEPLOY.md"
)

Write-Host "📦 Creating deployment package..." -ForegroundColor Yellow
Write-Host ""

# Clean up old deployment
if (Test-Path $deployDir) {
    Remove-Item -Recurse -Force $deployDir
}
if (Test-Path $zipFile) {
    Remove-Item -Force $zipFile
}

# Create deployment directory
New-Item -ItemType Directory -Path $deployDir | Out-Null
New-Item -ItemType Directory -Path "$deployDir/includes" | Out-Null

# Copy files
$fileCount = 0
foreach ($file in $deployFiles) {
    $sourcePath = Join-Path $repoRoot $file
    $destPath = Join-Path $deployDir $file
    
    if (Test-Path $sourcePath) {
        Copy-Item -Path $sourcePath -Destination $destPath -Force
        Write-Host "  ✓ $file" -ForegroundColor Green
        $fileCount++
    } else {
        Write-Host "  ✗ $file (not found)" -ForegroundColor Red
    }
}

Write-Host ""
Write-Host "📝 Creating deployment instructions..." -ForegroundColor Yellow

# Create quick start guide
$quickStart = @"
🧠 AI DASHBOARD - QUICK DEPLOYMENT
═══════════════════════════════════════════════════════════

📁 FILES INCLUDED IN THIS PACKAGE:
───────────────────────────────────────────────────────────
  ✓ page-ai-dashboard.php       - Main dashboard template
  ✓ includes/ai-learning.php    - AI learning system
  ✓ includes/wp-intelligence.php - WordPress integration
  ✓ AI-DASHBOARD-DEPLOY.md      - Full deployment guide

⚡ QUICK DEPLOY (5 MINUTES):
───────────────────────────────────────────────────────────

1. UPLOAD FILES:
   • Connect to your server via FTP or cPanel File Manager
   • Navigate to: /public_html/wp-content/themes/3000studiostheme/
   • Upload all files from this package to corresponding locations
   • Set permissions: Files = 644, Directories = 755

2. CREATE WORDPRESS PAGE:
   • Login to: https://3000studios.com/wp-admin
   • Pages → Add New
   • Title: "AI Dashboard"
   • Template: Select "AI Dashboard V2" (in right sidebar)
   • Publish

3. ACCESS YOUR DASHBOARD:
   • Go to: https://3000studios.com/ai-dashboard
   • You must be logged in as WordPress admin
   • Start using natural language commands!

📖 FULL GUIDE:
───────────────────────────────────────────────────────────
   See AI-DASHBOARD-DEPLOY.md for:
   • Detailed deployment steps
   • Feature overview
   • Troubleshooting guide
   • Customization options
   • Security information

🎤 FEATURES:
───────────────────────────────────────────────────────────
   ✓ Natural language page editing
   ✓ Voice control (Chrome/Edge + HTTPS)
   ✓ Real-time preview before apply
   ✓ AI learning system
   ✓ Neon-futuristic 3000 Studios design
   ✓ Quick action buttons
   ✓ Learning statistics dashboard

🔐 SECURITY:
───────────────────────────────────────────────────────────
   • Admin-only access (edit_theme_options capability)
   • WordPress nonce verification on all AJAX calls
   • Input sanitization and output escaping
   • Automatic backups before execution

📊 DATABASE:
───────────────────────────────────────────────────────────
   Database tables are created automatically on first load:
   • wp_studios_ai_learning
   • wp_studios_ai_patterns
   • wp_studios_ai_page_context

   No manual setup required!

💡 EXAMPLE COMMANDS:
───────────────────────────────────────────────────────────
   "Change the contact page title to Welcome"
   "Make the about page hero text blue"
   "Add a glow effect to buttons on homepage"
   "Find an image of sunset and add it to blog page"

🆘 SUPPORT:
───────────────────────────────────────────────────────────
   Email: mr.jwswain@gmail.com
   Site:  https://3000studios.com

═══════════════════════════════════════════════════════════
© 2025 Mr. J.W. Swain - 3000 Studios. All Rights Reserved.
"@

$quickStart | Out-File -FilePath (Join-Path $deployDir "QUICK-START.txt") -Encoding UTF8

Write-Host ""
Write-Host "📦 Creating ZIP archive..." -ForegroundColor Yellow

# Create ZIP file
Compress-Archive -Path "$deployDir/*" -DestinationPath $zipFile -Force

# Get file size
$zipSize = (Get-Item $zipFile).Length / 1KB

Write-Host ""
Write-Host "✅ DEPLOYMENT PACKAGE READY!" -ForegroundColor Green
Write-Host "=" * 60 -ForegroundColor Cyan
Write-Host ""
Write-Host "📦 Package Location:" -ForegroundColor Yellow
Write-Host "   $zipFile" -ForegroundColor White
Write-Host ""
Write-Host "📊 Package Size:" -ForegroundColor Yellow
Write-Host "   $([math]::Round($zipSize, 2)) KB" -ForegroundColor White
Write-Host ""
Write-Host "📁 Files Included:" -ForegroundColor Yellow
Write-Host "   $fileCount files + deployment guides" -ForegroundColor White
Write-Host ""
Write-Host "🚀 NEXT STEPS:" -ForegroundColor Cyan
Write-Host "   1. Extract the ZIP file" -ForegroundColor White
Write-Host "   2. Read QUICK-START.txt" -ForegroundColor White
Write-Host "   3. Upload files to your WordPress site" -ForegroundColor White
Write-Host "   4. Create WordPress page with AI Dashboard template" -ForegroundColor White
Write-Host "   5. Access at: https://3000studios.com/ai-dashboard" -ForegroundColor White
Write-Host ""
Write-Host "📖 Full documentation in:" -ForegroundColor Yellow
Write-Host "   AI-DASHBOARD-DEPLOY.md" -ForegroundColor White
Write-Host ""
Write-Host "=" * 60 -ForegroundColor Cyan
Write-Host "🎉 Ready to revolutionize your WordPress editing!" -ForegroundColor Green
Write-Host ""

# Open file explorer to deployment location
if ($IsWindows -or $env:OS -like "*Windows*") {
    Start-Process "explorer.exe" -ArgumentList "/select,`"$zipFile`""
}

# Summary
Write-Host "✅ Deployment package created successfully!" -ForegroundColor Green
Write-Host ""
