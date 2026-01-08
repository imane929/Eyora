// animations.js - Améliorations UX
document.addEventListener('DOMContentLoaded', function() {
    // Animation d'entrée pour les produits
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('fade-in-up');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);
    
    // Observer tous les produits
    document.querySelectorAll('.product-card').forEach(card => {
        observer.observe(card);
    });
    
    // Animation des filtres
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            // Retirer la classe active de tous les boutons
            document.querySelectorAll('.filter-btn').forEach(b => {
                b.classList.remove('active');
            });
            // Ajouter la classe active au bouton cliqué
            this.classList.add('active');
            
            // Animation du changement
            this.style.transform = 'scale(0.95)';
            setTimeout(() => {
                this.style.transform = '';
            }, 150);
        });
    });
    
    // Animation d'ajout au panier
    document.querySelectorAll('.btn-add-cart').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            
            if (this.disabled) return;
            
            const productId = this.dataset.id;
            const productCard = this.closest('.product-card');
            
            // Animation
            this.innerHTML = '<i class="fas fa-check"></i> Ajouté';
            this.classList.add('added');
            
            // Effet de "pulse" sur la carte
            productCard.style.animation = 'pulse 0.5s ease';
            
            setTimeout(() => {
                productCard.style.animation = '';
            }, 500);
            
            // Réinitialiser après 2 secondes
            setTimeout(() => {
                this.innerHTML = '<i class="fas fa-shopping-cart"></i> Ajouter';
                this.classList.remove('added');
            }, 2000);
        });
    });
});

// Animation CSS supplémentaire
const style = document.createElement('style');
style.textContent = `
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.02); }
        100% { transform: scale(1); }
    }
    
    .btn-add-cart.added {
        background: linear-gradient(135deg, #10b981, #34d399) !important;
    }
    
    .product-card {
        opacity: 0;
        transform: translateY(20px);
        transition: opacity 0.6s ease, transform 0.6s ease;
    }
    
    .product-card.fade-in-up {
        opacity: 1;
        transform: translateY(0);
    }
`;
document.head.appendChild(style);