# Utiliser l'image XAMPP officielle
FROM tomsik68/xampp

# Copier tout le projet dans le dossier de XAMPP
COPY . /opt/lampp/htdocs

# Assurer les bonnes permissions
RUN chmod -R 755 /opt/lampp/htdocs

# Modifier XAMPP pour écouter sur le port défini par Vercel
RUN sed -i "s/Listen 80/Listen ${PORT}/" /opt/lampp/etc/httpd.conf

# Exposer le port attendu par Vercel
EXPOSE 8080

# Lancer Apache en arrière-plan et garder le conteneur actif
CMD ["/opt/lampp/lampp", "start", "&&", "tail", "-f", "/dev/null"]
