FROM php:8.2-fpm

WORKDIR /app

RUN apt-get update && apt-get install -y --no-install-recommends \
    composer \
    git \
    curl \
    && rm -rf /var/lib/apt/lists/*

COPY composer.json ./

RUN mkdir -p /app/bootstrap/cache /app/storage && \
    composer install --no-dev --optimize-autoloader --no-interaction

COPY . .

RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache && \
    chmod -R 755 /app/storage /app/bootstrap/cache

EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
