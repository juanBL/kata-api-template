ARG PHP_VERSION="8.2.7-fpm-alpine"
ARG PHP_EXT_INSTALLER_VERSION=2.1.10
ARG COMPOSER_VERSION=2.1.8

# --------------------------------------------
# base stage
# --------------------------------------------
FROM mlocati/php-extension-installer:${PHP_EXT_INSTALLER_VERSION} AS php-extension-installer
FROM php:${PHP_VERSION} AS base

COPY --from=php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

RUN apk add --update --no-cache \
    bash \
    curl \
    && rm -rf /var/lib/apt/lists/*

RUN install-php-extensions \
    amqp \
    bcmath \
    gmp \
    intl \
    pdo \
    pgsql \
    pdo_pgsql \
    redis \
    zip

RUN curl -sS https://get.symfony.com/cli/installer | bash && mv /root/.symfony5/bin/symfony /usr/local/bin/symfony
RUN curl -L https://raw.githubusercontent.com/renatomefi/php-fpm-healthcheck/v0.5.0/php-fpm-healthcheck -o /usr/local/bin/php-fpm-healthcheck \
    && chmod +x /usr/local/bin/php-fpm-healthcheck

COPY deployments/docker/php/php.ini $PHP_INI_DIR/conf.d/php.ini
COPY deployments/docker/php/opcache-runtime.ini $PHP_INI_DIR/conf.d/opcache.ini
COPY deployments/docker/php/fpm.conf /usr/local/etc/php-fpm.d/zz-docker.conf
COPY deployments/docker/php/apcu.ini $PHP_INI_DIR/conf.d/apcu.ini

# --------------------------------------------
# builder stage
# --------------------------------------------
FROM composer:${COMPOSER_VERSION} AS composer
FROM base AS builder

COPY --from=composer /usr/bin/composer /usr/local/bin

RUN apk add --no-cache --virtual .pgsql-deps postgresql-dev; \
	apk add --no-cache --virtual .pgsql-rundeps so:libpq.so.5; \
	apk del .pgsql-deps
# --------------------------------------------
# dev stage
# --------------------------------------------
FROM builder AS development

ARG USER_ID=1000

COPY deployments/docker/php/opcache-development.ini $PHP_INI_DIR/conf.d/opcache.ini
RUN install-php-extensions \
    xdebug

COPY deployments/docker/php/xdebug.ini $PHP_INI_DIR/conf.d
COPY deployments/docker/php/disable_xdebug.sh /usr/local/bin/disable_xdebug
COPY deployments/docker/php/enable_xdebug.sh /usr/local/bin/enable_xdebug

RUN chmod u+x /usr/local/bin/disable_xdebug /usr/local/bin/enable_xdebug

# --------------------------------------------
# installer stage
# --------------------------------------------
FROM builder AS installer

COPY composer.json .
COPY composer.lock .

RUN composer install \
    --no-interaction \
    --no-progress \
    --no-ansi \
    # --no-dev \
    --ignore-platform-reqs \
    && chown -R www-data: .

# --------------------------------------------
# shared stage
# --------------------------------------------
FROM base AS shared-ecosystem

COPY . /var/www/html
COPY --from=installer /var/www/html /var/www/html

RUN mkdir -p /var/www/html/var/cache/test && chown www-data:www-data /var/www/html/var/cache/test
RUN mkdir -p /var/www/html/var/log && chown www-data:www-data /var/www/html/var/log
RUN chown -R www-data:www-data /var/www/html/var


# --------------------------------------------
# all-in-one stage
# --------------------------------------------
FROM shared-ecosystem AS all-in-one

# --------------------------------------------
# private api stage
# --------------------------------------------
FROM shared-ecosystem AS private-api-backend
RUN mkdir -p /var/www/html/var/cache/prod && chown www-data:www-data /var/www/html/var/cache/prod
RUN mkdir -p /var/www/html/var/cache/test && chown www-data:www-data /var/www/html/var/cache/test
RUN mkdir -p /var/www/html/var/cache/dev && chown www-data:www-data /var/www/html/var/cache/dev
RUN mkdir -p /var/www/html/var/log && chown www-data:www-data /var/www/html/var/log
