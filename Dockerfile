FROM php:8.3-cli

# Installer les extensions nécessaires
RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Copier ton code dans le conteneur
COPY . /app
WORKDIR /app

# Exposer le port utilisé par le serveur PHP
EXPOSE 8000

# Commande pour démarrer le serveur interne
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]
