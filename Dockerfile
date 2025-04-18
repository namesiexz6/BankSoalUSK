FROM php:8.2-fpm

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
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql mbstring zip exif pcntl bcmath gd

# ติดตั้ง Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# ตั้ง working directory
WORKDIR /var/www

# คัดลอกไฟล์ทั้งหมดไปยัง container
COPY . .

# ติดตั้ง dependencies ของ Laravel
RUN composer install --optimize-autoloader --no-dev

# ให้สิทธิ์ storage และ bootstrap/cache
RUN chmod -R 777 storage bootstrap/cache

# เปิดพอร์ต 8000
EXPOSE 8000

# คำสั่งเริ่มต้น: migrate และรัน Laravel server
CMD php artisan key:generate --force && \
    php artisan migrate --force && \
    php artisan storage:link && \
    php artisan serve --host=0.0.0.0 --port=8000