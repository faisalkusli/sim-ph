# Script Deploy Otomatis ke VPS
# Pastikan SSH key sudah di-setup di VPS

Write-Host "===========================================" -ForegroundColor Cyan
Write-Host "  DEPLOY AUTOMATION SCRIPT" -ForegroundColor Cyan
Write-Host "===========================================" -ForegroundColor Cyan
Write-Host ""

# 1. Git Push ke GitHub
Write-Host "[1/4] Pushing to GitHub..." -ForegroundColor Yellow
git add .
git commit -m "Update: $(Get-Date -Format 'yyyy-MM-dd HH:mm:ss')"
git push origin master

if ($LASTEXITCODE -eq 0) {
    Write-Host "✓ GitHub push berhasil!" -ForegroundColor Green
} else {
    Write-Host "✗ GitHub push gagal atau tidak ada perubahan" -ForegroundColor Red
}

Write-Host ""

# 2. Setup SSH Key jika belum
Write-Host "[2/4] Checking SSH setup..." -ForegroundColor Yellow
if (-not (Test-Path "$env:USERPROFILE\.ssh\id_rsa.pub")) {
    Write-Host "SSH key tidak ditemukan. Silakan setup manual terlebih dahulu." -ForegroundColor Red
    Write-Host "Jalankan: ssh-keygen -t rsa -b 4096" -ForegroundColor Yellow
    Write-Host "Kemudian copy public key ke VPS dengan: ssh-copy-id root@simph.cloud" -ForegroundColor Yellow
    exit 1
}

Write-Host ""

# 3. Deploy ke VPS
Write-Host "[3/4] Deploying to VPS..." -ForegroundColor Yellow
Write-Host "Hostname: simph.cloud" -ForegroundColor Cyan
Write-Host "Folder: /var/www/sim-ph" -ForegroundColor Cyan
Write-Host ""

$deployCommands = @"
cd /var/www/sim-ph && \
echo '→ Pulling latest code from GitHub...' && \
git pull origin master && \
echo '→ Installing composer dependencies...' && \
composer install --no-dev --optimize-autoloader && \
echo '→ Clearing and caching configurations...' && \
php artisan config:cache && \
php artisan route:cache && \
php artisan view:cache && \
echo '→ Setting permissions...' && \
chmod -R 775 storage bootstrap/cache && \
echo '✓ Deployment completed successfully!'
"@

# Eksekusi command via SSH
ssh root@simph.cloud $deployCommands

if ($LASTEXITCODE -eq 0) {
    Write-Host ""
    Write-Host "✓ VPS deployment berhasil!" -ForegroundColor Green
} else {
    Write-Host ""
    Write-Host "✗ VPS deployment gagal!" -ForegroundColor Red
    exit 1
}

Write-Host ""
Write-Host "[4/4] Deployment Summary" -ForegroundColor Yellow
Write-Host "===========================================" -ForegroundColor Cyan
Write-Host "✓ Code pushed to GitHub" -ForegroundColor Green
Write-Host "✓ VPS updated successfully" -ForegroundColor Green
Write-Host "✓ Application ready at: http://simph.cloud" -ForegroundColor Green
Write-Host "===========================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "Deployment completed at: $(Get-Date -Format 'yyyy-MM-dd HH:mm:ss')" -ForegroundColor Cyan
