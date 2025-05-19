@extends('layouts.admin')

@section('title', 'Modifier un quota - KLINKLIN Admin')

@section('page_title')
    <div class="d-sm-flex align-items-center justify-content-between">
        <h1 class="h3 mb-0 text-gray-800">Modifier un Quota</h1>
        <a href="{{ route('admin.quotas.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill">
            <i class="fas fa-arrow-left me-1"></i> Retour
        </a>
    </div>
@endsection


@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-edit me-2"></i> Modifier le quota #{{ $quota->id }}
                    </h6>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <h5 class="alert-heading fw-bold"><i class="fas fa-exclamation-triangle me-2"></i> Erreurs de validation</h5>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('admin.quotas.update', $quota->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card bg-light mb-3">
                                    <div class="card-header bg-primary text-white">
                                        <h5 class="mb-0"><i class="fas fa-user me-2"></i> Informations client</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="user_id" class="form-label">Utilisateur <span class="text-danger">*</span></label>
                                            <select name="user_id" id="user_id" class="form-control" required>
                                                @foreach($users as $user)
                                                    <option value="{{ $user->id }}" {{ $quota->user_id == $user->id ? 'selected' : '' }}>
                                                        {{ $user->name }} ({{ $user->email }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card bg-light mb-3">
                                    <div class="card-header bg-info text-white">
                                        <h5 class="mb-0"><i class="fas fa-weight me-2"></i> Informations du quota</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="total_kg" class="form-label">Kilogrammes totaux <span class="text-danger">*</span></label>
                                            <input type="number" name="total_kg" id="total_kg" class="form-control" min="1" step="1" value="{{ $quota->total_kg }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="used_kg" class="form-label">Kilogrammes utilisés <span class="text-danger">*</span></label>
                                            <input type="number" name="used_kg" id="used_kg" class="form-control" min="0" step="0.1" value="{{ $quota->used_kg }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="expiration_date" class="form-label">Date d'expiration <span class="text-danger">*</span></label>
                                            <input type="date" name="expiration_date" id="expiration_date" class="form-control" value="{{ $quota->expiration_date ? $quota->expiration_date->format('Y-m-d') : '' }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="is_active" class="form-label">Statut <span class="text-danger">*</span></label>
                                            <select name="is_active" id="is_active" class="form-control" required>
                                                <option value="1" {{ $quota->is_active ? 'selected' : '' }}>Actif</option>
                                                <option value="0" {{ !$quota->is_active ? 'selected' : '' }}>Inactif</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card bg-light mb-4">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0"><i class="fas fa-calculator me-2"></i> Estimation</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 offset-md-3">
                                        <table class="table table-sm">
                                            <tbody>
                                                <tr>
                                                    <td>Prix au kilo</td>
                                                    <td class="text-end">{{ number_format(\App\Models\PriceConfiguration::getCurrentPricePerKg(1000), 0, ',', ' ') }} FCFA</td>
                                                </tr>
                                                <tr>
                                                    <td>Kilogrammes</td>
                                                    <td class="text-end"><span id="display_kilograms">{{ $quota->total_kg }}</span> kg</td>
                                                </tr>
                                                <tr class="table-active">
                                                    <th>Total estimé</th>
                                                    <th class="text-end"><span id="total">{{ number_format($quota->total_kg * \App\Models\PriceConfiguration::getCurrentPricePerKg(1000), 0, ',', ' ') }}</span> FCFA</th>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.quotas.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Retour
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Enregistrer les modifications
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Price calculation
    const pricePerKg = {{ \App\Models\PriceConfiguration::getCurrentPricePerKg(1000) }};
    const kilogramsInput = document.getElementById('total_kg');
    const displayKilograms = document.getElementById('display_kilograms');
    const totalDisplay = document.getElementById('total');
    
    function updatePrices() {
        const kilograms = parseInt(kilogramsInput.value);
        
        // Update display kilograms
        displayKilograms.textContent = kilograms;
        
        // Calculate total
        const total = kilograms * pricePerKg;
        totalDisplay.textContent = formatNumber(total);
    }
    
    function formatNumber(number) {
        return new Intl.NumberFormat('fr-FR').format(number);
    }
    
    // Add event listeners
    kilogramsInput.addEventListener('input', updatePrices);
    
    // Initialize prices
    updatePrices();
});
</script>
@endpush 