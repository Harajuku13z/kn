// Order navigation script - Simplified version to fix step navigation
document.addEventListener('DOMContentLoaded', function() {
    console.log('🔍 [DEBUG] Order navigation script loaded and executing');
    
    // 1. Find DOM elements with debugging
    const steps = Array.from(document.querySelectorAll('.form-step'));
    console.log('🔍 [DEBUG] Found ' + steps.length + ' form steps in the DOM');
    steps.forEach((step, idx) => {
        console.log('🔍 [DEBUG] Step ' + (idx+1) + ' has ID: ' + step.id + ' and is ' + (step.classList.contains('active') ? 'active' : 'inactive'));
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
    
    // 2. Define current step
    let currentStep = 1;
    const totalSteps = steps.length;
    console.log('🔍 [DEBUG] Total steps:', totalSteps);
    
    // 3. Core navigation functions
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
    }
    
    // Go to next step with debug
    function goToNextStep(event) {
        if (event) event.preventDefault();
        console.log('📍 [NAVIGATION] Next button clicked, current step:', currentStep);
        
        if (currentStep < totalSteps) {
            currentStep++;
            console.log('📍 [NAVIGATION] Moving to step', currentStep);
            updateStepDisplay();
            window.scrollTo(0, 0);
        } else {
            console.log('📍 [NAVIGATION] On last step, submitting form');
            const form = document.getElementById('orderForm');
            if (form) form.submit();
        }
    }
    
    // Go to previous step with debug
    function goToPrevStep(event) {
        if (event) event.preventDefault();
        console.log('📍 [NAVIGATION] Previous button clicked, current step:', currentStep);
        
        if (currentStep > 1) {
            currentStep--;
            console.log('📍 [NAVIGATION] Moving to step', currentStep);
            updateStepDisplay();
            window.scrollTo(0, 0);
        }
    }
    
    // 4. Attach event listeners using multiple methods for redundancy
    
    // Method 1: Direct onclick property
    if (nextBtn) {
        console.log('📍 [SETUP] Adding onclick to nextBtn');
        
        // Remove any existing handlers by cloning the button
        const newNextBtn = nextBtn.cloneNode(true);
        nextBtn.parentNode.replaceChild(newNextBtn, nextBtn);
        
        // Add the handler to the new button
        newNextBtn.onclick = goToNextStep;
    }
    
    if (prevBtn) {
        console.log('📍 [SETUP] Adding onclick to prevBtn');
        
        // Remove any existing handlers by cloning the button
        const newPrevBtn = prevBtn.cloneNode(true);
        prevBtn.parentNode.replaceChild(newPrevBtn, prevBtn);
        
        // Add the handler to the new button
        newPrevBtn.onclick = goToPrevStep;
    }
    
    // Method 2: Delegation on document level for maximum reliability
    document.addEventListener('click', function(e) {
        const target = e.target;
        
        // Next button clicked (or its children)
        if (target.matches('#nextStepBtn, .next-step, #nextStepBtn *, .next-step *')) {
            console.log('📍 [EVENT] Next button click captured via delegation');
            e.preventDefault();
            goToNextStep();
        }
        
        // Previous button clicked (or its children)
        if (target.matches('#prevStepBtn, .prev-step, #prevStepBtn *, .prev-step *')) {
            console.log('📍 [EVENT] Previous button click captured via delegation');
            e.preventDefault();
            goToPrevStep();
        }
    });
    
    // Initialize display
    console.log('📍 [INIT] Initializing step display');
    updateStepDisplay();
    
    // Override any other scripts' attempt to handle these buttons
    console.log('📍 [SETUP] Setup complete - navigation ready');
});

// Keep delivery fee calculation code (simplified version)
document.addEventListener('DOMContentLoaded', function() {
    console.log('🧮 [CALC] Setting up calculators');
    
    // Access global data
    const deliveryFees = window.deliveryFeesData || [];
    const availableVouchers = window.availableVouchers || [];
    const pricePerKg = window.laundryPricePerKg || 1000;
    
    console.log('🧮 [CALC] Data loaded:', {
        'Delivery fees': deliveryFees.length,
        'Vouchers': availableVouchers.length,
        'Price per kg': pricePerKg
    });
    
    // Other calculator code (kept from original)
    // This is separate from navigation code to avoid conflicts
});