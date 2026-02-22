<?php
/**
 * Configuration de la base de données
 * Modifiez ces valeurs selon votre environnement.
 */

define('DB_HOST',    'localhost');
define('DB_PORT',    '3306');
define('DB_NAME',    'pos_system');
define('DB_USER',    'root');       // ← Changez ici
define('DB_PASS',    '');           // ← Changez ici
define('DB_CHARSET', 'utf8mb4');

// Informations de l'entreprise (ticket de caisse)
define('COMPANY_NAME',    'Ktechno');
define('COMPANY_ADDRESS', 'Cance,Les Cayes-Haiti');
define('COMPANY_PHONE',   '+509 4157 0822');
define('COMPANY_FOOTER',  'Merci pour votre visite ! À bientôt.');

// Session
define('SESSION_LIFETIME', 3600); // 1 heure
