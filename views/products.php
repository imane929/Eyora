<?php
$pdo = getDB();

// Types
$types = [
    'vue' => 'Lunettes de Vue',
    'soleil' => 'Lunettes de Soleil',
    'mode' => 'Lunettes de Mode'
];

// Filtrer par type
$typeFilter = isset($_GET['type']) ? $_GET['type'] : 'all';
$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';

// Construire la requête
$sql = "SELECT * FROM products WHERE 1=1";
$params = [];

if ($typeFilter !== 'all' && array_key_exists($typeFilter, $types)) {
    $sql .= " AND type = ?";
    $params[] = $typeFilter;
}

if (!empty($searchQuery)) {
    $sql .= " AND (name LIKE ? OR description LIKE ?)";
    $searchTerm = "%$searchQuery%";
    $params[] = $searchTerm;
    $params[] = $searchTerm;
}

// Ajouter l'ordre
$sql .= " ORDER BY is_featured DESC, created_at DESC";

// Préparer et exécuter
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();

// Compter les produits par type
$countVue = $pdo->query("SELECT COUNT(*) FROM products WHERE type = 'vue'")->fetchColumn();
$countSoleil = $pdo->query("SELECT COUNT(*) FROM products WHERE type = 'soleil'")->fetchColumn();
$countMode = $pdo->query("SELECT COUNT(*) FROM products WHERE type = 'mode'")->fetchColumn();
$countAll = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
?>

<div class="container">
    <!-- Hero Products -->
    <section class="products-hero">
        <h1>Notre Collection de Lunettes</h1>
        <p>Découvrez nos lunettes de vue, de soleil et de mode. Qualité, design et confort pour tous.</p>
    </section>

    <!-- Search & Filters -->
    <div class="search-container">
        <form method="GET" action="index.php" class="search-form">
            <input type="hidden" name="page" value="products">
            <input type="text" 
                   name="search" 
                   class="search-input" 
                   placeholder="Rechercher des lunettes..."
                   value="<?php echo htmlspecialchars($searchQuery); ?>">
            <button type="submit" class="search-button">
                <i class="fas fa-search"></i> Rechercher
            </button>
        </form>
    </div>

    <div class="filters">
        <a href="index.php?page=products&type=all" 
           class="filter-btn <?php echo $typeFilter === 'all' ? 'active' : ''; ?>">
            Tous (<?php echo $countAll; ?>)
        </a>
        <a href="index.php?page=products&type=vue" 
           class="filter-btn <?php echo $typeFilter === 'vue' ? 'active' : ''; ?>">
            Lunettes de Vue (<?php echo $countVue; ?>)
        </a>
        <a href="index.php?page=products&type=soleil" 
           class="filter-btn <?php echo $typeFilter === 'soleil' ? 'active' : ''; ?>">
            Lunettes de Soleil (<?php echo $countSoleil; ?>)
        </a>
        <a href="index.php?page=products&type=mode" 
           class="filter-btn <?php echo $typeFilter === 'mode' ? 'active' : ''; ?>">
            Lunettes de Mode (<?php echo $countMode; ?>)
        </a>
    </div>

    <!-- Stats -->
    <div class="products-stats">
        <div class="stat">
            <i class="fas fa-filter"></i>
            <span>Filtré : <?php echo $types[$typeFilter] ?? 'Tous les produits'; ?></span>
        </div>
        <div class="stat">
            <i class="fas fa-cube"></i>
            <span><?php echo count($products); ?> produit(s) trouvé(s)</span>
        </div>
    </div>

<!-- Products Grid -->
<?php if (empty($products)): ?>
<div class="no-results">
    <i class="fas fa-search"></i>
    <h3>Aucun produit trouvé</h3>
    <p>Nous n'avons trouvé aucun produit correspondant à votre recherche.</p>
    <a href="index.php?page=products" class="btn btn-primary">
        <i class="fas fa-redo"></i> Voir tous les produits
    </a>
