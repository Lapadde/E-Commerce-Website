@echo off
cd /d "%~dp0"
D:\Application\XAMPP\php\php.exe spark db:seed AdminSeeder
pause

