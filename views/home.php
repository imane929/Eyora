<?php
// Récupérer les données
$pdo = getDB();
$featuredProducts = $pdo->query("SELECT * FROM products WHERE is_featured = 1 LIMIT 6")->fetchAll();
$vueProducts = $pdo->query("SELECT * FROM products WHERE type = 'vue' LIMIT 4")->fetchAll();
$soleilProducts = $pdo->query("SELECT * FROM products WHERE type = 'soleil' LIMIT 4")->fetchAll();
$modeProducts = $pdo->query("SELECT * FROM products WHERE type = 'mode' LIMIT 4")->fetchAll();

$types = [
    'vue' => 'Lunettes de Vue',
    'soleil' => 'Lunettes de Soleil',
    'mode' => 'Lunettes de Mode'
];
?>

<div class="container">
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-content">
            <h1 class="hero-title">Voyez le monde<br>autrement avec Eyora</h1>
            <p class="hero-subtitle">
                Découvrez notre collection exclusive de lunettes de vue, de soleil et de mode.
                Des designs uniques, une qualité exceptionnelle pour votre confort visuel.
            </p>
            <div class="hero-actions">
                <a href="index.php?page=products" class="btn btn-primary">
                    <i class="fas fa-shopping-bag"></i> Découvrir la collection
                </a>
                <a href="#categories" class="btn btn-secondary">
                    <i class="fas fa-list"></i> Parcourir les catégories
                </a>
            </div>
        </div>
        <div class="hero-image">
            <div class="hero-image-wrapper">
                <img src="assets/images/hero-glasses.png" alt="Lunettes Eyora - Collection premium" 
                     onerror="this.onerror=null; this.src='assets/images/default-glasses.jpg';">
                <div class="hero-decor-1"></div>
                <div class="hero-decor-2"></div>
            </div>
        </div>
    </section>

    <!-- Featured Products -->
    <section class="section featured-products">
        <div class="section-header">
            <h2 class="section-title"><i class="fas fa-crown"></i> Nos Produits en Vedette</h2>
            <p class="section-subtitle">Découvrez nos meilleures lunettes sélectionnées pour leur design et qualité</p>
        </div>
        
        <?php if (empty($featuredProducts)): ?>
        <div class="no-results">
            <i class="fas fa-glasses"></i>
            <h3>Aucun produit en vedette</h3>
            <p>Les produits en vedette apparaîtront bientôt.</p>
            <a href="index.php?page=products" class="btn btn-primary">
                <i class="fas fa-store"></i> Visiter la boutique
            </a>
        </div>
        <?php else: ?>
        <div class="products-grid">
            <?php foreach ($featuredProducts as $product): 
                // CORRECTION : Utiliser des valeurs par défaut pour le stock
                $stock = isset($product['stock']) ? $product['stock'] : 10;
            ?>
            <div class="product-card">
                <div class="featured-badge"><i class="fas fa-star"></i> En vedette</div>
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
                    <p class="product-description"><?php echo substr(htmlspecialchars($product['description']), 0, 100); ?>...</p>
                    <div class="product-meta">
                        <div class="product-price-section">
                            <div class="product-price"><?php echo number_format($product['price'], 2); ?> €</div>
                            <div class="tax-info">TVA incluse</div>
                        </div>
                        <div class="product-actions">
                            <button class="btn-view-details" onclick="showProductDetails(<?php echo $product['id']; ?>)">
                                <i class="fas fa-eye"></i> Détails
                            </button>
                            <a href="index.php?action=add_to_cart&id=<?php echo $product['id']; ?>" class="btn-add-cart">
                                <i class="fas fa-shopping-cart"></i> Ajouter
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <div style="text-align: center; margin-top: 60px;">
            <a href="index.php?page=products" class="btn btn-primary">
                <i class="fas fa-arrow-right"></i> Voir tous les produits
            </a>
        </div>
        <?php endif; ?>
    </section>

    <!-- Categories -->
    <section id="categories" class="section categories-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title"><i class="fas fa-layer-group"></i> Nos Catégories</h2>
                <p class="section-subtitle">Explorez notre collection organisée par type pour trouver vos lunettes idéales</p>
            </div>
            
            <div class="categories-grid">
                <div class="category-card" onclick="window.location.href='index.php?page=products&type=vue'">
                    <div class="category-icon"><i class="fas fa-eye"></i></div>
                    <h3>Lunettes de Vue</h3>
                    <p>Correction visuelle avec style et confort optimal. Verres haute définition et montures ergonomiques.</p>
                    <div class="category-count">
                        <i class="fas fa-glasses"></i>
                        <span><?php echo count($vueProducts); ?> modèles disponibles</span>
                        <i class="fas fa-arrow-right"></i>
                    </div>
                </div>
                
                <div class="category-card" onclick="window.location.href='index.php?page=products&type=soleil'">
                    <div class="category-icon"><i class="fas fa-sun"></i></div>
                    <h3>Lunettes de Soleil</h3>
                    <p>Protection UV400 avec style. Designs tendance pour toutes les occasions, des classiques aux plus audacieux.</p>
                    <div class="category-count">
                        <i class="fas fa-glasses"></i>
                        <span><?php echo count($soleilProducts); ?> modèles disponibles</span>
                        <i class="fas fa-arrow-right"></i>
                    </div>
                </div>
                
                <div class="category-card" onclick="window.location.href='index.php?page=products&type=mode'">
                    <div class="category-icon"><i class="fas fa-star"></i></div>
                    <h3>Lunettes de Mode</h3>
                    <p>Accessoires tendance pour compléter votre look. Des pièces uniques créées par nos designers.</p>
                    <div class="category-count">
                        <i class="fas fa-glasses"></i>
                        <span><?php echo count($modeProducts); ?> modèles disponibles</span>
                        <i class="fas fa-arrow-right"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About -->
    <section class="section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title"><i class="fas fa-info-circle"></i> À Propos d'Eyora</h2>
                <p class="section-subtitle">Découvrez notre histoire, nos valeurs et notre engagement pour votre confort visuel</p>
            </div>
            
            <div class="about-content">
                <div class="about-text">
                    <div class="about-mission">
                        <h3><i class="fas fa-bullseye"></i> Notre Mission</h3>
                        <p>Eyora révolutionne l'expérience des lunettes en combinant technologie avancée, design innovant et accessibilité. Nous croyons fermement que tout le monde mérite de voir le monde avec clarté, style et confort optimal.</p>
                    </div>
                    
                    <div class="about-values">
                        <h3><i class="fas fa-heart"></i> Nos Valeurs</h3>
                        <div class="values-list">
                            <div class="value-item">
                                <div class="value-icon"><i class="fas fa-gem"></i></div>
                                <div>
                                    <h4>Qualité Premium</h4>
                                    <p>Matériaux durables, verres haute définition et finitions impeccables</p>
                                </div>
                            </div>
                            <div class="value-item">
                                <div class="value-icon"><i class="fas fa-paint-brush"></i></div>
                                <div>
                                    <h4>Design Innovant</h4>
                                    <p>Collections créées par des designers renommés, styles uniques</p>
                                </div>
                            </div>
                            <div class="value-item">
                                <div class="value-icon"><i class="fas fa-hand-holding-usd"></i></div>
                                <div>
                                    <h4>Accessibilité</h4>
                                    <p>Prix transparents, options pour tous les budgets, facilité d'achat</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="stats-grid">
                        <div class="stat-item">
                            <div class="stat-number">2000+</div>
                            <div class="stat-label">Clients satisfaits</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">50+</div>
                            <div class="stat-label">Modèles uniques</div>
                        </div>
                    </div>
                </div>
                
                <div class="about-image">
                    <div class="about-image-wrapper">
                        <img src="assets/images/about-eyora.jpg" 
                             alt="Boutique Eyora - Espace design et confort"
                             onerror="this.onerror=null; this.src='assets/images/default-glasses.jpg';">
                        <div class="about-overlay">
                            <h4><i class="fas fa-store"></i> Eyora's Boutique</h4>
                            <p><i class="fas fa-map-marker-alt"></i> 123 Avenue ABC, Mohammedia, Maroc</p>
                            <p><i class="fas fa-clock"></i> Lun-Ven: 9h-19h | Sam: 10h-18h</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>