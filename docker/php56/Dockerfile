# syntax=docker/dockerfile:1.7
#
# TEMPORARY. This exists to run the 2005 code and the 2021 test suite exactly
# as they are, so you have a green baseline before migrating. Delete this file,
# compose.legacy.yaml and the legacy-* Makefile targets once the suite passes
# on PHP 8.4.
#
# Nothing here is hardened, and nothing here should ever be deployed.
#
#   target=legacy       browse the site at http://localhost:8081
#   target=legacy-test  run vendor/bin/phpunit against MySQL 5.7
#
# php:5.6 went end-of-life in December 2018 and receives no security patches.
# The images are also amd64-only, so on Apple Silicon Docker runs them under
# emulation - slower, but it works. The compose file pins the platform.

FROM php:5.6-fpm AS legacy

# ext-mysql (the mysql_* family) is the whole point of this image. It was
# deprecated in 5.5 and removed in 7.0, which is why the modern stack can't
# run this code. mysqli and pdo_mysql come along for the tests.
#
# No apt-get anywhere: the official php images already carry the build
# toolchain, and Debian Jessie's repositories have moved to archive.debian.org,
# so `apt-get update` fails in this image. If you ever do need a package:
#   sed -i 's|deb.debian.org|archive.debian.org|g; /security.debian.org/d' \
#       /etc/apt/sources.list \
#     && apt-get -o Acquire::Check-Valid-Until=false update
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
# legacy-test - the original suite, original dependencies, original PHP
# ---------------------------------------------------------------------------
FROM legacy AS legacy-test

COPY . .
COPY --from=legacy-vendor /app/vendor ./vendor

CMD ["vendor/bin/phpunit"]
