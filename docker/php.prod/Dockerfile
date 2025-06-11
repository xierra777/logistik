FROM php:8.2-fpm

# Install dependencies
RUN apt-get update && apt-get install -y \
    bash \
    curl \
    wget \
    gnupg \
    ca-certificates \
    unzip \
    zip \
    libnss3 \
    libxss1 \
    libasound2 \
    fonts-liberation \
    libappindicator3-1 \
    libatk-bridge2.0-0 \
    libgtk-3-0 \
    xdg-utils \
    libu2f-udev \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libicu-dev \
    libxml2-dev \
    software-properties-common \
    default-mysql-client \
    cron \
    supervisor \
    git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) pdo pdo_mysql zip gd bcmath opcache intl exif pcntl sockets mbstring

# Install Redis extension
RUN pecl install redis && docker-php-ext-enable redis

# Install Composer (latest stable)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install Node.js 18 & npm latest
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs \
    && npm install -g npm

# Install Google Chrome stable for Puppeteer
RUN curl -fsSL https://dl.google.com/linux/linux_signing_key.pub | gpg --dearmor -o /usr/share/keyrings/google.gpg \
    && echo "deb [arch=amd64 signed-by=/usr/share/keyrings/google.gpg] http://dl.google.com/linux/chrome/deb/ stable main" > /etc/apt/sources.list.d/google-chrome.list \
    && apt-get update && apt-get install -y google-chrome-stable

# Install puppeteer globally (puppeteer-core + bundled Chromium not installed here, Chrome installed separately)
RUN npm install -g puppeteer

# Set working directory
WORKDIR /var/www

# Expose PHP-FPM port
EXPOSE 9000