</div>
<?php else: ?>
<div class="products-grid">
    <?php foreach ($products as $product): 
        // CORRECTION : Utiliser des valeurs par défaut si les clés n'existent pas
        $stock = isset($product['stock']) ? $product['stock'] : 10;
        $stock_status = $stock > 10 ? 'in-stock' : ($stock > 0 ? 'low-stock' : 'out-of-stock');
        $stock_text = $stock > 10 ? 'En stock' : ($stock > 0 ? 'Stock faible' : 'Rupture');
    ?>
    <div class="product-card">
        <?php if (isset($product['is_featured']) && $product['is_featured']): ?>
        <div class="featured-badge"><i class="fas fa-star"></i> En vedette</div>
        <?php endif; ?>
        
        <div class="stock-badge <?php echo $stock_status; ?>">
            <?php echo $stock_text; ?>
        </div>
        
        <div class="product-image">
            <img src="assets/images/<?php echo htmlspecialchars($product['image_url']); ?>" 
                 alt="<?php echo htmlspecialchars($product['name']); ?>"
                 onerror="this.onerror=null; this.src='assets/images/default-glasses.jpg';">
        </div>
        
        <div class="product-info">
            <span class="type-badge <?php echo $product['type']; ?>">
                <?php echo $types[$product['type']] ?? ucfirst($product['type']); ?>
            </span>
            
            <h3 class="product-name"><?php echo htmlspecialchars($product['name']); ?></h3>
            
            <p class="product-description">
                <?php echo substr(htmlspecialchars($product['description']), 0, 100); ?>...
            </p>
            
            <div class="product-meta">
                <div class="product-price-section">
                    <div class="product-price"><?php echo number_format($product['price'], 2); ?> €</div>
                    <div class="tax-info">TVA incluse</div>
                    <?php if ($stock > 0): ?>
                    <div class="stock-info">Stock : <?php echo $stock; ?> unités</div>
                    <?php endif; ?>
                </div>
                
                <div class="product-actions">
                    <button class="btn-view-details" onclick="showProductDetails(<?php echo $product['id']; ?>)">
                        <i class="fas fa-eye"></i> Détails
                    </button>
                    
                    <?php if ($stock > 0): ?>
                    <button class="btn-add-cart" onclick="addToCart(<?php echo $product['id']; ?>, '<?php echo htmlspecialchars($product['name']); ?>')">
                        <i class="fas fa-shopping-cart"></i> Ajouter
                    </button>

                    <script>
                        function addToCart(productId, productName) {
                        // Envoyer la requête AJAX
                        fetch(`index.php?action=add_to_cart&id=${productId}`)
                            .then(response => response.text())
                            .then(() => {
                                // Afficher une notification
                                showNotification(productName + ' ajouté au panier!', 'success');

                                // Mettre à jour le compteur du panier
                                updateCartCount();
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                showNotification('Erreur lors de l\'ajout au panier', 'error');
                            });
                        }
                        function updateCartCount() {
                            // Mettre à jour le badge du panier
                            const cartBadge = document.querySelector('.panier-badge');
                            if (cartBadge) {
                                let current = parseInt(cartBadge.textContent) || 0;
                                cartBadge.textContent = current + 1;
                            }
                        }
                    </script>

                    <?php else: ?>
                    <button class="btn-add-cart disabled" disabled>
                        <i class="fas fa-times"></i> Rupture
                    </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

    <!-- Categories Overview -->
    <section class="categories-overview">
        <h3>Explorez par catégorie</h3>
        <div class="category-links">
            <a href="index.php?page=products&type=vue" class="category-link">
                <i class="fas fa-eye"></i>
                <span>Lunettes de Vue</span>
                <small><?php echo $countVue; ?> modèles</small>
            </a>
            <a href="index.php?page=products&type=soleil" class="category-link">
                <i class="fas fa-sun"></i>
                <span>Lunettes de Soleil</span>
                <small><?php echo $countSoleil; ?> modèles</small>
            </a>
            <a href="index.php?page=products&type=mode" class="category-link">
                <i class="fas fa-star"></i>
                <span>Lunettes de Mode</span>
                <small><?php echo $countMode; ?> modèles</small>
            </a>
            <a href="index.php?page=products" class="category-link">
                <i class="fas fa-glasses"></i>
                <span>Toutes les lunettes</span>
                <small><?php echo $countAll; ?> modèles</small>
            </a>
        </div>
    </section>
</div>