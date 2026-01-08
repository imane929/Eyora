<?php
// Configuration du site
define('SITE_NAME', 'Eyora - Lunettes');
define('SITE_URL', 'http://localhost/Eyora');
define('ADMIN_EMAIL', 'contact@eyora.com');

// Paramètres de base de données
define('DB_HOST', 'localhost');
define('DB_NAME', 'eyora_db');
define('DB_USER', 'root');
define('DB_PASS', '');

// Thème par défaut
$currentTheme = isset($_COOKIE['theme']) ? $_COOKIE['theme'] : 'light';
?>