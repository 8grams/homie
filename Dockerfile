FROM dunglas/frankenphp
WORKDIR /app

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libzip-dev \
    libsqlite3-dev \
    git \
    curl \
    gettext-base \
    cron \
    zip

# Install PHP extensions
RUN docker-php-ext-install zip

# Install Composer
COPY --from=composer:2.8.5 /usr/bin/composer /usr/bin/composer


# Copy application files
COPY . .

# Install dependencies
RUN COMPOSER_PROCESS_TIMEOUT=600 composer install --prefer-dist --no-dev --no-interaction --no-progress --no-suggest

# Create Caddyfile
COPY ./docker/Caddyfile /etc/caddy/Caddyfile

# prepare production php.ini
RUN cp $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini

COPY ./docker/start.sh ./start.sh
COPY ./docker/backup.sh ./backup.sh
ADD init-cron /etc/cron.d/init-cron

RUN chmod +x ./start.sh && \
    chmod +x ./backup.sh && \
    chmod 0644 /etc/cron.d/init-cron && \
    /usr/bin/crontab /etc/cron.d/init-cron && \
    touch .dockerenv
    
# Expose ports
EXPOSE 80 443

# Start Caddy with FrankenPHP
ENTRYPOINT ["./start.sh"]
