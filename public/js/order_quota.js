// Script de commande pour les commandes au kilo (quota)
document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 Script de commande quota chargé - v1.0');
    
    // ==========================================
    // 1. INITIALISATION ET RÉCUPÉRATION DES DONNÉES
    // ==========================================
    
    // Récupération des données globales
    const deliveryFees = window.deliveryFeesData || [];
    const availableVouchers = window.availableVouchers || [];
    const pricePerKg = window.laundryPricePerKg || 1000;
    
    console.log('📊 Données chargées:', {
        'Frais de livraison': deliveryFees.length + ' districts',
        'Bons disponibles': availableVouchers.length,
        'Prix au kilo': pricePerKg + ' FCFA'
    });
    
    // ==========================================
    // 2. NAVIGATION ENTRE ÉTAPES
    // ==========================================
    
    // Éléments du DOM pour la navigation
    const steps = Array.from(document.querySelectorAll('.form-step'));
    const progressSteps = document.querySelectorAll('.step-custom');
    const progressLines = document.querySelectorAll('.progress-line-custom');
    const prevBtn = document.getElementById('prevStepBtn');
    const nextBtn = document.getElementById('nextStepBtn');
    
    console.log('📊 Éléments trouvés:', {
        'Étapes': steps.length,
        'Indicateurs progrès': progressSteps.length,
        'Bouton précédent': prevBtn ? 'Oui' : 'Non',
        'Bouton suivant': nextBtn ? 'Oui' : 'Non'
    });
    
    // État actuel de la navigation
    let currentStep = 1;
    const totalSteps = steps.length;
    
    // Fonctions de navigation
    function updateStepDisplay() {
        console.log('🔄 Mise à jour affichage pour étape', currentStep);
        
        // Afficher l'étape actuelle et masquer les autres
        steps.forEach((step, index) => {
            if (index === currentStep - 1) {
                step.classList.add('active');
            } else {
                step.classList.remove('active');
            }
        });
        
        // Mettre à jour l'affichage des boutons
        if (prevBtn) {
            prevBtn.style.display = currentStep === 1 ? 'none' : 'block';
            console.log('État du bouton précédent:', prevBtn.style.display);
        } else {
            console.error('Bouton précédent non trouvé!');
        }
        
        if (nextBtn) {
            nextBtn.innerHTML = currentStep === totalSteps 
                ? 'Finaliser la commande <i class="bi bi-check-circle ms-2"></i>'
                : 'Suivant <i class="bi bi-arrow-right ms-2"></i>';
            console.log('État du bouton suivant: visible pour l\'étape', currentStep);
        } else {
            console.error('Bouton suivant non trouvé!');
        }
        
        // Mettre à jour les indicateurs de progression
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
        
        // Mettre à jour les lignes de progression
        progressLines.forEach((line, index) => {
            line.classList.toggle('active', index < currentStep - 1);
        });
        
        // Recalculer les prix à chaque changement d'étape
        calculateTotals();
    }
    
    // Fonction étape suivante
    function goToNextStep(event) {
        if (event) {
            event.preventDefault();
            event.stopPropagation();
        }
        
        if (currentStep < totalSteps) {
            currentStep++;
            updateStepDisplay();
            window.scrollTo(0, 0);
            
            // Si on passe à l'étape 4 (récapitulatif) ou 5 (paiement), forcer une mise à jour complète
            if (currentStep === 4 || currentStep === 5) {
                console.log('🔄 Passage à l\'étape ' + currentStep + ' - mise à jour forcée');
                updateFullSummary();
            }
        } else {
            // Dernière étape - Soumettre le formulaire
            console.log('📝 Soumission du formulaire');
            const form = document.getElementById('orderForm');
            if (form) form.submit();
        }
    }
    
    // Fonction étape précédente
    function goToPrevStep(event) {
        if (event) {
            event.preventDefault();
            event.stopPropagation();
        }
        
        if (currentStep > 1) {
            currentStep--;
            updateStepDisplay();
            window.scrollTo(0, 0);
            
            // Si on revient à l'étape 4 (récapitulatif) ou 5 (paiement), forcer une mise à jour complète
            if (currentStep === 4 || currentStep === 5) {
                console.log('🔄 Retour à l\'étape ' + currentStep + ' - mise à jour forcée');
                updateFullSummary();
            }
        }
    }
    
    // ==========================================
    // 3. GESTION DES ARTICLES ET CALCUL DES PRIX
    // ==========================================
    
    // Gestion des boutons de quantité (+ et -)
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
    
    // Fonction pour obtenir les frais de livraison d'un district
    function getDeliveryFee(district) {
        if (!district) return 1000; // Valeur par défaut
        
        const fee = deliveryFees.find(item => 
            item.district && item.district.toLowerCase() === district.toLowerCase()
        );
        
        if (fee) {
            console.log('💰 Frais pour', district, ':', fee.fee, 'FCFA');
            return parseInt(fee.fee);
        } else {
            console.log('💰 Aucun frais trouvé pour', district);
            return 1000; // Valeur par défaut
        }
    }
    
    // Fonction principale pour calculer tous les totaux
    function calculateTotals() {
        console.log('🧮 Calcul des totaux en cours...');

        // 1. Calculer le poids total et le prix des articles
        let totalWeight = 0;
        let totalArticlesPrice = 0;
        let articlesCount = 0;
        
        document.querySelectorAll('.item-quantity').forEach(input => {
            const quantity = parseInt(input.value || 0);
            if (quantity > 0) {
                articlesCount += quantity;
                const weight = parseFloat(input.dataset.weight || 0);
                const articleWeight = quantity * weight;
                totalWeight += articleWeight;
            }
        });
        
        // Arrondir le poids à 2 décimales
        totalWeight = Math.round(totalWeight * 100) / 100;
        
        // Calculer le prix en fonction du poids
        totalArticlesPrice = totalWeight * pricePerKg;
        
        console.log('📊 Calcul des prix:', {
            'Poids total': totalWeight + ' kg',
            'Prix par kg': pricePerKg + ' FCFA',
            'Prix articles': totalArticlesPrice + ' FCFA'
        });
        
        // 2. Mettre à jour le récapitulatif des articles
        const summaryItemsContainer = document.getElementById('summary-items');
        if (summaryItemsContainer) {
            let html = '';
            let hasArticles = false;
            
            document.querySelectorAll('.item-quantity').forEach(input => {
                const quantity = parseInt(input.value || 0);
                if (quantity > 0) {
                    hasArticles = true;
                    const articleCard = input.closest('.card');
                    const articleName = articleCard ? articleCard.querySelector('.card-title').textContent : 'Article';
                    const weight = parseFloat(input.dataset.weight || 0);
                    const articleWeight = quantity * weight;
                    const articlePrice = articleWeight * pricePerKg;
                    
                    html += `<tr>
                        <td>${articleName}</td>
                        <td class="text-center">${quantity}</td>
                        <td class="text-center">${articleWeight.toFixed(2)}</td>
                        <td class="text-end">${formatMoney(articlePrice)} FCFA</td>
                    </tr>`;
                }
            });
            
            if (hasArticles) {
                summaryItemsContainer.innerHTML = html;
            } else {
                summaryItemsContainer.innerHTML = '<tr><td colspan="4" class="text-center">Aucun article sélectionné</td></tr>';
            }
        }
        
        // 3. Calculer frais de livraison
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
        const sameAddressCheckbox = document.getElementById('same_address_for_delivery');
        const sameAddress = sameAddressCheckbox && sameAddressCheckbox.checked;
        let deliveryDistrict = '';
        
        if (sameAddress) {
            deliveryDistrict = collectionDistrict;
            deliveryFee = pickupFee;
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
        
        // 4. Appliquer bon de livraison si coché
        let totalDeliveryFee = pickupFee + deliveryFee;
        
        const useVoucherCheckbox = document.getElementById('use_delivery_voucher');
        const voucherStatus = document.getElementById('voucher-status');
        if (useVoucherCheckbox && useVoucherCheckbox.checked && 
            voucherStatus && voucherStatus.dataset.valid === 'true') {
            totalDeliveryFee = 0;
        }
        
        // 5. Calculer le sous-total (avant remise)
        let subtotal = totalArticlesPrice + totalDeliveryFee;
        
        // 6. Appliquer code promo si coché (non utilisé dans la page quota)
        let discount = 0;
        let discountText = '';
        
        const useCouponCheckbox = document.getElementById('use_coupon');
        const couponStatus = document.getElementById('coupon-status');
        
        if (useCouponCheckbox && useCouponCheckbox.checked && 
            couponStatus && couponStatus.dataset.valid === 'true') {
            
            const couponData = JSON.parse(couponStatus.dataset.coupon || '{}');
            
            // Vérifier si le montant minimum requis est atteint
            if (couponData.min_order_amount && subtotal < couponData.min_order_amount) {
                // Montant minimum non atteint, ne pas appliquer la réduction
                
                // Mettre à jour le message pour informer l'utilisateur
                couponStatus.innerHTML = `<div class="alert alert-warning mt-2">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>Montant minimum requis non atteint. 
                    Montant actuel: ${formatMoney(subtotal)} FCFA, minimum requis: ${formatMoney(couponData.min_order_amount)} FCFA
                </div>`;
            } else {
                // Montant minimum atteint ou pas de minimum requis, appliquer la réduction
                if (couponData.type === 'percentage') {
                    discount = subtotal * (couponData.value / 100);
                    discountText = couponData.value + '%';
                } else {
                    discount = Math.min(couponData.value, subtotal);
                    discountText = formatMoney(discount) + ' FCFA';
                }
                
                // Appliquer le plafond de remise si défini
                if (couponData.max_discount_amount && discount > couponData.max_discount_amount) {
                    discount = couponData.max_discount_amount;
                }
                
                // Mettre à jour le message de succès
                couponStatus.innerHTML = `<div class="alert alert-success mt-2">
                    <i class="bi bi-check-circle-fill me-2"></i>Code valide: Remise de ${couponData.formatted_value} appliquée
                </div>`;
            }
        }
        
        // 7. Calculer total général (après remise)
        const grandTotal = Math.max(0, subtotal - discount);
        
        console.log('💰 Détail du calcul du total:', {
            'Prix articles': totalArticlesPrice + ' FCFA',
            'Frais de livraison': totalDeliveryFee + ' FCFA',
            'Sous-total': subtotal + ' FCFA',
            'Remise': discount + ' FCFA',
            'Total final': grandTotal + ' FCFA'
        });
        
        // 8. Mise à jour des affichages sur la page
        try {
            // Simulateur de prix
            updateElementText('totalWeightDisplay', totalWeight.toFixed(2));
            updateElementText('articlesPrice', formatMoney(totalArticlesPrice));
            updateElementText('deliveryFeeDisplay', formatMoney(totalDeliveryFee));
            
            if (document.getElementById('freeDeliveryBadge')) {
                document.getElementById('freeDeliveryBadge').style.display = 
                    totalDeliveryFee === 0 ? 'inline-block' : 'none';
            }
            
            // Mettre à jour les prix totaux
            updateElementText('estimatedPriceDisplay', formatMoney(grandTotal));
            updateElementText('summary-weight', totalWeight.toFixed(2));
            updateElementText('summary-articles-price', formatMoney(totalArticlesPrice));
            updateElementText('summary-delivery-fee', formatMoney(totalDeliveryFee));
            updateElementText('summary-total', formatMoney(grandTotal));
            
            if (document.getElementById('summary-free-delivery-badge')) {
                document.getElementById('summary-free-delivery-badge').style.display = 
                    totalDeliveryFee === 0 ? 'inline-block' : 'none';
            }
            
            // Mettre à jour les dates dans le récapitulatif
            const collectionDate = document.getElementById('collection_date');
            const collectionTimeSlot = document.getElementById('collection_time_slot');
            const deliveryDate = document.getElementById('delivery_date');
            const deliveryTimeSlot = document.getElementById('delivery_time_slot');
            
            if (collectionDate && collectionDate.value) {
                try {
                    const dateObj = new Date(collectionDate.value);
                    const options = { day: 'numeric', month: 'long', year: 'numeric' };
                    const formattedDate = dateObj.toLocaleDateString('fr-FR', options);
                    updateElementText('summary-collection-date', formattedDate);
                } catch (e) {
                    console.error('Erreur de formatage de date:', e);
                    updateElementText('summary-collection-date', collectionDate.value);
                }
            }
            
            if (collectionTimeSlot && collectionTimeSlot.selectedIndex > 0) {
                const selectedOption = collectionTimeSlot.options[collectionTimeSlot.selectedIndex];
                updateElementText('summary-collection-time', selectedOption.text);
            }
            
            if (deliveryDate && deliveryDate.value) {
                try {
                    const dateObj = new Date(deliveryDate.value);
                    const options = { day: 'numeric', month: 'long', year: 'numeric' };
                    const formattedDate = dateObj.toLocaleDateString('fr-FR', options);
                    updateElementText('summary-delivery-date', formattedDate);
                } catch (e) {
                    console.error('Erreur de formatage de date:', e);
                    updateElementText('summary-delivery-date', deliveryDate.value);
                }
            }
            
            if (deliveryTimeSlot && deliveryTimeSlot.selectedIndex > 0) {
                const selectedOption = deliveryTimeSlot.options[deliveryTimeSlot.selectedIndex];
                updateElementText('summary-delivery-time', selectedOption.text);
            }
            
            // Mettre à jour les adresses dans le récapitulatif
            if (collectionAddressSelect && collectionAddressSelect.selectedIndex > 0) {
                const selectedOption = collectionAddressSelect.options[collectionAddressSelect.selectedIndex];
                document.getElementById('summary-collection-address').innerHTML = selectedOption.text;
            }
            
            if (sameAddress) {
                if (collectionAddressSelect && collectionAddressSelect.selectedIndex > 0) {
                    const selectedOption = collectionAddressSelect.options[collectionAddressSelect.selectedIndex];
                    document.getElementById('summary-delivery-address').innerHTML = selectedOption.text + ' <em>(Même adresse que la collecte)</em>';
                }
            } else if (deliveryAddressSelect && deliveryAddressSelect.selectedIndex > 0) {
                const selectedOption = deliveryAddressSelect.options[deliveryAddressSelect.selectedIndex];
                document.getElementById('summary-delivery-address').innerHTML = selectedOption.text;
            }
            
            // Afficher la remise si applicable
            const discountRow = document.getElementById('summary-discount-row');
            if (discountRow) {
                discountRow.style.display = discount > 0 ? 'table-row' : 'none';
                if (document.getElementById('summary-discount-value')) {
                    document.getElementById('summary-discount-value').textContent = '- ' + formatMoney(discount);
                }
            }
            
            // Paiement
            updateElementText('payment-total', formatMoney(grandTotal));
            updateElementText('payment-articles-price', formatMoney(totalArticlesPrice));
            updateElementText('payment-delivery-fee', formatMoney(totalDeliveryFee));
            
            console.log('💰 Mise à jour des éléments de paiement:', {
                'payment-total': formatMoney(grandTotal) + ' FCFA',
                'payment-articles-price': formatMoney(totalArticlesPrice) + ' FCFA',
                'payment-delivery-fee': formatMoney(totalDeliveryFee) + ' FCFA'
            });
            
            if (document.getElementById('payment-free-delivery-badge')) {
                document.getElementById('payment-free-delivery-badge').style.display = 
                    totalDeliveryFee === 0 ? 'inline-block' : 'none';
            }
            
            // Mettre à jour la ligne de remise dans le paiement
            const paymentDiscountRow = document.getElementById('payment-discount-row');
            if (paymentDiscountRow) {
                paymentDiscountRow.style.display = discount > 0 ? 'flex' : 'none';
                if (document.getElementById('payment-discount-value')) {
                    document.getElementById('payment-discount-value').textContent = '- ' + formatMoney(discount);
                }
            }
            
            // Vérifier si le poids total dépasse le quota disponible
            const availableQuota = parseFloat(document.getElementById('available-quota')?.textContent || 0);
            const quotaMessage = document.getElementById('quota-message');
            const quotaSection = document.getElementById('quota-section');
            const quotaRadio = document.getElementById('payment-quota');
            
            if (quotaMessage && quotaSection && quotaRadio) {
                if (totalWeight > availableQuota) {
                    quotaMessage.innerHTML = `<div class="alert alert-warning mt-2 mb-0 py-1 px-2">
                        <small><i class="bi bi-exclamation-triangle me-1"></i>Poids sélectionné (${totalWeight.toFixed(2)} kg) supérieur à votre quota (${availableQuota} kg)</small>
                    </div>`;
                    quotaRadio.disabled = true;
                    // Si le paiement par quota était sélectionné, basculer vers paiement en espèces
                    if (quotaRadio.checked) {
                        document.getElementById('payment-cash').checked = true;
                    }
                } else if (totalWeight > 0) {
                    quotaMessage.innerHTML = `<div class="alert alert-success mt-2 mb-0 py-1 px-2">
                        <small><i class="bi bi-check-circle me-1"></i>Quota suffisant pour cette commande</small>
                    </div>`;
                    quotaRadio.disabled = false;
                } else {
                    quotaMessage.innerHTML = '';
                    quotaRadio.disabled = false;
                }
            }
            
            console.log('💰 Totaux calculés :', {
                'Poids total': `${totalWeight.toFixed(2)} kg`,
                'Prix articles': `${formatMoney(totalArticlesPrice)} FCFA`,
                'Frais de livraison': `${formatMoney(totalDeliveryFee)} FCFA`,
                'Total': `${formatMoney(grandTotal)} FCFA`
            });
        } catch (error) {
            console.error('❌ Erreur lors de la mise à jour des affichages:', error);
        }
    }
    
    // Fonction utilitaire pour mettre à jour le texte d'un élément
    function updateElementText(elementId, text) {
        const element = document.getElementById(elementId);
        if (element) {
            element.textContent = text;
        }
    }
    
    // Fonction utilitaire pour formater la monnaie
    function formatMoney(amount) {
        return new Intl.NumberFormat('fr-FR').format(Math.round(amount));
    }
    
    // ==========================================
    // 4. GESTION DES BONS DE LIVRAISON
    // ==========================================
    
    // Fonction pour appliquer un bon de livraison
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
    
    // Fonction pour appliquer un code promo
    function applyCoupon(code) {
        const useCouponCheckbox = document.getElementById('use_coupon');
        const couponStatus = document.getElementById('coupon-status');
        const couponInput = document.getElementById('coupon_code');
        
        if (useCouponCheckbox && couponStatus && couponInput) {
            // Vérifier si le code existe dans les codes promos disponibles
            const coupon = availableCoupons.find(c => c.code === code);
            
            if (coupon) {
                // Code promo trouvé - l'appliquer
                couponInput.value = code;
                useCouponCheckbox.checked = true;
                
                // Stocker les informations du coupon pour le calcul ultérieur
                couponStatus.dataset.valid = 'true';
                couponStatus.dataset.coupon = JSON.stringify(coupon);
                
                // Le message sera mis à jour lors du calcul des totaux
                // en fonction du montant minimum requis
            } else {
                // Code promo invalide
                couponStatus.innerHTML = '<div class="alert alert-danger mt-2"><i class="bi bi-exclamation-triangle-fill me-2"></i>Code promo invalide ou expiré</div>';
                couponStatus.dataset.valid = 'false';
                couponStatus.dataset.coupon = '{}';
            }
            
            // Recalculer les totaux - cela va vérifier les montants minimums
            calculateTotals();
        }
    }
    
    // ==========================================
    // 5. MISE EN PLACE DES ÉCOUTEURS D'ÉVÉNEMENTS
    // ==========================================
    
    // 1. Navigation - Boutons suivant/précédent
    if (nextBtn) {
        // Remplacer le bouton pour éliminer les écouteurs existants
        const newNextBtn = nextBtn.cloneNode(true);
        if (nextBtn.parentNode) {
            nextBtn.parentNode.replaceChild(newNextBtn, nextBtn);
        }
        
        // Ajouter le nouvel écouteur
        document.getElementById('nextStepBtn').addEventListener('click', goToNextStep);
    }
    
    if (prevBtn) {
        // Remplacer le bouton pour éliminer les écouteurs existants
        const newPrevBtn = prevBtn.cloneNode(true);
        if (prevBtn.parentNode) {
            prevBtn.parentNode.replaceChild(newPrevBtn, prevBtn);
        }
        
        // Ajouter le nouvel écouteur
        document.getElementById('prevStepBtn').addEventListener('click', goToPrevStep);
    }
    
    // 2. Écouteur de délégation global pour les boutons de navigation
    document.addEventListener('click', function(e) {
        const target = e.target;
        
        // Bouton suivant
        if (target.matches('#nextStepBtn, .next-step') || 
            target.closest('#nextStepBtn') || 
            target.closest('.next-step')) {
            e.preventDefault();
            e.stopPropagation();
            goToNextStep();
        }
        
        // Bouton précédent
        if (target.matches('#prevStepBtn, .prev-step') || 
            target.closest('#prevStepBtn') || 
            target.closest('.prev-step')) {
            e.preventDefault();
            e.stopPropagation();
            goToPrevStep();
        }
    }, true); // Phase de capture pour priorité maximale
    
    // 3. Écouteurs pour les changements d'adresse
    const addressFields = ['collection_address_id', 'delivery_address_id'];
    addressFields.forEach(id => {
        const select = document.getElementById(id);
        if (select) {
            select.addEventListener('change', calculateTotals);
        }
    });
    
    // 4. Écouteur pour la case à cocher "même adresse"
    const sameAddressCheckbox = document.getElementById('same_address_for_delivery');
    if (sameAddressCheckbox) {
        sameAddressCheckbox.addEventListener('change', function() {
            const deliveryAddressFields = document.getElementById('delivery-address-fields');
            if (deliveryAddressFields) {
                deliveryAddressFields.style.display = this.checked ? 'none' : 'block';
            }
            
            const deliveryAddressSelect = document.getElementById('delivery_address_id');
            if (deliveryAddressSelect) {
                if (this.checked) {
                    deliveryAddressSelect.removeAttribute('required');
                } else {
                    deliveryAddressSelect.setAttribute('required', 'required');
                }
            }
            
            calculateTotals();
        });
    }
    
    // 5. Écouteurs pour les bons de livraison
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
            
            calculateTotals();
        });
    }
    
    // Bouton pour appliquer un bon
    const applyVoucherBtn = document.getElementById('apply_voucher');
    if (applyVoucherBtn) {
        applyVoucherBtn.addEventListener('click', function() {
            const voucherInput = document.getElementById('delivery_voucher_code');
            if (voucherInput) {
                applyVoucher(voucherInput.value.trim());
            }
        });
    }
    
    // Écouteurs pour les items de bons
    document.querySelectorAll('.voucher-item').forEach(item => {
        item.addEventListener('click', function() {
            const code = this.getAttribute('data-code');
            if (code) {
                applyVoucher(code);
            }
        });
    });
    
    // 6. Écouteurs pour les quantités d'articles
    document.querySelectorAll('.item-quantity').forEach(input => {
        input.addEventListener('change', calculateTotals);
        input.addEventListener('input', calculateTotals);
    });
    
    // 7. Écouteurs pour les codes promo
    const useCouponCheckbox = document.getElementById('use_coupon');
    if (useCouponCheckbox) {
        useCouponCheckbox.addEventListener('change', function() {
            const couponFields = document.getElementById('coupon_fields');
            if (couponFields) {
                couponFields.style.display = this.checked ? 'block' : 'none';
            }
            
            // Réinitialiser si décoché
            if (!this.checked) {
                const couponStatus = document.getElementById('coupon-status');
                if (couponStatus) {
                    couponStatus.innerHTML = '';
                    couponStatus.dataset.valid = 'false';
                    couponStatus.dataset.coupon = '{}';
                }
            }
            
            calculateTotals();
        });
    }
    
    // Bouton pour appliquer un code promo
    const applyCouponBtn = document.getElementById('apply_coupon');
    if (applyCouponBtn) {
        applyCouponBtn.addEventListener('click', function() {
            const couponInput = document.getElementById('coupon_code');
            if (couponInput && couponInput.value) {
                applyCoupon(couponInput.value);
            }
        });
    }
    
    // ==========================================
    // 7. INITIALISATION
    // ==========================================
    
    // Configurer la section des bons si disponibles
    const voucherCount = document.getElementById('voucher-count-badge');
    if (voucherCount && availableVouchers.length > 0) {
        voucherCount.textContent = availableVouchers.length;
        voucherCount.style.display = 'inline-block';
        
        // Préparer et afficher la liste des bons disponibles
        const vouchersList = document.getElementById('available-vouchers-list');
        if (vouchersList) {
            const vouchersListGroup = vouchersList.querySelector('.list-group');
            if (vouchersListGroup) {
                let html = '';
                availableVouchers.forEach(voucher => {
                    html += `
                        <button type="button" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center voucher-item" 
                                data-code="${voucher.code}">
                            <div>
                                <strong>${voucher.code}</strong>
                                <small class="d-block text-muted">Bon de livraison gratuite</small>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-primary rounded-pill">${voucher.remaining_deliveries} restant(s)</span>
                            </div>
                        </button>
                    `;
                });
                vouchersListGroup.innerHTML = html;
            }
        }
    }
    
    // Fonction pour mettre à jour le récapitulatif complet
    function updateFullSummary() {
        console.log('🔄 Mise à jour complète du récapitulatif');
        
        // Forcer la mise à jour des dates et adresses
        const collectionDate = document.getElementById('collection_date');
        const collectionTimeSlot = document.getElementById('collection_time_slot');
        const deliveryDate = document.getElementById('delivery_date');
        const deliveryTimeSlot = document.getElementById('delivery_time_slot');
        const collectionAddressSelect = document.getElementById('collection_address_id');
        const deliveryAddressSelect = document.getElementById('delivery_address_id');
        const sameAddressCheckbox = document.getElementById('same_address_for_delivery');
        
        // Mettre à jour les dates
        if (collectionDate && collectionDate.value) {
            try {
                const date = new Date(collectionDate.value);
                const formattedDate = date.toLocaleDateString('fr-FR', { 
                    day: 'numeric', month: 'long', year: 'numeric' 
                });
                document.getElementById('summary-collection-date').textContent = formattedDate;
            } catch(e) {
                console.error('Erreur de formatage de date:', e);
                document.getElementById('summary-collection-date').textContent = collectionDate.value;
            }
        }
        
        if (collectionTimeSlot && collectionTimeSlot.selectedIndex > 0) {
            const selectedOption = collectionTimeSlot.options[collectionTimeSlot.selectedIndex];
            document.getElementById('summary-collection-time').textContent = selectedOption.text;
        }
        
        if (deliveryDate && deliveryDate.value) {
            try {
                const date = new Date(deliveryDate.value);
                const formattedDate = date.toLocaleDateString('fr-FR', { 
                    day: 'numeric', month: 'long', year: 'numeric' 
                });
                document.getElementById('summary-delivery-date').textContent = formattedDate;
            } catch(e) {
                console.error('Erreur de formatage de date:', e);
                document.getElementById('summary-delivery-date').textContent = deliveryDate.value;
            }
        }
        
        if (deliveryTimeSlot && deliveryTimeSlot.selectedIndex > 0) {
            const selectedOption = deliveryTimeSlot.options[deliveryTimeSlot.selectedIndex];
            document.getElementById('summary-delivery-time').textContent = selectedOption.text;
        }
        
        // Mettre à jour les adresses
        if (collectionAddressSelect && collectionAddressSelect.selectedIndex > 0) {
            const selectedOption = collectionAddressSelect.options[collectionAddressSelect.selectedIndex];
            document.getElementById('summary-collection-address').innerHTML = selectedOption.text;
        }
        
        const sameAddress = sameAddressCheckbox && sameAddressCheckbox.checked;
        if (sameAddress) {
            if (collectionAddressSelect && collectionAddressSelect.selectedIndex > 0) {
                const selectedOption = collectionAddressSelect.options[collectionAddressSelect.selectedIndex];
                document.getElementById('summary-delivery-address').innerHTML = selectedOption.text + ' <em>(Même adresse que la collecte)</em>';
            }
        } else if (deliveryAddressSelect && deliveryAddressSelect.selectedIndex > 0) {
            const selectedOption = deliveryAddressSelect.options[deliveryAddressSelect.selectedIndex];
            document.getElementById('summary-delivery-address').innerHTML = selectedOption.text;
        }
        
        // Recalculer les totaux et forcer la mise à jour des prix dans l'étape 5
        calculateTotals();
        
        // Forcer la mise à jour des prix dans l'étape 5 (paiement)
        const paymentTotalElement = document.getElementById('payment-total');
        const summaryTotalElement = document.getElementById('summary-total');
        
        if (paymentTotalElement && summaryTotalElement) {
            paymentTotalElement.textContent = summaryTotalElement.textContent;
            console.log('🔄 Mise à jour du total de paiement:', summaryTotalElement.textContent);
        }
        
        const paymentArticlesPriceElement = document.getElementById('payment-articles-price');
        const summaryArticlesPriceElement = document.getElementById('summary-articles-price');
        
        if (paymentArticlesPriceElement && summaryArticlesPriceElement) {
            paymentArticlesPriceElement.textContent = summaryArticlesPriceElement.textContent;
        }
        
        const paymentDeliveryFeeElement = document.getElementById('payment-delivery-fee');
        const summaryDeliveryFeeElement = document.getElementById('summary-delivery-fee');
        
        if (paymentDeliveryFeeElement && summaryDeliveryFeeElement) {
            paymentDeliveryFeeElement.textContent = summaryDeliveryFeeElement.textContent;
        }
        
        // Vérifier si la livraison est gratuite
        const summaryFreeDeliveryBadge = document.getElementById('summary-free-delivery-badge');
        const paymentFreeDeliveryBadge = document.getElementById('payment-free-delivery-badge');
        
        if (summaryFreeDeliveryBadge && paymentFreeDeliveryBadge) {
            paymentFreeDeliveryBadge.style.display = summaryFreeDeliveryBadge.style.display;
        }
        
        // Vérifier s'il y a une réduction
        const summaryDiscountRow = document.getElementById('summary-discount-row');
        const paymentDiscountRow = document.getElementById('payment-discount-row');
        
        if (summaryDiscountRow && paymentDiscountRow) {
            paymentDiscountRow.style.display = summaryDiscountRow.style.display === 'none' ? 'none' : 'flex';
            
            const summaryDiscountValue = document.getElementById('summary-discount-value');
            const paymentDiscountValue = document.getElementById('payment-discount-value');
            
            if (summaryDiscountValue && paymentDiscountValue) {
                paymentDiscountValue.textContent = summaryDiscountValue.textContent;
            }
        }
        
        // Double vérification des totaux
        const totalWeight = parseFloat(document.getElementById('summary-weight')?.textContent || 0);
        const articlesPrice = parseInt(document.getElementById('summary-articles-price')?.textContent.replace(/\s/g, '') || 0);
        const deliveryFee = parseInt(document.getElementById('summary-delivery-fee')?.textContent.replace(/\s/g, '') || 0);
        const discount = parseInt((document.getElementById('summary-discount-value')?.textContent || '0').replace(/[^\d]/g, '') || 0);
        
        const calculatedTotal = articlesPrice + deliveryFee - discount;
        console.log('🧮 Vérification des totaux:', {
            'Poids': totalWeight + ' kg',
            'Prix articles': articlesPrice + ' FCFA',
            'Frais livraison': deliveryFee + ' FCFA',
            'Remise': discount + ' FCFA',
            'Total calculé': calculatedTotal + ' FCFA',
            'Total affiché': document.getElementById('summary-total')?.textContent
        });
        
        // Si le total affiché ne correspond pas au total calculé, forcer la mise à jour
        const displayedTotal = parseInt(document.getElementById('summary-total')?.textContent.replace(/\s/g, '') || 0);
        if (displayedTotal !== calculatedTotal) {
            console.log('⚠️ Correction du total: ' + displayedTotal + ' → ' + calculatedTotal);
            updateElementText('summary-total', formatMoney(calculatedTotal));
            updateElementText('payment-total', formatMoney(calculatedTotal));
        }
        
        console.log('🔄 Mise à jour du récapitulatif terminée');
    }
    
    // Initialiser l'affichage et calculer les totaux
    updateStepDisplay();
    calculateTotals();
    
    // Forcer une mise à jour complète du récapitulatif après un court délai
    // pour s'assurer que tous les éléments du DOM sont bien chargés
    setTimeout(() => {
        updateFullSummary();
        
        // Si nous sommes déjà à l'étape 4 ou 5, forcer une mise à jour supplémentaire
        if (currentStep === 4 || currentStep === 5) {
            console.log('🔄 Page chargée sur étape ' + currentStep + ' - mise à jour forcée');
            setTimeout(updateFullSummary, 300);
        }
    }, 500);
}); 