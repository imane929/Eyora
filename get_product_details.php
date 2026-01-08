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

if (!isset($_GET['id'])) {
    echo '<div style="text-align: center; padding: 40px;">Produit non trouvé</div>';
    exit;
}

$product_id = intval($_GET['id']);
$pdo = getDB();

$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch();

if (!$product) {
    echo '<div style="text-align: center; padding: 40px;">Produit non trouvé</div>';
    exit;
}

$types = [
    'vue' => 'Lunettes de Vue',
    'soleil' => 'Lunettes de Soleil',
    'mode' => 'Lunettes de Mode'
];

// CORRECTION : Utiliser des valeurs par défaut si les clés n'existent pas
$stock = isset($product['stock']) ? $product['stock'] : 10;
$stock_status = $stock > 10 ? 'in-stock' : ($stock > 0 ? 'low-stock' : 'out-of-stock');
$stock_text = $stock > 10 ? 'En stock' : ($stock > 0 ? 'Stock faible' : 'Rupture de stock');
?>

<style>
    .product-details-modal {
        display: flex;
        gap: 40px;
        padding: 10px;
    }
    
    .product-details-image {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 20px;
    }
    
    .product-details-image img {
        width: 100%;
        max-height: 400px;
        object-fit: contain;
        border-radius: 12px;
        background: var(--bg-secondary);
        padding: 20px;
    }
    
    .product-thumbnails {
        display: flex;
        gap: 10px;
        overflow-x: auto;
        padding: 10px 0;
    }
    
    .thumbnail {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 8px;
        cursor: pointer;
        border: 2px solid transparent;
        transition: all 0.3s ease;
    }
    
    .thumbnail:hover,
    .thumbnail.active {
        border-color: var(--primary-color);
    }
    
    .product-details-content {
        flex: 1;
    }
    
    .product-details-header {
        margin-bottom: 25px;
        padding-bottom: 20px;
        border-bottom: 1px solid var(--border-color);
    }
    
    .product-category {
        display: inline-block;
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 500;
        margin-bottom: 15px;
    }
    
    .product-category.vue {
        background: rgba(59, 130, 246, 0.1);
        color: #3b82f6;
    }
    
    .product-category.soleil {
        background: rgba(245, 158, 11, 0.1);
        color: #f59e0b;
    }
    
    .product-category.mode {
        background: rgba(139, 92, 246, 0.1);
        color: #8b5cf6;
    }
    
    .product-details-header h2 {
        font-size: 2rem;
        margin: 0 0 15px 0;
        color: var(--text-primary);
    }
    
    .product-price-large {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--primary-color);
        margin: 0;
    }
    
    .tax-info {
        color: var(--text-muted);
        font-size: 0.9rem;
        margin-top: 5px;
    }
    
    .stock-status {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: 600;
        margin-top: 15px;
    }
    
    .stock-status.in-stock {
        background: rgba(16, 185, 129, 0.1);
        color: var(--success-color);
        border: 1px solid rgba(16, 185, 129, 0.3);
    }
    
    .stock-status.low-stock {
        background: rgba(245, 158, 11, 0.1);
        color: var(--warning-color);
        border: 1px solid rgba(245, 158, 11, 0.3);
    }
    
    .stock-status.out-of-stock {
        background: rgba(239, 68, 68, 0.1);
        color: var(--error-color);
        border: 1px solid rgba(239, 68, 68, 0.3);
    }
    
    .product-description-full {
        margin: 30px 0;
        line-height: 1.8;
        color: var(--text-secondary);
    }
    
    .details-list {
        background: var(--bg-secondary);
        padding: 25px;
        border-radius: 15px;
        margin-bottom: 30px;
    }
    
    .detail-item {
        display: grid;
        grid-template-columns: 150px 1fr;
        gap: 20px;
        margin-bottom: 20px;
        padding-bottom: 20px;
        border-bottom: 1px solid var(--border-color);
    }
    
    .detail-item:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }
    
    .detail-label {
        font-weight: 600;
        color: var(--text-secondary);
        font-size: 0.95rem;
    }
    
    .detail-value {
        color: var(--text-primary);
    }
    
    .product-actions-details {
        display: flex;
        gap: 20px;
        margin-top: 30px;
    }
    
    @media (max-width: 768px) {
        .product-details-modal {
            flex-direction: column;
        }
        
        .detail-item {
            grid-template-columns: 1fr;
            gap: 8px;
        }
        
        .product-actions-details {
            flex-direction: column;
        }
    }
</style>

