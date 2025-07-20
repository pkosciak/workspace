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

# Create and activate a Python virtual environment
RUN python3 -m venv /opt/venv && \
    source /opt/venv/bin/activate && \
    pip install --upgrade pip && \
    pip install wordfence

# Add the virtual environment's bin directory to PATH
ENV PATH="/opt/venv/bin:$PATH"

# Preconfigure Wordfence and persist its configuration
RUN mkdir -p /etc/wordfence && \
    wordfence configure --default --workers 4 --accept-terms && \
    cp /root/.config/wordfence/wordfence-cli.ini /etc/wordfence/wordfence-cli.ini

# Ensure Wordfence uses the preconfigured file
ENV WORDFENCE_CONFIG_PATH=/etc/wordfence/cli-config.json

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

# wp-cli
RUN curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar
RUN chmod +x wp-cli.phar
RUN mv wp-cli.phar /usr/local/bin/wp

RUN wget https://github.com/mailhog/mhsendmail/releases/download/v0.2.0/mhsendmail_linux_amd64
RUN chmod +x mhsendmail_linux_amd64
RUN mv mhsendmail_linux_amd64 /usr/local/bin/mhsendmail

RUN apk add --update linux-headers

RUN apk --no-cache add shadow && usermod -u 1000 www-data

# Create log directory with proper permissions
RUN mkdir -p /var/log \
    && touch /var/log/php-fpm.error.log \
    && chown -R www-data:www-data /var/log \
    && chmod -R 755 /var/log

RUN chown -R www-data:www-data /var/www/html
RUN chown -R 774 /var/www/html

USER www-data