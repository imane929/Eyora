<?php
// Inclure le header qui contient toute la configuration
include 'includes/header.php';

// Actions du panier (déjà dans header.php)

// Router
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

// Inclure la page appropriée
switch ($page) {
    case 'home':
        include 'views/home.php';
        break;
    case 'products':
        include 'views/products.php';
        break;
    case 'contact':
        include 'views/contact.php';
        break;
    default:
        include 'views/home.php';
}

// Inclure le footer
include 'includes/footer.php';
?>