@echo off
cd /d "C:\xampp\htdocs\Surat-Metrologi"

echo Testing rollback...
echo.

echo Running npm build...
npm run build

echo.
echo Build completed. Checking if dashboard loads...
echo Check http://127.0.0.1:8000/Admin/Dashboard in your browser
pause
