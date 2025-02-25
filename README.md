RickyBoyCMS - Documentation Fonctionnelle

1. Introduction

RickyBoyCMS est un système de gestion de contenu (CMS) développé en PHP avec MySQL et Bootstrap. Il permet aux utilisateurs de gérer du contenu web de manière intuitive avec un système de rôles (Administrateur, Rédacteur, Utilisateur).

2. Fonctionnalités principales

a) Gestion des utilisateurs

Inscription et connexion sécurisée avec hachage des mots de passe.

Rôles des utilisateurs :

Administrateur : Gestion complète du site (utilisateurs, articles, catégories).

Rédacteur : Peut ajouter et éditer des articles.

Utilisateur : Peut lire les articles publiés.

L'utilisateur entre ses informations à l'inscription, mais ne peut pas les modifier lui-même. Seul un administrateur peut mettre à jour son profil.

Vérification du mail enregistré (amélioration en cours, l’email de confirmation sera envoyé après inscription).

b) Gestion des articles

Ajout d’un article avec un titre, un contenu, une image et une catégorie.

Édition et suppression des articles (selon le rôle de l’utilisateur).

Affichage des articles avec un tri par date de création et catégorie.

Support des images pour illustrer les articles.

c) Gestion des catégories

Ajout et modification de catégories (par l’administrateur).

Classement des articles par catégorie.

d) Tableau de bord administrateur

Vue d’ensemble des articles et des utilisateurs.

Accès rapide aux fonctionnalités de gestion.

e) Sécurité

Hachage des mots de passe (bcrypt).

Sessions sécurisées et redirection en cas d’accès non autorisé.

Vérification des entrées pour éviter les injections SQL.

3. Cas d'utilisation

📌 Cas 1 : Inscription et Connexion

Un utilisateur remplit le formulaire d’inscription.

(Amélioration en cours) Un email de confirmation sera envoyé après inscription.

L’utilisateur se connecte et est redirigé vers la page d’accueil.

📌 Cas 2 : Création d’un article (Rédacteur ou Admin)

L’utilisateur accède au tableau de bord et clique sur "Ajouter un article".

Il saisit un titre, un contenu, sélectionne une catégorie et ajoute une image.

Il valide, et l’article est enregistré en base de données.

📌 Cas 3 : Gestion des articles (Admin)

L’administrateur voit la liste des articles depuis le tableau de bord.

Il peut modifier un article existant ou le supprimer.

📌 Cas 4 : Gestion des utilisateurs (Admin)

L’administrateur accède à la liste des utilisateurs.

Il peut modifier un rôle, bloquer un utilisateur ou le supprimer.

📌 Cas 5 : Lecture des articles (Tous les utilisateurs)

Un visiteur accède à la page d’accueil.

Il peut lire les articles publiés sans se connecter.

(Amélioration en cours) Un système de commentaires sera ajouté pour permettre aux utilisateurs connectés d’interagir avec les articles.

4. Accès au site et au code source

Site en ligne : https://rickyboycms.free.nf/

Code source : https://github.com/ErickEtManuelNew/RickyBoyCMS

5. Accès testeurs

Admin : admin@rickyboycms.com | Mot de passe : Admin123!

Rédacteur : redacteur@rickyboycms.com | Mot de passe : Redacteur123!

Utilisateur : user@rickyboycms.com | Mot de passe : User123!

6. Conclusion

RickyBoyCMS est une solution simple et efficace pour gérer du contenu en ligne avec un système de permissions robuste et une interface intuitive. Il peut être facilement étendu pour inclure d’autres fonctionnalités comme la gestion des commentaires et la vérification d’email lors de l'inscription (améliorations en cours).
