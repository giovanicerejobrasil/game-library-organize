FROM php:8.4-cli-alpine

# Install system dependencies & build tools
RUN apk add --no-cache \
    curl \
    git \
    libpq-dev \
    libzip-dev \
    zip \
    unzip \
    nodejs \
    npm \
    bash

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_pgsql pgsql zip bcmath pcntl

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
