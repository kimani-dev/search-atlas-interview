FROM php:8.2-fpm-bullseye

RUN apt-get update && apt-get install -my \
    bzip2 \
    ca-certificates \
    cron \
    curl \
    git \
    gnupg \
    libxml2-dev \
    libzip-dev \
    nano \
    supervisor \
    unzip \
    wget

# Install Node.js 20.x
RUN curl -sL https://deb.nodesource.com/setup_20.x | bash \
    && apt-get install -y nodejs\
    && mkdir -p /var/www/.config \
    && chmod 777 -R /var/www/.config

# Install common PHP extensions
RUN docker-php-ext-install -j$(nproc) iconv mysqli pdo pdo_mysql soap zip exif bcmath

# Install PHP extension: redis
RUN pecl install redis && docker-php-ext-enable redis

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Initialize Cron jobs
COPY cronjobs /etc/cron.d/cronjobs
RUN chmod 0644 /etc/cron.d/cronjobs
RUN crontab /etc/cron.d/cronjobs
RUN touch /var/log/cron.log

# Copy Supervisor profiles
COPY supervisor-laravel-worker.conf /etc/supervisor/conf.d/laravel-worker.conf

# Permission fixes
# RUN chmod 777 -R /code/library-tracker/storage && chmod 777 -R /code/library-tracker/bootstrap/cache

# Copy and run startup script
COPY docker_startup.sh /etc/docker_startup.sh
RUN chmod 0777 /etc/docker_startup.sh
CMD /etc/docker_startup.sh
