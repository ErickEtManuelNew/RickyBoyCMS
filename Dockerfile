# Utiliser l'image XAMPP officielle
FROM tomsik68/xampp

# Copier tout le projet dans le dossier de XAMPP
COPY . /opt/lampp/htdocs

# Assurer les bonnes permissions
RUN chmod -R 755 /opt/lampp/htdocs

# Exposer le port 80 pour Apache
EXPOSE 80

# Lancer Apache et MySQL au démarrage
CMD ["/opt/lampp/lampp", "start"]
