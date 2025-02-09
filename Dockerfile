FROM php:8.1-fpm

WORKDIR /var/www

COPY . .

RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-configure gd \
    && docker-php-ext-install pdo pdo_mysql gd

EXPOSE 8000

CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]