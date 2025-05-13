FROM php:8.2-apache

# تثبيت الاعتمادات الأساسية
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libicu-dev \
    libzip-dev \
    zip \
    unzip

# تثبيت امتدادات PHP
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip intl xml

# تفعيل mod_rewrite
RUN a2enmod rewrite

# تثبيت Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# إعداد مجلد العمل
WORKDIR /var/www/html

# نسخ ملفات composer أولاً
COPY composer.json composer.lock ./

# تثبيت التبعيات
RUN composer install --no-dev --prefer-dist --no-scripts --no-progress --no-interaction --optimize-autoloader --ignore-platform-reqs

# نسخ بقية المشروع
COPY . .

# إنشاء المجلدات الضرورية وضبط الأذونات
RUN mkdir -p storage/framework/sessions storage/framework/views storage/framework/cache
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 775 storage bootstrap/cache vendor

# نسخ .env
RUN cp .env.example .env

# إعداد Apache
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

# تإنشاء ملف يظهر حالة التطبيق
RUN echo '<?php echo "<h1>Nafsaji is running!</h1><p>Time: " . date("Y-m-d H:i:s") . "</p>"; ?>' > public/status.php

# تعريض المنفذ 80
EXPOSE 80

# أمر بدء التشغيل
CMD ["apache2-foreground"]
