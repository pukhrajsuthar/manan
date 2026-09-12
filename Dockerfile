FROM php:8.2-alpine AS builder

WORKDIR /app

RUN apk add --no-cache composer git

COPY composer.json composer.lock ./

RUN mkdir -p /app/bootstrap/cache /app/storage && \
    composer install --no-dev --optimize-autoloader --no-interaction

FROM php:8.2-alpine

WORKDIR /app

RUN apk add --no-cache \
    curl \
    libpng \
    oniguruma \
    libxml2

RUN docker-php-ext-install -j$(nproc) \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath

COPY --from=builder /app/vendor ./vendor
COPY --from=builder /app/bootstrap ./bootstrap
COPY . .

RUN mkdir -p /app/storage && \
    chown -R www-data:www-data /app/storage /app/bootstrap/cache && \
    chmod -R 755 /app/storage /app/bootstrap/cache

EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
