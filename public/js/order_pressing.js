// Script de commande pressing - Navigation et calcul des prix
document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 Script de commande pressing chargé - v1.0');
    
    // ==========================================
    // 1. INITIALISATION ET RÉCUPÉRATION DES DONNÉES
    // ==========================================
    
    // Récupération des données globales
    const deliveryFees = window.deliveryFeesData || [];
    const availableVouchers = window.availableVouchers || [];
    const availableCoupons = window.availableCoupons || [];
    const allPressingServices = window.allPressingServices || {};
    
    // ==========================================
    // 2. GESTION DE LA SÉLECTION DES PRESSINGS
    // ==========================================
    
    // Gestion de la sélection des pressings par carte
    const pressingCards = document.querySelectorAll('.pressing-select-card');
    const pressingRadios = document.querySelectorAll('.pressing-radio');
    const selectedPressingIdField = document.getElementById('selected_pressing_id_field');
    
    pressingCards.forEach(card => {
        card.addEventListener('click', function() {
            const pressingId = this.dataset.pressingId;
            
            // Mettre à jour la sélection visuelle
            pressingCards.forEach(c => c.classList.remove('border-primary'));
            this.classList.add('border-primary');
            
            // Cocher le radio button correspondant
            const radio = document.getElementById('pressing_' + pressingId);
            if (radio) {
                radio.checked = true;
            }
            
            // Mettre à jour le champ caché
            if (selectedPressingIdField) {
                selectedPressingIdField.value = pressingId;
            }
            
            console.log('Pressing sélectionné:', pressingId);
        });
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
            console.log('🔄 Bouton précédent:', currentStep === 1 ? 'masqué' : 'visible');
        } else {
            console.error('❌ Bouton précédent non trouvé dans le DOM');
        }
        
        if (nextBtn) {
            nextBtn.innerHTML = currentStep === totalSteps 
                ? 'Finaliser la commande <i class="bi bi-check-circle ms-2"></i>'
                : 'Suivant <i class="bi bi-arrow-right ms-2"></i>';
            console.log('🔄 Bouton suivant mis à jour pour l\'étape', currentStep);
        } else {
            console.error('❌ Bouton suivant non trouvé dans le DOM');
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
        
        // Si nous sommes à l'étape du récapitulatif, mettre à jour les informations
        if (currentStep === 4) {
            updateSummaryInformation();
        }
        
        // Recalculer les prix à chaque changement d'étape
        calculateTotals();
    }
    
    // Fonction pour mettre à jour les informations du récapitulatif
    function updateSummaryInformation() {
        // Mettre à jour les dates
        const collectionDate = document.getElementById('collection_date');
        const collectionTimeSlot = document.getElementById('collection_time_slot');
        const deliveryDate = document.getElementById('delivery_date');
        const deliveryTimeSlot = document.getElementById('delivery_time_slot');
        
        // Formater les dates pour l'affichage
        if (collectionDate && collectionDate.value) {
            const formattedCollectionDate = formatDate(collectionDate.value);
            const collectionTimeText = collectionTimeSlot ? collectionTimeSlot.options[collectionTimeSlot.selectedIndex]?.text || '' : '';
            
            // Mettre à jour la date de collecte dans le récapitulatif
            const summaryCollectionDate = document.getElementById('summary-collection-date');
            if (summaryCollectionDate) {
                summaryCollectionDate.textContent = formattedCollectionDate;
            }
            
            // Mettre à jour l'heure de collecte dans le récapitulatif
            const summaryCollectionTime = document.getElementById('summary-collection-time');
            if (summaryCollectionTime) {
                summaryCollectionTime.textContent = collectionTimeText;
            }
        }
        
        if (deliveryDate && deliveryDate.value) {
            const formattedDeliveryDate = formatDate(deliveryDate.value);
            const deliveryTimeText = deliveryTimeSlot ? deliveryTimeSlot.options[deliveryTimeSlot.selectedIndex]?.text || '' : '';
            
            // Mettre à jour la date de livraison dans le récapitulatif
            const summaryDeliveryDate = document.getElementById('summary-delivery-date');
            if (summaryDeliveryDate) {
                summaryDeliveryDate.textContent = formattedDeliveryDate;
            }
            
            // Mettre à jour l'heure de livraison dans le récapitulatif
            const summaryDeliveryTime = document.getElementById('summary-delivery-time');
            if (summaryDeliveryTime) {
                summaryDeliveryTime.textContent = deliveryTimeText;
            }
        }
        
        // Mettre à jour les adresses
        const collectionAddressSelect = document.getElementById('collection_address_id');
        if (collectionAddressSelect && collectionAddressSelect.selectedIndex > 0) {
            const selectedOption = collectionAddressSelect.options[collectionAddressSelect.selectedIndex];
            const addressText = selectedOption.textContent;
            
            // Mettre à jour l'adresse de collecte dans le récapitulatif
            const summaryCollectionAddress = document.getElementById('summary-collection-address');
            if (summaryCollectionAddress) {
                summaryCollectionAddress.textContent = addressText;
            }
        }
        
        // Vérifier si même adresse pour livraison
        const sameAddressCheckbox = document.getElementById('same_address_for_delivery');
        const sameAddress = sameAddressCheckbox && sameAddressCheckbox.checked;
        
        if (sameAddress) {
            // Utiliser la même adresse que pour la collecte
            const summaryDeliveryAddress = document.getElementById('summary-delivery-address');
            const summaryCollectionAddress = document.getElementById('summary-collection-address');
            
            if (summaryDeliveryAddress && summaryCollectionAddress) {
                summaryDeliveryAddress.textContent = summaryCollectionAddress.textContent;
            }
        } else {
            // Utiliser l'adresse de livraison spécifiée
            const deliveryAddressSelect = document.getElementById('delivery_address_id');
            if (deliveryAddressSelect && deliveryAddressSelect.selectedIndex > 0) {
                const selectedOption = deliveryAddressSelect.options[deliveryAddressSelect.selectedIndex];
                const addressText = selectedOption.textContent;
                
                // Mettre à jour l'adresse de livraison dans le récapitulatif
                const summaryDeliveryAddress = document.getElementById('summary-delivery-address');
                if (summaryDeliveryAddress) {
                    summaryDeliveryAddress.textContent = addressText;
                }
            }
        }
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
        }
    }
    
    // ==========================================
    // 3. GESTION DES ARTICLES ET CALCUL DES PRIX
    // ==========================================
    
    // Fonction pour obtenir les frais de livraison d'un district
    function getDeliveryFee(district) {
        if (!district) return 1000; // Valeur par défaut
        
        const fee = deliveryFees.find(item => 
            item.district && item.district.toLowerCase() === district.toLowerCase()
        );
        
        if (fee) {
            return parseInt(fee.fee);
        } else {
            return 1000; // Valeur par défaut
        }
    }
    
    // Fonction principale pour calculer tous les totaux
    function calculateTotals() {
        console.log('🧮 Calcul des totaux en cours...');

        // 1. Calculer le prix des services du pressing
        let totalServicesPrice = 0;
        let servicesCount = 0;
        
        document.querySelectorAll('.item-quantity').forEach(input => {
            const quantity = parseInt(input.value || 0);
            if (quantity > 0) {
                servicesCount += quantity;
                const price = parseFloat(input.dataset.price || 0);
                totalServicesPrice += quantity * price;
            }
        });
        
        // Mettre à jour le nombre de services sélectionnés
        const servicesCountElement = document.getElementById('selected-services-count');
        if (servicesCountElement) {
            servicesCountElement.textContent = servicesCount + ' service(s)';
        }
        
        // 2. Mettre à jour le récapitulatif des services
        const summaryItemsContainer = document.getElementById('summary-items');
        if (summaryItemsContainer) {
            let html = '';
            let hasServices = false;
            
            document.querySelectorAll('.item-quantity').forEach(input => {
                const quantity = parseInt(input.value || 0);
                if (quantity > 0) {
                    hasServices = true;
                    const serviceCard = input.closest('.card');
                    const serviceName = serviceCard ? serviceCard.querySelector('.card-title').textContent : 'Service';
                    const price = parseFloat(input.dataset.price || 0);
                    const totalPrice = quantity * price;
                    
                    html += `<tr>
                        <td>${serviceName}</td>
                        <td class="text-center">${quantity}</td>
                        <td class="text-end">${formatMoney(totalPrice)} FCFA</td>
                    </tr>`;
                }
            });
            
            if (hasServices) {
                summaryItemsContainer.innerHTML = html;
            } else {
                summaryItemsContainer.innerHTML = '<tr><td colspan="3" class="text-center">Aucun service sélectionné</td></tr>';
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
        
        // 4. Appliquer bon de livraison si coché
        let totalDeliveryFee = pickupFee + deliveryFee;
        
        const useVoucherCheckbox = document.getElementById('use_delivery_voucher');
        const voucherStatus = document.getElementById('voucher-status');
        if (useVoucherCheckbox && useVoucherCheckbox.checked && 
            voucherStatus && voucherStatus.dataset.valid === 'true') {
            totalDeliveryFee = 0;
        }
        
        // 5. Calculer le sous-total (avant remise)
        let subtotal = totalServicesPrice + totalDeliveryFee;
        
        // 6. Appliquer code promo si coché
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
        
        // 8. Mise à jour des affichages sur la page
        try {
            // Simulateur de prix
            updateElementText('servicesPrice', formatMoney(totalServicesPrice));
            updateElementText('deliveryFeeDisplay', formatMoney(totalDeliveryFee));
            
            if (document.getElementById('freeDeliveryBadge')) {
                document.getElementById('freeDeliveryBadge').style.display = 
                    totalDeliveryFee === 0 ? 'inline-block' : 'none';
            }
            
            // Afficher la remise si applicable
            const discountRow = document.getElementById('discount-row');
            if (discountRow) {
                discountRow.style.display = discount > 0 ? 'table-row' : 'none';
                if (document.getElementById('discount-value')) {
                    document.getElementById('discount-value').textContent = '- ' + formatMoney(discount);
                }
                if (document.getElementById('discount-text')) {
                    document.getElementById('discount-text').textContent = discountText;
                }
            }
            
            // Mettre à jour les prix totaux
            updateElementText('estimatedPriceDisplay', formatMoney(grandTotal));
            updateElementText('summary-articles-price', formatMoney(totalServicesPrice));
            updateElementText('summary-delivery-fee', formatMoney(totalDeliveryFee));
            
            if (document.getElementById('summary-free-delivery-badge')) {
                document.getElementById('summary-free-delivery-badge').style.display = 
                    totalDeliveryFee === 0 ? 'inline-block' : 'none';
            }
            
            // Mettre à jour la ligne de remise dans le récapitulatif
            if (document.getElementById('summary-discount-row')) {
                document.getElementById('summary-discount-row').style.display = discount > 0 ? 'table-row' : 'none';
                if (document.getElementById('summary-discount-value')) {
                    document.getElementById('summary-discount-value').textContent = '- ' + formatMoney(discount);
                }
            }
            
            updateElementText('summary-total', formatMoney(grandTotal));
            
            // Paiement
            updateElementText('payment-total', formatMoney(grandTotal));
            updateElementText('payment-articles-price', formatMoney(totalServicesPrice));
            updateElementText('payment-delivery-fee', formatMoney(totalDeliveryFee));
            
            if (document.getElementById('payment-free-delivery-badge')) {
                document.getElementById('payment-free-delivery-badge').style.display = 
                    totalDeliveryFee === 0 ? 'inline-block' : 'none';
            }
            
            // Mettre à jour la ligne de remise dans le paiement
            if (document.getElementById('payment-discount-row')) {
                document.getElementById('payment-discount-row').style.display = discount > 0 ? 'table-row' : 'none';
                if (document.getElementById('payment-discount-value')) {
                    document.getElementById('payment-discount-value').textContent = '- ' + formatMoney(discount);
                }
            }
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
    
    // Fonction utilitaire pour formater les dates
    function formatDate(dateString) {
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        const date = new Date(dateString);
        return date.toLocaleDateString('fr-FR', options);
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
    
    // 6. Écouteurs pour les codes promo
    const useCouponCheckbox = document.getElementById('use_coupon');
    const couponFieldsSection = document.getElementById('coupon_fields');
    const availableCouponsList = document.getElementById('available-coupons-list');
    
    if (useCouponCheckbox && couponFieldsSection) {
        useCouponCheckbox.addEventListener('change', function() {
            couponFieldsSection.style.display = this.checked ? 'block' : 'none';
            
            // Masquer la liste des coupons disponibles
            if (availableCouponsList) {
                availableCouponsList.style.display = 'none';
            }
            
            calculateTotals();
        });
    }
    
    // Écouteur pour le bouton d'application du code promo
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
    
    // Initialiser l'affichage et calculer les totaux
    updateStepDisplay();
    calculateTotals();
}); 