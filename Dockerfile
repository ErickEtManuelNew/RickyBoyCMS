# Utiliser l'image XAMPP avec PHP 8.1
FROM tomsik68/xampp

# Copier le code source dans le conteneur
COPY . /opt/lampp/htdocs

# Définir les permissions
RUN chmod -R 755 /opt/lampp/htdocs

# Exposer le port 80
EXPOSE 80

# Lancer Apache
CMD ["/opt/lampp/lampp", "start"]
