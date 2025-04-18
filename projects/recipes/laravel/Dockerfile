FROM php:8.2.27-fpm-alpine

RUN apk update && apk add bash git nodejs yarn

# Install dependencies
RUN apk add --no-cache \
    python3 \
    py3-pip \
    pcre \
    gcc \
    musl-dev \
    libffi-dev

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN chmod +x /usr/local/bin/install-php-extensions && sync \
    && install-php-extensions pdo_mysql \
        mysqli \
		gd \
        zip \
        intl \
        imagick \
        exif \
        xdebug

COPY --from=composer /usr/bin/composer /usr/bin/composer

#COPY ./.docker/php/certs/cert.pem /usr/local/share/ca-certificates/minica-ca.crt
#RUN apk add --no-cache ca-certificates \
# && cp /usr/local/share/ca-certificates/minica-ca.crt /etc/ssl/certs/ \
# && update-ca-certificates

RUN wget https://github.com/mailhog/mhsendmail/releases/download/v0.2.0/mhsendmail_linux_amd64
RUN chmod +x mhsendmail_linux_amd64
RUN mv mhsendmail_linux_amd64 /usr/local/bin/mhsendmail

RUN apk add --update linux-headers

RUN apk --no-cache add shadow && usermod -u 1000 www-data

RUN chown -R www-data:www-data /var/www/html
RUN chown -R 774 /var/www/html

USER www-data