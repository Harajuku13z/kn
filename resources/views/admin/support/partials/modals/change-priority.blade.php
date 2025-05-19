<!-- Modal de changement de priorité -->
<div class="modal fade" id="changePriorityModal" tabindex="-1" aria-labelledby="changePriorityModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="changePriorityModalLabel">
                    <i class="fas fa-flag me-2"></i> Changer la priorité
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <form id="changePriorityForm" action="{{ route('admin.support.update-priority', $ticket->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="priority" class="form-label">Nouvelle priorité</label>
                        <select class="form-select" id="priority" name="priority" required>
                            <option value="low" {{ $ticket->priority === 'low' ? 'selected' : '' }}>
                                <i class="fas fa-arrow-down"></i> Basse
                            </option>
                            <option value="medium" {{ $ticket->priority === 'medium' ? 'selected' : '' }}>
                                <i class="fas fa-equals"></i> Moyenne
                            </option>
                            <option value="high" {{ $ticket->priority === 'high' ? 'selected' : '' }}>
                                <i class="fas fa-arrow-up"></i> Haute
                            </option>
                            <option value="urgent" {{ $ticket->priority === 'urgent' ? 'selected' : '' }}>
                                <i class="fas fa-exclamation-triangle"></i> Urgente
                            </option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Annuler
                </button>
                <button type="button" class="btn btn-primary" onclick="updatePriority()">
                    <i class="fas fa-save me-1"></i> Enregistrer
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function updatePriority() {
    const form = document.getElementById('changePriorityForm');
    const priority = document.getElementById('priority').value;
    
    fetch(form.action, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ priority: priority })
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