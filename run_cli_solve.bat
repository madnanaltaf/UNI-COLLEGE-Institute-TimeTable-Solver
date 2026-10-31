@echo off
set PATH=C:\wamp64\bin\php\php8.3.28;%PATH%
echo Running Headless Batch Timetable CSP Solver and Export Pipeline...
"C:\wamp64\bin\php\php8.3.28\php.exe" artisan timetable:solve --export-dir=institutional_exports
pause
