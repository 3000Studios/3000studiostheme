@echo off
REM 3000 Studios Theme - Auto-Start Development Environment (Windows Batch)
REM Copyright 2025 Mr. J.W. Swain - 3000 Studios

echo.
echo 🚀 Starting 3000 Studios Development Environment...
echo.

REM Add Node.js and PHP to PATH
set PATH=C:\Program Files\nodejs;C:\xampp\php;%PATH%

REM Start XAMPP
echo 🔧 Starting XAMPP Control Panel...
start "" "C:\xampp\xampp-control.exe"

echo.
echo ✅ Development environment ready!
echo.
echo 📝 Available commands:
echo   npm run lint          - Check code quality
echo   npm run dev           - Start dev mode with auto-refresh
echo   npm run watch         - Watch for file changes
echo   npm run watch:push    - Watch + auto-push to GitHub
echo.
echo 🌐 XAMPP Services:
echo   Start Apache and MySQL in XAMPP Control Panel
echo   Access WordPress: http://localhost/wordpress
echo   phpMyAdmin: http://localhost/phpmyadmin
echo.
echo 💡 Pro Tip: Use 'npm run dev' to start auto-refresh mode
echo.

cmd /k
