# Utiliser l'image XAMPP officielle
FROM tomsik68/xampp

# Correction du port d'écoute Apache
RUN sed -i 's/^Listen 80$/Listen 8080/' /opt/lampp/etc/httpd.conf

# Exposer Apache et MySQL
EXPOSE 8080 3306

# Garder le conteneur actif
CMD ["/bin/bash", "-c", "/opt/lampp/lampp start && tail -f /dev/null"]
