@echo off
echo ========================================
echo Running Database Migration
echo ========================================
echo.

cd /d "%~dp0"

echo Checking PHP...
if not exist "d:\Application\XAMPP\php\php.exe" (
    echo ERROR: PHP not found at d:\Application\XAMPP\php\php.exe
    echo Please update the PHP path in this script.
    pause
    exit /b 1
)

echo Running migration...
d:\Application\XAMPP\php\php.exe spark migrate

if errorlevel 1 (
    echo.
    echo ERROR: Migration failed!
    echo Please check the error message above.
    pause
    exit /b 1
)

echo.
echo ========================================
echo Migration completed successfully!
echo ========================================
echo.
echo Next steps:
echo 1. Clear cache: Delete writable\cache\* files
echo 2. Try checkout again
echo.
pause

