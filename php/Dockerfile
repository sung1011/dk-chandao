FROM php:7.1.28-fpm

LABEL maintainer="tickles"

ENV PECL_INSTALL \
    protobuf-3.6.1
# yac-2.0.2 \
# mongodb-1.5.3 \
# igbinary-3.0.0a1 
ENV DOCKER_PHP_EXT_CONFIGURE \
    pdo_mysql --with-pdo-mysql
ENV DOCKER_PHP_EXT_INSTALL \
    pdo_mysql \
    mysqli 
ENV DOCKER_PHP_EXT_ENABLE \
    protobuf
# yac \
# mongodb \
# igbinary 

# ADD etc/php/php.ini /usr/local/etc/php/php.ini
# ADD etc/php-fpm.conf /usr/local/etc/php-fpm.conf
# ADD etc/php-fpm.d/www.conf /usr/local/etc/php-fpm.d/www.conf

RUN pecl install -f ${PECL_INSTALL} \
    && docker-php-ext-configure ${DOCKER_PHP_EXT_CONFIGURE} \
    && docker-php-ext-install ${DOCKER_PHP_EXT_INSTALL} \
    && docker-php-ext-enable ${DOCKER_PHP_EXT_ENABLE} \
    && rm -rf /tmp/pear