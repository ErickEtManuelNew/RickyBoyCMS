# Utiliser l'image PHP avec Apache
FROM php:8.1-apache

# Installer les extensions PHP nécessaires (MySQL, PDO)
RUN docker-php-ext-install mysqli pdo pdo_mysql && docker-php-ext-enable pdo_mysql

# Copier le code source dans le conteneur
COPY . /var/www/html/

# Définir les permissions
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

# Exposer le port 80 pour Apache
EXPOSE 80

# Lancer Apache
CMD ["apache2-foreground"]
