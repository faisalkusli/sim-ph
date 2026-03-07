@echo off
REM Batch file untuk menjalankan database seeder
REM Script ini akan reset database dan seed dengan test accounts

echo ================================
echo DATABASE SEEDER - SIM PERSURATAN
echo ================================
echo.
echo Starting migration and seeding...
echo.

cd c:\xampp\htdocs\sim-ph

REM Reset database dan seed
php artisan migrate:refresh --seed

echo.
echo ================================
echo SEEDING COMPLETE!
echo ================================
echo.
echo Test Accounts Created:
echo.
echo ADMIN:
echo   Email: hukum@malangkab.go.id
echo   Password: admin123
echo.
echo KABAG:
echo   Email: kabag@malangkab.go.id
echo   Password: kabag123
echo.
echo KASUBAG:
echo   Email: kasubag@malangkab.go.id
echo   Password: kasubag123
echo.
echo STAFF (3 accounts):
echo   Email: staff1/2/3@malangkab.go.id
echo   Password: staff123
echo.
echo EXTERNAL/TAMU:
echo   Email: external@gmail.com
echo   Password: user123
echo.
pause
