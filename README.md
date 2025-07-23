# 🍷 MyCave - Votre Cave à Vin Digitale

Application web pour gérer votre cave à vin personnelle avec authentification utilisateur et interface d'administration.

## 🚀 Fonctionnalités

### 👤 **Utilisateur**
- ✅ Inscription et connexion sécurisées
- ✅ Dashboard personnel avec compteur de bouteilles
- ✅ Ajout de nouvelles bouteilles avec upload d'image
- ✅ Modification et suppression de ses bouteilles
- ✅ Interface responsive (mobile, tablette, desktop)

### 👨‍💼 **Administrateur**
- ✅ Accès à toutes les fonctionnalités utilisateur
- ✅ Vue d'ensemble de tous les utilisateurs
- ✅ Gestion de toutes les bouteilles
- ✅ Statistiques globales

## 🛠️ Technologies utilisées

- **Frontend** : HTML5, CSS3 (SCSS), JavaScript (ES6+)
- **Backend** : PHP 7.4+
- **Base de données** : MySQL 5.7+
- **Sécurité** : Sessions PHP, Hash des mots de passe

## 📦 Installation

### Prérequis
- XAMPP, WAMP ou serveur local avec PHP 7.4+ et MySQL
- Navigateur web moderne

### 1. Cloner le projet
```bash
git clone https://github.com/votre-username/MyCave.git
cd MyCave
```

### 2. Configuration de la base de données

1. Démarrer MySQL dans XAMPP/WAMP
2. Aller sur http://localhost/phpmyadmin
3. Exécuter le fichier SQL :
```sql
-- Copier et exécuter le contenu de database/schema.sql
```

### 3. Configuration PHP

1. Modifier `config/database.php` si nécessaire :
```php
private $host = 'localhost';
private $db_name = 'mycave_db';
private $username = 'root';
private $password = '';
```

### 4. Permissions des dossiers
```bash
mkdir uploads
chmod 777 uploads
```

### 5. Compilation SCSS (optionnel)
```bash
npm install
npm run sass
```

## 🔑 Comptes de test

Une fois la base de données créée, vous pouvez vous connecter avec :

### Administrateur
- **Email** : admin@mycave.com
- **Mot de passe** : password

### Utilisateur
- **Email** : didier@mycave.com  
- **Mot de passe** : password

## 📁 Structure du projet

```
MyCave/
├── api/                    # API REST
│   ├── auth.php           # Authentification
│   └── wines.php          # Gestion des vins
├── assets/                # Ressources frontend
│   ├── css/              # CSS compilé
│   ├── scss/             # Sources SCSS
│   ├── img/              # Images
│   └── js/               # JavaScript
├── classes/              # Classes PHP
│   ├── User.php          # Gestion utilisateurs
│   └── Wine.php          # Gestion vins
├── config/               # Configuration
│   └── database.php      # Connexion DB
├── database/             # Scripts SQL
│   └── schema.sql        # Structure DB
├── includes/             # Includes PHP
│   └── session.php       # Gestion sessions
├── uploads/              # Images uploadées
├── dashboard.php         # Dashboard principal
├── login.php            # Page de connexion
├── add-wine.php         # Ajout/Édition vin
└── README.md            # Documentation
```

## 🎨 Interface

### Dashboard
- **Desktop** : 3 colonnes de cartes
- **Tablette** : 2 colonnes
- **Mobile** : 1 colonne
- Scroll vertical avec image de fond fixe
- Compteur dynamique de bouteilles

### Authentification
- Formulaire de connexion/inscription
- Validation côté client et serveur
- Sessions sécurisées

## 🔧 API Endpoints

### Authentification
```
POST /api/auth.php?action=login
POST /api/auth.php?action=register
POST /api/auth.php?action=logout
GET  /api/auth.php?action=me
```

### Vins
```
GET    /api/wines.php           # Lister les vins
POST   /api/wines.php           # Ajouter un vin
PUT    /api/wines.php           # Modifier un vin
DELETE /api/wines.php?id=X      # Supprimer un vin
```

## 🚀 Déploiement

### Développement local
1. Placer le projet dans `htdocs/` (XAMPP) ou `www/` (WAMP)
2. Accéder à http://localhost/MyCave/

### Production
1. Modifier les paramètres de base de données
2. Changer les chemins dans `config/database.php`
3. Sécuriser le dossier `uploads/`

## 🔒 Sécurité

- ✅ Mots de passe hashés avec `password_hash()`
- ✅ Protection CSRF via sessions
- ✅ Validation des uploads d'images
- ✅ Requêtes préparées (protection SQL injection)
- ✅ Vérification des permissions utilisateur

## 📱 Responsive Design

L'application s'adapte automatiquement :
- **≥1024px** : Layout desktop complet
- **768-1023px** : Layout tablette (2 colonnes)
- **≤767px** : Layout mobile (1 colonne)

## 🐛 Dépannage

### Base de données
```bash
# Vérifier la connexion MySQL
mysql -u root -p
```

### Permissions
```bash
# Vérifier les permissions du dossier uploads
ls -la uploads/
```

### SCSS
```bash
# Recompiler les styles
npm run sass
```

## 🤝 Contribution

1. Fork le projet
2. Créer une branche feature (`git checkout -b feature/AmazingFeature`)
3. Commit les changements (`git commit -m 'Add AmazingFeature'`)
4. Push la branche (`git push origin feature/AmazingFeature`)
5. Ouvrir une Pull Request

## 📄 Licence

Ce projet est sous licence MIT. Voir le fichier `LICENSE` pour plus de détails.

## 👨‍💻 Auteur

**Didier Martin** - MyCave Project

---

🍷 **Santé !** Profitez bien de votre cave digitale !
