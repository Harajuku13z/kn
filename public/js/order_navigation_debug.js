// Order navigation debug script - Simplified version to fix step navigation issue
document.addEventListener('DOMContentLoaded', function() {
    console.log('🔍 [DEBUG] Order navigation debug script loaded and executing');
    
    // 1. CONFIGURATION ET RÉCUPÉRATION DES DONNÉES
    // -------------------------------------------
    // Récupérer les données des frais de livraison et bons
    const deliveryFees = window.deliveryFeesData || [];
    const availableVouchers = window.availableVouchers || [];
    const pricePerKg = window.laundryPricePerKg || 1000;
    
    console.log('💰 [DATA] Données chargées:', {
        'Frais de livraison': deliveryFees.length > 0 ? `${deliveryFees.length} districts` : 'Non chargés',
        'Bons de livraison': availableVouchers.length > 0 ? `${availableVouchers.length} disponibles` : 'Aucun disponible',
        'Prix par kg': pricePerKg + ' FCFA'
    });
    
    // Inspecter les frais de livraison pour débogage
    if (deliveryFees.length > 0) {
        console.log('💰 [DATA] Exemple de frais de livraison:', deliveryFees[0]);
    } else {
        console.warn('⚠️ [DATA] Avertissement: Aucun frais de livraison chargé!');
    }
    
    // 2. NAVIGATION ENTRE ÉTAPES
    // -------------------------
    // Find DOM elements with debugging
    const steps = Array.from(document.querySelectorAll('.form-step'));
    console.log('🔍 [DEBUG] Found ' + steps.length + ' form steps in the DOM');
    steps.forEach((step, idx) => {
        const stepNum = step.getAttribute('data-form-step');
        console.log('🔍 [DEBUG] Step ' + (idx+1) + ' has ID: ' + step.id + ', data-form-step: ' + stepNum + ' and is ' + (step.classList.contains('active') ? 'active' : 'inactive'));
    });
    
    // Progress indicators
    const progressSteps = document.querySelectorAll('.step-custom');
    const progressLines = document.querySelectorAll('.progress-line-custom');
    console.log('🔍 [DEBUG] Found ' + progressSteps.length + ' progress indicators');
    
    // Navigation buttons
    const prevBtn = document.getElementById('prevStepBtn');
    const nextBtn = document.getElementById('nextStepBtn');
    console.log('🔍 [DEBUG] Navigation buttons found:', {
        prevBtn: prevBtn ? 'Yes' : 'No', 
        nextBtn: nextBtn ? 'Yes' : 'No'
    });
    
    // Define current step
    let currentStep = 1;
    const totalSteps = steps.length;
    console.log('🔍 [DEBUG] Total steps:', totalSteps);
    
    // Core navigation functions
    function updateStepDisplay() {
        console.log('📍 [NAVIGATION] Updating display to show step', currentStep);
        
        // Hide all steps and show current
        steps.forEach((step, index) => {
            if (index === currentStep - 1) {
                step.classList.add('active');
                console.log('📍 Activating step ' + (index+1) + ' - ' + step.id);
            } else {
                step.classList.remove('active');
            }
        });
        
        // Alternative method: use data-form-step attribute
        document.querySelectorAll('[data-form-step]').forEach(step => {
            const stepNum = parseInt(step.getAttribute('data-form-step'));
            if (stepNum === currentStep) {
                step.classList.add('active');
                console.log('📍 [DIRECT] Activating step by data-attr: ' + stepNum);
            } else {
                step.classList.remove('active');
            }
        });
        
        // Update button display
        if (prevBtn) {
            prevBtn.style.display = currentStep === 1 ? 'none' : 'block';
        }
        
        if (nextBtn) {
            nextBtn.innerHTML = currentStep === totalSteps 
                ? 'Finaliser la commande <i class="bi bi-check-circle ms-2"></i>'
                : 'Suivant <i class="bi bi-arrow-right ms-2"></i>';
        }
        
        // Update progress indicators
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
        
        // Update progress lines
        progressLines.forEach((line, index) => {
            line.classList.toggle('active', index < currentStep - 1);
        });
        
        // Si nous passons à l'étape 3, recalculer les prix
        if (currentStep === 3) {
            calculateTotals();
        }
    }
    
    // Go to next step with debug
    function goToNextStep(event) {
        if (event) {
            event.preventDefault();
            event.stopPropagation();
        }
        console.log('📍 [NAVIGATION] Next button clicked, current step:', currentStep);
        
        if (currentStep < totalSteps) {
            currentStep++;
            console.log('📍 [NAVIGATION] Moving to step', currentStep);
            updateStepDisplay();
            window.scrollTo(0, 0);
            
            // Recalculer les totaux après changement d'étape
            calculateTotals();
        } else {
            console.log('📍 [NAVIGATION] On last step, submitting form');
            const form = document.getElementById('orderForm');
            if (form) form.submit();
        }
    }
    
    // Go to previous step with debug
    function goToPrevStep(event) {
        if (event) {
            event.preventDefault();
            event.stopPropagation();
        }
        console.log('📍 [NAVIGATION] Previous button clicked, current step:', currentStep);
        
        if (currentStep > 1) {
            currentStep--;
            console.log('📍 [NAVIGATION] Moving to step', currentStep);
            updateStepDisplay();
            window.scrollTo(0, 0);
            
            // Recalculer les totaux après changement d'étape
            calculateTotals();
        }
    }
    
    // 3. GESTION DES ARTICLES ET CALCUL DES PRIX
    // -----------------------------------------
    // Fonction pour obtenir les frais de livraison pour un district
    function getDeliveryFee(district) {
        console.log('💰 [CALC] Recherche frais de livraison pour district:', district);
        if (!district) {
            console.log('💰 [CALC] District non défini, retour valeur par défaut');
            return 1000; // Valeur par défaut si non défini
        }
        
        const fee = deliveryFees.find(item => 
            item.district && item.district.toLowerCase() === district.toLowerCase()
        );
        
        if (fee) {
            console.log('💰 [CALC] Frais trouvés pour', district, ':', fee.fee, 'FCFA');
            return parseInt(fee.fee);
        } else {
            console.log('💰 [CALC] Aucun frais trouvé pour', district, ', retour valeur par défaut');
            return 1000; // Valeur par défaut si non trouvé
        }
    }
    
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
    
    // Fonction pour calculer tous les totaux
    function calculateTotals() {
        console.log('💰 [CALC] Calcul des totaux en cours...');
        
        // 1. Calculer poids et prix des articles
        let totalWeight = 0;
        let totalPrice = 0;
        let itemsCount = 0;
        
        document.querySelectorAll('.item-quantity').forEach(input => {
            const quantity = parseInt(input.value || 0);
            const weight = parseFloat(input.dataset.weight || 0);
            const itemWeight = quantity * weight;
            
            totalWeight += itemWeight;
            totalPrice += itemWeight * pricePerKg;
            
            if (quantity > 0) {
                itemsCount++;
            }
        });
        
        console.log('💰 [CALC] Articles:', {
            'Nombre avec quantité > 0': itemsCount,
            'Poids total': totalWeight.toFixed(2) + ' kg',
            'Prix articles': totalPrice + ' FCFA'
        });
        
        // 2. Calculer frais de livraison
        let pickupFee = 1000; // Valeur par défaut
        let deliveryFee = 1000; // Valeur par défaut
        
        // Obtenir district de collecte
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
        
        // Obtenir district de livraison
        const sameAddress = document.getElementById('same_address_for_delivery')?.checked || false;
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
        
        // 3. Appliquer bon de livraison si coché
        let totalDeliveryFee = pickupFee + deliveryFee;
        
        const useVoucherCheckbox = document.getElementById('use_delivery_voucher');
        const voucherStatus = document.getElementById('voucher-status');
        if (useVoucherCheckbox && useVoucherCheckbox.checked && 
            voucherStatus && voucherStatus.dataset.valid === 'true') {
            totalDeliveryFee = 0;
            console.log('💰 [CALC] Bon de livraison appliqué, frais annulés');
        }
        
        // 4. Calculer total général
        const grandTotal = totalPrice + totalDeliveryFee;
        
        console.log('💰 [CALC] Frais de livraison:', {
            'District collecte': collectionDistrict || 'Non défini',
            'District livraison': deliveryDistrict || 'Non défini',
            'Frais collecte': pickupFee + ' FCFA',
            'Frais livraison': deliveryFee + ' FCFA',
            'Total frais': totalDeliveryFee + ' FCFA',
            'Total général': grandTotal + ' FCFA'
        });
        
        // 5. Mise à jour des affichages d'infos de debug
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
        
        // 6. Mise à jour simulateur de prix
        try {
            // Affichage du poids
            if (document.getElementById('totalWeightDisplay')) {
                document.getElementById('totalWeightDisplay').textContent = totalWeight.toFixed(2);
            }
            
            // Affichage prix articles
            if (document.getElementById('articlesPrice')) {
                document.getElementById('articlesPrice').textContent = new Intl.NumberFormat('fr-FR').format(Math.round(totalPrice));
            }
            
            // Affichage frais de livraison
            if (document.getElementById('deliveryFeeDisplay')) {
                document.getElementById('deliveryFeeDisplay').textContent = new Intl.NumberFormat('fr-FR').format(totalDeliveryFee);
            }
            
            // Affichage badge livraison gratuite
            if (document.getElementById('freeDeliveryBadge')) {
                document.getElementById('freeDeliveryBadge').style.display = totalDeliveryFee === 0 ? 'inline-block' : 'none';
            }
            
            // Affichage prix total estimé
            if (document.getElementById('estimatedPriceDisplay')) {
                document.getElementById('estimatedPriceDisplay').textContent = new Intl.NumberFormat('fr-FR').format(Math.round(grandTotal));
            }
            
            // 7. Mise à jour récapitulatif
            if (document.getElementById('summary-weight')) {
                document.getElementById('summary-weight').textContent = totalWeight.toFixed(2);
            }
            
            if (document.getElementById('summary-articles-price')) {
                document.getElementById('summary-articles-price').textContent = new Intl.NumberFormat('fr-FR').format(Math.round(totalPrice));
            }
            
            if (document.getElementById('summary-delivery-fee')) {
                document.getElementById('summary-delivery-fee').textContent = new Intl.NumberFormat('fr-FR').format(totalDeliveryFee);
            }
            
            if (document.getElementById('summary-free-delivery-badge')) {
                document.getElementById('summary-free-delivery-badge').style.display = totalDeliveryFee === 0 ? 'inline-block' : 'none';
            }
            
            if (document.getElementById('summary-total')) {
                document.getElementById('summary-total').textContent = new Intl.NumberFormat('fr-FR').format(Math.round(grandTotal));
            }
            
            // 8. Mise à jour écran paiement
            if (document.getElementById('payment-total')) {
                document.getElementById('payment-total').textContent = new Intl.NumberFormat('fr-FR').format(Math.round(grandTotal));
            }
            
            if (document.getElementById('payment-articles-price')) {
                document.getElementById('payment-articles-price').textContent = new Intl.NumberFormat('fr-FR').format(Math.round(totalPrice));
            }
            
            if (document.getElementById('payment-delivery-fee')) {
                document.getElementById('payment-delivery-fee').textContent = new Intl.NumberFormat('fr-FR').format(totalDeliveryFee);
            }
            
            if (document.getElementById('payment-free-delivery-badge')) {
                document.getElementById('payment-free-delivery-badge').style.display = totalDeliveryFee === 0 ? 'inline-block' : 'none';
            }
            
            console.log('💰 [CALC] Affichages mis à jour avec succès');
        } catch (error) {
            console.error('💰 [CALC] Erreur lors de la mise à jour des affichages:', error);
        }
    }
    
    // 4. GESTION DES BONS DE LIVRAISON
    // -------------------------------
    function applyVoucher(code) {
        console.log('🎫 [VOUCHER] Tentative d\'application du bon:', code);
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
                console.log('🎫 [VOUCHER] Bon valide appliqué:', voucher);
            } else {
                // Bon invalide
                voucherStatus.innerHTML = '<div class="alert alert-danger mt-2"><i class="bi bi-exclamation-triangle-fill me-2"></i>Code invalide ou déjà utilisé</div>';
                voucherStatus.dataset.valid = 'false';
                console.log('🎫 [VOUCHER] Bon invalide ou non trouvé');
            }
            
            // Recalculer les totaux
            calculateTotals();
        }
    }
    
    // Configuration de la section des bons si disponibles
    const useVoucherSection = document.getElementById('voucher-section');
    if (useVoucherSection) {
        if (availableVouchers && availableVouchers.length > 0) {
            useVoucherSection.style.display = 'block';
            
            // Afficher le compteur de bons
            const voucherCountBadge = document.getElementById('voucher-count');
            if (voucherCountBadge) {
                voucherCountBadge.textContent = availableVouchers.length;
            }
            
            // Afficher la liste des bons disponibles
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
            console.log('🎫 [VOUCHER] Aucun bon disponible, section masquée');
        }
    }
    
    // Écouteur pour checkbox d'utilisation de bon
    const useVoucherCheckbox = document.getElementById('use_delivery_voucher');
    if (useVoucherCheckbox) {
        useVoucherCheckbox.addEventListener('change', function() {
            const voucherFields = document.getElementById('voucher_fields');
            if (voucherFields) {
                voucherFields.style.display = this.checked ? 'block' : 'none';
            }
            
            // Réinitialiser si décoché
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
    
    // Bouton pour appliquer le bon
    const applyVoucherBtn = document.getElementById('apply_voucher');
    if (applyVoucherBtn) {
        applyVoucherBtn.addEventListener('click', function() {
            const voucherInput = document.getElementById('delivery_voucher_code');
            if (voucherInput) {
                applyVoucher(voucherInput.value.trim());
            }
        });
    }
    
    // 5. CONFIGURATION DES ÉVÉNEMENTS ET ÉCOUTEURS
    // ------------------------------------------
    // Attach event listeners using multiple methods for redundancy
    
    // Direct event listeners
    if (nextBtn) {
        // Remove existing handlers
        nextBtn.replaceWith(nextBtn.cloneNode(true));
        
        // Get the new reference after replacing
        const newNextBtn = document.getElementById('nextStepBtn');
        if (newNextBtn) {
            console.log('📍 [SETUP] Adding new event listener to nextBtn');
            newNextBtn.addEventListener('click', goToNextStep);
        }
    }
    
    if (prevBtn) {
        // Remove existing handlers
        prevBtn.replaceWith(prevBtn.cloneNode(true));
        
        // Get the new reference after replacing
        const newPrevBtn = document.getElementById('prevStepBtn');
        if (newPrevBtn) {
            console.log('📍 [SETUP] Adding new event listener to prevBtn');
            newPrevBtn.addEventListener('click', goToPrevStep);
        }
    }
    
    // Global event delegation as backup
    document.addEventListener('click', function(e) {
        const target = e.target;
        
        if (target.matches('#nextStepBtn') || 
            target.closest('#nextStepBtn') ||
            target.matches('.next-step') || 
            target.closest('.next-step')) {
            console.log('📍 [EVENT] Next button click captured via delegation');
            e.preventDefault();
            e.stopPropagation(); // Stop event bubbling
            goToNextStep();
        }
        
        if (target.matches('#prevStepBtn') || 
            target.closest('#prevStepBtn') ||
            target.matches('.prev-step') || 
            target.closest('.prev-step')) {
            console.log('📍 [EVENT] Previous button click captured via delegation');
            e.preventDefault();
            e.stopPropagation(); // Stop event bubbling
            goToPrevStep();
        }
    }, true); // Use capture phase for maximum priority
    
    // Ajout d'écouteurs pour les changements d'adresse
    ['collection_address_id', 'delivery_address_id'].forEach(id => {
        const select = document.getElementById(id);
        if (select) {
            select.addEventListener('change', function() {
                console.log('📍 [EVENT] Changement d\'adresse détecté sur', id);
                calculateTotals();
            });
        }
    });
    
    // Ajout d'écouteurs pour les changements de quantité d'articles
    document.querySelectorAll('.item-quantity').forEach(input => {
        input.addEventListener('change', function() {
            console.log('📍 [EVENT] Changement de quantité détecté');
            calculateTotals();
        });
    });
    
    // Écouteur pour la case à cocher "même adresse"
    const sameAddressCheckbox = document.getElementById('same_address_for_delivery');
    if (sameAddressCheckbox) {
        sameAddressCheckbox.addEventListener('change', function() {
            const deliveryAddressFields = document.getElementById('delivery-address-fields');
            if (this.checked) {
                if (deliveryAddressFields) deliveryAddressFields.style.display = 'none';
                const deliveryAddressSelect = document.getElementById('delivery_address_id');
                if (deliveryAddressSelect) deliveryAddressSelect.removeAttribute('required');
            } else {
                if (deliveryAddressFields) deliveryAddressFields.style.display = 'block';
                const deliveryAddressSelect = document.getElementById('delivery_address_id');
                if (deliveryAddressSelect) deliveryAddressSelect.setAttribute('required', 'required');
            }
            console.log('📍 [EVENT] Changement option "même adresse":', this.checked);
            calculateTotals();
        });
    }
    
    // ADDITIONAL DEBUG: Add direct button functionality
    // This is a safety measure to ensure the buttons work
    const navBtnContainer = document.querySelector('.d-flex.justify-content-between.mt-5.pt-3.border-top');
    if (navBtnContainer) {
        console.log('📍 [DEBUG] Adding debug navigation buttons');
        
        // Create a debug container
        const debugContainer = document.createElement('div');
        debugContainer.style.padding = '10px';
        debugContainer.style.margin = '10px 0';
        debugContainer.style.border = '2px dashed red';
        debugContainer.style.backgroundColor = '#ffeeee';
        debugContainer.innerHTML = '<p style="margin-bottom:5px;font-weight:bold;">Navigation de secours (Debug):</p>';
        
        // Add step buttons
        for (let i = 1; i <= totalSteps; i++) {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'btn btn-sm btn-outline-danger mx-1';
            btn.textContent = 'Étape ' + i;
            btn.onclick = function() {
                currentStep = i;
                updateStepDisplay();
                window.scrollTo(0, 0);
                calculateTotals();
            };
            debugContainer.appendChild(btn);
        }
        
        // Add debug button for delivery fees calculation
        const calcBtn = document.createElement('button');
        calcBtn.type = 'button';
        calcBtn.className = 'btn btn-sm btn-warning mx-1';
        calcBtn.textContent = 'Recalculer Prix';
        calcBtn.onclick = calculateTotals;
        debugContainer.appendChild(calcBtn);
        
        // Insert debug container before normal navigation
        navBtnContainer.parentNode.insertBefore(debugContainer, navBtnContainer);
    }
    
    // Initialize display and calculations
    console.log('📍 [INIT] Initializing step display and calculations');
    updateStepDisplay();
    calculateTotals();
    
    console.log('📍 [SETUP] Setup complete - navigation and price calculations ready');
});