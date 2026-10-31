@echo off
set PATH=C:\wamp64\bin\php\php8.3.28;%PATH%
echo Starting Institutional Timetable Solver Web Server...
echo Open your browser at: http://localhost:8000
echo (Or access directly via Wamp Apache at: http://localhost/TimeTable-Solver/public)
echo.
"C:\wamp64\bin\php\php8.3.28\php.exe" artisan serve --port=8000
pause
