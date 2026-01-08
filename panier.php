<?php
session_start();

// Configuration DB
define('DB_HOST', 'localhost');
define('DB_NAME', 'eyora_db');
define('DB_USER', 'root');
define('DB_PASS', '');

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

// GESTION DES ACTIONS DU PANIER
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'update_quantity':
            if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
                $product_id = intval($_POST['product_id']);
                $quantity = intval($_POST['quantity']);
                
                if ($quantity > 0 && isset($_SESSION['panier'][$product_id])) {
                    $_SESSION['panier'][$product_id]['quantity'] = $quantity;
                    $_SESSION['success'] = "Quantité mise à jour";
                } elseif ($quantity <= 0 && isset($_SESSION['panier'][$product_id])) {
                    unset($_SESSION['panier'][$product_id]);
                    $_SESSION['success'] = "Article retiré du panier";
                }
            }
            // Rediriger pour éviter la resoumission
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
    }
}

// Gestion des actions GET
if (isset($_GET['action'])) {
    switch ($_GET['action']) {
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
            
        case 'clear_cart':
            $_SESSION['panier'] = [];
            $_SESSION['success'] = "Panier vidé";
            header('Location: panier.php');
            exit;
    }
}

// Initialiser panier si non existant
if (!isset($_SESSION['panier'])) {
    $_SESSION['panier'] = [];
}

// Calculer le total
$total = 0;
$total_quantity = 0;
if (isset($_SESSION['panier']) && !empty($_SESSION['panier'])) {
    foreach ($_SESSION['panier'] as $item) {
        $total += $item['price'] * $item['quantity'];
        $total_quantity += $item['quantity'];
    }
}

// Récupérer info pour le header
$currentTheme = isset($_COOKIE['theme']) ? $_COOKIE['theme'] : 'light';
$cartCount = $total_quantity;
?>

