{{-- Blade template for Simple Order Script --}}
<div>
    <!-- The whole future lies in uncertainty: live immediately. - Seneca -->
</div>

<script>
// Script entièrement côté client - Zéro AJAX
document.addEventListener('DOMContentLoaded', function() {
    console.log('Script amélioré chargé - Version sans AJAX');
    
    // 1. PRÉCHARGEMENT DES DONNÉES
    // ---------------------------

    const deliveryFees = window.deliveryFeesData || [];
    console.log('Frais de livraison chargés:', deliveryFees.length);
    
   
    const availableVouchers = window.availableVouchers || [];
    console.log('Bons de livraison disponibles:', availableVouchers.length);

    // 2. NAVIGATION ENTRE ÉTAPES
    // ---------------------------
    let currentStep = 1;
    const totalSteps = 5;
    const orderForm = document.getElementById('orderForm');
    
    // Utiliser à la fois les IDs et les classes pour une meilleure compatibilité
    const prevBtn = document.getElementById('prevStepBtn') || document.querySelector('.prev-step');
    const nextBtn = document.getElementById('nextStepBtn') || document.querySelector('.next-step');
    
    console.log('Elements de navigation:', { 
        formulaire: orderForm, 
        precedent: prevBtn, 
        suivant: nextBtn 
    });
    
    const steps = document.querySelectorAll('.form-step');
    const progressSteps = document.querySelectorAll('.step-custom');
    const progressLines = document.querySelectorAll('.progress-line-custom');
    
    // Fonction pour mettre à jour l'affichage des étapes
    function updateStep() {
        console.log('Mise à jour étape:', currentStep);
        
        // Masquer toutes les étapes
        steps.forEach(step => step.classList.remove('active'));
        
        // Afficher l'étape courante
        steps[currentStep - 1].classList.add('active');
        
        // Mettre à jour l'affichage des boutons de navigation
        if (currentStep === 1) {
            if (prevBtn) prevBtn.style.display = 'none';
        } else {
            if (prevBtn) prevBtn.style.display = 'block';
        }
        
        if (currentStep === totalSteps) {
            if (nextBtn) nextBtn.innerHTML = 'Finaliser la commande <i class="bi bi-check-circle ms-2"></i>';
        } else {
            if (nextBtn) nextBtn.innerHTML = 'Suivant <i class="bi bi-arrow-right ms-2"></i>';
        }
        
        // Mettre à jour la barre de progression
        progressSteps.forEach((step, index) => {
            if (index < currentStep) {
                step.classList.add('completed');
                step.classList.remove('active');
            } else if (index === currentStep - 1) {
                step.classList.add('active');
                step.classList.remove('completed');
            } else {
                step.classList.remove('active', 'completed');
            }
        });
        
        progressLines.forEach((line, index) => {
            if (index < currentStep - 1) {
                line.classList.add('active');
            } else {
                line.classList.remove('active');
            }
        });
    }
    
    // Fonction directe de navigation
    function goToNextStep() {
        console.log('Navigation vers étape suivante');
        if (currentStep < totalSteps) {
            currentStep++;
            updateStep();
            window.scrollTo(0, 0);
        } else {
            // Dernière étape - Soumettre le formulaire
            console.log('Soumission du formulaire');
            if (orderForm) orderForm.submit();
        }
    }
    
    function goToPrevStep() {
        console.log('Navigation vers étape précédente');
        if (currentStep > 1) {
            currentStep--;
            updateStep();
            window.scrollTo(0, 0);
        }
    }
    
    // Navigation vers l'étape suivante - Implémentation simplifiée
    if (nextBtn) {
        console.log('Configuration du bouton Suivant');
        
        // Méthode 1: Recréer le bouton pour éliminer les écouteurs précédents
        const newNextBtn = nextBtn.cloneNode(true);
        if (nextBtn.parentNode) {
            nextBtn.parentNode.replaceChild(newNextBtn, nextBtn);
        }
        
        // Méthode 2: Ajouter un gestionnaire d'événement onclick
        try {
            document.getElementById('nextStepBtn').onclick = goToNextStep;
        } catch (e) {
            console.error('Erreur ajout gestionnaire nextStepBtn:', e);
        }
        
        // Méthode 3: Ajouter le gestionnaire via addEventListener
        try {
            document.querySelectorAll('.next-step').forEach(btn => {
                btn.addEventListener('click', goToNextStep);
            });
        } catch (e) {
            console.error('Erreur ajout écouteur via querySelectorAll:', e);
        }
        
        // Méthode 4: Forcer un gestionnaire direct sur le document pour les clics sur le bouton
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('next-step') || 
                e.target.closest('.next-step') || 
                e.target.id === 'nextStepBtn' || 
                e.target.closest('#nextStepBtn')) {
                console.log('Bouton Suivant capturé via délégation d\'événement');
                goToNextStep();
            }
        });
    } else {
        console.error('Bouton Suivant non trouvé!');
    }
    
    // Navigation vers l'étape précédente
    if (prevBtn) {
        console.log('Configuration du bouton Précédent');
        
        // Méthode 1: Recréer le bouton pour éliminer les écouteurs précédents
        const newPrevBtn = prevBtn.cloneNode(true);
        if (prevBtn.parentNode) {
            prevBtn.parentNode.replaceChild(newPrevBtn, prevBtn);
        }
        
        // Méthode 2: Ajouter un gestionnaire d'événement onclick
        try {
            document.getElementById('prevStepBtn').onclick = goToPrevStep;
        } catch (e) {
            console.error('Erreur ajout gestionnaire prevStepBtn:', e);
        }
        
        // Méthode 3: Ajouter le gestionnaire via addEventListener
        try {
            document.querySelectorAll('.prev-step').forEach(btn => {
                btn.addEventListener('click', goToPrevStep);
            });
        } catch (e) {
            console.error('Erreur ajout écouteur via querySelectorAll:', e);
        }
    } else {
        console.error('Bouton Précédent non trouvé!');
    }
    
    // 3. GESTION DES ARTICLES
    // -----------------------
    // Gestion des quantités d'articles
    const quantityButtons = document.querySelectorAll('.quantity-plus, .quantity-minus');
    quantityButtons.forEach(button => {
        button.addEventListener('click', function() {
            const input = this.parentNode.querySelector('input');
            let value = parseInt(input.value || 0);
            
            if (this.classList.contains('quantity-plus')) {
                value++;
            } else if (this.classList.contains('quantity-minus') && value > 0) {
                value--;
            }
            
            input.value = value;
            calculateTotals();
        });
    });
    
    // 4. GESTION DES ADRESSES
    // -----------------------
    // Même adresse pour la livraison
    const sameAddressCheckbox = document.getElementById('same_address_for_delivery');
    if (sameAddressCheckbox) {
        sameAddressCheckbox.addEventListener('change', function() {
            const deliveryAddressFields = document.getElementById('delivery-address-fields');
            if (this.checked) {
                deliveryAddressFields.style.display = 'none';
                document.getElementById('delivery_address_id').removeAttribute('required');
            } else {
                deliveryAddressFields.style.display = 'block';
                document.getElementById('delivery_address_id').setAttribute('required', 'required');
            }
            calculateTotals(); // Recalculer les frais si l'adresse change
        });
    }
    
    // 5. CALCUL DES FRAIS DE LIVRAISON
    // --------------------------------
    function getDeliveryFee(district) {
        // Rechercher dans les données préchargées
        const feeInfo = deliveryFees.find(fee => fee.district.toLowerCase() === district.toLowerCase());
        return feeInfo ? parseInt(feeInfo.fee) : 1000; // Valeur par défaut si non trouvé
    }
    
    function calculateTotals() {
        let totalWeight = 0;
        let totalPrice = 0;
        const pricePerKg = window.laundryPricePerKg || 1000;
        
        // Calcul du poids et prix des articles
        document.querySelectorAll('.item-quantity').forEach(input => {
            const quantity = parseInt(input.value || 0);
            const weight = parseFloat(input.dataset.weight || 0);
            const itemWeight = quantity * weight;
            
            totalWeight += itemWeight;
            totalPrice += itemWeight * pricePerKg;
        });
        
        // Calcul des frais de livraison
        let pickupFee = 1000; // Valeur par défaut
        let deliveryFee = 1000; // Valeur par défaut
        
        // Extraction du quartier de collecte
        const collectionAddressSelect = document.getElementById('collection_address_id');
        let collectionDistrict = '';
        
        if (collectionAddressSelect && collectionAddressSelect.selectedIndex > 0) {
            const selectedOption = collectionAddressSelect.options[collectionAddressSelect.selectedIndex];
            const addressText = selectedOption.textContent;
            const matches = addressText.match(/,\s*([^,]+)$/);
            if (matches && matches.length > 1) {
                collectionDistrict = matches[1].trim();
                pickupFee = getDeliveryFee(collectionDistrict);
            }
        }
        
        // Extraction du quartier de livraison
        const sameAddress = document.getElementById('same_address_for_delivery').checked;
        let deliveryDistrict = '';
        
        if (sameAddress) {
            deliveryDistrict = collectionDistrict;
            deliveryFee = getDeliveryFee(deliveryDistrict);
        } else {
            const deliveryAddressSelect = document.getElementById('delivery_address_id');
            if (deliveryAddressSelect && deliveryAddressSelect.selectedIndex > 0) {
                const selectedOption = deliveryAddressSelect.options[deliveryAddressSelect.selectedIndex];
                const addressText = selectedOption.textContent;
                const matches = addressText.match(/,\s*([^,]+)$/);
                if (matches && matches.length > 1) {
                    deliveryDistrict = matches[1].trim();
                    deliveryFee = getDeliveryFee(deliveryDistrict);
                }
            }
        }
        
        // Calculer le total des frais de livraison
        let totalDeliveryFee = pickupFee + deliveryFee;
        
        // Si un bon de livraison est appliqué, annuler les frais
        const useVoucherCheckbox = document.getElementById('use_delivery_voucher');
        const voucherStatus = document.getElementById('voucher-status');
        if (useVoucherCheckbox && useVoucherCheckbox.checked && voucherStatus && voucherStatus.dataset.valid === 'true') {
            totalDeliveryFee = 0;
        }
        
        // Prix total
        const grandTotal = totalPrice + totalDeliveryFee;
        
        // Debug des informations de district et frais
        if (document.getElementById('debug-collection-district')) {
            document.getElementById('debug-collection-district').textContent = collectionDistrict || 'Non défini';
        }
        if (document.getElementById('debug-delivery-district')) {
            document.getElementById('debug-delivery-district').textContent = deliveryDistrict || 'Non défini';
        }
        if (document.getElementById('debug-api-response')) {
            document.getElementById('debug-api-response').textContent = 
                JSON.stringify({
                    pickup_fee: pickupFee,
                    delivery_fee: deliveryFee,
                    total_fee: totalDeliveryFee,
                    formatted_total: new Intl.NumberFormat('fr-FR').format(totalDeliveryFee) + ' FCFA'
                });
        }
        
        // Mise à jour des affichages
        try {
            // Simulateur de prix
            if (document.getElementById('totalWeightDisplay')) {
                document.getElementById('totalWeightDisplay').textContent = totalWeight.toFixed(2);
            }
            
            if (document.getElementById('articlesPrice')) {
                document.getElementById('articlesPrice').textContent = new Intl.NumberFormat('fr-FR').format(Math.round(totalPrice));
            }
            
            if (document.getElementById('deliveryFeeDisplay')) {
                document.getElementById('deliveryFeeDisplay').textContent = new Intl.NumberFormat('fr-FR').format(totalDeliveryFee);
            }
            
            // Afficher/masquer le badge "Gratuit"
            if (document.getElementById('freeDeliveryBadge')) {
                document.getElementById('freeDeliveryBadge').style.display = totalDeliveryFee === 0 ? 'inline-block' : 'none';
            }
            
            if (document.getElementById('estimatedPriceDisplay')) {
                document.getElementById('estimatedPriceDisplay').textContent = new Intl.NumberFormat('fr-FR').format(Math.round(grandTotal));
            }
            
            // Récapitulatif
            if (document.getElementById('summary-weight')) {
                document.getElementById('summary-weight').textContent = totalWeight.toFixed(2);
            }
            
            if (document.getElementById('summary-articles-price')) {
                document.getElementById('summary-articles-price').textContent = new Intl.NumberFormat('fr-FR').format(Math.round(totalPrice));
            }
            
            if (document.getElementById('summary-delivery-fee')) {
                document.getElementById('summary-delivery-fee').textContent = new Intl.NumberFormat('fr-FR').format(totalDeliveryFee);
            }
            
            // Afficher/masquer le badge "Gratuit" dans récapitulatif
            if (document.getElementById('summary-free-delivery-badge')) {
                document.getElementById('summary-free-delivery-badge').style.display = totalDeliveryFee === 0 ? 'inline-block' : 'none';
            }
            
            if (document.getElementById('summary-total')) {
                document.getElementById('summary-total').textContent = new Intl.NumberFormat('fr-FR').format(Math.round(grandTotal));
            }
            
            // Paiement
            if (document.getElementById('payment-total')) {
                document.getElementById('payment-total').textContent = new Intl.NumberFormat('fr-FR').format(Math.round(grandTotal));
            }
            
            if (document.getElementById('payment-articles-price')) {
                document.getElementById('payment-articles-price').textContent = new Intl.NumberFormat('fr-FR').format(Math.round(totalPrice));
            }
            
            if (document.getElementById('payment-delivery-fee')) {
                document.getElementById('payment-delivery-fee').textContent = new Intl.NumberFormat('fr-FR').format(totalDeliveryFee);
            }
            
            // Afficher/masquer le badge "Gratuit" dans paiement
            if (document.getElementById('payment-free-delivery-badge')) {
                document.getElementById('payment-free-delivery-badge').style.display = totalDeliveryFee === 0 ? 'inline-block' : 'none';
            }
        } catch (error) {
            console.error('Erreur mise à jour totaux:', error);
        }
    }
    
    // 6. GESTION DES BONS DE LIVRAISON
    // --------------------------------
    // Afficher le bouton uniquement si l'utilisateur a des bons disponibles
    const useVoucherSection = document.getElementById('voucher-section');
    if (useVoucherSection) {
        if (availableVouchers && availableVouchers.length > 0) {
            useVoucherSection.style.display = 'block';
            
            // Afficher le nombre de bons disponibles
            const voucherCountBadge = document.getElementById('voucher-count');
            if (voucherCountBadge) {
                voucherCountBadge.textContent = availableVouchers.length;
            }
            
            // Remplir la liste des bons disponibles
            const vouchersList = document.getElementById('vouchers-list');
            if (vouchersList) {
                let html = '';
                availableVouchers.forEach(voucher => {
                    html += `
                        <button type="button" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center voucher-item" 
                                data-code="${voucher.code}">
                            <div>
                                <strong>${voucher.code}</strong>
                                <small class="d-block text-muted">${voucher.description || 'Bon de livraison'}</small>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-info rounded-pill">${voucher.remaining_deliveries} restante(s)</span>
                                <small class="d-block text-muted">${voucher.valid_until_formatted}</small>
                            </div>
                        </button>
                    `;
                });
                vouchersList.innerHTML = html;
                
                // Ajouter des écouteurs pour les boutons de bon
                document.querySelectorAll('.voucher-item').forEach(item => {
                    item.addEventListener('click', function() {
                        const code = this.getAttribute('data-code');
                        applyVoucher(code);
                    });
                });
            }
        } else {
            useVoucherSection.style.display = 'none';
        }
    }
    
    // Fonction pour appliquer un bon
    function applyVoucher(code) {
        const useVoucherCheckbox = document.getElementById('use_delivery_voucher');
        const voucherStatus = document.getElementById('voucher-status');
        const voucherInput = document.getElementById('delivery_voucher_code');
        
        if (useVoucherCheckbox && voucherStatus && voucherInput) {
            // Vérifier si le code existe dans les bons disponibles
            const voucher = availableVouchers.find(v => v.code === code);
            
            if (voucher) {
                // Bon valide
                voucherInput.value = code;
                useVoucherCheckbox.checked = true;
                voucherStatus.innerHTML = `<div class="alert alert-success mt-2">
                    <i class="bi bi-check-circle-fill me-2"></i>Bon valide: Livraison gratuite appliquée (${voucher.remaining_deliveries} utilisation(s) restante(s))
                </div>`;
                voucherStatus.dataset.valid = 'true';
            } else {
                // Bon invalide
                voucherStatus.innerHTML = '<div class="alert alert-danger mt-2"><i class="bi bi-exclamation-triangle-fill me-2"></i>Code invalide ou déjà utilisé</div>';
                voucherStatus.dataset.valid = 'false';
            }
            
            // Recalculer les totaux
            calculateTotals();
        }
    }
    
    // Écouteur pour le checkbox d'utilisation du bon
    const useVoucherCheckbox = document.getElementById('use_delivery_voucher');
    if (useVoucherCheckbox) {
        useVoucherCheckbox.addEventListener('change', function() {
            const voucherFields = document.getElementById('voucher_fields');
            if (voucherFields) {
                voucherFields.style.display = this.checked ? 'block' : 'none';
            }
            
            // Si on décoche, réinitialiser
            if (!this.checked) {
                const voucherStatus = document.getElementById('voucher-status');
                if (voucherStatus) {
                    voucherStatus.innerHTML = '';
                    voucherStatus.dataset.valid = 'false';
                }
            }
            
            // Recalculer les totaux
            calculateTotals();
        });
    }
    
    // Écouteur pour le bouton d'application du bon
    const applyVoucherBtn = document.getElementById('apply_voucher');
    if (applyVoucherBtn) {
        applyVoucherBtn.addEventListener('click', function() {
            const voucherInput = document.getElementById('delivery_voucher_code');
            if (voucherInput) {
                applyVoucher(voucherInput.value.trim());
            }
        });
    }
    
    // Écouter les changements d'adresse pour recalculer les frais
    ['collection_address_id', 'delivery_address_id'].forEach(id => {
        const select = document.getElementById(id);
        if (select) {
            select.addEventListener('change', calculateTotals);
        }
    });
    
    // Écouter les changements de quantité pour recalculer les totaux
    document.querySelectorAll('.item-quantity').forEach(input => {
        input.addEventListener('change', calculateTotals);
    });
    
    // Initialiser l'affichage des étapes et les calculs
    updateStep();
    calculateTotals();
    
    console.log('Script entièrement initialisé');
});
</script>
