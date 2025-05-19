<!-- Simulateur de Prix -->
<div class="simulator-card">
    <form id="weightSimulator">
        <div class="row">
            <!-- Colonne de gauche (70%) -->
            <div class="col-lg-8 border-end pe-4">
                <h4 class="mb-4">Sélection des articles</h4>
                
                <!-- Onglets pour les catégories -->
                <ul class="nav nav-pills mb-4 flex-nowrap overflow-auto" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#clothes" type="button">Vêtements</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#household" type="button">Linge de maison</button>
                    </li>
                </ul>

                <!-- Contenu des onglets -->
                <div class="tab-content">
                    <!-- Vêtements -->
                    <div class="tab-pane fade show active" id="clothes">
                        <div class="row g-3 items-container">
                            <!-- Articles de vêtements -->
                            <div class="col-md-6">
                                <div class="article-item d-flex align-items-center p-3 border rounded">
                                    <div class="article-info flex-grow-1">
                                        <h5 class="mb-1">Chemise</h5>
                                        <small class="text-muted">Poids : 0.3 kg</small>
                                    </div>
                                    <div class="article-quantity">
                                        <div class="input-group input-group-sm" style="width: 120px;">
                                            <button type="button" class="btn btn-outline-secondary quantity-btn" data-action="decrease">-</button>
                                            <input type="number" class="form-control text-center quantity-input" 
                                                   data-weight="0.3" value="0" min="0">
                                            <button type="button" class="btn btn-outline-secondary quantity-btn" data-action="increase">+</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="article-item d-flex align-items-center p-3 border rounded">
                                    <div class="article-info flex-grow-1">
                                        <h5 class="mb-1">Pantalon</h5>
                                        <small class="text-muted">Poids : 0.5 kg</small>
                                    </div>
                                    <div class="article-quantity">
                                        <div class="input-group input-group-sm" style="width: 120px;">
                                            <button type="button" class="btn btn-outline-secondary quantity-btn" data-action="decrease">-</button>
                                            <input type="number" class="form-control text-center quantity-input" 
                                                   data-weight="0.5" value="0" min="0">
                                            <button type="button" class="btn btn-outline-secondary quantity-btn" data-action="increase">+</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="article-item d-flex align-items-center p-3 border rounded">
                                    <div class="article-info flex-grow-1">
                                        <h5 class="mb-1">Robe</h5>
                                        <small class="text-muted">Poids : 0.6 kg</small>
                                    </div>
                                    <div class="article-quantity">
                                        <div class="input-group input-group-sm" style="width: 120px;">
                                            <button type="button" class="btn btn-outline-secondary quantity-btn" data-action="decrease">-</button>
                                            <input type="number" class="form-control text-center quantity-input" 
                                                   data-weight="0.6" value="0" min="0">
                                            <button type="button" class="btn btn-outline-secondary quantity-btn" data-action="increase">+</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="article-item d-flex align-items-center p-3 border rounded">
                                    <div class="article-info flex-grow-1">
                                        <h5 class="mb-1">T-shirt</h5>
                                        <small class="text-muted">Poids : 0.2 kg</small>
                                    </div>
                                    <div class="article-quantity">
                                        <div class="input-group input-group-sm" style="width: 120px;">
                                            <button type="button" class="btn btn-outline-secondary quantity-btn" data-action="decrease">-</button>
                                            <input type="number" class="form-control text-center quantity-input" 
                                                   data-weight="0.2" value="0" min="0">
                                            <button type="button" class="btn btn-outline-secondary quantity-btn" data-action="increase">+</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="article-item d-flex align-items-center p-3 border rounded">
                                    <div class="article-info flex-grow-1">
                                        <h5 class="mb-1">Pull/Sweat</h5>
                                        <small class="text-muted">Poids : 0.7 kg</small>
                                    </div>
                                    <div class="article-quantity">
                                        <div class="input-group input-group-sm" style="width: 120px;">
                                            <button type="button" class="btn btn-outline-secondary quantity-btn" data-action="decrease">-</button>
                                            <input type="number" class="form-control text-center quantity-input" 
                                                   data-weight="0.7" value="0" min="0">
                                            <button type="button" class="btn btn-outline-secondary quantity-btn" data-action="increase">+</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="article-item d-flex align-items-center p-3 border rounded">
                                    <div class="article-info flex-grow-1">
                                        <h5 class="mb-1">Jupe</h5>
                                        <small class="text-muted">Poids : 0.4 kg</small>
                                    </div>
                                    <div class="article-quantity">
                                        <div class="input-group input-group-sm" style="width: 120px;">
                                            <button type="button" class="btn btn-outline-secondary quantity-btn" data-action="decrease">-</button>
                                            <input type="number" class="form-control text-center quantity-input" 
                                                   data-weight="0.4" value="0" min="0">
                                            <button type="button" class="btn btn-outline-secondary quantity-btn" data-action="increase">+</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="article-item d-flex align-items-center p-3 border rounded">
                                    <div class="article-info flex-grow-1">
                                        <h5 class="mb-1">Veste</h5>
                                        <small class="text-muted">Poids : 0.8 kg</small>
                                    </div>
                                    <div class="article-quantity">
                                        <div class="input-group input-group-sm" style="width: 120px;">
                                            <button type="button" class="btn btn-outline-secondary quantity-btn" data-action="decrease">-</button>
                                            <input type="number" class="form-control text-center quantity-input" 
                                                   data-weight="0.8" value="0" min="0">
                                            <button type="button" class="btn btn-outline-secondary quantity-btn" data-action="increase">+</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="article-item d-flex align-items-center p-3 border rounded">
                                    <div class="article-info flex-grow-1">
                                        <h5 class="mb-1">Jean</h5>
                                        <small class="text-muted">Poids : 0.6 kg</small>
                                    </div>
                                    <div class="article-quantity">
                                        <div class="input-group input-group-sm" style="width: 120px;">
                                            <button type="button" class="btn btn-outline-secondary quantity-btn" data-action="decrease">-</button>
                                            <input type="number" class="form-control text-center quantity-input" 
                                                   data-weight="0.6" value="0" min="0">
                                            <button type="button" class="btn btn-outline-secondary quantity-btn" data-action="increase">+</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Nouveaux articles -->
                            <div class="col-md-6">
                                <div class="article-item d-flex align-items-center p-3 border rounded">
                                    <div class="article-info flex-grow-1">
                                        <h5 class="mb-1">Costume 2 pièces</h5>
                                        <small class="text-muted">Poids : 1.2 kg</small>
                                    </div>
                                    <div class="article-quantity">
                                        <div class="input-group input-group-sm" style="width: 120px;">
                                            <button type="button" class="btn btn-outline-secondary quantity-btn" data-action="decrease">-</button>
                                            <input type="number" class="form-control text-center quantity-input" 
                                                   data-weight="1.2" value="0" min="0">
                                            <button type="button" class="btn btn-outline-secondary quantity-btn" data-action="increase">+</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="article-item d-flex align-items-center p-3 border rounded">
                                    <div class="article-info flex-grow-1">
                                        <h5 class="mb-1">Costume 3 pièces</h5>
                                        <small class="text-muted">Poids : 1.8 kg</small>
                                    </div>
                                    <div class="article-quantity">
                                        <div class="input-group input-group-sm" style="width: 120px;">
                                            <button type="button" class="btn btn-outline-secondary quantity-btn" data-action="decrease">-</button>
                                            <input type="number" class="form-control text-center quantity-input" 
                                                   data-weight="1.8" value="0" min="0">
                                            <button type="button" class="btn btn-outline-secondary quantity-btn" data-action="increase">+</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="article-item d-flex align-items-center p-3 border rounded">
                                    <div class="article-info flex-grow-1">
                                        <h5 class="mb-1">Cravate</h5>
                                        <small class="text-muted">Poids : 0.1 kg</small>
                                    </div>
                                    <div class="article-quantity">
                                        <div class="input-group input-group-sm" style="width: 120px;">
                                            <button type="button" class="btn btn-outline-secondary quantity-btn" data-action="decrease">-</button>
                                            <input type="number" class="form-control text-center quantity-input" 
                                                   data-weight="0.1" value="0" min="0">
                                            <button type="button" class="btn btn-outline-secondary quantity-btn" data-action="increase">+</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="article-item d-flex align-items-center p-3 border rounded">
                                    <div class="article-info flex-grow-1">
                                        <h5 class="mb-1">Robe de soirée</h5>
                                        <small class="text-muted">Poids : 1 kg</small>
                                    </div>
                                    <div class="article-quantity">
                                        <div class="input-group input-group-sm" style="width: 120px;">
                                            <button type="button" class="btn btn-outline-secondary quantity-btn" data-action="decrease">-</button>
                                            <input type="number" class="form-control text-center quantity-input" 
                                                   data-weight="1" value="0" min="0">
                                            <button type="button" class="btn btn-outline-secondary quantity-btn" data-action="increase">+</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="article-item d-flex align-items-center p-3 border rounded">
                                    <div class="article-info flex-grow-1">
                                        <h5 class="mb-1">Manteau</h5>
                                        <small class="text-muted">Poids : 1.5 kg</small>
                                    </div>
                                    <div class="article-quantity">
                                        <div class="input-group input-group-sm" style="width: 120px;">
                                            <button type="button" class="btn btn-outline-secondary quantity-btn" data-action="decrease">-</button>
                                            <input type="number" class="form-control text-center quantity-input" 
                                                   data-weight="1.5" value="0" min="0">
                                            <button type="button" class="btn btn-outline-secondary quantity-btn" data-action="increase">+</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Linge de maison -->
                    <div class="tab-pane fade" id="household">
                        <div class="row g-3 items-container">
                            <div class="col-md-6">
                                <div class="article-item d-flex align-items-center p-3 border rounded">
                                    <div class="article-info flex-grow-1">
                                        <h5 class="mb-1">Draps (paire)</h5>
                                        <small class="text-muted">Poids : 1.5 kg</small>
                                    </div>
                                    <div class="article-quantity">
                                        <div class="input-group input-group-sm" style="width: 120px;">
                                            <button type="button" class="btn btn-outline-secondary quantity-btn" data-action="decrease">-</button>
                                            <input type="number" class="form-control text-center quantity-input" 
                                                   data-weight="1.5" value="0" min="0">
                                            <button type="button" class="btn btn-outline-secondary quantity-btn" data-action="increase">+</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="article-item d-flex align-items-center p-3 border rounded">
                                    <div class="article-info flex-grow-1">
                                        <h5 class="mb-1">Serviettes (lot de 5)</h5>
                                        <small class="text-muted">Poids : 1 kg</small>
                                    </div>
                                    <div class="article-quantity">
                                        <div class="input-group input-group-sm" style="width: 120px;">
                                            <button type="button" class="btn btn-outline-secondary quantity-btn" data-action="decrease">-</button>
                                            <input type="number" class="form-control text-center quantity-input" 
                                                   data-weight="1" value="0" min="0">
                                            <button type="button" class="btn btn-outline-secondary quantity-btn" data-action="increase">+</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="article-item d-flex align-items-center p-3 border rounded">
                                    <div class="article-info flex-grow-1">
                                        <h5 class="mb-1">Couette</h5>
                                        <small class="text-muted">Poids : 2 kg</small>
                                    </div>
                                    <div class="article-quantity">
                                        <div class="input-group input-group-sm" style="width: 120px;">
                                            <button type="button" class="btn btn-outline-secondary quantity-btn" data-action="decrease">-</button>
                                            <input type="number" class="form-control text-center quantity-input" 
                                                   data-weight="2" value="0" min="0">
                                            <button type="button" class="btn btn-outline-secondary quantity-btn" data-action="increase">+</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Nouveaux articles -->
                            <div class="col-md-6">
                                <div class="article-item d-flex align-items-center p-3 border rounded">
                                    <div class="article-info flex-grow-1">
                                        <h5 class="mb-1">Nappe</h5>
                                        <small class="text-muted">Poids : 0.8 kg</small>
                                    </div>
                                    <div class="article-quantity">
                                        <div class="input-group input-group-sm" style="width: 120px;">
                                            <button type="button" class="btn btn-outline-secondary quantity-btn" data-action="decrease">-</button>
                                            <input type="number" class="form-control text-center quantity-input" 
                                                   data-weight="0.8" value="0" min="0">
                                            <button type="button" class="btn btn-outline-secondary quantity-btn" data-action="increase">+</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="article-item d-flex align-items-center p-3 border rounded">
                                    <div class="article-info flex-grow-1">
                                        <h5 class="mb-1">Rideaux (par paire)</h5>
                                        <small class="text-muted">Poids : 2.5 kg</small>
                                    </div>
                                    <div class="article-quantity">
                                        <div class="input-group input-group-sm" style="width: 120px;">
                                            <button type="button" class="btn btn-outline-secondary quantity-btn" data-action="decrease">-</button>
                                            <input type="number" class="form-control text-center quantity-input" 
                                                   data-weight="2.5" value="0" min="0">
                                            <button type="button" class="btn btn-outline-secondary quantity-btn" data-action="increase">+</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="article-item d-flex align-items-center p-3 border rounded">
                                    <div class="article-info flex-grow-1">
                                        <h5 class="mb-1">Tapis (petit)</h5>
                                        <small class="text-muted">Poids : 2 kg</small>
                                    </div>
                                    <div class="article-quantity">
                                        <div class="input-group input-group-sm" style="width: 120px;">
                                            <button type="button" class="btn btn-outline-secondary quantity-btn" data-action="decrease">-</button>
                                            <input type="number" class="form-control text-center quantity-input" 
                                                   data-weight="2" value="0" min="0">
                                            <button type="button" class="btn btn-outline-secondary quantity-btn" data-action="increase">+</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Colonne de droite (30%) -->
            <div class="col-lg-4 mt-4 mt-lg-0">
                <div class="summary-card">
                    <h4 class="mb-4">Résumé</h4>
                    <div class="summary-details">
                        <div class="d-flex justify-content-between mb-3">
                            <span>Poids total:</span>
                            <span id="totalWeight">0 kg</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span>Prix unitaire:</span>
                            <span>500 FCFA/kg</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="fw-bold">Total:</span>
                            <span class="fw-bold" id="totalPrice">0 FCFA</span>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Commander</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
