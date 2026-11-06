FROM php:8.4-cli-alpine

RUN apk add --no-cache git unzip && docker-php-ext-install pdo_mysql
RUN echo "post_max_size=40M" > "$PHP_INI_DIR/conf.d/uploads.ini"

COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

RUN addgroup -g 1000 app && adduser -D -u 1000 -G app app
WORKDIR /var/www/backend
RUN chown app:app /var/www/backend
USER app

EXPOSE 8000
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
