FROM php:8.2-apache

# Instala extensiones necesarias para PostgreSQL y Composer
RUN apt-get update \
    && apt-get install -y --no-install-recommends libpq-dev unzip git \
    && docker-php-ext-install pdo pdo_pgsql pgsql \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*

# Instala Composer globalmente
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Configurar DocumentRoot en /var/www/nexo/public
ENV APACHE_DOCUMENT_ROOT=/var/www/nexo/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf