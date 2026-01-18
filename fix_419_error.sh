#!/bin/bash

# Script untuk memperbaiki error 419 Page Expired di Laravel Production
# Jalankan di VPS dengan: sudo bash fix_419_error.sh

echo "========================================="
echo "FIX 419 ERROR - LARAVEL PRODUCTION"
echo "========================================="

# Pindah ke directory aplikasi
cd /var/www/adi_presensi || { echo "Directory /var/www/adi_presensi not found!"; exit 1; }

echo "1. Memeriksa environment file..."
if [ ! -f .env ]; then
    echo "   .env file not found! Copying from .env.example..."
    cp .env.example .env
fi

echo "2. Memeriksa APP_KEY..."
APP_KEY=$(grep "^APP_KEY=" .env | cut -d'=' -f2)
if [ -z "$APP_KEY" ] || [ "$APP_KEY" == "" ]; then
    echo "   APP_KEY is empty! Generating new key..."
    php artisan key:generate
else
    echo "   APP_KEY exists: ${APP_KEY:0:20}..."
fi

echo "3. Memeriksa konfigurasi session..."
SESSION_DRIVER=$(grep "^SESSION_DRIVER=" .env | cut -d'=' -f2)
if [ -z "$SESSION_DRIVER" ]; then
    echo "   SESSION_DRIVER not set, defaulting to 'file'..."
    echo "SESSION_DRIVER=file" >> .env
else
    echo "   SESSION_DRIVER is: $SESSION_DRIVER"
fi

echo "4. Memeriksa permissions storage directory..."
sudo chown -R www-data:www-data /var/www/adi_presensi
sudo chmod -R 755 /var/www/adi_presensi/storage
sudo chmod -R 755 /var/www/adi_presensi/bootstrap/cache

# Buat directory sessions jika belum ada
sudo mkdir -p /var/www/adi_presensi/storage/framework/sessions
sudo chown -R www-data:www-data /var/www/adi_presensi/storage/framework/sessions
sudo chmod -R 755 /var/www/adi_presensi/storage/framework/sessions

echo "5. Membersihkan session files lama..."
sudo find /var/www/adi_presensi/storage/framework/sessions -type f -mtime +1 -delete

echo "6. Clear Laravel cache..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

echo "7. Cache ulang config..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "8. Memeriksa .env configuration..."
echo "   APP_URL harus sesuai dengan domain production"
CURRENT_APP_URL=$(grep "^APP_URL=" .env | cut -d'=' -f2)
echo "   Current APP_URL: $CURRENT_APP_URL"
echo "   Expected APP_URL: https://sdn.aventra.my.id"

if [ "$CURRENT_APP_URL" != "https://sdn.aventra.my.id" ]; then
    echo "   Updating APP_URL to https://sdn.aventra.my.id..."
    sed -i "s|^APP_URL=.*|APP_URL=https://sdn.aventra.my.id|" .env
fi

echo "9. Memeriksa SESSION_DOMAIN..."
SESSION_DOMAIN=$(grep "^SESSION_DOMAIN=" .env | cut -d'=' -f2)
if [ -z "$SESSION_DOMAIN" ]; then
    echo "   Setting SESSION_DOMAIN to .sdn.aventra.my.id..."
    echo "SESSION_DOMAIN=.sdn.aventra.my.id" >> .env
fi

echo "10. Memeriksa SESSION_SECURE_COOKIE..."
SESSION_SECURE_COOKIE=$(grep "^SESSION_SECURE_COOKIE=" .env | cut -d'=' -f2)
if [ -z "$SESSION_SECURE_COOKIE" ]; then
    echo "   Setting SESSION_SECURE_COOKIE to true (for HTTPS)..."
    echo "SESSION_SECURE_COOKIE=true" >> .env
fi

echo "11. Restart Apache..."
sudo systemctl restart apache2

echo "12. Testing dengan curl..."
echo "   Testing GET request to home page..."
curl -I https://sdn.aventra.my.id

echo "========================================="
echo "PERBAIKAN SELESAI!"
echo "Coba akses https://sdn.aventra.my.id dan test login"
echo "========================================="
echo ""
echo "Jika masih error 419, coba langkah tambahan:"
echo "1. Check file permissions:"
echo "   sudo chmod -R 775 storage bootstrap/cache"
echo "2. Clear browser cookies untuk domain sdn.aventra.my.id"
echo "3. Test dengan browser incognito/private mode"
echo "4. Check Apache error logs:"
echo "   sudo tail -f /var/log/apache2/error.log"
echo "5. Check Laravel logs:"
echo "   tail -f storage/logs/laravel.log"
