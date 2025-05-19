@extends('layouts.dashboard')

@section('title', 'Détails du pressing - KLINKLIN')

@push('styles')
<style>
    :root {
        --klin-primary: #4A148C;
        --klin-primary-dark: #38006b;
        --klin-secondary: #f26d50;
        --klin-light-bg: #f8f5fc;
        --klin-border-color: #e0d8e7;
    }
    
    .pressing-header {
        background-color: var(--klin-light-bg);
        border-radius: 10px;
        padding: 2rem;
        margin-bottom: 2rem;
    }
    
    .pressing-header .pressing-image {
        height: 200px;
        width: 100%;
        object-fit: cover;
        border-radius: 10px;
    }
    
    .pressing-name {
        color: var(--klin-primary);
        font-weight: 700;
    }
    
    .rating {
        color: #FFD700;
    }
    
    .badge {
        font-size: 0.8rem;
        padding: 0.4rem 0.6rem;
        margin-right: 0.3rem;
    }
    
    .tab-content {
        padding: 1.5rem;
        border: 1px solid var(--klin-border-color);
        border-top: none;
        border-radius: 0 0 10px 10px;
    }
    
    .nav-tabs .nav-link {
        color: var(--klin-primary);
        border: 1px solid transparent;
    }
    
    .nav-tabs .nav-link.active {
        color: var(--klin-primary);
        border-color: var(--klin-border-color) var(--klin-border-color) transparent;
        font-weight: 600;
    }
    
    .service-card {
        border: 1px solid var(--klin-border-color);
        border-radius: 10px;
        transition: transform 0.2s, box-shadow 0.2s;
        margin-bottom: 1.5rem;
    }
    
    .service-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }
    
    .service-card .card-header {
        background-color: var(--klin-light-bg);
        border-bottom: 1px solid var(--klin-border-color);
        font-weight: 600;
        color: var(--klin-primary);
    }
    
    .quantity-selector {
        display: flex;
        align-items: center;
    }
    
    .quantity-selector button {
        width: 40px;
        height: 40px;
        background-color: var(--klin-light-bg);
        border: 1px solid var(--klin-border-color);
        font-weight: bold;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .quantity-selector input {
        width: 60px;
        text-align: center;
        border: 1px solid var(--klin-border-color);
        height: 40px;
    }
    
    .service-price {
        font-weight: 600;
        color: var(--klin-primary);
        font-size: 1.1rem;
    }
    
    .form-control, .form-select {
        border-radius: 0.375rem;
        border: 1px solid var(--klin-border-color);
    }
    
    .btn-klin-primary {
        background-color: var(--klin-primary);
        border-color: var(--klin-primary);
        color: white;
        border-radius: 50px;
        padding: 0.6rem 1.5rem;
        font-weight: 500;
    }
    
    .btn-klin-primary:hover {
        background-color: var(--klin-primary-dark);
        border-color: var(--klin-primary-dark);
    }
    
    .progress-bar-custom {
        margin-bottom: 3rem !important;
    }
    
    .progress-bar-custom .step-custom {
        flex-basis: 100px;
        flex-shrink: 0;
        color: #6c757d;
        font-size: 0.85em;
    }
    
    .progress-bar-custom .step-icon {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        background-color: #e0e0e0;
        color: white;
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 0 auto 8px auto;
        border: 2px solid #e0e0e0;
        font-weight: bold;
    }
    
    .progress-bar-custom .step-icon span {
        font-size: 0.9rem;
    }
    
    .progress-bar-custom .step-custom.active .step-icon {
        background-color: var(--klin-primary);
        border-color: var(--klin-primary);
    }
    
    .progress-bar-custom .step-custom.active .step-text {
        color: var(--klin-primary);
        font-weight: bold;
    }
    
    .progress-bar-custom .step-custom.completed .step-icon {
        background-color: #28a745;
        border-color: #28a745;
    }
    
    .progress-bar-custom .step-custom.completed .step-text {
        color: #28a745;
    }
    
    .progress-bar-custom .progress-line-custom {
        height: 4px;
        background-color: #e0e0e0;
        margin-top: 17px;
    }
    
    .progress-bar-custom .progress-line-custom.active {
        background-color: var(--klin-primary);
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Barre de progression -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="progress-bar-custom d-flex justify-content-between align-items-center">
                <div class="step-custom text-center completed" data-step="1">
                    <div class="step-text">Type</div>
                    <div class="step-icon"><i class="bi bi-check"></i></div>
                </div>
                <div class="progress-line-custom flex-grow-1 active"></div>
                <div class="step-custom text-center active" data-step="2">
                    <div class="step-text">Pressing</div>
                    <div class="step-icon"><span>2</span></div>
                </div>
                <div class="progress-line-custom flex-grow-1"></div>
                <div class="step-custom text-center" data-step="3">
                    <div class="step-text">Services</div>
                    <div class="step-icon"><span>3</span></div>
                </div>
                <div class="progress-line-custom flex-grow-1"></div>
                <div class="step-custom text-center" data-step="4">
                    <div class="step-text">Détails</div>
                    <div class="step-icon"><span>4</span></div>
                </div>
                <div class="progress-line-custom flex-grow-1"></div>
                <div class="step-custom text-center" data-step="5">
                    <div class="step-text">Paiement</div>
                    <div class="step-icon"><span>5</span></div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="display-5 fw-bold pressing-name">{{ $pressing->name }}</h1>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="{{ route('orders.create.pressing') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Retour à la liste
            </a>
        </div>
    </div>
    
    <!-- Pressing Details Header -->
    <div class="pressing-header">
        <div class="row">
            <div class="col-md-4">
                @if($pressing->image)
                    <img src="{{ asset('storage/' . $pressing->image) }}" class="pressing-image" alt="{{ $pressing->name }}">
                @else
                    <img src="{{ asset('images/default-pressing.jpg') }}" class="pressing-image" alt="{{ $pressing->name }}">
                @endif
            </div>
            <div class="col-md-8">
                <div class="mb-3">
                    <div class="rating">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= $pressing->rating)
                                <i class="bi bi-star-fill"></i>
                            @elseif($i - 0.5 <= $pressing->rating)
                                <i class="bi bi-star-half"></i>
                            @else
                                <i class="bi bi-star"></i>
                            @endif
                        @endfor
                        <span class="text-muted ms-2">({{ $pressing->reviews_count ?? 0 }} avis)</span>
                    </div>
                </div>
                
                <div class="mb-3">
                    @if($pressing->is_express)
                        <span class="badge bg-danger">Express</span>
                    @endif
                    @if($pressing->has_delivery)
                        <span class="badge bg-success">Livraison</span>
                    @endif
                    @if($pressing->eco_friendly)
                        <span class="badge bg-info">Éco-responsable</span>
                    @endif
                </div>
                
                <p class="mb-2">
                    <i class="bi bi-geo-alt text-secondary"></i> <strong>Adresse:</strong> {{ $pressing->address }}
                </p>
                <p class="mb-2">
                    <i class="bi bi-map text-secondary"></i> <strong>Quartier:</strong> {{ $pressing->neighborhood }}
                </p>
                <p class="mb-2">
                    <i class="bi bi-telephone text-secondary"></i> <strong>Téléphone:</strong> {{ $pressing->phone }}
                </p>
                <p class="mb-2">
                    <i class="bi bi-clock text-secondary"></i> <strong>Horaires:</strong> {{ $pressing->opening_hours ?? 'Non spécifié' }}
                </p>
                <p class="mb-2">
                    <i class="bi bi-hourglass-split text-secondary"></i> <strong>Délai de livraison:</strong> {{ $pressing->delivery_time ?? 'Standard (48h)' }}
                </p>
                
                @if($pressing->description)
                    <p class="mt-3">{{ $pressing->description }}</p>
                @endif
            </div>
        </div>
    </div>
    
    <form action="{{ route('orders.create.pressing.services') }}" method="POST">
        @csrf
        <input type="hidden" name="pressing_id" value="{{ $pressing->id }}">
        
        <!-- Services Selection -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 text-klin-primary">
                    <i class="bi bi-list-check me-2"></i> Services disponibles
                </h5>
            </div>
            <div class="card-body">
                <ul class="nav nav-tabs" id="servicesTab" role="tablist">
                    @foreach($categories as $index => $category)
                        <li class="nav-item" role="presentation">
                            <button class="nav-link {{ $index === 0 ? 'active' : '' }}" 
                                    id="tab-{{ $category->id }}" 
                                    data-bs-toggle="tab" 
                                    data-bs-target="#content-{{ $category->id }}" 
                                    type="button" 
                                    role="tab" 
                                    aria-controls="content-{{ $category->id }}" 
                                    aria-selected="{{ $index === 0 ? 'true' : 'false' }}">
                                {{ $category->name }}
                            </button>
                        </li>
                    @endforeach
                </ul>
                
                <div class="tab-content" id="servicesTabContent">
                    @foreach($categories as $index => $category)
                        <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}" 
                             id="content-{{ $category->id }}" 
                             role="tabpanel" 
                             aria-labelledby="tab-{{ $category->id }}">
                            
                            <div class="row">
                                @foreach($services->where('category_id', $category->id) as $service)
                                    <div class="col-md-6">
                                        <div class="service-card card">
                                            <div class="card-header d-flex justify-content-between align-items-center">
                                                <span>{{ $service->name }}</span>
                                                <span class="service-price">{{ number_format($service->price, 0, ',', ' ') }} FCFA</span>
                                            </div>
                                            <div class="card-body">
                                                @if($service->description)
                                                    <p class="card-text mb-3">{{ $service->description }}</p>
                                                @endif
                                                
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div class="quantity-selector">
                                                        <button type="button" class="btn-decrease" onclick="decreaseQuantity('service-{{ $service->id }}')">-</button>
                                                        <input type="number" id="service-{{ $service->id }}" name="services[{{ $service->id }}]" value="0" min="0" max="50" class="form-control">
                                                        <button type="button" class="btn-increase" onclick="increaseQuantity('service-{{ $service->id }}')">+</button>
                                                    </div>
                                                    
                                                    <div class="service-subtotal" id="subtotal-{{ $service->id }}">
                                                        0 FCFA
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                            @if($services->where('category_id', $category->id)->count() === 0)
                                <div class="alert alert-info">
                                    Aucun service disponible dans cette catégorie.
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 text-klin-primary">
                            <i class="bi bi-card-checklist me-2"></i> Récapitulatif
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table" id="orderSummary">
                                <thead>
                                    <tr>
                                        <th>Service</th>
                                        <th>Quantité</th>
                                        <th class="text-end">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr id="noItemsRow">
                                        <td colspan="3" class="text-center">Aucun service sélectionné</td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="2">Total</th>
                                        <th class="text-end" id="orderTotal">0 FCFA</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 text-klin-primary">
                            <i class="bi bi-info-circle me-2"></i> Informations complémentaires
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="special_instructions" class="form-label">Instructions spéciales</label>
                            <textarea class="form-control" id="special_instructions" name="special_instructions" rows="3" placeholder="Instructions particulières pour le pressing..."></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="d-flex justify-content-between mt-4 mb-5">
            <a href="{{ route('orders.create.pressing') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Retour
            </a>
            
            <button type="submit" class="btn btn-klin-primary" id="continueButton" disabled>
                Continuer <i class="bi bi-arrow-right"></i>
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize service prices map
    const servicePrices = {
        @foreach($services as $service)
            {{ $service->id }}: {{ $service->price }},
        @endforeach
    };
    
    // Update all service inputs when quantities change
    const serviceInputs = document.querySelectorAll('input[name^="services["]');
    serviceInputs.forEach(input => {
        input.addEventListener('change', function() {
            updateOrderSummary();
        });
    });
    
    // Initial update
    updateOrderSummary();
    
    // Function to update the order summary
    function updateOrderSummary() {
        const summaryTableBody = document.querySelector('#orderSummary tbody');
        const noItemsRow = document.getElementById('noItemsRow');
        const continueButton = document.getElementById('continueButton');
        let totalAmount = 0;
        let hasItems = false;
        
        // Clear existing rows except the "no items" row
        const existingRows = summaryTableBody.querySelectorAll('tr:not(#noItemsRow)');
        existingRows.forEach(row => row.remove());
        
        // Add new rows for selected services
        serviceInputs.forEach(input => {
            const serviceId = input.id.replace('service-', '');
            const quantity = parseInt(input.value);
            
            if (quantity > 0) {
                hasItems = true;
                
                // Get service name
                const serviceCard = input.closest('.service-card');
                const serviceName = serviceCard.querySelector('.card-header span:first-child').textContent;
                
                // Calculate subtotal
                const price = servicePrices[serviceId];
                const subtotal = price * quantity;
                totalAmount += subtotal;
                
                // Create and append row
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${serviceName}</td>
                    <td>${quantity}</td>
                    <td class="text-end">${formatPrice(subtotal)} FCFA</td>
                `;
                summaryTableBody.insertBefore(row, noItemsRow);
                
                // Update service subtotal display
                const subtotalElement = document.getElementById(`subtotal-${serviceId}`);
                if (subtotalElement) {
                    subtotalElement.textContent = `${formatPrice(subtotal)} FCFA`;
                }
            } else {
                // Reset service subtotal display
                const subtotalElement = document.getElementById(`subtotal-${serviceId}`);
                if (subtotalElement) {
                    subtotalElement.textContent = '0 FCFA';
                }
            }
        });
        
        // Update total
        document.getElementById('orderTotal').textContent = `${formatPrice(totalAmount)} FCFA`;
        
        // Show/hide no items row
        noItemsRow.style.display = hasItems ? 'none' : '';
        
        // Enable/disable continue button
        continueButton.disabled = !hasItems;
    }
});

// Helper functions for quantity adjustment
function increaseQuantity(inputId) {
    const input = document.getElementById(inputId);
    const currentValue = parseInt(input.value);
    if (currentValue < parseInt(input.max)) {
        input.value = currentValue + 1;
        input.dispatchEvent(new Event('change'));
    }
}

function decreaseQuantity(inputId) {
    const input = document.getElementById(inputId);
    const currentValue = parseInt(input.value);
    if (currentValue > parseInt(input.min)) {
        input.value = currentValue - 1;
        input.dispatchEvent(new Event('change'));
    }
}

function formatPrice(price) {
    return new Intl.NumberFormat('fr-FR').format(price);
}
</script>
@endpush 