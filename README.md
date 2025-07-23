# 🍷 MyCave - Gestionnaire de Cave à Vin

Version PHP dynamique avec base de données, authentification et API REST.

## 📦 Structure du Projet

```
MyCave/
├── 📁 api/                 # API REST
│   ├── auth.php           # Authentification (login, register, logout)
│   └── wines.php          # CRUD des vins
├── 📁 assets/             # Ressources statiques
│   ├── css/style.css      # Styles compilés
│   ├── img/               # Images et logos
│   └── scss/              # Sources SCSS
├── 📁 classes/            # Classes PHP
│   ├── User.php           # Gestion des utilisateurs
│   └── Wine.php           # Gestion des vins
├── 📁 config/             # Configuration
│   └── database.php       # Connexion base de données
├── 📁 database/           # Base de données
│   └── schema.sql         # Structure et données de test
├── 📁 includes/           # Fichiers PHP réutilisables
│   └── session.php        # Gestion des sessions
├── 📁 uploads/            # Images uploadées
├── dashboard.html         # 📊 Version statique (conservée)
├── dashboard.php          # 📊 Version dynamique
├── add.html              # ➕ Version statique (conservée)
├── add-wine.php          # ➕ Version dynamique
└── login.php             # 🔐 Authentification
```

## 🚀 Installation Rapide

### 1. Prérequis
- **XAMPP/WAMP/MAMP** ou serveur local PHP 7.4+
- **MySQL** 5.7+
- **Navigateur moderne**

### 2. Configuration Base de Données

```bash
# 1. Créer la base de données
mysql -u root -p < database/schema.sql

# 2. Ou via phpMyAdmin :
# - Importer le fichier database/schema.sql
```

### 3. Configuration PHP

Éditer `config/database.php` si nécessaire :
```php
private $host = 'localhost';
private $db_name = 'mycave_db';
private $username = 'root';
private $password = '';
```

### 4. Permissions

```bash
# Créer le dossier uploads avec permissions
mkdir uploads
chmod 777 uploads
```

## 🔑 Comptes de Test

### Utilisateur Standard
- **Email:** didier@mycave.com
- **Mot de passe:** password
- **Cave:** 12 bouteilles pré-remplies

### Administrateur
- **Email:** admin@mycave.com
- **Mot de passe:** password
- **Privilèges:** Gestion de tous les vins

## 📋 Fonctionnalités

### ✅ Version Actuelle (PHP)

#### 🔐 Authentification
- [x] Connexion / Inscription
- [x] Gestion des sessions
- [x] Rôles utilisateur (user/admin)
- [x] Hachage sécurisé des mots de passe

#### 🍷 Gestion des Vins
- [x] Ajout de bouteilles avec photo
- [x] Modification des informations
- [x] Suppression avec confirmation
- [x] Upload d'images sécurisé
- [x] Affichage responsive des cartes

#### 📊 Dashboard
- [x] Vue d'ensemble personnalisée
- [x] Compteur de bouteilles en temps réel
- [x] Interface responsive (mobile/tablet/desktop)
- [x] Chargement dynamique via API

#### 🎨 Design
- [x] Interface moderne avec glassmorphism
- [x] Background fixe avec overlay scrollable
- [x] Responsive design (3 cols → 2 cols → 1 col)
- [x] Animations et transitions fluides

### 🔌 API REST

#### Authentification (`/api/auth.php`)
```bash
POST /api/auth.php
{
  "action": "login",
  "email": "didier@mycave.com",
  "password": "password"
}

POST /api/auth.php
{
  "action": "register",
  "name": "Nouveau User",
  "email": "user@example.com",
  "password": "motdepasse"
}

GET /api/auth.php?action=me
# Retourne les infos de l'utilisateur connecté
```

