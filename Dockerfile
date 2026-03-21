# Use PHP 11's recommended runtime (PHP 8.3 with Apache)
FROM php:8.3-apache

# Set working directory
WORKDIR /var/www/html

# Avoid interactive prompts during apt-get
ENV DEBIAN_FRONTEND=noninteractive

# Update repository and install system dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    libonig-dev \
    libxml2-dev \
    libicu-dev \
    zip \
    unzip \
    git \
    curl \
    libmagickwand-dev \
    gnupg \
    && rm -rf /var/lib/apt/lists/*

# Configure and install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        zip \
        intl \
        opcache

# Enable Apache rewrite module
RUN a2enmod rewrite

# Update Apache DocumentRoot to Laravel's public folder
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Setup custom php.ini configuration
COPY docker/php.ini /usr/local/etc/php/conf.d/custom-php.ini

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy only the dependency manifest files first for better cache utilization
COPY composer.json composer.lock ./

# Install project dependencies (optimize for production)
RUN composer install --no-scripts --no-autoloader --prefer-dist

# Copy the rest of the application code
COPY . .

# Finalize composer autoloader and run scripts
RUN composer dump-autoload --optimize && composer run-post-autoload-script

# Set production environment variables
ENV APP_ENV=production
ENV APP_DEBUG=false

# Setup permissions for Laravel storage and cache directories
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Create data symlink to storage
RUN php artisan storage:link

# Expose port 80
EXPOSE 80

# Setup the entrypoint to handle migrations and startup
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["apache2-foreground"]
