#!/bin/bash

# عدم الخروج عند حدوث أخطاء
set +e

echo "============================================="
echo "Starting Laravel Application Setup"
echo "============================================="

# التحقق من متغيرات البيئة
echo "Checking database environment variables..."
if [ -z "$DB_HOST" ] || [ -z "$DB_PORT" ] || [ -z "$DB_USERNAME" ] || [ -z "$DB_PASSWORD" ] || [ -z "$DB_DATABASE" ]; then
  echo "Warning: Missing database environment variables. Using default values."
  # استخدام القيم الافتراضية في حالة عدم وجود المتغيرات
  DB_HOST=${DB_HOST:-"interchange.proxy.rlwy.net"}
  DB_PORT=${DB_PORT:-"16147"}
  DB_USERNAME=${DB_USERNAME:-"root"}
  DB_PASSWORD=${DB_PASSWORD:-"nQDUFQBFRjNkaYnBDmKQakXsRrbtorol"}
  DB_DATABASE=${DB_DATABASE:-"railway"}
fi

# انتظار قاعدة البيانات MySQL
echo "Waiting for MySQL database connection..."
wait_time=5
max_tries=12
tries=0

while [ $tries -lt $max_tries ]; do
  tries=$((tries + 1))
  if mysql -h "$DB_HOST" -P "$DB_PORT" -u "$DB_USERNAME" -p"$DB_PASSWORD" "$DB_DATABASE" -e "SELECT 1" &> /dev/null; then
    echo "MySQL connection successful!"
    break
  fi
  echo "Waiting for MySQL... ($tries/$max_tries)"
  sleep $wait_time
done

# إذا لم نتمكن من الاتصال، نواصل على أي حال
if [ $tries -eq $max_tries ]; then
  echo "Warning: Could not connect to MySQL, but continuing anyway..."
fi

# ضبط أذونات المجلدات
echo "Setting directory permissions..."
chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache

# محاولة تنفيذ الترحيل بطريقة آمنة
echo "Attempting database migrations safely..."
# إنشاء جدول ترحيلات جديد إذا لم يكن موجوداً
php artisan migrate:install 2>/dev/null || true

# تشغيل الترحيلات وتجاهل الأخطاء
echo "Running migrations (ignoring errors)..."
php artisan migrate --force --no-interaction 2>/dev/null || true

echo "Clearing Laravel cache..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

echo "Creating storage link..."
php artisan storage:link 2>/dev/null || true

echo "============================================="
echo "Laravel setup completed. Starting web server."
echo "============================================="

# تشغيل خادم الويب
exec apache2-foreground
