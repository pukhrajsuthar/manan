#!/usr/bin/env bash
# -------------------------------------------------------
# Manan Furniture Invoice — one-shot Docker setup script
# -------------------------------------------------------
set -e

echo "==> Copying .env"
cp .env.example .env

echo "==> Building Docker images"
docker compose build --no-cache

echo "==> Starting containers"
docker compose up -d

echo "==> Waiting for MySQL to be ready..."
sleep 15

echo "==> Installing Composer dependencies"
docker compose exec app composer install

echo "==> Generating application key"
docker compose exec app php artisan key:generate

echo "==> Running migrations"
docker compose exec app php artisan migrate --force

echo "==> Seeding: GST rules, Manan Furniture company, furniture items"
docker compose exec app php artisan db:seed --force

echo ""
echo "✅  Setup complete!"
echo "   API is running at: http://localhost:8000/api"
echo "   Health check:      http://localhost:8000/api/health"
echo "   Dashboard:         http://localhost:8000/api/invoices/dashboard"
