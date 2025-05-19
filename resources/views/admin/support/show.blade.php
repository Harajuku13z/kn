@extends('admin.dashboard')

@section('title', 'Ticket #' . $ticket->reference_number . ' - KLINKLIN Admin')

@section('content')
<div class="container-fluid">
    <!-- Messages flash -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif
    
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif
    
    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <!-- En-tête -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Ticket #{{ $ticket->reference_number }}</h1>
            <p class="text-muted mb-0">{{ $ticket->subject }}</p>
        </div>
        <div class="d-flex">
            <button type="button" class="btn btn-danger mr-2" onclick="deleteTicket()">
                <i class="fas fa-trash"></i> Supprimer
            </button>
            <a href="{{ route('admin.support.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Informations du ticket -->
        <div class="col-md-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informations du ticket</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="font-weight-bold">Statut</label>
                        <select class="form-control" id="status" onchange="updateStatus(this.value)">
                            <option value="open" {{ $ticket->status === 'open' ? 'selected' : '' }}>Ouvert</option>
                            <option value="in_progress" {{ $ticket->status === 'in_progress' ? 'selected' : '' }}>En traitement</option>
                            <option value="waiting_user" {{ $ticket->status === 'waiting_user' ? 'selected' : '' }}>En attente de réponse</option>
                            <option value="closed" {{ $ticket->status === 'closed' ? 'selected' : '' }}>Fermé</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="font-weight-bold">Priorité</label>
                        <select class="form-control" id="priority" onchange="updatePriority(this.value)">
                            <option value="low" {{ $ticket->priority === 'low' ? 'selected' : '' }}>Basse</option>
                            <option value="medium" {{ $ticket->priority === 'medium' ? 'selected' : '' }}>Moyenne</option>
                            <option value="high" {{ $ticket->priority === 'high' ? 'selected' : '' }}>Haute</option>
                            <option value="urgent" {{ $ticket->priority === 'urgent' ? 'selected' : '' }}>Urgente</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="font-weight-bold">Catégorie</label>
                        <select class="form-control" id="category" onchange="updateCategory(this.value)">
                            <option value="general" {{ $ticket->category === 'general' ? 'selected' : '' }}>Question générale</option>
                            <option value="account" {{ $ticket->category === 'account' ? 'selected' : '' }}>Compte</option>
                            <option value="orders" {{ $ticket->category === 'orders' ? 'selected' : '' }}>Commandes</option>
                            <option value="payment" {{ $ticket->category === 'payment' ? 'selected' : '' }}>Paiement</option>
                            <option value="subscription" {{ $ticket->category === 'subscription' ? 'selected' : '' }}>Abonnement</option>
                            <option value="technical" {{ $ticket->category === 'technical' ? 'selected' : '' }}>Problème technique</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="font-weight-bold">Client</label>
                        <p>
                            {{ $ticket->user->name }}<br>
                            <small class="text-muted">{{ $ticket->user->email }}</small>
                        </p>
                    </div>
                    <div class="mb-3">
                        <label class="font-weight-bold">Date de création</label>
                        <p>{{ $ticket->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    @if($ticket->closed_at)
                    <div class="mb-3">
                        <label class="font-weight-bold">Date de fermeture</label>
                        <p>{{ $ticket->closed_at->format('d/m/Y H:i') }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Conversation -->
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Conversation</h6>
                </div>
                <div class="card-body">
                    <div class="messages-container" id="conversation">
                        @foreach($messages as $message)
                            @include('admin.support.partials.message', ['message' => $message])
                        @endforeach
                    </div>

                    <!-- Formulaire de réponse simplifié -->
                    <div class="reply-form mt-4">
                        <form method="POST" action="{{ route('admin.support.reply', $ticket->id) }}">
                            @csrf
                            <div class="form-group">
                                <label for="message">Votre réponse</label>
                                <textarea class="form-control" id="message" name="message" rows="4" required></textarea>
                            </div>
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="closeAfterReply" name="close_after_reply" value="1">
                                    <label class="custom-control-label" for="closeAfterReply">Fermer le ticket après la réponse</label>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane"></i> Envoyer
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .ticket-header {
        background-color: #f8f9fa;
        border-radius: 10px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }
    
    .status-indicator {
        padding: 0.35rem 0.75rem;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
    }
    
    .status-indicator i {
        margin-right: 0.5rem;
    }
    
    .status-open {
        background-color: #cff4fc;
        color: #055160;
    }
    
    .status-in_progress {
        background-color: #cfe2ff;
        color: #084298;
    }
    
    .status-waiting_user {
        background-color: #fff3cd;
        color: #664d03;
    }
    
    .status-closed {
        background-color: #e2e3e5;
        color: #41464b;
    }
    
    .priority-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 8px;
    }
    
    .priority-low {
        background-color: #20c997;
    }
    
    .priority-medium {
        background-color: #fd7e14;
    }
    
    .priority-high {
        background-color: #dc3545;
    }
    
    .priority-urgent {
        background-color: #dc3545;
        box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.3);
    }
    
    .category-badge {
        padding: 0.35rem 0.75rem;
        border-radius: 50px;
        font-size: 0.85rem;
        background-color: #f1f1f1;
        color: #555;
        font-weight: 500;
    }
    
    .messages-container {
        max-height: 650px;
        overflow-y: auto;
        padding: 1rem;
        background-color: #f8f9fa;
        border-radius: 10px;
    }
    
    .message {
        margin-bottom: 1.5rem;
        position: relative;
        display: flex;
        flex-direction: column;
    }
    
    .message-user {
        align-items: flex-start;
        margin-right: 15%;
    }
    
    .message-admin {
        align-items: flex-end;
        margin-left: 15%;
    }
    
    .message-bubble {
        border-radius: 18px;
        padding: 1rem 1.25rem;
        position: relative;
        max-width: 100%;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    
    .message-user .message-bubble {
        background-color: #ffffff;
        border: 1px solid #e0e0e0;
        border-bottom-left-radius: 4px;
    }
    
    .message-admin .message-bubble {
        background-color: #e9f5ff;
        border: 1px solid #cce5ff;
        border-bottom-right-radius: 4px;
    }
    
    .message-auto .message-bubble {
        background-color: #fff3cd;
        border: 1px solid #ffecb5;
    }
    
    .message-header {
        display: flex;
        align-items: center;
        margin-bottom: 0.75rem;
    }
    
    .message-user .message-header {
        justify-content: flex-start;
    }
    
    .message-admin .message-header {
        justify-content: flex-end;
    }
    
    .message-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        margin-right: 0.75rem;
    }
    
    .message-admin .message-avatar {
        margin-right: 0;
        margin-left: 0.75rem;
        order: 2;
    }
    
    .message-sender {
        font-weight: 600;
        font-size: 0.9rem;
    }
    
    .message-time {
        font-size: 0.75rem;
        color: #6c757d;
        margin-top: 0.5rem;
    }
    
    .message-user .message-time {
        align-self: flex-start;
    }
    
    .message-admin .message-time {
        align-self: flex-end;
    }
    
    .message-content {
        font-size: 0.95rem;
        line-height: 1.5;
        white-space: pre-wrap;
        word-break: break-word;
    }
    
    .reply-form {
        background-color: #ffffff;
        border-radius: 10px;
        padding: 1.5rem;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        margin-top: 1.5rem;
    }
    
    .ticket-info-card {
        border-radius: 10px;
        overflow: hidden;
        position: sticky;
        top: 20px;
    }
    
    .ticket-info-header {
        background-color: #f8f9fa;
        padding: 1rem;
        border-bottom: 1px solid #e9ecef;
    }
    
    .ticket-info-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .ticket-info-list li {
        padding: 0.75rem 1rem;
        border-bottom: 1px solid #e9ecef;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .ticket-info-list li:last-child {
        border-bottom: none;
    }
    
    .ticket-info-label {
        color: #6c757d;
        font-size: 0.9rem;
    }
    
    .ticket-info-value {
        font-weight: 500;
        font-size: 0.9rem;
        max-width: 60%;
        text-align: right;
        word-break: break-word;
    }
    
    .ticket-closed-banner {
        background-color: #e2e3e5;
        color: #41464b;
        padding: 1rem;
        border-radius: 10px;
        margin-bottom: 1.5rem;
        text-align: center;
        font-weight: 500;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .ticket-closed-banner i {
        font-size: 1.2rem;
        margin-right: 0.5rem;
    }
    
    .template-preview {
        background-color: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 0.25rem;
        padding: 1rem;
        margin-top: 1rem;
        display: none;
    }
    
    .btn-status {
        border-radius: 50px;
        font-size: 0.85rem;
        padding: 0.35rem 0.75rem;
    }
    
    .btn-status i {
        margin-right: 0.25rem;
    }
    
    .rich-editor {
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
        min-height: 150px;
        background-color: #fff;
        resize: vertical;
    }
    
    .action-buttons {
        position: sticky;
        bottom: 0;
        background-color: #fff;
        padding: 1rem 0;
        border-top: 1px solid #e9ecef;
        margin-top: 1rem;
        z-index: 10;
    }
    
    .ticket-actions-toolbar {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        padding: 0.75rem;
        margin-bottom: 1.5rem;
    }
    
    .ticket-subject {
        font-size: 1.4rem;
        font-weight: 600;
        color: #343a40;
        margin-bottom: 0.75rem;
        line-height: 1.3;
        word-break: break-word;
    }
    
    .empty-messages {
        text-align: center;
        padding: 3rem 1rem;
        color: #6c757d;
    }
    
    .empty-messages i {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }
    
    .reply-form-container {
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
    
    .message-date-divider {
        display: flex;
        align-items: center;
        margin: 1.5rem 0;
        color: #6c757d;
    }
    
    .message-date-divider::before,
    .message-date-divider::after {
        content: "";
        flex: 1;
        border-bottom: 1px solid #dee2e6;
    }
    
    .message-date-divider span {
        padding: 0 1rem;
        font-size: 0.85rem;
        background-color: #f8f9fa;
    }
</style>
@endpush

@push('scripts')
<script>
function updateStatus(status) {
    fetch('{{ route('admin.support.update-status', $ticket->id) }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ status: status })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Mettre à jour l'interface si nécessaire
            alert('Statut mis à jour avec succès');
        }
    });
}

function updatePriority(priority) {
    fetch('{{ route('admin.support.update-priority', $ticket->id) }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ priority: priority })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Mettre à jour l'interface si nécessaire
            alert('Priorité mise à jour avec succès');
        }
    });
}

function updateCategory(category) {
    fetch('{{ route('admin.support.update-category', $ticket->id) }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ category: category })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Mettre à jour l'interface si nécessaire
            alert('Catégorie mise à jour avec succès');
        }
    });
}

function deleteTicket() {
    if (confirm('Êtes-vous sûr de vouloir supprimer ce ticket ? Cette action est irréversible.')) {
        fetch('{{ route('admin.support.destroy', $ticket->id) }}', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = '{{ route('admin.support.index') }}';
            }
        });
    }
}
</script>
@endpush 