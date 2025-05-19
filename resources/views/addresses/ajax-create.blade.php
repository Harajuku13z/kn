<div class="modal-header">
    <h5 class="modal-title">Ajouter une nouvelle adresse</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <form id="ajaxAddressForm">
        @csrf
        <div class="mb-3">
            <label for="ajax_name" class="form-label">Nom de l'adresse</label>
            <select id="ajax_name" class="form-select" name="name" required>
                <option value="Domicile">Domicile</option>
                <option value="Bureau">Bureau</option>
                <option value="Autre">Autre</option>
            </select>
            <div class="invalid-feedback name-error"></div>
        </div>

        <div class="mb-3">
            <label for="ajax_address" class="form-label">Adresse complète</label>
            <textarea id="ajax_address" class="form-control" name="address" required></textarea>
            <div class="invalid-feedback address-error"></div>
        </div>

        <div class="mb-3">
            <label for="ajax_city_id" class="form-label">Ville</label>
            <select id="ajax_city_id" class="form-select" name="city_id" required>
                <option value="">Sélectionnez une ville</option>
                @foreach($cities as $city)
                    <option value="{{ $city->id }}">{{ $city->name }}</option>
                @endforeach
            </select>
            <div class="invalid-feedback city_id-error"></div>
        </div>

        <div class="mb-3">
            <label for="ajax_district" class="form-label">Localisation (Arrondissement / Quartier)</label>
            <select id="ajax_district" class="form-select" name="district" required>
                <option value="">Sélectionnez d'abord une ville</option>
            </select>
            <div class="invalid-feedback district-error"></div>
            <div class="form-text mt-1" id="ajax-district-info">
                Sélectionnez votre quartier pour voir les frais de livraison
            </div>
        </div>

        <div class="mb-3">
            <label for="ajax_landmark" class="form-label">Point de repère</label>
            <input id="ajax_landmark" type="text" class="form-control" name="landmark">
            <small class="form-text text-muted">Exemple: Près de l'église, en face du marché, etc.</small>
            <div class="invalid-feedback landmark-error"></div>
        </div>

        <div class="mb-3">
            <label for="ajax_phone" class="form-label">Téléphone de contact</label>
            <input id="ajax_phone" type="text" class="form-control" name="phone" required>
            <div class="invalid-feedback phone-error"></div>
        </div>

        <div class="mb-3">
            <label for="ajax_contact_name" class="form-label">Nom de la personne à contacter</label>
            <input id="ajax_contact_name" type="text" class="form-control" name="contact_name" required>
            <div class="invalid-feedback contact_name-error"></div>
        </div>

        <div class="mb-3">
            <label for="ajax_type" class="form-label">Type d'adresse</label>
            <select id="ajax_type" class="form-select" name="type" required>
                <option value="both">Collecte et livraison</option>
                <option value="pickup">Collecte uniquement</option>
                <option value="delivery">Livraison uniquement</option>
            </select>
            <div class="invalid-feedback type-error"></div>
        </div>

        <div class="mb-3">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="is_default" id="ajax_is_default" value="1">
                <label class="form-check-label" for="ajax_is_default">
                    Définir comme adresse par défaut
                </label>
            </div>
        </div>
        
        <!-- Champs cachés pour les coordonnées (conservés pour compatibilité) -->
        <input type="hidden" name="latitude" id="ajax_latitude">
        <input type="hidden" name="longitude" id="ajax_longitude">
    </form>

    <div id="address-form-alert" class="alert d-none"></div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
    <button type="button" class="btn btn-primary-custom" id="saveAddress">
        <i class="bi bi-save me-1"></i> Enregistrer l'adresse
    </button>
</div>

<script>
$(document).ready(function() {
    // Variable pour stocker les frais associés aux quartiers
    let districtFeesMap = {};

    // Données des districts préchargées
    const allDistrictsByCity = @json($allDistrictsByCity);
    
    // Fonction pour charger les districts en fonction de la ville sélectionnée
    function loadDistricts(cityId) {
        const districtSelect = document.getElementById('ajax_district');
        const districtInfo = document.getElementById('ajax-district-info');
        
        // Vider les options actuelles
        districtSelect.innerHTML = '<option value="">Sélectionnez un quartier</option>';
        
        if (!cityId) {
            districtInfo.textContent = 'Sélectionnez votre quartier pour voir les frais de livraison';
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
        }
    }

    // Fonction pour mettre à jour les informations de frais
    function updateFeeInfo(district) {
        const districtInfo = document.getElementById('ajax-district-info');
        
        if (district && districtFeesMap[district]) {
            const fee = districtFeesMap[district];
            districtInfo.innerHTML = `<strong>Frais de livraison: ${fee.toLocaleString()} FCFA</strong>`;
        } else {
            districtInfo.textContent = 'Sélectionnez votre quartier pour voir les frais de livraison';
        }
    }

    // Écouter les changements sur le sélecteur de ville
    $('#ajax_city_id').on('change', function() {
        loadDistricts(this.value);
    });

    // Écouter les changements sur le sélecteur de quartier
    $('#ajax_district').on('change', function() {
        updateFeeInfo(this.value);
    });

    // Charger les quartiers au chargement de la page si une ville est sélectionnée
    if ($('#ajax_city_id').val()) {
        loadDistricts($('#ajax_city_id').val());
    }

    // Gestion du bouton de sauvegarde
    $('#saveAddress').click(function() {
        const form = $('#ajaxAddressForm');
        const formData = new FormData(form[0]);
        
        // Réinitialiser les messages d'erreur
        $('.invalid-feedback').empty();
        $('.is-invalid').removeClass('is-invalid');
        $('#address-form-alert').addClass('d-none').removeClass('alert-danger alert-success');
        
        $.ajax({
            url: '{{ route('addresses.ajax.store') }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    // Afficher un message de succès
                    $('#address-form-alert')
                        .removeClass('d-none alert-danger')
                        .addClass('alert-success')
                        .text(response.message || 'Adresse créée avec succès');
                    
                    // Déclencher un événement custom pour notifier le parent
                    $(document).trigger('addressCreated', [response.address_id, response.address_display]);
                    
                    // Fermer le modal après un délai
                    setTimeout(function() {
                        $('#addressModal').modal('hide');
                    }, 1500);
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    // Erreurs de validation
                    const errors = xhr.responseJSON.errors;
                    
                    // Afficher les erreurs
                    for (const field in errors) {
                        $(`#ajax_${field}`).addClass('is-invalid');
                        $(`.${field}-error`).text(errors[field][0]);
                    }
                    
                    $('#address-form-alert')
                        .removeClass('d-none alert-success')
                        .addClass('alert-danger')
                        .text('Veuillez corriger les erreurs dans le formulaire.');
                } else {
                    // Autre erreur
                    $('#address-form-alert')
                        .removeClass('d-none alert-success')
                        .addClass('alert-danger')
                        .text('Une erreur est survenue lors de la création de l\'adresse.');
                }
            }
        });
    });
});
</script> 