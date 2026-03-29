FROM php:8.2-apache

# Instalăm extensiile necesare aplicației
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        libcurl4-openssl-dev \
    && docker-php-ext-install mysqli pdo pdo_mysql curl \
    && a2enmod rewrite headers \
    && rm -rf /var/lib/apt/lists/*

# Copiem aplicația în web root-ul Apache
COPY site_g/ /var/www/html/

# Permisiuni sigure pentru runtime Apache
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
