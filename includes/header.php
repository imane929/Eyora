<?php
session_start();

// Configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'eyora_db');
define('DB_USER', 'root');
define('DB_PASS', '');
define('SITE_URL', 'http://localhost/Eyora');

// Connexion DB
function getDB() {
    static $pdo = null;
    if ($pdo === null) {
        try {
            $pdo = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
                DB_USER,
                DB_PASS,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );
        } catch (PDOException $e) {
            die("Erreur DB: " . $e->getMessage());
        }
    }
    return $pdo;
}

// Initialiser panier
if (!isset($_SESSION['panier'])) {
    $_SESSION['panier'] = [];
}

// Actions du panier - CORRECTION : Ajouter ici la logique du panier
if (isset($_GET['action'])) {
    $pdo = getDB();
    
    switch ($_GET['action']) {
        case 'add_to_cart':
            if (isset($_GET['id'])) {
                $product_id = intval($_GET['id']);
                
                $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
                $stmt->execute([$product_id]);
                $product = $stmt->fetch();
                
                if ($product) {
                    if (isset($_SESSION['panier'][$product_id])) {
                        $_SESSION['panier'][$product_id]['quantity'] += 1;
                    } else {
                        $_SESSION['panier'][$product_id] = [
                            'id' => $product['id'],
                            'name' => $product['name'],
                            'price' => $product['price'],
                            'image' => $product['image_url'],
                            'quantity' => 1
                        ];
                    }
                    $_SESSION['success'] = $product['name'] . " ajouté au panier!";
                }
            }
            // Rediriger vers la page précédente
            header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? 'index.php'));
            exit;
            
        case 'remove_from_cart':
            if (isset($_GET['id'])) {
                $product_id = intval($_GET['id']);
                if (isset($_SESSION['panier'][$product_id])) {
                    unset($_SESSION['panier'][$product_id]);
                    $_SESSION['success'] = "Article retiré du panier";
                }
            }
            header('Location: panier.php');
            exit;
            
        case 'update_cart':
            if (isset($_POST['quantities'])) {
                foreach ($_POST['quantities'] as $id => $qty) {
                    $id = intval($id);
                    $qty = intval($qty);
                    if ($qty > 0 && isset($_SESSION['panier'][$id])) {
                        $_SESSION['panier'][$id]['quantity'] = $qty;
                    } elseif ($qty <= 0 && isset($_SESSION['panier'][$id])) {
                        unset($_SESSION['panier'][$id]);
                    }
                }
                $_SESSION['success'] = "Panier mis à jour";
            }
            header('Location: panier.php');
            exit;
            
        case 'clear_cart':
            $_SESSION['panier'] = [];
            $_SESSION['success'] = "Panier vidé";
            header('Location: panier.php');
            exit;
    }
}

// Gestion formulaire contact
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['contact_submit'])) {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');
    
    if (!empty($name) && !empty($email) && !empty($subject) && !empty($message)) {
        $pdo = getDB();
        $stmt = $pdo->prepare("
            INSERT INTO contacts (name, email, subject, message, ip_address) 
            VALUES (?, ?, ?, ?, ?)
        ");
        
        if ($stmt->execute([$name, $email, $subject, $message, $_SERVER['REMOTE_ADDR']])) {
            $_SESSION['success'] = "Message envoyé avec succès!";
        } else {
            $_SESSION['error'] = "Erreur lors de l'envoi";
        }
    } else {
        $_SESSION['error'] = "Tous les champs sont requis";
    }
    
    header('Location: index.php?page=contact');
    exit;
}

// Récupérer info
$currentTheme = isset($_COOKIE['theme']) ? $_COOKIE['theme'] : 'light';
$cartCount = 0;
if (isset($_SESSION['panier']) && !empty($_SESSION['panier'])) {
    foreach ($_SESSION['panier'] as $item) {
        $cartCount += isset($item['quantity']) ? $item['quantity'] : 0;
    }
}
$currentPage = isset($_GET['page']) ? $_GET['page'] : 'home';
?>

<!DOCTYPE html>
<html lang="fr" data-theme="<?php echo htmlspecialchars($currentTheme); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eyora - Voyez le monde autrement</title>
    
    <!-- CSS -->
    <link rel="stylesheet" href="css/theme.css">
    <link rel="stylesheet" href="css/style.css">
    
    <!-- CSS spécifique page -->
    <?php if ($currentPage === 'home'): ?>
    <link rel="stylesheet" href="css/home.css">
    <?php elseif ($currentPage === 'products'): ?>
    <link rel="stylesheet" href="css/products.css">
    <?php elseif ($currentPage === 'contact'): ?>
    <link rel="stylesheet" href="css/style.css">
    <?php endif; ?>
    
    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Open+Sans:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <!-- Notice fixe -->
    <div class="temp-notice">
        <span>Bienvenue sur Eyora |</span>
        <a href="index.php?page=products" class="notice-link">Découvrez notre collection</a>
    </div>
    
    <!-- Header fixe -->
    <header class="header">
        <nav class="nav-container">
            <div class="logo">
                <a href="index.php">
                    <i class="fas fa-glasses"></i>
                    <div>
                        <span>Eyora</span>
                        <small>Voyez le monde autrement</small>
                    </div>
                </a>
            </div>
            
            <ul class="nav-menu" id="mainMenu">
                <li>
                    <a href="index.php?page=home" 
                       class="nav-link <?php echo $currentPage === 'home' ? 'active' : ''; ?>">
                        <i class="fas fa-home"></i> Accueil
                    </a>
                </li>
                <li>
                    <a href="index.php?page=products" 
                       class="nav-link <?php echo $currentPage === 'products' ? 'active' : ''; ?>">
                        <i class="fas fa-glasses"></i> Produits
                    </a>
                </li>
                <li>
                    <a href="index.php?page=contact" 
                       class="nav-link <?php echo $currentPage === 'contact' ? 'active' : ''; ?>">
                        <i class="fas fa-envelope"></i> Contact
                    </a>
                </li>
            </ul>
            
            <div class="nav-actions">
                <a href="panier.php" class="panier-icon" title="Voir mon panier">
                    <i class="fas fa-shopping-cart"></i>
                    <?php if ($cartCount > 0): ?>
                    <span class="panier-badge"><?php echo $cartCount; ?></span>
                    <?php endif; ?>
                </a>
                
                <button class="theme-toggle" id="themeToggle" aria-label="Changer le thème">
                    <i class="fas fa-moon"></i>
                    <i class="fas fa-sun"></i>
                </button>
                
                <button class="mobile-menu-btn" id="mobileMenuBtn" aria-label="Menu mobile">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </nav>
    </header>
    
    <!-- Contenu principal -->
    <div class="main-content">