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
    && docker-php-ext-install pdo pdo_pgsql mbstring zip exif pcntl bcmath gd

# ติดตั้ง Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# ไปที่โฟลเดอร์แอป
WORKDIR /var/www

# คัดลอกโปรเจกต์ลงใน container
COPY . .

# ติดตั้ง Laravel
RUN composer install --optimize-autoloader --no-dev
RUN chmod -R 777 storage bootstrap/cache

# รัน Laravel Server
CMD php artisan serve --host=0.0.0.0 --port=8000

EXPOSE 8000
