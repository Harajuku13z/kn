document.addEventListener('DOMContentLoaded', function() {
    // Initialisation des éléments du simulateur
    initSimulator();
    
    // Animation au défilement
    initScrollAnimation();
    
    // Animation des cartes de prix et articles
    initCardAnimations();
});

function initSimulator() {
    const form = document.getElementById('priceForm');
    if (!form) return;

    const pieceCalculator = document.getElementById('pieceCalculator');
    const weightCalculator = document.getElementById('weightCalculator');
    const weightInput = document.getElementById('weightInput');
    
    // Gestion du mode de calcul
    document.querySelectorAll('input[name="calculMode"]').forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'piece') {
                pieceCalculator.style.display = 'block';
                weightCalculator.style.display = 'none';
            } else {
                pieceCalculator.style.display = 'none';
                weightCalculator.style.display = 'block';
            }
            calculateTotal(weightInput);
        });
    });

    // Gestion des quantités
    document.querySelectorAll('.btn-decrease, .btn-increase').forEach(button => {
        button.addEventListener('click', function() {
            const input = this.parentElement.querySelector('input');
            let value = parseInt(input.value);
            
            if (this.classList.contains('btn-decrease')) {
                value = Math.max(0, value - 1);
            } else {
                value++;
            }
            
            input.value = value;
            calculateTotal(weightInput);
        });
    });

    // Gestion des changements de quantité manuelle
    document.querySelectorAll('.article-count').forEach(input => {
        input.addEventListener('change', () => calculateTotal(weightInput));
        input.addEventListener('input', () => calculateTotal(weightInput));
    });

    // Gestion du poids
    if (weightInput) {
        weightInput.addEventListener('change', () => calculateTotal(weightInput));
        weightInput.addEventListener('input', () => calculateTotal(weightInput));
    }

    // Gestion des changements de service
    const serviceType = document.getElementById('serviceType');
    if (serviceType) {
        serviceType.addEventListener('change', () => calculateTotal(weightInput));
    }

    // Gestion des changements de délai
    document.querySelectorAll('input[name="delivery"]').forEach(radio => {
        radio.addEventListener('change', () => calculateTotal(weightInput));
    });

    // Calcul initial
    calculateTotal(weightInput);
}

function calculateTotal(weightInput) {
    let subtotal = 0;
    const calculMode = document.querySelector('input[name="calculMode"]:checked').value;
    
    if (calculMode === 'piece') {
        // Calcul par pièce
        document.querySelectorAll('.article-count').forEach(input => {
            const quantity = parseInt(input.value) || 0;
            const price = parseInt(input.dataset.price) || 0;
            subtotal += quantity * price;
        });
    } else {
        // Calcul par kilo
        const weight = parseFloat(weightInput.value) || 0;
        subtotal = weight * 500; // 500 FCFA par kilo
    }

    // Application du multiplicateur de service
    const serviceSelect = document.getElementById('serviceType');
    const serviceMultiplier = parseFloat(serviceSelect.options[serviceSelect.selectedIndex].dataset.multiplier);
    const servicePrice = subtotal * (serviceMultiplier - 1);

    // Application du multiplicateur de livraison
    const deliveryMultiplier = parseFloat(document.querySelector('input[name="delivery"]:checked').dataset.multiplier);
    const deliveryPrice = subtotal * (deliveryMultiplier - 1);

    // Calcul du total
    const total = subtotal + servicePrice + deliveryPrice;

    // Mise à jour de l'affichage avec animation
    updatePriceDisplay('subtotalPrice', subtotal);
    updatePriceDisplay('servicePrice', servicePrice);
    updatePriceDisplay('deliveryPrice', deliveryPrice);
    updatePriceDisplay('totalPrice', total);
}

function updatePriceDisplay(elementId, value) {
    const element = document.getElementById(elementId);
    if (element) {
        // Animation du changement de prix
        element.style.transition = 'all 0.3s ease';
        element.style.transform = 'scale(1.1)';
        element.textContent = value.toLocaleString() + ' FCFA';
        
        setTimeout(() => {
            element.style.transform = 'scale(1)';
        }, 300);
    }
}

function initScrollAnimation() {
    const animateOnScroll = function() {
        const elements = document.querySelectorAll('.animate-on-scroll');
        elements.forEach(element => {
            const elementPosition = element.getBoundingClientRect().top;
            const screenPosition = window.innerHeight / 1.3;
            
            if (elementPosition < screenPosition) {
                element.classList.add('animate');
            }
        });
    }
    
    // Exécuter l'animation au chargement initial
    animateOnScroll();
    
    // Exécuter l'animation lors du défilement
    window.addEventListener('scroll', animateOnScroll);
}

function initCardAnimations() {
    // Animation des cartes de prix
    const priceCards = document.querySelectorAll('.price-card');
    if (priceCards.length > 0) {
        priceCards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-10px)';
                this.style.boxShadow = '0 10px 20px rgba(0,0,0,0.1)';
            });

            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = '0 5px 15px rgba(0,0,0,0.05)';
            });
        });
    }

    // Animation des articles au survol
    const articleItems = document.querySelectorAll('.article-item');
    if (articleItems.length > 0) {
        articleItems.forEach(item => {
            item.addEventListener('mouseenter', function() {
                this.style.transform = 'translateX(5px)';
                this.style.borderColor = '#461871';
                this.style.backgroundColor = '#f8f9fa';
            });

            item.addEventListener('mouseleave', function() {
                this.style.transform = 'translateX(0)';
                this.style.borderColor = '#dee2e6';
                this.style.backgroundColor = 'white';
            });
        });
    }
} 