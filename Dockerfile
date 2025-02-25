# Utiliser l'image XAMPP officielle
FROM tomsik68/xampp

# Copier tout le projet dans le dossier de XAMPP
COPY . /opt/lampp/htdocs

# Assurer les bonnes permissions
RUN chmod -R 755 /opt/lampp/htdocs

# Modifier XAMPP pour écouter sur le port défini par Render
RUN sed -i "s/Listen 80/Listen ${PORT}/" /opt/lampp/etc/httpd.conf

# Exposer le port dynamique défini par Render
EXPOSE 10000

# Lancer Apache et MySQL en maintenant le conteneur actif
CMD /bin/bash -c "/opt/lampp/lampp start && tail -f /dev/null"
