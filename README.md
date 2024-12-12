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
   git clone https://github.com/your-repository-url.git

2. Accédez au répertoire du projet:
    cd your-project-folder

3. Installez les dépendances avec Composer:
    **composer install**

4. Configurez votre base de données:

    Mettez à jour le fichier **/src/Config/config.php** avec vos informations (hôte, nom d'utilisateur, mot de passe).

    Créez la base de données et les tables en exécutant:
        **php public/create_tables.php**

5. Remplissez la base de données avec des données fictives:
        **php src/Config/fill_database.php**

**project-folder**


├── composer.json          **Gestion des dépendances**
├── public                 **Fichiers accessibles publiquement**
│   ├── add_review.php     **Page d'ajout d'avis**
│   ├── create_tables.php  **Script de création des tables**
│   ├── index.php          **Point d'entrée principal**
│   ├── profile.php        **Gestion du profil utilisateur**
│   ├── reservation.php    **Page de réservation**
│   ├── spectacle_details.php **Page des détails d'un spectacle**
├── src
│   ├── Config             **Fichiers de configuration**
│   │   ├── config.php     **Informations de connexion MySQL**
│   │   ├── Database.php   **Connexion à la base de données**
│   │   ├── fill_database.php **Script de remplissage de données fictives**
│   │   └── TableCreator.php **Création des tables**
│   ├── Controllers        **Logique applicative**
│   │   ├── UserController.php
│   │   ├── ReviewController.php
│   ├── Models             **Représentation des données**
│   │   ├── User.php       **Modèle utilisateur**
│   │   ├── Review.php     **Modèle pour les avis**
│   ├── Services           **Services utilitaires (authentification, etc.)**
│   ├── Views              **(Optionnel) Fichiers de vues**
├── tests                  **Tests unitaires et fonctionnels**
└── vendor                 **Dépendances Composer**


**Contributeurs**


AS :
KI :
YE :
LT :
DK :
