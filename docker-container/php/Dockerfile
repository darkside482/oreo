FROM php:fpm-alpine3.12

ENV EXT_APCU_VERSION=5.1.18 EXT_REDIS_VERSION=5.3.2 EXT_IGBINARY_VERSION=3.2.1

RUN apk update && apk --no-cache --virtual .build-deps add postgresql-dev openssl-dev librdkafka-dev $PHPIZE_DEPS \
    && docker-php-source extract \
    && pecl install xdebug-3.0.3 \
    # ext-opache
    && docker-php-ext-enable opcache \
    # ext-xdebug
    && docker-php-ext-enable xdebug \
    # ext-igbinary
    && mkdir -p /usr/src/php/ext/igbinary \
    && curl -fsSL https://github.com/igbinary/igbinary/archive/$EXT_IGBINARY_VERSION.tar.gz | tar xvz -C /usr/src/php/ext/igbinary --strip 1 \
    && docker-php-ext-install igbinary \
    # ext-apcu
    && mkdir -p /usr/src/php/ext/apcu \
    && curl -fsSL https://github.com/krakjoe/apcu/archive/v$EXT_APCU_VERSION.tar.gz | tar xvz -C /usr/src/php/ext/apcu --strip 1 \
    # ext-redis
    && mkdir -p /usr/src/php/ext/redis \
    && curl -fsSL https://github.com/phpredis/phpredis/archive/$EXT_REDIS_VERSION.tar.gz | tar xvz -C /usr/src/php/ext/redis --strip 1 \
    && docker-php-ext-configure redis --enable-redis-igbinary \
    # install
    && docker-php-ext-install \
        apcu \
        redis \
        pdo \
        pdo_pgsql \
        tokenizer \
        pcntl \
    &&  docker-php-source delete \
    && apk del .build-deps \
    && apk --no-cache add libpq librdkafka\
    &&  echo -e '\
opcache.interned_strings_buffer=16\n\
#opcache.load_comments=Off\n\
opcache.max_accelerated_files=16000\n\
#opcache.save_comments=Off\n\
' >> /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini \
    && echo -e "xdebug.mode=debug\n\
            zend_extension=xdebug.so\n\
            xdebug.mode=debug\n\
            xdebug.client_host=host.docker.internal\n\
            xdebug.start_with_request=yes" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini


RUN curl -sS https://getcomposer.org/installer | php -- \
    --install-dir=/usr/bin --filename=composer && chmod +x /usr/bin/composer

WORKDIR /var/www