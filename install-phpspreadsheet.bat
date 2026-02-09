@echo off
echo ========================================
echo Install PhpSpreadsheet Library
echo ========================================
echo.

cd /d "%~dp0"

REM Set XAMPP paths (sesuaikan dengan lokasi XAMPP Anda)
set XAMPP_PATH=D:\Application\XAMPP
set PHP_PATH=%XAMPP_PATH%\php\php.exe
set COMPOSER_PATH=composer.phar

REM Check if PHP exists in XAMPP
if not exist "%PHP_PATH%" (
    echo PHP tidak ditemukan di: %PHP_PATH%
    echo.
    echo Silakan sesuaikan path XAMPP di script ini jika XAMPP Anda berada di lokasi lain.
    echo Atau pastikan XAMPP sudah terinstall.
    pause
    exit /b 1
)

REM Check if composer.phar exists in current directory, if not try to download or use global composer
if exist "%COMPOSER_PATH%" (
    echo Composer ditemukan di folder project
    set COMPOSER_CMD=%PHP_PATH% %COMPOSER_PATH%
) else (
    REM Try to use global composer first
    where composer >nul 2>nul
    if %ERRORLEVEL% EQU 0 (
        echo Composer ditemukan (global installation)
        set COMPOSER_CMD=composer
    ) else (
        echo Composer tidak ditemukan!
        echo.
        echo [1/2] Mencoba download Composer installer...
        %PHP_PATH% -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
        
        if not exist "composer-setup.php" (
            echo ERROR: Gagal download Composer installer
            echo.
            echo Silakan download Composer manual:
            echo 1. Buka: https://getcomposer.org/download/
            echo 2. Download composer.phar
            echo 3. Simpan di folder project ini
            echo 4. Jalankan script ini lagi
            pause
            exit /b 1
        )
        
        echo [2/2] Installing Composer...
        %PHP_PATH% composer-setup.php
        
        REM Clean up installer
        if exist "composer-setup.php" del "composer-setup.php"
        
        if not exist "%COMPOSER_PATH%" (
            echo ERROR: Gagal install Composer
            echo.
            echo Silakan download Composer manual:
            echo 1. Buka: https://getcomposer.org/download/
            echo 2. Download composer.phar
            echo 3. Simpan di folder project ini
            echo 4. Jalankan script ini lagi
            pause
            exit /b 1
        )
        
        echo Composer berhasil diinstall!
        set COMPOSER_CMD=%PHP_PATH% %COMPOSER_PATH%
    )
)

echo Menginstall PhpSpreadsheet...
echo Menggunakan: %COMPOSER_CMD%
echo.
echo Pastikan koneksi internet aktif...
echo.

REM Try to install PhpSpreadsheet, ignore platform requirements if needed
echo Mencoba install PhpSpreadsheet...
%COMPOSER_CMD% require phpoffice/phpspreadsheet --no-interaction --ignore-platform-reqs

if %ERRORLEVEL% NEQ 0 (
    echo.
    echo WARNING: Install dengan --ignore-platform-reqs gagal
    echo Mencoba install versi yang kompatibel...
    %COMPOSER_CMD% require phpoffice/phpspreadsheet:^2.0 --no-interaction --ignore-platform-reqs
    
    if %ERRORLEVEL% NEQ 0 (
        echo.
        echo ========================================
        echo ERROR: Gagal menginstall PhpSpreadsheet
        echo ========================================
        echo.
        echo Kemungkinan masalah:
        echo 1. Extension GD tidak aktif di PHP
        echo 2. Koneksi internet bermasalah
        echo.
        echo Solusi untuk Extension GD:
        echo 1. Buka file: %XAMPP_PATH%\php\php.ini
        echo 2. Cari baris: ;extension=gd
        echo 3. Hapus tanda ; (semicolon) menjadi: extension=gd
        echo 4. Simpan file dan restart Apache
        echo 5. Jalankan script ini lagi
        echo.
        pause
        exit /b 1
    )
)

if %ERRORLEVEL% EQU 0 (
    echo.
    echo ========================================
    echo PhpSpreadsheet berhasil diinstall!
    echo ========================================
    echo.
    echo Silakan refresh halaman laporan penjualan dan coba download Excel lagi.
) else (
    echo.
    echo ========================================
    echo Gagal menginstall PhpSpreadsheet!
    echo ========================================
    echo.
    echo Pastikan:
    echo 1. XAMPP sudah terinstall
    echo 2. Composer sudah tersedia
    echo 3. Koneksi internet aktif untuk download library
)

echo.
pause

