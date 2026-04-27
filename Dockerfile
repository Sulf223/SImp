FROM php:8.2-apache

# Metadata
LABEL maintainer="SImp Portal Team"
LABEL description="SImp - C++ Learning Platform with Interactive Sorting Visualizers"
LABEL version="2.0"

# Instalăm extensiile necesare și dependențele
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        libcurl4-openssl-dev \
        git \
        curl \
        wget \
        libpng-dev \
        libjpeg-dev \
        libfreetype6-dev \
    && docker-php-ext-install \
        mysqli \
        pdo \
        pdo_mysql \
        curl \
        gd \
    && a2enmod rewrite headers \
    && rm -rf /var/lib/apt/lists/*

# Setează DocumentRoot
WORKDIR /var/www/html

# Copiem aplicația în web root-ul Apache
COPY site_g/ /var/www/html/

# Permisiuni sigure pentru runtime Apache
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Cache busting: versiune build
ENV APP_VERSION=2.0
ENV CSS_VERSION=modern-2026

# Health check
HEALTHCHECK --interval=30s --timeout=10s --start-period=40s --retries=3 \
    CMD curl -f http://localhost/index.php?page=bun_venit || exit 1

# Port exposure
EXPOSE 80

# Default command
CMD ["apache2-foreground"]

