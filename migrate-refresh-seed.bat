@echo off
cd /d "%~dp0"
echo Refreshing database...
D:\Application\XAMPP\php\php.exe spark migrate:refresh --seed
pause

