    </div> <!-- Fin .main-content -->
    
    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <!-- Section logo -->
                <div class="footer-section">
                    <div class="footer-logo">
                        <i class="fas fa-glasses"></i>
                        <div>
                            <span>Eyora</span>
                            <p>Voyez le monde autrement</p>
                        </div>
                    </div>
                    
                    <p class="footer-description">
                        Eyora offre des lunettes de qualité supérieure avec des designs uniques 
                        pour votre confort et style quotidien.
                    </p>
                    
                    <div class="social-links">
                        <a href="https://facebook.com" target="_blank" class="social-link" aria-label="Facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="https://instagram.com" target="_blank" class="social-link" aria-label="Instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="https://twitter.com" target="_blank" class="social-link" aria-label="Twitter">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="https://pinterest.com" target="_blank" class="social-link" aria-label="Pinterest">
                            <i class="fab fa-pinterest"></i>
                        </a>
                    </div>
                </div>
                
                <!-- Section navigation -->
                <div class="footer-section">
                    <h3><i class="fas fa-compass"></i> Navigation</h3>
                    <ul class="footer-links">
                        <li><a href="index.php?page=home"><i class="fas fa-chevron-right"></i> Accueil</a></li>
                        <li><a href="index.php?page=products"><i class="fas fa-chevron-right"></i> Tous les produits</a></li>
                        <li><a href="index.php?page=products&type=vue"><i class="fas fa-chevron-right"></i> Lunettes de Vue</a></li>
                        <li><a href="index.php?page=products&type=soleil"><i class="fas fa-chevron-right"></i> Lunettes de Soleil</a></li>
                        <li><a href="index.php?page=products&type=mode"><i class="fas fa-chevron-right"></i> Lunettes de Mode</a></li>
                        <li><a href="index.php?page=contact"><i class="fas fa-chevron-right"></i> Contactez-nous</a></li>
                        <li><a href="panier.php"><i class="fas fa-chevron-right"></i> Mon panier</a></li>
                    </ul>
                </div>
                
                <!-- Section catégories -->
                <div class="footer-section">
                    <h3><i class="fas fa-tags"></i> Catégories</h3>
                    <ul class="footer-links">
                        <li><a href="index.php?page=products&type=vue" class="category-link vue"><i class="fas fa-eye"></i> Lunettes de Vue</a></li>
                        <li><a href="index.php?page=products&type=soleil" class="category-link soleil"><i class="fas fa-sun"></i> Lunettes de Soleil</a></li>
                        <li><a href="index.php?page=products&type=mode" class="category-link mode"><i class="fas fa-star"></i> Lunettes de Mode</a></li>
                        <li><a href="index.php?page=products" class="category-link all"><i class="fas fa-glasses"></i> Tous les produits</a></li>
                    </ul>
                </div>
                
                <!-- Section contact -->
                <div class="footer-section">
                    <h3><i class="fas fa-headset"></i> Contact</h3>
                    <ul class="footer-contact">
                        <li>
                            <i class="fas fa-map-marker-alt"></i>
                            <div>
                                <strong>Adresse</strong>
                                <span>123 Avenue ABC, Maroc</span>
                            </div>
                        </li>
                        <li>
                            <i class="fas fa-phone"></i>
                            <div>
                                <strong>Téléphone</strong>
                                <span>+212 6 23 45 67 89</span>
                            </div>
                        </li>
                        <li>
                            <i class="fas fa-envelope"></i>
                            <div>
                                <strong>Email</strong>
                                <span>contact@eyora.com</span>
                            </div>
                        </li>
                        <li>
                            <i class="fas fa-clock"></i>
                            <div>
                                <strong>Horaires</strong>
                                <span>Lun-Ven: 9h-19h<br>Sam: 10h-18h</span>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            
            <!-- Bas du footer -->
            <div class="footer-bottom">
                <div class="footer-links-bottom">
                    <a href="#">Conditions Générales</a>
                    <a href="#">Politique de confidentialité</a>
                    <a href="#">Mentions légales</a>
                    <a href="#">CGV</a>
                </div>
                
                <p>&copy; <?php echo date('Y'); ?> Eyora - Tous droits réservés.</p>
                <p class="footer-credit"></p>
            </div>
        </div>
    </footer>
    
    <!-- Bouton retour en haut -->
    <button id="backToTop" class="back-to-top" aria-label="Retour en haut">
        <i class="fas fa-chevron-up"></i>
    </button>
    
    <!-- Modal détails produit -->
    <div id="productModal" class="modal">
        <div class="modal-content">
            <span class="close-modal" onclick="closeModal()">&times;</span>
            <div id="productDetails"></div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="js/theme-switcher.js"></script>
    <script src="js/main.js"></script>
    
    <?php if ($currentPage === 'home' || $currentPage === 'products'): ?>
    <script>
    // Fonction pour afficher les détails produit
    function showProductDetails(productId) {
        fetch(`get_product_details.php?id=${productId}`)
            .then(response => {
                if (!response.ok) throw new Error('Erreur réseau');
                return response.text();
            })
            .then(html => {
                document.getElementById('productDetails').innerHTML = html;
                document.getElementById('productModal').style.display = 'block';
                document.body.style.overflow = 'hidden';
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('productDetails').innerHTML = `
                    <div class="product-details-error">
                        <i class="fas fa-exclamation-triangle"></i>
                        <h3>Détails non disponibles</h3>
                        <p>Les détails du produit ne sont pas disponibles pour le moment.</p>
                        <button onclick="closeModal()" class="btn btn-primary">
                            <i class="fas fa-times"></i> Fermer
                        </button>
                    </div>
                `;
                document.getElementById('productModal').style.display = 'block';
                document.body.style.overflow = 'hidden';
            });
    }

    function closeModal() {
        document.getElementById('productModal').style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    // Notifications
    <?php if (isset($_SESSION['success'])): ?>
    setTimeout(() => {
        showNotification("<?php echo $_SESSION['success']; ?>", 'success');
        <?php unset($_SESSION['success']); ?>
    }, 500);
    <?php endif; ?>

    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'info-circle'}"></i>
            <span>${message}</span>
            <button class="close-notif">&times;</button>
        `;
        
        document.body.appendChild(notification);
        
        notification.querySelector('.close-notif').onclick = () => {
            notification.remove();
        };
        
        setTimeout(() => {
            if (notification.parentNode) {
                notification.style.animation = 'slideOut 0.3s ease-out';
                setTimeout(() => notification.remove(), 300);
            }
        }, 5000);
    }
    </script>
    <?php endif; ?>
</body>
</html>