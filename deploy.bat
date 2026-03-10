@echo off
REM Deploy Script untuk Windows
REM Jalankan script ini untuk deploy aplikasi ke VPS

echo ==========================================
echo   DEPLOY AUTOMATION SCRIPT
echo ==========================================
echo.

REM 1. Git Push ke GitHub
echo [1/4] Pushing to GitHub...
git add .
git commit -m "Update: %date% %time%"
git push origin master

if %errorlevel% equ 0 (
    echo [OK] GitHub push berhasil!
) else (
    echo [INFO] Tidak ada perubahan atau push gagal
)

echo.

REM 2. Deploy ke VPS
echo [2/4] Deploying to VPS simph.cloud...
echo Hostname: simph.cloud
echo Folder: /var/www/sim-ph
echo.

ssh root@simph.cloud "cd /var/www/sim-ph && git pull origin master && composer install --no-dev --optimize-autoloader && php artisan config:cache && php artisan route:cache && php artisan view:cache && chmod -R 775 storage bootstrap/cache && echo 'Deployment completed!'"

if %errorlevel% equ 0 (
    echo.
    echo [OK] VPS deployment berhasil!
) else (
    echo.
    echo [ERROR] VPS deployment gagal!
    pause
    exit /b 1
)

echo.
echo [3/4] Deployment Summary
echo ==========================================
echo [OK] Code pushed to GitHub
echo [OK] VPS updated successfully
echo [OK] Application ready at: http://simph.cloud
echo ==========================================
echo.

pause