.simulator-card {
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    padding: 2rem;
}

.items-container {
    max-height: 600px;
    overflow-y: auto;
    padding-right: 10px;
}

.items-container::-webkit-scrollbar {
    width: 6px;
}

.items-container::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

.items-container::-webkit-scrollbar-thumb {
    background: var(--violet-fonce);
    border-radius: 3px;
}

.article-item {
    transition: transform 0.2s ease;
}

.article-item:hover {
    transform: translateY(-2px);
}

/* Mobile Styles */
@media (max-width: 991px) {
    .simulator-card {
        padding: 1rem;
    }

    .items-container {
        max-height: none;
        padding-bottom: 2rem;
    }

    .summary-card {
        background: white;
        padding: 1.5rem;
        margin-top: 2rem;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .summary-details {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        align-items: center;
    }

    .summary-details hr {
        grid-column: 1 / -1;
        margin: 0.5rem 0;
    }

    .summary-details button {
        grid-column: 1 / -1;
    }

    .col-lg-8 {
        border: none !important;
        padding: 0 !important;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('weightSimulator');
    const totalWeightElement = document.getElementById('totalWeight');
    const totalPriceElement = document.getElementById('totalPrice');
    const pricePerKg = 500;

    function updateTotals() {
        let totalWeight = 0;
        document.querySelectorAll('.quantity-input').forEach(input => {
            const weight = parseFloat(input.dataset.weight);
            const quantity = parseInt(input.value);
            totalWeight += weight * quantity;
        });

        const totalPrice = totalWeight * pricePerKg;
        
        totalWeightElement.textContent = totalWeight.toFixed(1) + ' kg';
        totalPriceElement.textContent = Math.round(totalPrice) + ' FCFA';
    }

    document.querySelectorAll('.quantity-btn').forEach(button => {
        button.addEventListener('click', function() {
            const input = this.parentElement.querySelector('.quantity-input');
            const currentValue = parseInt(input.value);
            
            if (this.dataset.action === 'increase') {
                input.value = currentValue + 1;
            } else if (this.dataset.action === 'decrease' && currentValue > 0) {
                input.value = currentValue - 1;
            }
            
            updateTotals();
        });
    });

    document.querySelectorAll('.quantity-input').forEach(input => {
        input.addEventListener('change', updateTotals);
    });

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        // Ajoutez ici la logique pour traiter la commande
    });
});
</script> 