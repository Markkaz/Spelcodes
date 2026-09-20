# syntax=docker/dockerfile:1.7
#
# One base image, three targets. Dev, test and prod share the same PHP build,
# the same extensions and the same version pin, so they cannot drift apart.
#
#   target=dev   -> bind-mounted source, Xdebug, Composer, verbose errors
#   target=test  -> CI-shaped, pcov for coverage, no bind mount required
#   target=prod  -> code baked in, opcache tuned, non-root, no build tools
#
ARG PHP_VERSION=8.4

# ---------------------------------------------------------------------------
# base - everything all three targets need, and nothing else
# ---------------------------------------------------------------------------
FROM php:${PHP_VERSION}-fpm-alpine AS base

# fcgi gives us cgi-fcgi for the php-fpm healthcheck below. unzip and git are
# required by Composer to unpack packages - without them it falls back to
# source downloads and fails.
RUN apk add --no-cache fcgi unzip git

# pdo_mysql and opcache are bundled with PHP - no third-party installer script,
# no extra build dependencies. ext-mysql (mysql_*) does not exist after PHP 7.0
# and deliberately is not provided here: see docker/README.md.
RUN docker-php-ext-install -j"$(nproc)" pdo_mysql opcache

WORKDIR /var/www/html

# php-fpm pool config shared by every target (ping endpoint, logging to stdout)
COPY docker/php/php-fpm.d/zz-app.conf /usr/local/etc/php-fpm.d/zz-app.conf

# Fails fast if the pool stops answering, so orchestrators restart it.
HEALTHCHECK --interval=10s --timeout=3s --start-period=20s --retries=3 \
    CMD SCRIPT_NAME=/ping SCRIPT_FILENAME=/ping REQUEST_METHOD=GET \
        cgi-fcgi -bind -connect 127.0.0.1:9000 || exit 1

# ---------------------------------------------------------------------------
# vendor - composer install in its own layer so code changes don't re-resolve
# ---------------------------------------------------------------------------
FROM base AS vendor

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# NOTE: composer.json currently pins "php": "~5.6". Until Step 0 raises that,
# build with:  --build-arg COMPOSER_FLAGS=--ignore-platform-reqs
ARG COMPOSER_FLAGS=""

COPY composer.json composer.lock* ./
RUN composer install \
        --no-dev --no-scripts --no-autoloader \
        --prefer-dist --no-interaction ${COMPOSER_FLAGS}

COPY . .
RUN composer dump-autoload --optimize --classmap-authoritative --no-dev

# ---------------------------------------------------------------------------
# dev - bind-mounted source, step debugging, writable by your host user
# ---------------------------------------------------------------------------
FROM base AS dev

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN apk add --no-cache --virtual .build-deps $PHPIZE_DEPS linux-headers \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && apk del .build-deps

COPY docker/php/conf.d/app-dev.ini /usr/local/etc/php/conf.d/zz-app.ini

# Remap www-data to your host uid/gid so bind-mounted files stay writable
# on Linux. Harmless on macOS and Windows.
ARG UID=1000
ARG GID=1000
RUN apk add --no-cache shadow \
    && groupmod -o -g "${GID}" www-data \
    && usermod  -o -u "${UID}" -g "${GID}" www-data \
    && apk del shadow \
    && chown -R www-data:www-data /var/www

USER www-data

# ---------------------------------------------------------------------------
# test - what CI runs. Code copied in, dev dependencies present, pcov for
# coverage (several times faster than Xdebug and it does not slow every run).
# ---------------------------------------------------------------------------
FROM base AS test

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN apk add --no-cache --virtual .build-deps $PHPIZE_DEPS \
    && pecl install pcov \
    && docker-php-ext-enable pcov \
    && apk del .build-deps

COPY docker/php/conf.d/app-test.ini /usr/local/etc/php/conf.d/zz-app.ini

ARG COMPOSER_FLAGS=""
COPY composer.json composer.lock* ./
RUN composer install --no-scripts --no-autoloader --prefer-dist \
        --no-interaction ${COMPOSER_FLAGS}
COPY . .
RUN composer dump-autoload --optimize

USER www-data
CMD ["vendor/bin/phpunit"]

# ---------------------------------------------------------------------------
# prod - no Composer, no compilers, no source mount, nothing writable
# ---------------------------------------------------------------------------
FROM base AS prod

COPY docker/php/conf.d/app-prod.ini /usr/local/etc/php/conf.d/zz-app.ini

COPY --from=vendor --chown=root:root /var/www/html /var/www/html

# The application owns nothing it does not need to write. If you later add an
# upload or cache path, grant it explicitly here rather than loosening the lot.
RUN find /var/www/html -type d -exec chmod 755 {} + \
    && find /var/www/html -type f -exec chmod 644 {} + \
    && rm -rf /var/www/html/tests /var/www/html/phpunit.xml

USER www-data

# ---------------------------------------------------------------------------
# web - nginx carrying its own copy of the document root.
#
# The alternative is a shared named volume, but a volume only seeds from the
# image the first time it is created, so every later deploy would serve stale
# assets. Baking them in keeps nginx and php on the same build by definition.
# ---------------------------------------------------------------------------
FROM nginx:1.27-alpine AS web

COPY docker/nginx/templates /etc/nginx/templates
COPY --from=vendor /var/www/html /var/www/html

# PHP is never executed here; the deny rules in the site config keep the
# non-public parts of the tree unreachable until Step 0 moves them behind
# public/ properly.
RUN rm -rf /var/www/html/tests /var/www/html/docker
