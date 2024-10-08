FROM php:8.1-fpm-alpine

RUN apk update && apk add bash

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN chmod +x /usr/local/bin/install-php-extensions && sync \
    && install-php-extensions pdo_mysql \
        mysqli \
		gd \
        xdebug

# composer
COPY --from=composer /usr/bin/composer /usr/bin/composer

# wp-cli
RUN curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar
RUN chmod +x wp-cli.phar
RUN mv wp-cli.phar /usr/local/bin/wp

#mailhog
RUN wget https://github.com/mailhog/mhsendmail/releases/download/v0.2.0/mhsendmail_linux_amd64
RUN chmod +x mhsendmail_linux_amd64
RUN mv mhsendmail_linux_amd64 /usr/local/bin/mhsendmail

RUN apk add --update linux-headers

RUN apk --no-cache add shadow && usermod -u 1000 www-data

COPY .docker/php/php.ini "${PHP_INI_DIR}"
COPY .docker/php/www.conf "/usr/local/etc/php-fpm.d/www.conf"
COPY .docker/xdebug/xdebug.ini "${PHP_INI_DIR}/conf.d"

RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 775 /var/www/html

USER www-data