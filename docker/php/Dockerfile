FROM php:8.2-fpm

# Install dependencies
RUN apt-get update && apt-get install -y \
    curl \
    wget \
    gnupg \
    ca-certificates \
    unzip \
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
    libonig-dev \
    libxml2-dev \
    software-properties-common \
    && docker-php-ext-install pdo pdo_mysql zip

# Install Node.js 18 & NPM
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs \
    && npm install -g npm

# ✅ Install Google Chrome (for Puppeteer)
RUN curl -fsSL https://dl.google.com/linux/linux_signing_key.pub | gpg --dearmor -o /usr/share/keyrings/google.gpg \
    && echo "deb [arch=amd64 signed-by=/usr/share/keyrings/google.gpg] http://dl.google.com/linux/chrome/deb/ stable main" > /etc/apt/sources.list.d/google-chrome.list \
    && apt-get update && apt-get install -y google-chrome-stable

# ✅ Install puppeteer-core (without bundled Chromium)
RUN npm install -g puppeteer

# Set working directory
WORKDIR /var/www

# Expose port (jika perlu debugging atau untuk fpm external)
EXPOSE 9000
