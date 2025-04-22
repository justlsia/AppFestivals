# Festivals App 🎉

Application web permettant de consulter et gérer les festivals tendances du moment.

## Description

Festivals App est une application web développée dans un cadre scolaire. Elle permet aux utilisateurs de découvrir les festivals populaires sans avoir besoin de se connecter. Un système d’authentification permet également aux utilisateurs de contribuer en ajoutant, modifiant ou supprimant des festivals, avec un contrôle renforcé pour éviter les doublons ou les suppressions accidentelles.

Les administrateurs disposent de privilèges supplémentaires, notamment la gestion des profils utilisateurs.

---

## Fonctionnalités principales

-  Consultation libre des festivals en liste ou via une recherche
-  Ajout, modification et suppression de festivals (avec sécurité)
-  Authentification par email/mot de passe ou via Google (OAuth)
-  Récupération de mot de passe par email
-  Gestion de son profil utilisateur (consultation + modification)
-  Gestion des utilisateurs (admin uniquement)

---

## Technologies utilisées

- **Frontend :** HTML, CSS, JavaScript, Bootstrap
- **Backend :** PHP, PHPMailer, Google OAuth, Sentry (logs)
- **Base de données :** MariaDB

---

## Installation

### Prérequis

- PHP installé
- Serveur local (Apache, XAMPP, MAMP, etc.)
- Base de données MariaDB

### Étapes d'installation

```bash
# 1. Cloner le dépôt
git clone https://github.com/votre-utilisateur/festivals-app.git

# 2. Importer la base de données
# Ouvrir votre interface MariaDB (phpMyAdmin ou autre)
# Importer le fichier : sql/database.sql

# 3. Configuration
# Copier le fichier d'exemple et adapter selon votre environnement
cp .env.exemple .env

# Modifier les valeurs dans .env : identifiants DB, clés OAuth, Sentry.

```
---

## Captures d'écran

Ces images illustrent les principales pages de l'application :

- Interface principale
![alt](path/to/image))
- Page de connexion

- Gestion des festivals (CRUD)

- Profil utilisateur

- Page de gestion des utilisateurs (admin)


