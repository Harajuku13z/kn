@extends('layouts.dashboard')

@section('title', 'Ticket #' . $ticket->reference_number . ' - KLINKLIN')

@push('styles')
<style>
    /* Styles pour les statuts de ticket */
    .ticket-status {
        padding: 0.35rem 0.75rem;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 500;
    }
    
    .ticket-status.status-open {
        background-color: #cff4fc;
        color: #055160;
    }
    
    .ticket-status.status-in_progress {
        background-color: #cfe2ff;
        color: #084298;
    }
    
    .ticket-status.status-waiting_user {
        background-color: #fff3cd;
        color: #664d03;
    }
    
    .ticket-status.status-closed {
        background-color: #e2e3e5;
        color: #41464b;
    }
    
    /* Styles pour la conversation */
    .message-container {
        max-height: 500px;
        overflow-y: auto;
    }
    
    .message-bubble {
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }
    
    /* Style pour le formulaire de réponse */
    textarea.form-control {
        min-height: 100px;
        resize: vertical;
    }
    
    /* Style pour la bannière ticket fermé */
    .ticket-closed-banner {
        background-color: #e2e3e5;
        color: #41464b;
        padding: 1rem;
        border-radius: 10px;
        margin-bottom: 1.5rem;
        text-align: center;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('support.index') }}">Aide & Support</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Ticket #{{ $ticket->reference_number }}</li>
                </ol>
            </nav>
        </div>
    </div>
    
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('support.index') }}" class="btn btn-outline-secondary me-3 d-flex align-items-center">
            <i class="bi bi-arrow-left me-2"></i> Retour
        </a>
        <div>
            <h1 class="h3 mb-1">{{ $ticket->subject }}</h1>
            <div class="d-flex align-items-center">
                <span class="text-muted me-2">Ticket #{{ $ticket->reference_number }}</span>
                <span class="ticket-status status-{{ $ticket->status }}">{{ $ticket->getStatusLabel() }}</span>
            </div>
        </div>
        <div class="ms-auto">
            @if($ticket->isOpen())
                <button type="button" class="btn btn-outline-secondary close-ticket-btn">
                    <i class="bi bi-check-circle me-2"></i> Fermer
                </button>
            @else
                <button type="button" class="btn btn-outline-primary reopen-ticket-btn">
                    <i class="bi bi-arrow-counterclockwise me-2"></i> Rouvrir
                </button>
            @endif
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-8">
            @if($ticket->isClosed())
                <div class="ticket-closed-banner">
                    <i class="bi bi-lock me-2"></i> Ce ticket est fermé. Vous pouvez le rouvrir si vous avez besoin d'assistance supplémentaire.
                </div>
            @endif
            
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Conversation</h5>
                    <span class="badge bg-secondary">{{ count($messages) }} messages</span>
                </div>
                <div class="card-body p-0">
                    <div class="message-container p-3" id="messageContainer">
                        @foreach($messages as $message)
                            <div class="mb-4 {{ $message->is_from_admin ? 'ps-md-5' : 'pe-md-5' }}">
                                <div class="message-bubble p-3 rounded-3 {{ $message->is_from_admin ? 'bg-light border' : 'bg-primary text-white' }} {{ $message->is_auto_reply ? 'bg-warning-subtle text-dark' : '' }}">
                                    {!! $message->message !!}
                                </div>
                                <div class="small text-muted mt-1 {{ $message->is_from_admin ? 'text-end' : '' }}">
                                    {{ $message->created_at->format('d/m/Y H:i') }}
                                    @if($message->is_auto_reply)
                                        <span class="badge bg-warning text-dark ms-1">Auto</span>
                                    @elseif($message->is_from_admin)
                                        <span class="badge bg-secondary ms-1">Support</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            
            @if($ticket->isOpen())
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Répondre</h5>
                    </div>
                    <div class="card-body">
                        <form id="replyForm" action="{{ route('support.reply', $ticket->id) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <textarea id="message" name="message" class="form-control" rows="4" placeholder="Écrivez votre réponse ici..." required></textarea>
                                <div class="invalid-feedback" id="message-error"></div>
                            </div>
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="bi bi-send me-2"></i> Envoyer
                            </button>
                        </form>
                    </div>
                </div>
            @endif
        </div>
        
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Informations</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <strong>Statut:</strong>
                            <span class="ticket-status status-{{ $ticket->status }}">{{ $ticket->getStatusLabel() }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <strong>Catégorie:</strong>
                            <span>{{ $ticket->getCategoryLabel() }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <strong>Créé le:</strong>
                            <span>{{ $ticket->created_at->format('d/m/Y') }}</span>
                        </div>
                        @if($ticket->closed_at)
                        <div class="d-flex justify-content-between">
                            <strong>Fermé le:</strong>
                            <span>{{ $ticket->closed_at->format('d/m/Y') }}</span>
                        </div>
                        @endif
                    </div>
                    
                    @if($ticket->isOpen())
                    <div class="alert alert-info mb-0">
                        <i class="bi bi-info-circle me-2"></i> Notre équipe traite votre demande et vous répondra dans les meilleurs délais.
                    </div>
                    @endif
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Contact</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="me-3 text-primary">
                            <i class="bi bi-telephone fs-4"></i>
                        </div>
                        <div>
                            <div class="fw-bold">Téléphone</div>
                            <div>+225 07 07 07 07 07</div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="me-3 text-primary">
                            <i class="bi bi-envelope fs-4"></i>
                        </div>
                        <div>
                            <div class="fw-bold">Email</div>
                            <div>support@klinklin.com</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmation pour fermer le ticket -->
<div class="modal fade" id="closeTicketModal" tabindex="-1" aria-labelledby="closeTicketModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="closeTicketModalLabel">Fermer le ticket</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir fermer ce ticket ? Si votre problème est résolu, vous pouvez le fermer.</p>
                <p>Vous pourrez toujours le rouvrir ultérieurement si nécessaire.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" id="confirmCloseTicket">
                    <i class="bi bi-check-circle me-2"></i> Fermer le ticket
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmation pour rouvrir le ticket -->
<div class="modal fade" id="reopenTicketModal" tabindex="-1" aria-labelledby="reopenTicketModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reopenTicketModalLabel">Rouvrir le ticket</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir rouvrir ce ticket ?</p>
                <p>Notre équipe sera notifiée et reprendra le traitement de votre demande.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" id="confirmReopenTicket">
                    <i class="bi bi-arrow-counterclockwise me-2"></i> Rouvrir le ticket
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Faire défiler la conversation jusqu'en bas
        const messageContainer = document.getElementById('messageContainer');
        if (messageContainer) {
            messageContainer.scrollTop = messageContainer.scrollHeight;
        }
        
        // Soumission du formulaire de réponse
        const replyForm = document.getElementById('replyForm');
        if (replyForm) {
            replyForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Récupérer le contenu du textarea
                const messageInput = document.getElementById('message');
                
                // Vérifier que le message n'est pas vide
                if (messageInput.value.trim().length === 0) {
                    document.getElementById('message-error').textContent = 'Le message ne peut pas être vide.';
                    document.getElementById('message-error').style.display = 'block';
                    return;
                }
                
                // Désactiver le bouton de soumission
                const submitBtn = document.getElementById('submitBtn');
                const originalBtnText = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Envoi en cours...';
                
                // Envoyer le formulaire en AJAX
                const formData = new FormData(replyForm);
                
                fetch(replyForm.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Ajouter le nouveau message à la conversation
                        const messageContainer = document.getElementById('messageContainer');
                        messageContainer.insertAdjacentHTML('beforeend', data.html);
                        
                        // Vider le textarea
                        messageInput.value = '';
                        
                        // Faire défiler jusqu'au nouveau message
                        messageContainer.scrollTop = messageContainer.scrollHeight;
                        
                        // Afficher une notification de succès
                        showNotification({
                            title: 'Message envoyé',
                            message: 'Votre réponse a été envoyée avec succès.',
                            type: 'success'
                        });
                    } else {
                        // Afficher les erreurs
                        if (data.errors && data.errors.message) {
                            document.getElementById('message-error').textContent = data.errors.message[0];
                            document.getElementById('message-error').style.display = 'block';
                        } else {
                            showNotification({
                                title: 'Erreur',
                                message: 'Une erreur est survenue lors de l\'envoi de votre réponse.',
                                type: 'danger'
                            });
                        }
                    }
                    
                    // Réactiver le bouton
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnText;
                })
                .catch(error => {
                    console.error('Error:', error);
                    
                    // Réactiver le bouton
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnText;
                    
                    // Afficher un message d'erreur
                    showNotification({
                        title: 'Erreur',
                        message: 'Une erreur est survenue lors de l\'envoi de votre réponse.',
                        type: 'danger'
                    });
                });
            });
        }
        
        // Fermeture du ticket
        const closeTicketBtn = document.querySelector('.close-ticket-btn');
        if (closeTicketBtn) {
            closeTicketBtn.addEventListener('click', function() {
                const modal = new bootstrap.Modal(document.getElementById('closeTicketModal'));
                modal.show();
            });
        }
        
        // Confirmation de fermeture du ticket
        const confirmCloseTicket = document.getElementById('confirmCloseTicket');
        if (confirmCloseTicket) {
            confirmCloseTicket.addEventListener('click', function() {
                this.disabled = true;
                this.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Fermeture en cours...';
                
                fetch('{{ route("support.close", $ticket->id) }}', {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Fermer le modal
                        bootstrap.Modal.getInstance(document.getElementById('closeTicketModal')).hide();
                        
                        // Recharger la page
                        window.location.reload();
                    } else {
                        showNotification({
                            title: 'Erreur',
                            message: 'Une erreur est survenue lors de la fermeture du ticket.',
                            type: 'danger'
                        });
                        
                        this.disabled = false;
                        this.innerHTML = '<i class="bi bi-check-circle me-2"></i> Fermer le ticket';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    
                    showNotification({
                        title: 'Erreur',
                        message: 'Une erreur est survenue lors de la fermeture du ticket.',
                        type: 'danger'
                    });
                    
                    this.disabled = false;
                    this.innerHTML = '<i class="bi bi-check-circle me-2"></i> Fermer le ticket';
                });
            });
        }
        
        // Réouverture du ticket
        const reopenTicketBtn = document.querySelector('.reopen-ticket-btn');
        if (reopenTicketBtn) {
            reopenTicketBtn.addEventListener('click', function() {
                const modal = new bootstrap.Modal(document.getElementById('reopenTicketModal'));
                modal.show();
            });
        }
        
        // Confirmation de réouverture du ticket
        const confirmReopenTicket = document.getElementById('confirmReopenTicket');
        if (confirmReopenTicket) {
            confirmReopenTicket.addEventListener('click', function() {
                this.disabled = true;
                this.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Réouverture en cours...';
                
                fetch('{{ route("support.reopen", $ticket->id) }}', {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Fermer le modal
                        bootstrap.Modal.getInstance(document.getElementById('reopenTicketModal')).hide();
                        
                        // Recharger la page
                        window.location.reload();
                    } else {
                        showNotification({
                            title: 'Erreur',
                            message: 'Une erreur est survenue lors de la réouverture du ticket.',
                            type: 'danger'
                        });
                        
                        this.disabled = false;
                        this.innerHTML = '<i class="bi bi-arrow-counterclockwise me-2"></i> Rouvrir le ticket';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    
                    showNotification({
                        title: 'Erreur',
                        message: 'Une erreur est survenue lors de la réouverture du ticket.',
                        type: 'danger'
                    });
                    
                    this.disabled = false;
                    this.innerHTML = '<i class="bi bi-arrow-counterclockwise me-2"></i> Rouvrir le ticket';
                });
            });
        }
    });
</script>
@endpush 