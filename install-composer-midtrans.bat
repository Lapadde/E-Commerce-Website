@echo off
echo ========================================
echo Installing Composer and Midtrans Library
echo ========================================
echo.

cd /d "%~dp0"

echo [1/4] Checking PHP...
if not exist "d:\Application\XAMPP\php\php.exe" (
    echo ERROR: PHP not found at d:\Application\XAMPP\php\php.exe
    echo Please update the PHP path in this script.
    pause
    exit /b 1
)

echo [2/4] Downloading Composer installer...
d:\Application\XAMPP\php\php.exe -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"

if not exist "composer-setup.php" (
    echo ERROR: Failed to download Composer installer
    pause
    exit /b 1
)

echo [3/4] Installing Composer...
d:\Application\XAMPP\php\php.exe composer-setup.php

if not exist "composer.phar" (
    echo ERROR: Failed to install Composer
    pause
    exit /b 1
)

echo [4/4] Installing Midtrans library...
d:\Application\XAMPP\php\php.exe composer.phar require midtrans/midtrans-php --no-interaction

if errorlevel 1 (
    echo ERROR: Failed to install Midtrans library
    pause
    exit /b 1
)

echo.
echo ========================================
echo Installation completed successfully!
echo ========================================
echo.
echo Next steps:
echo 1. Configure MIDTRANS_SERVER_KEY and MIDTRANS_CLIENT_KEY in .env file
echo 2. Get credentials from https://dashboard.midtrans.com
echo.
pause

