FROM php:fpm-alpine

RUN set -ex \
    && apk add --update --no-cache --virtual .build-deps \
        $PHPIZE_DEPS \
        imagemagick-dev \
        libmemcached-dev \
        libtool \
        libzip libpng libjpeg-turbo libwebp freetype icu icu-data-full \
        icu-dev icu-libs zlib-dev g++ make automake autoconf libzip-dev \
        libpng-dev libwebp-dev libjpeg-turbo-dev freetype-dev \
    && apk add --update --no-cache --virtual .zip-runtime-deps libzip-dev \
    && docker-php-ext-install zip \
    && docker-php-ext-configure gd --enable-gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install gd \
    && docker-php-ext-install exif \
    && apk add --update --no-cache --virtual .imagick-runtime-deps imagemagick \
    && pecl install imagick \
    && docker-php-ext-enable imagick \
    && docker-php-ext-enable gd \
    && apk del .build-deps \
    && rm -fr /tmp/pear

COPY ./app/ /var/www/html/

RUN chmod 777 /var/www/html/tmp

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html

RUN composer install