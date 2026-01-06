# 🏢 LOKISALLE - Plateforme de réservation de salles

**Lokisalle** est une application web complète permettant la location de salles de réunion, de formation ou de bureaux pour les professionnels. Ce projet a été réalisé en **PHP Natif** dans le cadre d'un examen de développement web.

## 🚀 Fonctionnalités principales

### Côté Utilisateur
* **Accueil dynamique** : Affichage des offres disponibles avec filtres et visuels attractifs.
* **Fiche produit détaillée** : Informations complètes sur la salle (prix TTC, capacité, localisation).
* **Système de réservation** : Possibilité pour les membres de réserver un créneau en temps réel.
* **Gestion de compte** : Inscription, connexion sécurisée (hachage bcrypt) et profil utilisateur.
* **Avis & Contact** : Dépôt d'avis notés sur 10 et formulaire de contact.

### Côté Administration (Back-Office)
* **Dashboard de statistiques** : Top des salles les mieux notées et les plus louées.
* **Gestion des Salles** : Ajout, modification, suppression et upload d'images.
* **Gestion des Produits** : Association d'une salle à une période et un prix.
* **Suivi des Commandes** : Historique des ventes et calcul automatique du chiffre d'affaires.

## 🛠️ Stack Technique
* **Frontend** : HTML5, CSS3, Bootstrap 5 (Responsive Design).
* **Backend** : PHP 8.x (Architecture procédurale structurée).
* **Base de données** : MySQL (7 tables avec jointures complexes).
* **Sécurité** : Protection contre les injections SQL (requêtes préparées) et sessions sécurisées.

## 📦 Installation en local
1. Cloner le dépôt : `git clone https://github.com/ton-pseudo/lokisalle.git`
2. Importer le fichier `database.sql` dans votre interface phpMyAdmin.
3. Configurer les accès à la base de données dans `inc/init.inc.php`.
4. Lancer le projet via un serveur local (WAMP, XAMPP ou MAMP).

## 📝 Licence
Ce projet est sous licence MIT.

Le projet contient également un Assistant IA nommé **Loky** pour aider les utilisateurs à mieux comprendre et utiliser l'application. Loky nécessite une connexion Internet pour fonctionner.

Ce projet a été entièrement réalisé par l'équipe : **METAVEX**. 