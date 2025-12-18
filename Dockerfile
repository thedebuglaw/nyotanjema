FROM webdevops/php-nginx:7.4

ENV WEB_DOCUMENT_ROOT=/app/public
ENV PHP_DISPLAY_ERRORS=0
ENV PHP_MEMORY_LIMIT=512M

WORKDIR /app
COPY . /app

# Fix permissions for Laravel
RUN mkdir -p storage bootstrap/cache \
    && chown -R application:application storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

RUN composer install --no-dev --optimize-autoloader

RUN php artisan optimize || true
