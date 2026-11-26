FROM php:8.4-cli-bookworm AS php-base
RUN docker-php-ext-install pdo_mysql
WORKDIR /srv/app

FROM php-base AS dependencies
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer
RUN apt-get update && apt-get install -y --no-install-recommends git unzip && rm -rf /var/lib/apt/lists/*
COPY composer.json composer.lock artisan ./
COPY app/ app/
COPY bootstrap/ bootstrap/
COPY config/ config/
COPY database/migrations/ database/migrations/
COPY database/seeders/ database/seeders/
COPY database/factories/ database/factories/
COPY public/ public/
COPY resources/ resources/
COPY routes/ routes/
RUN rm -f bootstrap/cache/*.php public/hot && mkdir -p storage/framework/sessions storage/framework/views storage/framework/cache/data storage/logs
RUN composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader

FROM node:24-bookworm-slim AS assets
WORKDIR /srv/app
COPY package.json package-lock.json .npmrc ./
RUN npm ci
COPY vite.config.js ./
COPY resources/ resources/
COPY app/ app/
COPY routes/ routes/
COPY public/ public/
RUN mkdir -p storage/framework/views
COPY --from=dependencies /srv/app/vendor ./vendor
RUN npm run build

FROM php-base AS app
COPY --from=dependencies /srv/app /srv/app
COPY --from=assets /srv/app/public/build /srv/app/public/build
RUN mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs storage/app/public bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache
USER www-data
EXPOSE 8000
CMD ["sh", "-ec", "php artisan migrate --force && php artisan db:seed --force && exec php artisan serve --host=0.0.0.0 --port=8000 --no-reload"]
