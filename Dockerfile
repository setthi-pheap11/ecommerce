FROM php:8.2-fpm

# Set working directory
WORKDIR /var/www

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    zip \
    unzip \
    nodejs \
    npm

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_pgsql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create system user to run Composer and Artisan Commands
RUN useradd -G www-data,root -u 1000 -d /home/ecommerce ecommerce
RUN mkdir -p /home/ecommerce/.composer && \
    chown -R ecommerce:ecommerce /home/ecommerce

# Copy existing application directory contents
COPY . /var/www

# Copy existing application directory permissions
COPY --chown=ecommerce:ecommerce . /var/www

# Change current user to ecommerce
USER ecommerce

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]
