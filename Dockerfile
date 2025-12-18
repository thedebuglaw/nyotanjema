FROM richarvey/nginx-php-fpm:3.1.6

ENV WEBROOT=/var/www/html/public
ENV PHP_ERRORS_STDERR=1
ENV RUN_SCRIPTS=1
ENV REAL_IP_HEADER=1

COPY . /var/www/html

RUN composer install --no-dev --optimize-autoloader

RUN php artisan key:generate || true
RUN php artisan optimize || true
