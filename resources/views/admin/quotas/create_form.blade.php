@extends('layouts.admin')

@section('title', 'Créer un quota - KLINKLIN Admin')

@section('page_title')
    <div class="d-sm-flex align-items-center justify-content-between">
        <h1 class="h3 mb-0 text-gray-800">Nouveau Quota</h1>
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
                        <i class="fas fa-box-open me-2"></i> Nouveau quota pour {{ $user->name }}
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

                    <form action="{{ route('admin.quotas.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card bg-light mb-3">
                                    <div class="card-header bg-primary text-white">
                                        <h5 class="mb-0"><i class="fas fa-user me-2"></i> Informations client</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label class="form-label">Nom</label>
                                            <input type="text" class="form-control" value="{{ $user->name }}" readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Email</label>
                                            <input type="email" class="form-control" value="{{ $user->email }}" readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Téléphone</label>
                                            <input type="text" class="form-control" value="{{ $user->phone ?? 'Non renseigné' }}" readonly>
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
                                            <label for="kilograms" class="form-label">Kilogrammes <span class="text-danger">*</span></label>
                                            <input type="number" name="kilograms" id="kilograms" class="form-control" min="1" step="1" value="10" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="expiration_date" class="form-label">Date d'expiration <span class="text-danger">*</span></label>
                                            <input type="date" name="expiration_date" id="expiration_date" class="form-control" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="status" class="form-label">Statut <span class="text-danger">*</span></label>
                                            <select name="status" id="status" class="form-control" required>
                                                <option value="active">Actif</option>
                                                <option value="inactive">Inactif</option>
                                                <option value="expired">Expiré</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card bg-light mb-4">
                            <div class="card-header bg-secondary text-white">
                                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i> Informations supplémentaires</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="notes" class="form-label">Notes</label>
                                    <textarea name="notes" id="notes" class="form-control" rows="3"></textarea>
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
                                                    <td class="text-end">{{ number_format($currentPrice, 0, ',', ' ') }} FCFA</td>
                                                </tr>
                                                <tr>
                                                    <td>Kilogrammes</td>
                                                    <td class="text-end"><span id="display_kilograms">10</span> kg</td>
                                                </tr>
                                                <tr class="table-active">
                                                    <th>Total estimé</th>
                                                    <th class="text-end"><span id="total">{{ number_format($currentPrice * 10, 0, ',', ' ') }}</span> FCFA</th>
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
                                <i class="fas fa-save me-1"></i> Enregistrer le quota
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
    // Set default expiration date (1 year from now)
    const oneYearFromNow = new Date();
    oneYearFromNow.setFullYear(oneYearFromNow.getFullYear() + 1);
    
    // Format date as YYYY-MM-DD
    const formatDate = (date) => {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    };
    
    document.getElementById('expiration_date').value = formatDate(oneYearFromNow);

    // Price calculation
    const pricePerKg = {{ $currentPrice }};
    const kilogramsInput = document.getElementById('kilograms');
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