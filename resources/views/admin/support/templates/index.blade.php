@extends('layouts.admin')

@section('title', 'Modèles de réponses automatiques - KLINKLIN Admin')

@push('styles')
<style>
    .template-preview {
        background-color: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 0.25rem;
        padding: 1rem;
        margin-top: 1rem;
        max-height: 200px;
        overflow-y: auto;
    }
    
    .category-badge {
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.75rem;
        background-color: #f1f1f1;
        color: #555;
    }
    
    .template-row {
        transition: background-color 0.2s;
    }
    
    .template-row:hover {
        background-color: #f8f9fa;
    }
    
    .template-row td {
        vertical-align: middle;
    }
    
    .toggle-switch {
        position: relative;
        display: inline-block;
        width: 50px;
        height: 24px;
    }
    
    .toggle-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }
    
    .toggle-slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: .4s;
        border-radius: 24px;
    }
    
    .toggle-slider:before {
        position: absolute;
        content: "";
        height: 16px;
        width: 16px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }
    
    input:checked + .toggle-slider {
        background-color: #4A148C;
    }
    
    input:focus + .toggle-slider {
        box-shadow: 0 0 1px #4A148C;
    }
    
    input:checked + .toggle-slider:before {
        transform: translateX(26px);
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Modèles de réponses automatiques</h1>
        <div>
            <a href="{{ route('admin.support.templates.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> Nouveau modèle
            </a>
            <a href="{{ route('admin.support.index') }}" class="btn btn-outline-secondary ms-2">
                <i class="bi bi-arrow-left me-1"></i> Retour aux tickets
            </a>
        </div>
    </div>
    
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Nom</th>
                            <th>Catégorie</th>
                            <th>Sujet</th>
                            <th>Contenu</th>
                            <th>Actif</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($templates as $template)
                            <tr class="template-row">
                                <td>
                                    <strong>{{ $template->name }}</strong>
                                </td>
                                <td>
                                    <span class="category-badge">{{ $categories[$template->category] ?? $template->category }}</span>
                                </td>
                                <td>
                                    {{ \Str::limit($template->subject, 50) }}
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-outline-secondary preview-btn" data-content="{{ htmlspecialchars($template->content) }}">
                                        <i class="bi bi-eye me-1"></i> Aperçu
                                    </button>
                                </td>
                                <td>
                                    <label class="toggle-switch">
                                        <input type="checkbox" class="toggle-active" data-id="{{ $template->id }}" {{ $template->is_active ? 'checked' : '' }}>
                                        <span class="toggle-slider"></span>
                                    </label>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.support.templates.edit', $template->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline-danger delete-btn" data-id="{{ $template->id }}" data-name="{{ $template->name }}">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <p class="text-muted mb-0">Aucun modèle de réponse automatique trouvé.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal d'aperçu du modèle -->
<div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="previewModalLabel">Aperçu du modèle</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="template-preview" id="templatePreviewContent"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmation de suppression -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirmer la suppression</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer le modèle "<span id="templateName"></span>" ?</p>
                <p class="text-danger">Cette action est irréversible.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form id="deleteForm" action="" method="POST" style="display: inline-block;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Aperçu du modèle
        const previewBtns = document.querySelectorAll('.preview-btn');
        const previewModal = new bootstrap.Modal(document.getElementById('previewModal'));
        const templatePreviewContent = document.getElementById('templatePreviewContent');
        
        previewBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const content = this.dataset.content;
                templatePreviewContent.innerHTML = content;
                previewModal.show();
            });
        });
        
        // Confirmation de suppression
        const deleteBtns = document.querySelectorAll('.delete-btn');
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        const deleteForm = document.getElementById('deleteForm');
        const templateNameSpan = document.getElementById('templateName');
        
        deleteBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.dataset.id;
                const name = this.dataset.name;
                
                templateNameSpan.textContent = name;
                deleteForm.action = `{{ url('admin/support/templates') }}/${id}`;
                
                deleteModal.show();
            });
        });
        
        // Activation/désactivation du modèle
        const toggleSwitches = document.querySelectorAll('.toggle-active');
        
        toggleSwitches.forEach(toggle => {
            toggle.addEventListener('change', function() {
                const id = this.dataset.id;
                const isActive = this.checked;
                
                fetch(`{{ url('admin/support/templates') }}/${id}/toggle`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification({
                            title: 'Statut mis à jour',
                            message: data.message,
                            type: 'success'
                        });
                    } else {
                        // Remettre le switch dans son état précédent
                        this.checked = !isActive;
                        
                        showNotification({
                            title: 'Erreur',
                            message: 'Une erreur est survenue lors de la mise à jour du statut.',
                            type: 'danger'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    
                    // Remettre le switch dans son état précédent
                    this.checked = !isActive;
                    
                    showNotification({
                        title: 'Erreur',
                        message: 'Une erreur est survenue lors de la mise à jour du statut.',
                        type: 'danger'
                    });
                });
            });
        });
    });
</script>
@endpush 