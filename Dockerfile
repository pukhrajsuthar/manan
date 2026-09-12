FROM php:8.4-cli

WORKDIR /app

RUN apt-get update && apt-get install -y --no-install-recommends \
    git curl unzip libzip-dev libonig-dev libpng-dev \
    && docker-php-ext-install -j$(nproc) zip pdo_mysql mbstring exif pcntl bcmath \
    && rm -rf /var/lib/apt/lists/*

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY . /app

RUN mkdir -p bootstrap/cache storage && \
    composer install --prefer-dist --no-dev -q && \
    chmod -R 777 bootstrap/cache storage

EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
