FROM php:8.2-apache

# Installer extensions nécessaires
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Activer mod_rewrite pour Apache
RUN a2enmod rewrite

# Copier les fichiers source
COPY ./src /var/www/html

# Donner les droits
RUN chown -R www-data:www-data /var/www/html