<div class="product-details-modal">
    <div class="product-details-image">
        <img src="assets/images/<?php echo htmlspecialchars($product['image_url']); ?>" 
             alt="<?php echo htmlspecialchars($product['name']); ?>"
             id="mainProductImage"
             onerror="this.onerror=null; this.src='assets/images/default-glasses.jpg';">
        
        <!-- Thumbnails -->
        <div class="product-thumbnails">
            <img src="assets/images/<?php echo htmlspecialchars($product['image_url']); ?>" 
                 alt="<?php echo htmlspecialchars($product['name']); ?>"
                 class="thumbnail active"
                 onclick="document.getElementById('mainProductImage').src = this.src; 
                          document.querySelectorAll('.thumbnail').forEach(t => t.classList.remove('active'));
                          this.classList.add('active');"
                 onerror="this.onerror=null; this.src='assets/images/default-glasses.jpg';">
        </div>
    </div>
    
    <div class="product-details-content">
        <div class="product-details-header">
            <div class="product-category <?php echo $product['type']; ?>">
                <?php echo $types[$product['type']] ?? ucfirst($product['type']); ?>
            </div>
            
            <h2><?php echo htmlspecialchars($product['name']); ?></h2>
            
            <p class="product-price-large"><?php echo number_format($product['price'], 2); ?> €</p>
            <p class="tax-info">TVA incluse</p>
            
            <div class="stock-status <?php echo $stock_status; ?>">
                <i class="fas fa-<?php echo $stock_status === 'in-stock' ? 'check-circle' : 
                                        ($stock_status === 'low-stock' ? 'exclamation-circle' : 'times-circle'); ?>"></i>
                <span><?php echo $stock_text; ?></span>
                <?php if ($stock > 0): ?>
                <span>(<?php echo $stock; ?> disponible(s))</span>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="product-description-full">
            <?php echo nl2br(htmlspecialchars($product['description'])); ?>
        </div>
        
        <div class="details-list">
            <div class="detail-item">
                <div class="detail-label">Référence</div>
                <div class="detail-value">
                    <?php echo htmlspecialchars(isset($product['reference']) ? $product['reference'] : 'REF-' . str_pad($product['id'], 3, '0', STR_PAD_LEFT)); ?>
                </div>
            </div>
            
            <div class="detail-item">
                <div class="detail-label">Matériau</div>
                <div class="detail-value">
                    <?php echo htmlspecialchars(isset($product['material']) ? $product['material'] : 'Acétate'); ?>
                </div>
            </div>
            
            <div class="detail-item">
                <div class="detail-label">Couleur</div>
                <div class="detail-value">
                    <?php echo htmlspecialchars(isset($product['color']) ? $product['color'] : 'Noir'); ?>
                </div>
            </div>
            
            <?php if (isset($product['dimensions']) && !empty($product['dimensions'])): ?>
            <div class="detail-item">
                <div class="detail-label">Dimensions</div>
                <div class="detail-value"><?php echo htmlspecialchars($product['dimensions']); ?></div>
            </div>
            <?php endif; ?>
            
            <div class="detail-item">
                <div class="detail-label">Garantie</div>
                <div class="detail-value">24 mois</div>
            </div>
            
            <div class="detail-item">
                <div class="detail-label">Livraison</div>
                <div class="detail-value">Gratuite en France métropolitaine</div>
            </div>
        </div>
        
        <div class="product-actions-details">
            <?php if ($stock > 0): ?>
            <a href="index.php?action=add_to_cart&id=<?php echo $product['id']; ?>" 
               class="btn btn-primary" style="flex: 1;">
                <i class="fas fa-shopping-cart"></i>
                Ajouter au panier
            </a>
            <?php else: ?>
            <button class="btn btn-primary" disabled style="flex: 1; opacity: 0.7; cursor: not-allowed;">
                <i class="fas fa-times"></i>
                Rupture de stock
            </button>
            <?php endif; ?>
            
            <button onclick="closeModal()" class="btn btn-secondary" style="flex: 1;">
                <i class="fas fa-times"></i>
                Fermer
            </button>
        </div>
    </div>
</div>

<script>
// Mettre à jour l'image principale au clic sur les miniatures
document.querySelectorAll('.thumbnail').forEach(thumbnail => {
    thumbnail.addEventListener('click', function() {
        document.getElementById('mainProductImage').src = this.src;
        document.querySelectorAll('.thumbnail').forEach(t => t.classList.remove('active'));
        this.classList.add('active');
    });
});
</script>