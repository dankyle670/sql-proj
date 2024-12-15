# Système de Gestion des Spectacles

## Aperçu du Projet

Ce projet est un **Système de Gestion des Spectacles** conçu pour gérer :
- Les théâtres, salles et spectacles.
- Les réservations par les abonnés.
- Les avis et les notes des utilisateurs.
- Les profils utilisateurs.

Le projet permet d'automatiser les processus de gestion des spectacles tout en fournissant une interface utilisateur intuitive.

---

## Table des Matières

1. [Aperçu du Projet](#aperçu-du-projet)
2. [Technologies Utilisées](#technologies-utilisées)
3. [Installation](#installation)
4. [Structure du Répertoire](#structure-du-répertoire)
5. [Schéma de la Base de Données](#schéma-de-la-base-de-données)
6. [Utilisation](#utilisation)
7. [Routes Disponibles](#routes-disponibles)
8. [Fonctionnalités](#fonctionnalités)
9. [Contributeurs](#contributeurs)

---

## Technologies Utilisées

- **PHP** (7.4 ou plus récent)
- **MySQL** pour la base de données
- **FakerPHP** pour générer des données fictives
- **Composer** pour la gestion des dépendances

---

## Installation

### Prérequis

Avant de commencer, assurez-vous d'avoir installé :
- PHP (7.4 ou plus récent)
- MySQL
- Composer (pour les dépendances)

### Étapes d'Installation

1. Clonez le dépôt:
   git clone https://github.com/dankyle670/sql-proj.git

2. Accédez au répertoire du projet:
    cd your-project-folder

3. Installez les dépendances avec Composer:
    **composer require fakerphp/faker**
    **composer install**


4. Configurez votre base de données:

    Mettez à jour le fichier **/src/Config/config.php** avec vos informations (hôte, nom d'utilisateur, mot de passe).

    Créez la base de données et les tables en exécutant:
        **php public/create_tables.php**

5. Remplissez la base de données avec des données fictives:
        **php src/Config/fill_database.php**


6. Connectez-vous à MySQL et exécutez les requêtes suivantes pour ajouter la colonne 'image' et la remplir avec des données:
```sql
ALTER TABLE spectacles_spectacle ADD COLUMN image VARCHAR(255);

UPDATE spectacles_spectacle
SET image = CONCAT('https://books.google.com/books/content?id=', id, '&printsec=frontcover&img=1&zoom=1');

7. Enfin lancez le server avec la commande:
        **php -S localhost:8000 -t public**



**project-folder**


├── composer.json          **Gestion des dépendances**
├── public                 **Fichiers accessibles publiquement**
│   ├── add_review.php     **Page d'ajout d'avis**
│   ├── add_review.css     **Page d'ajout d'avis**
│   ├── all_review.php     **Voir tous les avis**
│   ├── all_review.css     **css**
│   ├── get_suggestions.php** Gérer lessuggestion**
│   ├── Home.php           **Page d'acceuil**
│   ├── login.css           **css**
│   ├── login.php           **Vue**
│   ├── profile.css           **css**
│   ├── profile.php        **Gestion du profil utilisateur**
│   ├── reservation.css           **css**
│   ├── reservation.php    **Page de réservation**
│   ├── signup.css           **css**
│   ├── signup.php           **Vue**
│   ├── create_tables.php  **Script de création des tables**
│   ├── index.php          **Point d'entrée principal**
│   ├── spectacle_details.php **Page des détails d'un spectacle**
│   ├── spectacle_details.css **Page des détails d'un spectacle**
│   ├── style.css **style génerale**
├── src
│   ├── Config             **Fichiers de configuration**
│   │   ├── config.php     **Informations de connexion MySQL**
│   │   ├── Database.php   **Connexion à la base de données**
│   │   ├── fill_database.php **Script de remplissage de données fictives**
│   │   └── TableCreator.php **Création des tables**
│   ├── Controllers        **Logique applicative**
│   │   ├── ProfileController.php
│   │   ├── ReservationCopntroller
│   │   ├── ReviewController.php
│   │   ├── spectacleController.php
│   │   ├── UserController.php
│   ├── Models             **Représentation des données**
│   │   ├── Reservation.php     **Modèle pour les Reservation**
│   │   ├── Review.php     **Modèle pour les review**
│   │   ├── Spectacle.php     **Modèle pour les Spectacle**
│   │   ├── User.php       **Modèle utilisateur**
│   ├── Services           **Services utilitaires (authentification, etc.)**
│   ├── Views              **(Optionnel) Fichiers de vues**
├── tests                  **Tests unitaires et fonctionnels**
└── vendor                 **Dépendances Composer**


**Contributeurs**


AS : Amadou Samake
KI : Kilian Izatoola
YE : Yvann Ehoura
LT : Ousmane Sacko
DK : Daniele Kouamé
