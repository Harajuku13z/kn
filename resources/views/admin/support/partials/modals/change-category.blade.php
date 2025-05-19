<!-- Modal de changement de catégorie -->
<div class="modal fade" id="changeCategoryModal" tabindex="-1" aria-labelledby="changeCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="changeCategoryModalLabel">
                    <i class="fas fa-tag me-2"></i> Changer la catégorie
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <form id="changeCategoryForm" action="{{ route('admin.support.update-category', $ticket->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="category" class="form-label">Nouvelle catégorie</label>
                        <select class="form-select" id="category" name="category" required>
                            <option value="general" {{ $ticket->category === 'general' ? 'selected' : '' }}>
                                Question générale
                            </option>
                            <option value="account" {{ $ticket->category === 'account' ? 'selected' : '' }}>
                                Compte
                            </option>
                            <option value="orders" {{ $ticket->category === 'orders' ? 'selected' : '' }}>
                                Commandes
                            </option>
                            <option value="payment" {{ $ticket->category === 'payment' ? 'selected' : '' }}>
                                Paiement
                            </option>
                            <option value="subscription" {{ $ticket->category === 'subscription' ? 'selected' : '' }}>
                                Abonnement
                            </option>
                            <option value="technical" {{ $ticket->category === 'technical' ? 'selected' : '' }}>
                                Problème technique
                            </option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Annuler
                </button>
                <button type="button" class="btn btn-primary" onclick="updateCategory()">
                    <i class="fas fa-save me-1"></i> Enregistrer
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function updateCategory() {
    const form = document.getElementById('changeCategoryForm');
    const category = document.getElementById('category').value;
    
    fetch(form.action, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ category: category })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.reload();
        } else {
            showToast({
                title: 'Erreur',
                message: 'Une erreur est survenue. Veuillez réessayer.',
                type: 'danger',
                icon: 'exclamation-triangle-fill'
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast({
            title: 'Erreur',
            message: 'Une erreur est survenue. Veuillez réessayer.',
            type: 'danger',
            icon: 'exclamation-triangle-fill'
        });
    });
}
</script>
@endpush 