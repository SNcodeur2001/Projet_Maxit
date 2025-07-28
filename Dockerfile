FROM php:8.3-cli

# Installer Composer
RUN apt-get update && apt-get install -y libpq-dev unzip curl \
    && docker-php-ext-install pdo pdo_pgsql \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /app

# Copier uniquement les fichiers nécessaires
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader

COPY . /app

EXPOSE 8000
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]
