# ==========================================================================
# SImp - Premium Docker Environment
# Optimized for Engineering-Modern Design System
# PHP 8.2 + Apache (Hardened)
# ==========================================================================

FROM php:8.2-apache AS base

# Metadata
LABEL maintainer="SImp Portal Team"
LABEL description="Premium C++ Learning Platform - SImp Project"
LABEL version="2.1"

# 1. Install System Dependencies & PHP Extensions
RUN apt-get update && apt-get install -y --no-install-recommends \
        libcurl4-openssl-dev \
        libpng-dev \
        libjpeg-dev \
        libfreetype6-dev \
        libzip-dev \
        zip \
        unzip \
        curl \
        ca-certificates \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        mysqli \
        pdo_mysql \
        gd \
        zip \
        opcache \
    && a2enmod rewrite headers expires \
    && rm -rf /var/lib/apt/lists/*

# 2. Production-Ready PHP Configuration
RUN { \
        echo 'opcache.memory_consumption=128'; \
        echo 'opcache.interned_strings_buffer=8'; \
        echo 'opcache.max_accelerated_files=4000'; \
        echo 'opcache.revalidate_freq=2'; \
        echo 'opcache.fast_shutdown=1'; \
        echo 'upload_max_filesize=64M'; \
        echo 'post_max_size=64M'; \
        echo 'memory_limit=256M'; \
        echo 'expose_php=Off'; \
        echo 'display_errors=Off'; \
        echo 'log_errors=On'; \
        echo 'error_log=/var/www/html/logs/php_error.log'; \
        echo 'date.timezone=Europe/Bucharest'; \
    } > /usr/local/etc/php/conf.d/simp-prod.ini

# 3. Hardened Apache Configuration
RUN { \
        echo 'ServerTokens Prod'; \
        echo 'ServerSignature Off'; \
        echo 'Header set X-Content-Type-Options "nosniff"'; \
        echo 'Header set X-Frame-Options "SAMEORIGIN"'; \
        echo 'Header set X-XSS-Protection "1; mode=block"'; \
    } > /etc/apache2/conf-available/security-hardened.conf \
    && a2enconf security-hardened

# 4. Set Working Directory
WORKDIR /var/www/html

# 5. Copy Application Source
# Using COPY --chown is more efficient than a separate RUN chown
COPY --chown=www-data:www-data site_g/ /var/www/html/

# 6. Setup Writable Directories & Permissions
RUN mkdir -p /var/www/html/uploads /var/www/html/logs \
    && chown -R www-data:www-data /var/www/html/uploads /var/www/html/logs \
    && chmod -R 775 /var/www/html/uploads /var/www/html/logs \
    && find /var/www/html -type d -exec chmod 755 {} \; \
    && find /var/www/html -type f -exec chmod 644 {} \;

# 7. Environment & Build Metadata
ARG APP_VERSION=2.1
ENV APP_VERSION=${APP_VERSION} \
    APP_ENV=production \
    CSS_VERSION=premium-v1

# 8. Health Check
HEALTHCHECK --interval=30s --timeout=10s --start-period=40s --retries=3 \
    CMD curl -f http://localhost/index.php?page=bun_venit || exit 1

# Port exposure
EXPOSE 80

# Use the standard production entrypoint
CMD ["apache2-foreground"]
