FROM serversideup/php:8.3-fpm-nginx

ENV PHP_OPCACHE_ENABLE=1
ENV SESSION_SECURE_COOKIE=true
ENV NGINX_ACCESS_LOG=/dev/stdout
ENV NGINX_ERROR_LOG=/dev/stderr

WORKDIR /var/www/html

USER root

# Install Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get update \
    && apt-get install -y nodejs \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Copy application files
COPY --chown=www-data:www-data . /var/www/html

# Switch to non-root user for all remaining actions
USER www-data

# Install dependencies and build assets
RUN npm ci \
    && npm run build \
    && rm -rf /var/www/html/.npm

# Install PHP dependencies
RUN composer install --no-interaction --optimize-autoloader --no-dev \
    && rm -rf /var/www/html/.composer/cache