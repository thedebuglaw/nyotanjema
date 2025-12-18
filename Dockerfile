FROM webdevops/php-nginx:7.4

ENV WEB_DOCUMENT_ROOT=/app/public
ENV PHP_DISPLAY_ERRORS=0
ENV PHP_MEMORY_LIMIT=512M

WORKDIR /app
COPY . /app

RUN composer install --no-dev --optimize-autoloader

# Make startup script executable
RUN chmod +x /app/start.sh

CMD ["/app/start.sh"]
