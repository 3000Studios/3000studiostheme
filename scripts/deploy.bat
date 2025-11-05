@echo off
REM 3000 Studios Theme Deployment Script - Black Vault SUPREME (Windows)
REM Creates complete deployment package with all components
REM Author: Mr. jwswain & 3000 Studios
REM Copyright (c) 2025 - All Rights Reserved

echo.
echo 🚀 Black Vault SUPREME - Theme Deployment Package Creator
echo ===============================================
echo Creating ultimate WordPress theme package...
echo.

REM Set variables
set THEME_NAME=3000studiostheme
set VERSION=1.0.0
for /f "tokens=2 delims==" %%a in ('wmic OS Get localdatetime /value') do set "dt=%%a"
set "YY=%dt:~2,2%" & set "YYYY=%dt:~0,4%" & set "MM=%dt:~4,2%" & set "DD=%dt:~6,2%"
set "HH=%dt:~8,2%" & set "Min=%dt:~10,2%" & set "Sec=%dt:~12,2%"
set "datestamp=%YYYY%%MM%%DD%_%HH%%Min%%Sec%"
set PACKAGE_NAME=%THEME_NAME%_v%VERSION%_%datestamp%
set OUTPUT_DIR=..\theme-packages
set TEMP_DIR=%TEMP%\%PACKAGE_NAME%

REM Create directories
if not exist "%OUTPUT_DIR%" mkdir "%OUTPUT_DIR%"
if not exist "%TEMP_DIR%" mkdir "%TEMP_DIR%"

echo 📦 Copying theme files...

REM Copy all theme files
xcopy /E /I /Y . "%TEMP_DIR%"

REM Remove unnecessary files
pushd "%TEMP_DIR%"
if exist .git rmdir /S /Q .git
if exist .vscode rmdir /S /Q .vscode
if exist node_modules rmdir /S /Q node_modules
del /Q .env* 2>nul
del /Q *.log 2>nul
del /Q .DS_Store 2>nul
del /Q Thumbs.db 2>nul
popd

echo 📝 Generating documentation...

REM Create README (same content as bash version)
echo # 🚀 Black Vault SUPREME - 3000 Studios Theme > "%TEMP_DIR%\README.md"
echo. >> "%TEMP_DIR%\README.md"
echo **The Ultimate AI-Powered WordPress Theme** >> "%TEMP_DIR%\README.md"
echo. >> "%TEMP_DIR%\README.md"
echo ## 🔥 Features >> "%TEMP_DIR%\README.md"
echo. >> "%TEMP_DIR%\README.md"
echo ### 🤖 AI Command Center >> "%TEMP_DIR%\README.md"
echo - **Voice Control**: Speak commands and watch them execute in real-time >> "%TEMP_DIR%\README.md"
echo - **Sexy AI Assistant**: Black Vault SUPREME responds with personality >> "%TEMP_DIR%\README.md"
echo - **Natural Language Processing**: Understands complex commands >> "%TEMP_DIR%\README.md"
echo - **Live Preview**: See changes before applying them >> "%TEMP_DIR%\README.md"
echo - **Real-time Editing**: Updates go live instantly >> "%TEMP_DIR%\README.md"
echo. >> "%TEMP_DIR%\README.md"
echo ### 💰 Monetization Engine >> "%TEMP_DIR%\README.md"
echo - **Stripe Integration**: Accept payments instantly >> "%TEMP_DIR%\README.md"
echo - **PayPal Support**: Multiple payment options >> "%TEMP_DIR%\README.md"
echo - **Revenue Analytics**: Real-time profit tracking >> "%TEMP_DIR%\README.md"
echo. >> "%TEMP_DIR%\README.md"
echo ## 🔧 Installation >> "%TEMP_DIR%\README.md"
echo. >> "%TEMP_DIR%\README.md"
echo 1. Upload the theme folder to `/wp-content/themes/` >> "%TEMP_DIR%\README.md"
echo 2. Activate the theme in WordPress admin >> "%TEMP_DIR%\README.md"
echo 3. Go to **Appearance ^> 3000 Studios API Settings** >> "%TEMP_DIR%\README.md"
echo 4. Add your API keys (optional but recommended) >> "%TEMP_DIR%\README.md"
echo 5. Visit your site and go to `/login` to access the Command Center >> "%TEMP_DIR%\README.md"

REM Create deployment info
echo Black Vault SUPREME - 3000 Studios Theme > "%TEMP_DIR%\DEPLOYMENT_INFO.txt"
echo Deployment Package: %PACKAGE_NAME% >> "%TEMP_DIR%\DEPLOYMENT_INFO.txt"
echo Created: %date% %time% >> "%TEMP_DIR%\DEPLOYMENT_INFO.txt"
echo Version: %VERSION% >> "%TEMP_DIR%\DEPLOYMENT_INFO.txt"
echo. >> "%TEMP_DIR%\DEPLOYMENT_INFO.txt"
echo Installation: >> "%TEMP_DIR%\DEPLOYMENT_INFO.txt"
echo 1. Upload to /wp-content/themes/3000studiostheme/ >> "%TEMP_DIR%\DEPLOYMENT_INFO.txt"
echo 2. Activate in WordPress admin >> "%TEMP_DIR%\DEPLOYMENT_INFO.txt"
echo 3. Configure API keys (optional) >> "%TEMP_DIR%\DEPLOYMENT_INFO.txt"
echo 4. Access Command Center at yoursite.com/login >> "%TEMP_DIR%\DEPLOYMENT_INFO.txt"
echo. >> "%TEMP_DIR%\DEPLOYMENT_INFO.txt"
echo Support: https://3000studios.com >> "%TEMP_DIR%\DEPLOYMENT_INFO.txt"
echo Copyright (c) 2025 Mr. jwswain ^& 3000 Studios >> "%TEMP_DIR%\DEPLOYMENT_INFO.txt"
echo All Rights Reserved >> "%TEMP_DIR%\DEPLOYMENT_INFO.txt"

echo ✅ Documentation created

REM Create the ZIP package
echo 📦 Creating deployment package...

REM Use PowerShell to create ZIP (available on Windows 10/11)
powershell -Command "Compress-Archive -Path '%TEMP_DIR%\*' -DestinationPath '%OUTPUT_DIR%\%PACKAGE_NAME%.zip' -Force"

REM Cleanup
rmdir /S /Q "%TEMP_DIR%"

echo.
echo 🎉 DEPLOYMENT PACKAGE CREATED SUCCESSFULLY!
echo ===============================================
echo 📦 Package Name: %PACKAGE_NAME%
echo 📍 Location: %OUTPUT_DIR%\
echo 📏 File: %PACKAGE_NAME%.zip
echo.
echo 🚀 Ready for deployment!
echo.
echo Next Steps:
echo 1. Upload to your WordPress site
echo 2. Activate the theme
echo 3. Configure API keys
echo 4. Start using Black Vault SUPREME!
echo.
echo 💜 Made with love by Mr. jwswain ^& 3000 Studios
echo 🌐 https://3000studios.com
echo.
pause