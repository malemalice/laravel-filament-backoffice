# Use the official PHP 8.2 image with FPM
FROM php:8.2-fpm

# Set the working directory
WORKDIR /var/www/html

# Install system dependencies
RUN apt-get update && apt-get install -y \
    nginx \
    curl \
    zip \
    unzip \
    git \
    nano \
    supervisor \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libxml2-dev \
    libzip-dev \
    zlib1g-dev \
    libonig-dev \
    libicu-dev \
    && apt-get clean

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath opcache intl zip

# Install Composer
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

# Install Node.js and npm
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs \
    && npm install -g npm

# Copy application files to the container
COPY . .

# Install PHP dependencies
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Install Node.js dependencies and build assets
RUN npm install && npm run build

# Set permissions for Laravel
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Copy Nginx configuration file
COPY ./nginx.conf /etc/nginx/nginx.conf

# Copy supervisord configuration
COPY ./supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Expose ports
EXPOSE 80

# Start supervisord to run both Nginx and PHP-FPM
CMD ["supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
