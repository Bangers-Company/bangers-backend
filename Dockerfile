FROM dunglas/frankenphp:1-php8.4

# Arguments defined in docker-compose.yml
ARG user
ARG uid

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
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_pgsql pgsql mbstring exif pcntl bcmath gd

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create system user to run Composer and Artisan Commands
RUN useradd -G sudo,www-data -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    mkdir -p /data/caddy && \
    mkdir -p /config/caddy && \
    chown -R $user:$user /home/$user && \
    chown -R $user:$user /data/caddy && \
    chown -R $user:$user /config/caddy

# Set working directory
WORKDIR /var/www

# Use the default FrankenPHP configuration
ENV FRANKENPHP_CONFIG="worker ./public/index.php"

USER $user
