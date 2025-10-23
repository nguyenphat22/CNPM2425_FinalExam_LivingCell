# Sử dụng image PHP 8.2 kèm Apache
FROM php:8.2-apache

# Cài extension cần thiết
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Copy code vào container
COPY . /var/www/html/

# Thiết lập thư mục làm việc
WORKDIR /var/www/html/

# Cài Composer
RUN apt-get update && apt-get install -y unzip git \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && composer install --no-interaction --no-dev --optimize-autoloader

# Mở cổng
EXPOSE 80

# Chạy Apache
CMD ["apache2-foreground"]
