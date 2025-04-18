FROM composer:latest AS composer

FROM php:8.4-fpm

# ติดตั้ง PHP Extension ที่ Laravel ใช้
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip unzip git curl \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libpq-dev \
    libgmp-dev \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql mbstring zip exif pcntl bcmath gd gmp

# ติดตั้ง Composer
COPY --from=composer /usr/bin/composer /usr/bin/composer

# ตั้ง working directory
WORKDIR /var/www

# คัดลอกไฟล์โปรเจกต์ทั้งหมด
COPY . .

# ให้สิทธิ์ storage และ bootstrap/cache
RUN chmod -R 777 storage bootstrap/cache

# เปิดพอร์ต 8000
EXPOSE 8000

# คำสั่งเริ่มต้น: ติดตั้ง dependencies, migrate และรัน Laravel server
CMD composer install --optimize-autoloader --no-dev && \
    php artisan key:generate --force && \
    php artisan migrate --force --seed && \
    php artisan storage:link && \
    php artisan serve --host=0.0.0.0 --port=8000
