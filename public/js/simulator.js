document.addEventListener('DOMContentLoaded', function() {
    const PRICE_PER_KG = 500; // Prix par kg en FCFA
    const DELIVERY_FEE = 1000; // Frais de livraison en FCFA

    // Sélecteurs des éléments
    const form = document.getElementById('weightSimulator');
    const quantityInputs = document.querySelectorAll('.quantity-input');
    const totalWeightElement = document.getElementById('totalWeight');
    const totalPriceElement = document.getElementById('totalPrice');

    // Gestionnaire pour les boutons + et -
    document.querySelectorAll('.quantity-btn').forEach(button => {
        button.addEventListener('click', function() {
            const input = this.parentElement.querySelector('.quantity-input');
            const action = this.dataset.action;
            
            let value = parseInt(input.value);
            if (action === 'increase') {
                input.value = value + 1;
            } else if (action === 'decrease' && value > 0) {
                input.value = value - 1;
            }
            
            updateTotals();
        });
    });

    // Gestionnaire pour les inputs de quantité
    quantityInputs.forEach(input => {
        input.addEventListener('change', function() {
            if (this.value < 0) this.value = 0;
            updateTotals();
        });
    });

    // Fonction pour calculer le poids total
    function calculateTotalWeight() {
        let totalWeight = 0;
        quantityInputs.forEach(input => {
            const quantity = parseInt(input.value) || 0;
            const weight = parseFloat(input.dataset.weight);
            totalWeight += quantity * weight;
        });
        return totalWeight;
    }

    // Fonction pour calculer le prix total
    function calculateTotalPrice(weight) {
        const weightPrice = weight * PRICE_PER_KG;
        return weightPrice + DELIVERY_FEE;
    }

    // Fonction pour mettre à jour les totaux
    function updateTotals() {
        const totalWeight = calculateTotalWeight();
        const totalPrice = calculateTotalPrice(totalWeight);
        
        // Mise à jour de l'affichage
        totalWeightElement.textContent = totalWeight.toFixed(1) + ' kg';
        totalPriceElement.textContent = totalPrice.toLocaleString() + ' FCFA';
    }

    // Gestionnaire de soumission du formulaire
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Création du récapitulatif de la commande
        const orderSummary = {
            items: [],
            totalWeight: calculateTotalWeight(),
            totalPrice: calculateTotalPrice(calculateTotalWeight())
        };

        // Collecte des articles sélectionnés
        quantityInputs.forEach(input => {
            const quantity = parseInt(input.value);
            if (quantity > 0) {
                const itemName = input.closest('.article-item').querySelector('h5').textContent;
                const weight = parseFloat(input.dataset.weight);
                orderSummary.items.push({
                    name: itemName,
                    quantity: quantity,
                    weight: weight,
                    totalWeight: quantity * weight
                });
            }
        });

        // Ici, vous pouvez envoyer orderSummary à votre backend
        console.log('Résumé de la commande:', orderSummary);
        
        // Redirection ou traitement supplémentaire
        alert('Commande enregistrée ! Redirection vers le processus de paiement...');
    });

    // Initialisation des totaux au chargement
    updateTotals();
}); 