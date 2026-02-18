FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    git unzip libzip-dev libpng-dev \
    && docker-php-ext-install zip pdo pdo_mysql

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
WORKDIR /var/www/html

COPY . .
RUN composer install --no-dev --optimize-autoloader

EXPOSE 8000
CMD ["php", "artisan", "serve", "--host=0.0.0.0"]
