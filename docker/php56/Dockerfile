# syntax=docker/dockerfile:1.7
#
# TEMPORARY. This exists to run the 2005 code and the 2021 test suite exactly
# as they are, so you have a green baseline before migrating. Delete this file,
# compose.legacy.yaml and the legacy-* Makefile targets once the suite passes
# on PHP 8.4.
#
# Nothing here is hardened, and nothing here should ever be deployed.
#
#   target=legacy       php-fpm, serves the site at http://localhost:8081
#   target=legacy-test  php-cli, runs phpunit (Xdebug for coverage)
#
# php:5.6 went end-of-life in December 2018 and receives no security patches.
# The images are also amd64-only, so on Apple Silicon Docker runs them under
# emulation - slower, but it works. The compose file pins the platform.

# ---------------------------------------------------------------------------
# legacy - php-fpm, for serving the site
# ---------------------------------------------------------------------------
FROM php:5.6-fpm AS legacy

# ext-mysql (the mysql_* family) is the whole point of this image. It was
# deprecated in 5.5 and removed in 7.0, which is why the modern stack cannot
# run this code. mysqli and pdo_mysql come along for the tests.
#
# No apt-get anywhere: the official php images already carry the build
# toolchain, and Debian's repositories for this release have moved to
# archive.debian.org, so `apt-get update` fails in this image.
RUN docker-php-ext-install -j"$(nproc)" mysql mysqli pdo_mysql

COPY docker/php56/conf.d/app-legacy.ini /usr/local/etc/php/conf.d/zz-app.ini
COPY docker/php56/php-fpm.d/zz-app.conf /usr/local/etc/php-fpm.d/zz-app.conf

WORKDIR /var/www/html

# No fcgi package available (see the apt note above), so check the port with
# PHP itself rather than cgi-fcgi.
HEALTHCHECK --interval=10s --timeout=3s --start-period=20s --retries=3 \
    CMD php -r "exit(@fsockopen('127.0.0.1', 9000) ? 0 : 1);"

# ---------------------------------------------------------------------------
# legacy-vendor - resolve dependencies where the tooling actually exists
#
# php:5.6 ships without unzip, git and ext-zip, so Composer cannot unpack a
# single package inside it, and Debian's repositories for this release moved to
# archive.debian.org so apt-get can't install them either. The official
# composer image has all of it, so vendor/ is built there and copied across.
#
# --ignore-platform-reqs is required because that image runs PHP 8: it is only
# reading composer.lock and unpacking the pinned versions, so the resolved
# packages are identical to what PHP 5.6 would have produced.
# ---------------------------------------------------------------------------
FROM composer:2.2 AS legacy-vendor

WORKDIR /app
COPY composer.json composer.lock* ./
RUN composer install --no-scripts --no-autoloader --prefer-dist \
        --no-interaction --ignore-platform-reqs

COPY . .
RUN composer dump-autoload --ignore-platform-reqs

# ---------------------------------------------------------------------------
# legacy-test - php-cli, NOT php-fpm
#
# The test runner is a command-line process and has no use for php-fpm.
#
# Code coverage on PHP 5.6 needs Xdebug 2.5.5, the last release that supports
# it. pcov requires PHP 7.1+, and the official 5.6 images do not ship phpdbg
# (checked: /usr/local/bin has php-cgi but no phpdbg), so there is no
# extension-free route. Building Xdebug needs a compiler, which needs apt,
# which needs Debian's archived repositories - hence the sources rewrite below.
#
#   vendor/bin/phpunit                                    tests
#   vendor/bin/phpunit --coverage-html coverage/html      coverage
# ---------------------------------------------------------------------------
FROM php:5.6-cli AS legacy-test

RUN docker-php-ext-install -j"$(nproc)" mysql mysqli pdo_mysql

# Debian Stretch is end-of-life and has moved to archive.debian.org. Its
# Release files are past their Valid-Until date and signed with keys that have
# since expired, so the checks have to be relaxed to install anything at all.
# This is acceptable in a throwaway test image and nowhere else.
RUN set -eux; \
    codename="$(sed -n 's/^deb [^ ]* \([a-z-]*\) .*/\1/p' /etc/apt/sources.list | head -1)"; \
    printf 'deb http://archive.debian.org/debian %s main\n' "$codename" > /etc/apt/sources.list; \
    echo 'Acquire::Check-Valid-Until "false";' > /etc/apt/apt.conf.d/99archive; \
    apt-get -o Acquire::AllowInsecureRepositories=true update; \
    apt-get install -y --no-install-recommends --allow-unauthenticated $PHPIZE_DEPS; \
    pecl install xdebug-2.5.5; \
    docker-php-ext-enable xdebug; \
    apt-get purge -y --auto-remove $PHPIZE_DEPS; \
    rm -rf /var/lib/apt/lists/*

# Coverage only. Step debugging and the profiler would slow every run for no
# benefit here. Xdebug still costs roughly 2x on ordinary runs; if that starts
# to bite, comment out docker-php-ext-enable above and load it per-run with
#   php -d zend_extension=xdebug.so vendor/bin/phpunit --coverage-text
RUN { \
      echo 'xdebug.coverage_enable=1'; \
      echo 'xdebug.remote_enable=0'; \
      echo 'xdebug.profiler_enable=0'; \
      echo 'xdebug.default_enable=0'; \
    } > /usr/local/etc/php/conf.d/zzz-xdebug-app.ini

COPY docker/php56/conf.d/app-legacy.ini /usr/local/etc/php/conf.d/zz-app.ini

WORKDIR /var/www/html

COPY . .
COPY --from=legacy-vendor /app/vendor ./vendor

CMD ["vendor/bin/phpunit"]