<!DOCTYPE html>
<html lang="fr" data-theme="<?php echo htmlspecialchars($currentTheme); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panier - Eyora</title>
    
    <!-- CSS -->
    <link rel="stylesheet" href="css/theme.css">
    <link rel="stylesheet" href="css/style.css">
    
    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Open+Sans:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
    /* Styles spécifiques au panier */
    .cart-section {
        padding: 100px 0 60px;
        min-height: calc(100vh - 300px);
    }
    
    .section-title {
        font-size: 2.8rem;
        margin-bottom: 40px;
        text-align: center;
        color: var(--text-primary);
        font-family: 'Poppins', sans-serif;
    }
    
    .section-title i {
        color: var(--primary-color);
        margin-right: 15px;
    }
    
    .empty-cart {
        text-align: center;
        padding: 80px 40px;
        background: var(--bg-secondary);
        border-radius: 20px;
        margin: 40px 0;
    }
    
    .empty-cart i {
        font-size: 4rem;
        color: var(--text-muted);
        margin-bottom: 30px;
        opacity: 0.5;
    }
    
    .empty-cart h2 {
        margin-bottom: 20px;
        color: var(--text-primary);
        font-size: 1.8rem;
    }
    
    .empty-cart p {
        color: var(--text-secondary);
        margin-bottom: 30px;
        max-width: 400px;
        margin: 0 auto 30px;
        font-size: 1.1rem;
    }
    
    .cart-content {
        display: grid;
        grid-template-columns: 1fr 350px;
        gap: 40px;
        margin-top: 40px;
    }
    
    .cart-items {
        background: var(--card-bg);
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }
    
    .cart-header {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr 1fr 80px;
        padding: 20px 25px;
        background: var(--bg-tertiary);
        font-weight: 600;
        color: var(--text-primary);
        border-bottom: 1px solid var(--border-color);
        font-size: 0.9rem;
    }
    
    .cart-item {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr 1fr 80px;
        padding: 25px;
        align-items: center;
        border-bottom: 1px solid var(--border-color);
        transition: background-color 0.3s ease;
    }
    
    .cart-item:hover {
        background: var(--bg-secondary);
    }
    
    .cart-item:last-child {
        border-bottom: none;
    }
    
    .cart-item-product {
        display: flex;
        align-items: center;
        gap: 20px;
    }
    
    .cart-item-product img {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 10px;
        border: 1px solid var(--border-color);
    }
    
    .cart-item-info h3 {
        margin-bottom: 8px;
        font-size: 1.1rem;
        color: var(--text-primary);
    }
    
    .cart-item-info p {
        color: var(--text-muted);
        font-size: 0.9rem;
    }
    
    .cart-item-price,
    .cart-item-total {
        font-weight: 600;
        color: var(--text-primary);
        font-size: 1.1rem;
    }
    
    .cart-item-total {
        color: var(--primary-color);
    }
    
    .quantity-controls {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .quantity-btn {
        width: 32px;
        height: 32px;
        border-radius: 6px;
        border: 1px solid var(--border-color);
        background: var(--card-bg);
        color: var(--text-primary);
        cursor: pointer;
        font-size: 1.1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }
    
    .quantity-btn:hover {
        border-color: var(--primary-color);
        color: var(--primary-color);
        background: rgba(var(--primary-color-rgb), 0.1);
    }
    
    .quantity-display {
        min-width: 40px;
        text-align: center;
        font-weight: 600;
        font-size: 1.1rem;
    }
    
    .remove-btn {
        color: var(--error-color);
        font-size: 1.2rem;
        text-decoration: none;
        padding: 8px;
        border-radius: 6px;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
    }
    
    .remove-btn:hover {
        background: rgba(var(--error-color-rgb), 0.1);
        transform: scale(1.1);
    }
    
    .cart-summary {
        background: var(--card-bg);
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        position: sticky;
        top: 150px;
        height: fit-content;
    }
    
    .cart-summary h3 {
        margin-bottom: 25px;
        padding-bottom: 20px;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        align-items: center;
        gap: 10px;
        color: var(--text-primary);
        font-size: 1.5rem;
    }
    
    .summary-details {
        margin-bottom: 30px;
    }
    
    .summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 15px;
        padding-bottom: 15px;
        border-bottom: 1px solid var(--border-color);
        color: var(--text-primary);
    }
    
    .summary-row:last-child {
        border-bottom: none;
    }
    
    .summary-row.total {
        font-size: 1.3rem;
        font-weight: 700;
        color: var(--primary-color);
        padding-top: 15px;
        border-top: 2px solid var(--border-color);
    }
    
    .summary-actions {
        display: flex;
        flex-direction: column;
        gap: 15px;
        margin-bottom: 25px;
    }
    
    .checkout-btn {
        margin-top: 10px;
        padding: 16px;
        font-size: 1.1rem;
    }
    
    .secure-payment {
        text-align: center;
        padding-top: 20px;
        border-top: 1px solid var(--border-color);
        color: var(--text-muted);
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px;
    }
    
    .secure-payment i.fa-lock {
        color: var(--success-color);
        font-size: 1.5rem;
    }
    
    .payment-icons {
        display: flex;
        gap: 15px;
        font-size: 1.8rem;
    }
    
    .payment-icons i {
        color: var(--text-secondary);
    }
    
    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        padding: 14px 28px;
        border-radius: 50px;
        font-family: 'Poppins', sans-serif;
        font-weight: 500;
        font-size: 1rem;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
        color: white;
        box-shadow: 0 5px 15px rgba(var(--primary-color-rgb), 0.3);
    }
    
    .btn-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(var(--primary-color-rgb), 0.4);
    }
    
    .btn-secondary {
        background: transparent;
        border-color: var(--primary-color);
        color: var(--primary-color);
    }
    
    .btn-secondary:hover {
        background: rgba(var(--primary-color-rgb), 0.1);
        transform: translateY(-3px);
    }
    
    .btn-outline {
        background: transparent;
        border: 2px solid var(--border-color);
        color: var(--text-primary);
    }
    
    .btn-outline:hover {
        border-color: var(--primary-color);
        color: var(--primary-color);
        transform: translateY(-3px);
    }
    
    /* Notification */
    .notification {
        position: fixed;
        top: 120px;
        right: 20px;
        background: linear-gradient(135deg, #10b981, #34d399);
        color: white;
        padding: 16px 24px;
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        display: flex;
        align-items: center;
        gap: 12px;
        z-index: 10000;
        animation: slideIn 0.3s ease-out;
        max-width: 400px;
    }
    
    @keyframes slideIn {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    
    @keyframes slideOut {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(100%); opacity: 0; }
    }
    
    /* Responsive */
    @media (max-width: 992px) {
        .cart-content {
            grid-template-columns: 1fr;
        }
        
        .cart-summary {
            position: static;
        }
    }
    
    @media (max-width: 768px) {
        .cart-section {
            padding: 80px 0 40px;
        }
        
        .section-title {
            font-size: 2.2rem;
        }
        
        .cart-header {
            display: none;
        }
        
        .cart-item {
            grid-template-columns: 1fr;
            gap: 20px;
            padding: 20px;
            text-align: center;
        }
        
        .cart-item-product {
            flex-direction: column;
            text-align: center;
        }
        
        .quantity-controls {
            justify-content: center;
        }
        
        .cart-item-price,
        .cart-item-total {
            font-size: 1.2rem;
        }
    }
    
    @media (max-width: 480px) {
        .cart-section {
            padding: 60px 0 30px;
        }
        
        .section-title {
            font-size: 1.8rem;
        }
        
        .empty-cart {
            padding: 40px 20px;
        }
        
        .cart-summary {
            padding: 20px;
        }
    }
    </style>
</head>
<body>
    <!-- Header simplifié -->
    <div style="position: fixed; top: 0; left: 0; width: 100%; background: var(--bg-primary); z-index: 9998; border-bottom: 1px solid var(--border-color); padding: 15px 0;">
        <div class="container" style="max-width: 1400px; margin: 0 auto; padding: 0 20px; display: flex; justify-content: space-between; align-items: center;">
            <div class="logo">
                <a href="index.php" style="display: flex; align-items: center; gap: 12px; text-decoration: none;">
                    <i class="fas fa-glasses" style="font-size: 2rem; color: var(--primary-color);"></i>
                    <span style="font-family: 'Poppins', sans-serif; font-size: 1.5rem; font-weight: 700; color: var(--text-primary);">Eyora</span>
                </a>
            </div>
            
            <div style="display: flex; align-items: center; gap: 20px;">
                <a href="index.php" class="btn btn-outline" style="text-decoration: none;">
                    <i class="fas fa-home"></i> Accueil
                </a>
                <a href="index.php?page=products" class="btn btn-outline" style="text-decoration: none;">
                    <i class="fas fa-shopping-bag"></i> Boutique
                </a>
                <a href="panier.php" class="btn btn-primary" style="text-decoration: none; position: relative;">
                    <i class="fas fa-shopping-cart"></i> Panier
                    <?php if ($cartCount > 0): ?>
                    <span style="position: absolute; top: -8px; right: -8px; background: var(--error-color); color: white; border-radius: 50%; width: 22px; height: 22px; font-size: 0.75rem; display: flex; align-items: center; justify-content: center; font-weight: bold;">
                        <?php echo $cartCount; ?>
                    </span>
                    <?php endif; ?>
                </a>
            </div>
        </div>
    </div>

    <!-- Espace pour le header fixe -->
    <div style="height: 70px;"></div>

    <!-- Notification -->
    <?php if (isset($_SESSION['success'])): ?>
    <div class="notification" id="successNotification">
        <i class="fas fa-check-circle"></i>
        <span><?php echo $_SESSION['success']; ?></span>
        <button onclick="document.getElementById('successNotification').remove()" style="background: none; border: none; color: white; font-size: 1.5rem; cursor: pointer; margin-left: 10px;">
            &times;
        </button>
    </div>
    <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <div class="container" style="max-width: 1400px; margin: 0 auto; padding: 0 20px;">
        <section class="cart-section">
            <h1 class="section-title"><i class="fas fa-shopping-cart"></i> Mon Panier</h1>
            
            <?php if (empty($_SESSION['panier'])): ?>
            <div class="empty-cart">
                <i class="fas fa-shopping-cart"></i>
                <h2>Votre panier est vide</h2>
                <p>Ajoutez des produits à votre panier pour commencer vos achats.</p>
                <a href="index.php?page=products" class="btn btn-primary">
                    <i class="fas fa-store"></i> Découvrir nos produits
                </a>
            </div>
            <?php else: ?>
            <div class="cart-content">
                <!-- Liste des produits -->
                <div class="cart-items">
                    <div class="cart-header">
                        <div class="cart-header-product">Produit</div>
                        <div class="cart-header-price">Prix unitaire</div>
                        <div class="cart-header-quantity">Quantité</div>
                        <div class="cart-header-total">Total</div>
                        <div class="cart-header-actions">Actions</div>
                    </div>
                    
                    <?php foreach ($_SESSION['panier'] as $product_id => $item): 
                        $item_total = $item['price'] * $item['quantity'];
                    ?>
                    <div class="cart-item">
                        <div class="cart-item-product">
                            <img src="assets/images/<?php echo htmlspecialchars($item['image']); ?>" 
                                 alt="<?php echo htmlspecialchars($item['name']); ?>"
                                 onerror="this.onerror=null; this.src='assets/images/default-glasses.jpg';">
                            <div class="cart-item-info">
                                <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                                <p>Référence: <?php echo $product_id; ?></p>
                            </div>
                        </div>
                        
                        <div class="cart-item-price">
                            <?php echo number_format($item['price'], 2); ?> €
                        </div>
                        
                        <div class="cart-item-quantity">
                            <form method="POST" action="panier.php" class="quantity-form" id="form-<?php echo $product_id; ?>" onsubmit="return updateQuantity(<?php echo $product_id; ?>)">
                                <input type="hidden" name="action" value="update_quantity">
                                <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                                <input type="hidden" name="quantity" 
                                       id="quantity-<?php echo $product_id; ?>"
                                       value="<?php echo $item['quantity']; ?>">
                                <div class="quantity-controls">
                                    <button type="button" class="quantity-btn minus" 
                                            onclick="changeQuantity(<?php echo $product_id; ?>, -1)">-</button>
                                    <span class="quantity-display" id="display-<?php echo $product_id; ?>">
                                        <?php echo $item['quantity']; ?>
                                    </span>
                                    <button type="button" class="quantity-btn plus" 
                                            onclick="changeQuantity(<?php echo $product_id; ?>, 1)">+</button>
                                </div>
                            </form>
                        </div>
                        
                        <div class="cart-item-total" id="total-<?php echo $product_id; ?>">
                            <?php echo number_format($item_total, 2); ?> €
                        </div>
                        
                        <div class="cart-item-actions">
                            <a href="panier.php?action=remove_from_cart&id=<?php echo $product_id; ?>" 
                               class="remove-btn" title="Supprimer"
                               onclick="return confirm('Supprimer cet article du panier ?')">
                                <i class="fas fa-trash"></i>
                            </a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- Résumé du panier -->
                <div class="cart-summary">
                    <h3><i class="fas fa-receipt"></i> Résumé de la commande</h3>
                    
                    <div class="summary-details">
                        <div class="summary-row">
                            <span>Sous-total</span>
                            <span id="subtotal"><?php echo number_format($total, 2); ?> €</span>
                        </div>
                        <div class="summary-row">
                            <span>Livraison</span>
                            <span>Gratuite</span>
                        </div>
                        <div class="summary-row">
                            <span>TVA (20%)</span>
                            <span id="tva"><?php echo number_format($total * 0.20, 2); ?> €</span>
                        </div>
                        <div class="summary-row total">
                            <span>Total TTC</span>
                            <span id="total-ttc"><?php echo number_format($total * 1.20, 2); ?> €</span>
                        </div>
                    </div>
                    
                    <div class="summary-actions">
                        <a href="index.php?page=products" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Continuer mes achats
                        </a>
                        
                        <a href="panier.php?action=clear_cart" class="btn btn-outline" 
                           onclick="return confirm('Vider tout le panier ? Cette action est irréversible.')">
                            <i class="fas fa-trash"></i> Vider le panier
                        </a>
                        
                        <button class="btn btn-primary checkout-btn" onclick="checkout()">
                            <i class="fas fa-credit-card"></i> Procéder au paiement
                        </button>
                    </div>
                    
                    <div class="secure-payment">
                        <i class="fas fa-lock"></i>
                        <span>Paiement 100% sécurisé</span>
                        <div class="payment-icons">
                            <i class="fab fa-cc-visa"></i>
                            <i class="fab fa-cc-mastercard"></i>
                            <i class="fab fa-cc-paypal"></i>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </section>
    </div>

    <!-- Footer simplifié -->
    <footer style="background: var(--bg-tertiary); padding: 40px 0; margin-top: 60px; border-top: 1px solid var(--border-color);">
        <div class="container" style="max-width: 1400px; margin: 0 auto; padding: 0 20px; text-align: center;">
            <p style="color: var(--text-secondary); margin-bottom: 20px;">
                &copy; <?php echo date('Y'); ?> Eyora - Tous droits réservés.
            </p>
            <div style="display: flex; justify-content: center; gap: 30px; margin-bottom: 20px;">
                <a href="index.php" style="color: var(--text-secondary); text-decoration: none;">Accueil</a>
                <a href="index.php?page=products" style="color: var(--text-secondary); text-decoration: none;">Produits</a>
                <a href="index.php?page=contact" style="color: var(--text-secondary); text-decoration: none;">Contact</a>
            </div>
            <p style="color: var(--text-muted); font-size: 0.9rem;">
                Eyora - Voyez le monde autrement
            </p>
        </div>
    </footer>

    <script>
    // Fonction pour changer la quantité
    function changeQuantity(productId, change) {
        const quantityInput = document.getElementById('quantity-' + productId);
        const displaySpan = document.getElementById('display-' + productId);
        const price = parseFloat(document.querySelector(`#total-${productId}`).textContent.replace(' €', '').replace(',', '.')) / parseInt(quantityInput.value);
        
        let current = parseInt(quantityInput.value) || 1;
        
        // Mettre à jour la quantité
        current += change;
        if (current < 1) current = 1;
        
        // Mettre à jour l'input et l'affichage
        quantityInput.value = current;
        displaySpan.textContent = current;
        
        // Mettre à jour le total pour cet article
        const itemTotal = price * current;
        document.getElementById('total-' + productId).textContent = itemTotal.toFixed(2).replace('.', ',') + ' €';
        
        // Mettre à jour les totaux généraux
        updateTotals();
        
        // Soumettre le formulaire après un délai
        clearTimeout(window.quantityTimeout);
        window.quantityTimeout = setTimeout(() => {
            document.getElementById('form-' + productId).submit();
        }, 1000);
    }
    
    // Fonction pour mettre à jour les totaux généraux
    function updateTotals() {
        let subtotal = 0;
        
        // Calculer le nouveau sous-total
        document.querySelectorAll('.cart-item').forEach(item => {
            const totalElement = item.querySelector('.cart-item-total');
            const itemTotal = parseFloat(totalElement.textContent.replace(' €', '').replace(',', '.'));
            subtotal += itemTotal;
        });
        
        // Mettre à jour les totaux affichés
        document.getElementById('subtotal').textContent = subtotal.toFixed(2).replace('.', ',') + ' €';
        
        const tva = subtotal * 0.20;
        document.getElementById('tva').textContent = tva.toFixed(2).replace('.', ',') + ' €';
        
        const totalTTC = subtotal * 1.20;
        document.getElementById('total-ttc').textContent = totalTTC.toFixed(2).replace('.', ',') + ' €';
    }
    
    // Fonction pour soumettre le formulaire
    function updateQuantity(productId) {
        // La fonction est déjà appelée par changeQuantity
        return false; // Empêche la soumission normale du formulaire
    }
    
    // Fonction checkout
    function checkout() {
        alert('Merci pour votre commande ! Cette fonctionnalité est en cours de développement.\n\nPour le moment, vous pouvez nous contacter pour finaliser votre achat.');
        // window.location.href = 'index.php?page=contact';
    }
    
    // Auto-suppression de la notification après 5 secondes
    <?php if (isset($_SESSION['success'])): ?>
    setTimeout(() => {
        const notification = document.getElementById('successNotification');
        if (notification) {
            notification.style.animation = 'slideOut 0.3s ease-out';
            setTimeout(() => notification.remove(), 300);
        }
    }, 5000);
    <?php endif; ?>
    </script>
</body>
</html>