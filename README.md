# 🖥️ Système POS – Installation

## Prérequis
- PHP 8.x (extensions : `pdo_mysql`, `mbstring`)
- MySQL 5.7+ / MariaDB 10.3+
- Apache / Nginx (ou `php -S localhost:8000`)

---

## Installation rapide

### 1. Base de données
```bash
mysql -u root -p < sql/database.sql
```

### 2. Configuration
Éditez `config/config.php` :
```php
define('DB_USER', 'votre_user');
define('DB_PASS', 'votre_mot_de_passe');
define('DB_NAME', 'pos_system');

define('COMPANY_NAME',    'Votre Boutique');
define('COMPANY_ADDRESS', 'Votre adresse');
```

### 3. Lancer
```bash
php -S localhost:8000
# puis ouvrir http://localhost:8000
```

---

## Identifiants de démonstration

| Login    | Mot de passe | Rôle     |
|----------|-------------|----------|
| admin    | admin123    | Admin    |
| caissier | caissier1   | Caissier |

---

## Architecture MVC + DAO

```
pos-final/
├── config/
│   ├── config.php          ← Paramètres BDD & app
│   └── Database.php        ← Connexion PDO Singleton
├── dao/
│   ├── BaseDAO.php
│   ├── UtilisateurDAO.php
│   ├── CommandeDAO.php
│   └── ProduitDAO.php
├── controllers/
│   ├── AuthController.php
│   └── CommandeController.php
├── views/
│   ├── auth/login.php
│   ├── orders/
│   │   ├── dashboard.php
│   │   ├── pos.php          ← Interface de vente
│   │   ├── history.php
│   │   ├── edit.php
│   │   └── ticket.php       ← Ticket thermique 80mm
│   └── partials/
│       ├── header.php
│       └── footer.php
├── public/
│   ├── css/app.css
│   └── js/app.js
├── sql/database.sql
├── helpers.php
└── index.php                ← Routeur frontal
```

## Sécurité
- PDO + requêtes préparées → anti-injection SQL ✅
- `htmlspecialchars()` sur toutes les sorties → anti-XSS ✅
- `password_hash()` bcrypt cost 12 ✅
- Régénération session à la connexion ✅
- Expiration session 1h ✅
- Délai anti-brute-force ✅

## Ticket thermique
- Format **80mm** (modifiable en 58mm dans `ticket.php`)
- `@page { size: 80mm auto; margin: 0; }`
- Impression automatique à l'ouverture de l'onglet
