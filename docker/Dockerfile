FROM php:8.2-apache

# Install system dependencies and PHP extensions in one go
RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    && docker-php-ext-install pdo pdo_mysql mysqli mbstring zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Configure Apache to allow .htaccess files
RUN echo '<Directory /var/www/html>' > /etc/apache2/conf-available/rewrite.conf \
    && echo '    Options Indexes FollowSymLinks' >> /etc/apache2/conf-available/rewrite.conf \
    && echo '    AllowOverride All' >> /etc/apache2/conf-available/rewrite.conf \
    && echo '    Require all granted' >> /etc/apache2/conf-available/rewrite.conf \
    && echo '</Directory>' >> /etc/apache2/conf-available/rewrite.conf \
    && a2enconf rewrite

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY htdocs/ /var/www/html/
COPY app/ /var/www/html/app/

# Set proper permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Copy custom PHP configuration
COPY docker/php.ini /usr/local/etc/php/php.ini

# Expose port 80
EXPOSE 80
