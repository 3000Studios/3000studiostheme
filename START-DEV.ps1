#!/usr/bin/env pwsh
# 3000 Studios Theme - Auto-Start Development Environment
# © 2025 Mr. J.W. Swain - 3000 Studios

Write-Host "`n🚀 Starting 3000 Studios Development Environment..." -ForegroundColor Cyan

# Add Node.js and PHP to PATH for this session
$env:PATH = "C:\Program Files\nodejs;C:\xampp\php;" + $env:PATH

Write-Host "`n📊 Verifying installations..." -ForegroundColor Yellow
Write-Host "Node.js: " -NoNewline
node --version
Write-Host "npm: " -NoNewline
npm --version
Write-Host "PHP: " -NoNewline
php --version | Select-Object -First 1
Write-Host "Git: " -NoNewline
git --version

Write-Host "`n🔧 Starting XAMPP Control Panel..." -ForegroundColor Yellow
Start-Process "C:\xampp\xampp-control.exe"

Write-Host "`n⏳ Waiting for XAMPP to initialize..." -ForegroundColor Yellow
Start-Sleep -Seconds 3

Write-Host "`n✅ Development environment ready!" -ForegroundColor Green
Write-Host "`n📝 Available commands:" -ForegroundColor Cyan
Write-Host "  npm run lint          - Check code quality"
Write-Host "  npm run dev           - Start dev mode with auto-refresh"
Write-Host "  npm run watch         - Watch for file changes"
Write-Host "  npm run watch:push    - Watch + auto-push to GitHub"
Write-Host "`n🌐 XAMPP Services:" -ForegroundColor Cyan
Write-Host "  Start Apache and MySQL in XAMPP Control Panel"
Write-Host "  Access WordPress: http://localhost/wordpress"
Write-Host "  phpMyAdmin: http://localhost/phpmyadmin"

Write-Host "`n💡 Pro Tip: Use 'npm run dev' to start auto-refresh mode`n" -ForegroundColor Yellow