#### Vins (`/api/wines.php`)
```bash
GET /api/wines.php
# Liste tous les vins de l'utilisateur

POST /api/wines.php
# FormData avec name, year, grapes, country, region, description, picture

PUT /api/wines.php
# JSON avec id + champs à modifier

DELETE /api/wines.php?id=123
# Supprime le vin avec l'id 123
```

## 🗄️ Base de Données

### Table `users`
```sql
- id (INT, AUTO_INCREMENT, PRIMARY KEY)
- email (VARCHAR(255), UNIQUE)
- password (VARCHAR(255), HASHED)
- name (VARCHAR(100))
- role (ENUM: 'user', 'admin')
- created_at (TIMESTAMP)
```

### Table `wines`
```sql
- id (INT, AUTO_INCREMENT, PRIMARY KEY)
- user_id (INT, FOREIGN KEY)
- name (VARCHAR(255))
- year (INT)
- grapes (VARCHAR(255))
- country (VARCHAR(100))
- region (VARCHAR(100))
- description (TEXT)
- picture (VARCHAR(255))
- created_at (TIMESTAMP)
```

## 🎯 URLs Principales

| Page | URL | Description |
|------|-----|-------------|
| 🔐 **Connexion** | `/login.php` | Authentification + inscription |
| 📊 **Dashboard** | `/dashboard.php` | Vue d'ensemble des vins |
| ➕ **Ajouter** | `/add-wine.php` | Nouveau vin |
| ✏️ **Modifier** | `/add-wine.php?id=123` | Édition d'un vin |

## 🛠️ Technologies

- **Backend:** PHP 7.4+ avec PDO
- **Base de données:** MySQL avec relations
- **Frontend:** JavaScript ES6+ (Fetch API)
- **Styles:** SCSS avec variables et mixins
- **Sécurité:** Sessions PHP, password_hash(), requêtes préparées
- **Upload:** Gestion sécurisée des fichiers images

## 📱 Responsive Design

### Desktop (≥1024px)
- 3 colonnes de cartes de vins
- Header complet avec actions
- Sidebar potentielle pour futures fonctionnalités

### Tablet (425px → 1023px)
- 2 colonnes de cartes
- Header adapté
- Navigation optimisée

### Mobile (≤425px)
- 1 colonne unique
- Header compact
- Boutons tactiles optimisés

## 🔄 Workflow de Développement

### 1. Version Actuelle
- ✅ **dashboard.html** → **dashboard.php** (Fonctionnel)
- ✅ **add.html** → **add-wine.php** (Fonctionnel)
- ✅ API REST complète
- ✅ Base de données avec données de test

### 2. Prochaines Étapes
- [ ] Interface d'administration
- [ ] Recherche et filtres avancés
- [ ] Export/Import de cave
- [ ] Statistiques et graphiques
- [ ] Notifications et rappels

## 🚨 Sécurité

### Mesures Implémentées
- ✅ Hachage des mots de passe avec `password_hash()`
- ✅ Requêtes préparées (protection SQL injection)
- ✅ Validation des entrées utilisateur
- ✅ Gestion sécurisée des sessions
- ✅ Upload d'images avec validation de type
- ✅ Vérification des permissions (utilisateur propriétaire)

### À Améliorer
- [ ] Rate limiting sur les API
- [ ] CSRF tokens
- [ ] Validation côté serveur renforcée
- [ ] Logs de sécurité

## 📈 Performance

### Optimisations Actuelles
- ✅ Images optimisées avec lazy loading
- ✅ CSS/JS minifiés
- ✅ Requêtes SQL optimisées avec index
- ✅ Gestion d'erreurs complète

### Améliorations Futures
- [ ] Cache Redis/Memcached
- [ ] CDN pour les assets
- [ ] Compression Gzip
- [ ] Service Workers

## 🎉 Démarrage

1. **Cloner** le projet
2. **Importer** `database/schema.sql`
3. **Configurer** `config/database.php`
4. **Créer** le dossier `uploads/`
5. **Tester** avec les comptes fournis

**🍷 Votre cave digitale est prête !**
