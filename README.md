# Projet API Laravel

## Vue d'ensemble du projet
Ce projet est une API développée avec le framework Laravel pour gérer des utilisateurs et des publications. Il intègre un système d'authentification robuste, un contrôle d'accès basé sur les rôles, ainsi que des opérations CRUD pour les utilisateurs et les publications.

---

## Fonctionnalités/Statut

- **Authentification & Autorisation** :
  - Inscription et connexion des administrateurs.
  - Inscription et connexion des utilisateurs.
  - Contrôle d'accès basé sur les rôles (Admin, Utilisateur, Super Admin).

- **Opérations CRUD** :
  - **Publications** :
    - Créer, modifier, supprimer, et consulter toutes les publications.
  - **Utilisateurs** :
    - Les administrateurs peuvent gérer les utilisateurs (création, modification, suppression).
  
- **Endpoints** :
  - Accessibles via des routes publiques et protégées.
  - API documentée avec **Postman** et **Swagger**.

---

## Installation

### 1. Cloner le dépôt :
```bash
git clone https://github.com/yourusername/gestion-de-post-api.git
```

### 2. Accéder au répertoire du projet :
```bash
cd Projet_API\Api
```

### 3. Installer les dépendances :
```bash
composer install
```

### 4. Configurer le fichier `.env` :
- Copier le fichier `.env.example` et le renommer en `.env` :
```bash
cp .env.example .env
```
- Mettre à jour les informations de connexion à la base de données dans le fichier `.env` :
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nom_de_votre_base_de_donnees
DB_USERNAME=nom_utilisateur
DB_PASSWORD=mot_de_passe
```

### 5. Générer la clé de l'application :
```bash
php artisan key:generate
```

### 6. Exécuter les migrations de base de données :
```bash
php artisan migrate
```

### 7. Peupler la base de données (Optionnel) :
Si vous avez des seeders pour insérer des données de test :
```bash
php artisan db:seed
```

### 8. Lancer le serveur :
```bash
php artisan serve
```

- Accédez à l'API à l'adresse : `http://localhost:8000`.

---

## Utilisation

### Routes Publiques :
- **Inscription d'un utilisateur** : `POST /register`
- **Connexion d'un utilisateur** : `POST /login`
- **Voir toutes les publications** : `GET /posts`

### Routes Protégées (Requiert une authentification) :
- **Créer une publication** : `POST /posts/create`
- **Modifier une publication** : `PUT /posts/edit/{id}`
- **Supprimer une publication** : `DELETE /posts/{id}`

### Routes Administrateurs :
- **Promouvoir un utilisateur** : `PUT /admin/promote/{id}`
- **Créer un administrateur** : `POST /admin/create`
- **Gérer les utilisateurs** :
  - `GET /admin/users`
  - `POST /admin/users`
  - `PUT /admin/users/{id}`
  - `DELETE /admin/users/{id}`

### Déconnexion :
- **Déconnexion** : `POST /logout`

---

## Commandes Artisan
Voici quelques commandes Laravel Artisan utiles pour ce projet :

1. **Exécuter les migrations** :
   ```bash
   php artisan migrate
   ```

2. **Peupler la base de données** :
   ```bash
   php artisan db:seed
   ```

3. **Effacer le cache** :
   ```bash
   php artisan cache:clear
   ```

4. **Lister toutes les routes** :
   ```bash
   php artisan route:list
   ```

---

## Collection Postman
Une collection Postman est disponible dans le dossier `docs`. Vous pouvez l'importer dans Postman pour tester l'API.

---

## Documentation Swagger
Pour générer ou accéder à la documentation Swagger de l'API, utilisez les commandes suivantes :

1. **Générer la documentation Swagger** :
   ```bash
   php artisan l5-swagger:generate
   ```

2. **Accéder à la documentation** :
   - Ouvrez votre navigateur et accédez à : `http://localhost:8000/api/documentation`.

---

## Contribuer

1. Forkez le dépôt.
2. Créez une nouvelle branche pour votre fonctionnalité :
   ```bash
   git checkout -b feature-branch
   ```
3. Validez vos modifications :
   ```bash
   git commit -m "Ajout d'une nouvelle fonctionnalité"
   ```
4. Poussez la branche sur votre dépôt :
   ```bash
   git push origin feature-branch
   ```
5. Créez une Pull Request.

---