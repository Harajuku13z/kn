@extends('layouts.dashboard')

@section('title', 'Modifier une adresse')

@section('content')
<div class="dashboard-content">
    <div class="row mb-4">
        <div class="col-12">
            <h1>Modifier une adresse</h1>
            <p class="text-muted">Mettez à jour les informations de votre adresse</p>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card custom-card">
        <div class="card-body">
            <form method="POST" action="{{ route('addresses.update', $address) }}" class="needs-validation" id="addressForm" novalidate>
                @csrf
                @method('PUT')

                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nom de l'adresse</label>
                            <select id="name" class="form-select @error('name') is-invalid @enderror" name="name" required>
                                <option value="Domicile" {{ old('name', $address->name) == 'Domicile' ? 'selected' : '' }}>Domicile</option>
                                <option value="Bureau" {{ old('name', $address->name) == 'Bureau' ? 'selected' : '' }}>Bureau</option>
                                <option value="Autre" {{ old('name', $address->name) == 'Autre' ? 'selected' : '' }}>Autre</option>
                            </select>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Adresse complète</label>
                            <textarea id="address" class="form-control @error('address') is-invalid @enderror" name="address" rows="3" required>{{ old('address', $address->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="city_id" class="form-label">Ville</label>
                            <select id="city_id" class="form-select @error('city_id') is-invalid @enderror" name="city_id" required>
                                <option value="">Sélectionnez une ville</option>
                                @foreach($cities as $city)
                                    <option value="{{ $city->id }}" {{ old('city_id', $address->city_id) == $city->id ? 'selected' : '' }}>
                                        {{ $city->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('city_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="district" class="form-label">Localisation (Arrondissement / Quartier)</label>
                            <select id="district" class="form-select @error('district') is-invalid @enderror" name="district" required>
                                <option value="">Sélectionnez d'abord une ville</option>
                            </select>
                            @error('district')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text mt-1" id="district-info">
                                Sélectionnez votre arrondissement ou quartier pour voir les frais de livraison
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="landmark" class="form-label">Point de repère</label>
                            <input id="landmark" type="text" class="form-control @error('landmark') is-invalid @enderror" name="landmark" value="{{ old('landmark', $address->landmark) }}">
                            <div class="form-text">Exemple: Près de l'église, en face du marché, etc.</div>
                            @error('landmark')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Téléphone de contact</label>
                            <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone', $address->phone) }}" required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="contact_name" class="form-label">Nom de la personne à contacter</label>
                            <input id="contact_name" type="text" class="form-control @error('contact_name') is-invalid @enderror" name="contact_name" value="{{ old('contact_name', $address->contact_name) }}" required>
                            @error('contact_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="type" class="form-label">Type d'adresse</label>
                            <select id="type" class="form-select @error('type') is-invalid @enderror" name="type" required>
                                <option value="both" {{ old('type', $address->type) == 'both' ? 'selected' : '' }}>Collecte et livraison</option>
                                <option value="pickup" {{ old('type', $address->type) == 'pickup' ? 'selected' : '' }}>Collecte uniquement</option>
                                <option value="delivery" {{ old('type', $address->type) == 'delivery' ? 'selected' : '' }}>Livraison uniquement</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <div class="form-check mt-4">
                                <input class="form-check-input" type="checkbox" name="is_default" id="is_default" value="1" {{ old('is_default', $address->is_default) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_default">
                                    Définir comme adresse par défaut
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 d-flex justify-content-between">
                        <a href="{{ route('addresses.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i> Retour à la liste
                        </a>
                        <div>
                            <button type="button" class="btn btn-outline-danger me-2" onclick="confirmDelete()">
                                <i class="bi bi-trash me-1"></i> Supprimer
                            </button>
                            <button type="submit" class="btn btn-primary-custom">
                                <i class="bi bi-save me-1"></i> Mettre à jour l'adresse
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Champs cachés pour les coordonnées (conservés pour compatibilité) -->
                <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude', $address->latitude) }}">
                <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude', $address->longitude) }}">
            </form>

            <!-- Formulaire de suppression caché -->
            <form id="delete-form" action="{{ route('addresses.destroy', $address) }}" method="POST" class="d-none">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
.custom-card {
    border-radius: 15px;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.05);
    border: none;
}
optgroup {
    font-weight: bold;
    color: #0d6efd;
}
</style>
@endpush

@push('scripts')
<script>
// Variable pour stocker les frais associés aux quartiers
let districtFeesMap = {};

// Données des districts préchargées
const allDistrictsByCity = @json($allDistrictsByCity);

// Fonction pour charger les quartiers en fonction de la ville sélectionnée
function loadDistricts(cityId) {
    const districtSelect = document.getElementById('district');
    const districtInfo = document.getElementById('district-info');
    
    // Vider les options actuelles
    districtSelect.innerHTML = '<option value="">Sélectionnez un quartier</option>';
    
    if (!cityId) {
        districtInfo.textContent = 'Sélectionnez votre arrondissement ou quartier pour voir les frais de livraison';
        return;
    }
    
    // Réinitialiser la carte des frais
    districtFeesMap = {};
    
    // Récupérer les données préchargées pour cette ville
    const cityData = allDistrictsByCity[cityId];
    
    if (!cityData) {
        districtSelect.innerHTML = '<option value="">Aucun quartier disponible</option>';
        districtInfo.textContent = 'Aucun quartier actif trouvé pour cette ville';
        return;
    }
    
    // Cas spécial pour Brazzaville
    if (cityData.is_brazzaville) {
        // Ajouter les arrondissements
        if (cityData.arrondissements && cityData.arrondissements.length > 0) {
            const arronGroup = document.createElement('optgroup');
            arronGroup.label = 'Arrondissements';
            
            cityData.arrondissements.forEach(item => {
                const option = document.createElement('option');
                option.value = item.name;
                option.textContent = `${item.name} (${item.formatted_fee})`;
                arronGroup.appendChild(option);
                districtFeesMap[item.name] = item.fee;
            });
            
            districtSelect.appendChild(arronGroup);
        }
        
        // Ajouter les quartiers
        if (cityData.quartiers && cityData.quartiers.length > 0) {
            const quartierGroup = document.createElement('optgroup');
            quartierGroup.label = 'Quartiers';
            
            cityData.quartiers.forEach(item => {
                const option = document.createElement('option');
                option.value = item.name;
                option.textContent = `${item.name} (${item.formatted_fee})`;
                quartierGroup.appendChild(option);
                districtFeesMap[item.name] = item.fee;
            });
            
            districtSelect.appendChild(quartierGroup);
        }
    } else {
        // Pour les autres villes, afficher une liste simple
        if (cityData.districts && cityData.districts.length > 0) {
            cityData.districts.forEach(item => {
                const option = document.createElement('option');
                option.value = item.name;
                option.textContent = `${item.name} (${item.formatted_fee})`;
                districtSelect.appendChild(option);
                districtFeesMap[item.name] = item.fee;
            });
        }
    }
    
    // Si aucun quartier n'a été ajouté
    if (districtSelect.options.length <= 1) {
        districtSelect.innerHTML = '<option value="">Aucun quartier disponible</option>';
        districtInfo.textContent = 'Aucun quartier actif trouvé pour cette ville';
    } else {
        districtInfo.textContent = 'Les frais de livraison sont indiqués à côté de chaque quartier';
        
        // Restaurer la valeur précédemment sélectionnée
        const currentDistrict = "{{ old('district', $address->district) }}";
        if (currentDistrict) {
            const option = districtSelect.querySelector(`option[value="${currentDistrict}"]`);
            if (option) {
                option.selected = true;
                updateFeeInfo(currentDistrict);
            }
        }
    }
}

// Fonction pour mettre à jour les informations de frais lorsqu'un quartier est sélectionné
function updateFeeInfo(district) {
    const districtInfo = document.getElementById('district-info');
    
    if (district && districtFeesMap[district]) {
        const fee = districtFeesMap[district];
        districtInfo.innerHTML = `<strong>Frais de livraison: ${fee.toLocaleString()} FCFA</strong>`;
    } else {
        districtInfo.textContent = 'Sélectionnez votre quartier pour voir les frais de livraison';
    }
}

// Écouter les changements sur le sélecteur de ville
document.getElementById('city_id').addEventListener('change', function() {
    loadDistricts(this.value);
});

// Écouter les changements sur le sélecteur de quartier
document.getElementById('district').addEventListener('change', function() {
    updateFeeInfo(this.value);
});

// Charger les quartiers au chargement de la page si une ville est sélectionnée
document.addEventListener('DOMContentLoaded', function() {
    const citySelect = document.getElementById('city_id');
    if (citySelect.value) {
        loadDistricts(citySelect.value);
    }
});

function confirmDelete() {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette adresse ?')) {
        document.getElementById('delete-form').submit();
    }
}
</script>
@endpush
@endsection 