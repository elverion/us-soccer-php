FROM php:8.2-fpm

RUN apt-get update \
    && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libxml2-dev \
    libcurl4-openssl-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    && docker-php-ext-install xml curl pdo pdo_mysql

WORKDIR /var/www

COPY . /var/www
COPY ./.docker/entrypoint.sh /entrypoint.sh
COPY ./.docker/php/php.ini /usr/local/etc/php/conf.d/local.ini

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN composer install

EXPOSE 9000

CMD ["/entrypoint.sh"]