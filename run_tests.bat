@echo off
set PATH=C:\wamp64\bin\php\php8.3.28;%PATH%
echo Running Full Automated Quality Assurance Test Suite (21 Tests, 103 Assertions)...
"C:\wamp64\bin\php\php8.3.28\php.exe" artisan test
pause
