class QuotaOrderForm {
    constructor() {
        this.form = document.getElementById('quotaOrderForm');
        this.steps = document.querySelectorAll('.form-step');
        this.progressSteps = document.querySelectorAll('.step-custom');
        this.progressLines = document.querySelectorAll('.progress-line-custom');
        this.currentStep = 1;
        
        this.initializeEventListeners();
    }

    initializeEventListeners() {
        // Navigation buttons
        document.getElementById('step1Next')?.addEventListener('click', () => this.nextStep());
        document.getElementById('step2Prev')?.addEventListener('click', () => this.prevStep());
        document.getElementById('step2Next')?.addEventListener('click', () => this.nextStep());
        document.getElementById('step3Prev')?.addEventListener('click', () => this.prevStep());
        document.getElementById('step3Next')?.addEventListener('click', () => this.nextStep());
        document.getElementById('step4Prev')?.addEventListener('click', () => this.prevStep());
        document.getElementById('step4Next')?.addEventListener('click', () => this.nextStep());
        document.getElementById('step5Prev')?.addEventListener('click', () => this.prevStep());

        // Same address checkbox
        const sameAddressCheckbox = document.getElementById('same_address');
        if (sameAddressCheckbox) {
            sameAddressCheckbox.addEventListener('change', () => this.handleSameAddressChange());
        }

        // Article quantity changes
        document.addEventListener('change', (e) => {
            if (e.target.matches('.article-quantity')) {
                this.updateArticleQuantity(e.target);
            }
        });
    }

    showStep(stepNumber) {
        // Hide all steps
        this.steps.forEach(step => step.classList.remove('active'));
        
        // Show current step
        const currentStepElement = document.getElementById(`step-${stepNumber}`);
        if (currentStepElement) {
            currentStepElement.classList.add('active');
        }

        // Update progress bar
        this.progressSteps.forEach((step, index) => {
            if (index + 1 < stepNumber) {
                step.classList.add('completed');
                step.classList.remove('active');
            } else if (index + 1 === stepNumber) {
                step.classList.add('active');
                step.classList.remove('completed');
            } else {
                step.classList.remove('active', 'completed');
            }
        });

        // Update progress lines
        this.progressLines.forEach((line, index) => {
            if (index + 1 < stepNumber) {
                line.classList.add('active');
            } else {
                line.classList.remove('active');
            }
        });

        // Scroll to top
        window.scrollTo({ top: 0, behavior: 'smooth' });

        // Update current step
        this.currentStep = stepNumber;
    }

    nextStep() {
        if (this.validateCurrentStep()) {
            this.showStep(this.currentStep + 1);
        }
    }

    prevStep() {
        this.showStep(this.currentStep - 1);
    }

    validateCurrentStep() {
        const validations = {
            1: () => this.validateStep1(),
            2: () => this.validateStep2(),
            3: () => this.validateStep3(),
            4: () => this.validateStep4(),
            5: () => this.validateStep5()
        };

        return validations[this.currentStep]?.() ?? true;
    }

    validateStep1() {
        const requiredFields = ['collection_date', 'collection_time_slot', 'collection_address_id'];
        return this.validateRequiredFields(requiredFields);
    }

    validateStep2() {
        const requiredFields = ['delivery_date', 'delivery_time_slot'];
        if (!document.getElementById('same_address').checked) {
            requiredFields.push('delivery_address_id');
        }
        return this.validateRequiredFields(requiredFields);
    }

    validateStep3() {
        // Vérifier si au moins un article a été sélectionné
        const selectedArticles = document.querySelectorAll('.article-quantity[value="1"]');
        if (selectedArticles.length === 0) {
            Swal.fire({
                title: 'Attention',
                text: 'Veuillez sélectionner au moins un article',
                icon: 'warning'
            });
            return false;
        }
        return true;
    }

    validateStep4() {
        // Validation du récapitulatif
        return true;
    }

    validateStep5() {
        // Validation du paiement
        return true;
    }

    validateRequiredFields(fields) {
        let isValid = true;
        
        fields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            const feedbackElement = field.nextElementSibling;
            
            if (!field.value.trim()) {
                isValid = false;
                field.classList.add('is-invalid');
                if (feedbackElement && feedbackElement.classList.contains('invalid-feedback')) {
                    feedbackElement.textContent = 'Ce champ est requis';
                }
            } else {
                field.classList.remove('is-invalid');
                if (feedbackElement && feedbackElement.classList.contains('invalid-feedback')) {
                    feedbackElement.textContent = '';
                }
            }
        });
        
        return isValid;
    }

    handleSameAddressChange() {
        const sameAddressCheckbox = document.getElementById('same_address');
        const deliveryAddressSection = document.getElementById('delivery_address_section');
        const deliveryAddressSelect = document.getElementById('delivery_address_id');
        
        if (sameAddressCheckbox.checked) {
            deliveryAddressSection.style.display = 'none';
            deliveryAddressSelect.value = document.getElementById('collection_address_id').value;
        } else {
            deliveryAddressSection.style.display = 'block';
        }
    }

    updateArticleQuantity(input) {
        const articleId = input.dataset.articleId;
        const quantity = parseInt(input.value);
        
        // Mettre à jour l'affichage du prix total si nécessaire
        this.updateTotalPrice();
    }

    updateTotalPrice() {
        // Logique pour mettre à jour le prix total
        // À implémenter selon vos besoins
    }
}

// Initialiser le formulaire quand le DOM est chargé
document.addEventListener('DOMContentLoaded', () => {
    window.quotaOrderForm = new QuotaOrderForm();
}); 