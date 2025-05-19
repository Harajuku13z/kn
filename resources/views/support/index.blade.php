@extends('layouts.dashboard')

@section('title', 'Aide & Support - KLINKLIN')

@push('styles')
<style>
    .support-header {
        background-color: #f8f9fa;
        border-radius: 10px;
        padding: 2rem;
        margin-bottom: 2rem;
    }
    
    .support-card {
        transition: transform 0.2s, box-shadow 0.2s;
        height: 100%;
        cursor: pointer;
    }
    
    .support-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    
    .ticket-list {
        max-height: 600px;
        overflow-y: auto;
    }
    
    .ticket-item {
        border-left: 4px solid #e9ecef;
        transition: background-color 0.2s;
    }
    
    .ticket-item:hover {
        background-color: #f8f9fa;
    }
    
    .ticket-item.status-open {
        border-left-color: #17a2b8;
    }
    
    .ticket-item.status-in_progress {
        border-left-color: #007bff;
    }
    
    .ticket-item.status-waiting_user {
        border-left-color: #ffc107;
    }
    
    .ticket-item.status-closed {
        border-left-color: #6c757d;
    }
    
    .status-badge {
        font-size: 0.8rem;
        padding: 0.25rem 0.5rem;
    }
    
    .category-badge {
        font-size: 0.75rem;
        padding: 0.2rem 0.5rem;
        background-color: #f1f1f1;
        color: #555;
    }
    
    .ticket-reference {
        font-size: 0.8rem;
        color: #6c757d;
    }
    
    .ticket-date {
        font-size: 0.8rem;
        color: #6c757d;
    }
    
    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
    }
    
    .empty-state-icon {
        font-size: 4rem;
        color: #e9ecef;
        margin-bottom: 1rem;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h1 class="h3 mb-4">Aide & Support</h1>
        </div>
    </div>
    
    <div class="support-header">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h2 class="h4 mb-3">Comment pouvons-nous vous aider aujourd'hui ?</h2>
                <p class="text-muted mb-0">Consultez notre FAQ pour des réponses rapides ou créez un ticket pour une assistance personnalisée.</p>
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <a href="{{ route('support.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i> Nouveau ticket
                </a>
                <a href="{{ route('support.faq') }}" class="btn btn-outline-secondary ms-2">
                    <i class="bi bi-question-circle me-2"></i> FAQ
                </a>
            </div>
        </div>
    </div>
    
    <div class="row mb-4">
        <div class="col-md-4 mb-4">
            <div class="card h-100 support-card" onclick="window.location.href='{{ route('support.create') }}'">
                <div class="card-body text-center p-4">
                    <div class="mb-3">
                        <i class="bi bi-ticket-perforated text-primary" style="font-size: 2.5rem;"></i>
                    </div>
                    <h5 class="card-title">Créer un ticket</h5>
                    <p class="card-text text-muted">Besoin d'aide ? Créez un nouveau ticket et notre équipe vous répondra dans les plus brefs délais.</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card h-100 support-card" onclick="window.location.href='{{ route('support.faq') }}'">
                <div class="card-body text-center p-4">
                    <div class="mb-3">
                        <i class="bi bi-question-circle text-info" style="font-size: 2.5rem;"></i>
                    </div>
                    <h5 class="card-title">FAQ</h5>
                    <p class="card-text text-muted">Consultez notre foire aux questions pour trouver rapidement des réponses à vos interrogations.</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card h-100 support-card" onclick="window.location.href='#contact-info'">
                <div class="card-body text-center p-4">
                    <div class="mb-3">
                        <i class="bi bi-telephone text-success" style="font-size: 2.5rem;"></i>
                    </div>
                    <h5 class="card-title">Contact direct</h5>
                    <p class="card-text text-muted">Besoin d'une assistance immédiate ? Contactez-nous directement par téléphone ou email.</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Mes tickets <small class="text-muted">(triés par dernière interaction)</small></h5>
                    <div>
                        <a href="{{ route('support.create') }}" class="btn btn-sm btn-primary">
                            <i class="bi bi-plus-circle me-1"></i> Nouveau ticket
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($tickets->count() > 0)
                        <div class="ticket-list">
                            <div class="list-group list-group-flush">
                                @foreach($tickets as $ticket)
                                    <a href="{{ route('support.show', $ticket->id) }}" class="list-group-item list-group-item-action ticket-item status-{{ $ticket->status }} p-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-1">{{ $ticket->subject }}</h6>
                                                <div class="d-flex align-items-center">
                                                    <span class="ticket-reference me-2">#{{ $ticket->reference_number }}</span>
                                                    <span class="category-badge rounded-pill me-2">{{ $categories[$ticket->category] ?? $ticket->category }}</span>
                                                </div>
                                            </div>
                                            <div class="text-end">
                                                @if($ticket->status === 'open')
                                                    <span class="status-badge badge bg-info">{{ $ticket->getStatusLabel() }}</span>
                                                @elseif($ticket->status === 'in_progress')
                                                    <span class="status-badge badge bg-primary">{{ $ticket->getStatusLabel() }}</span>
                                                @elseif($ticket->status === 'waiting_user')
                                                    <span class="status-badge badge bg-warning text-dark">{{ $ticket->getStatusLabel() }}</span>
                                                @elseif($ticket->status === 'closed')
                                                    <span class="status-badge badge bg-secondary">{{ $ticket->getStatusLabel() }}</span>
                                                @endif
                                                <div class="ticket-date mt-1">
                                                    <i class="bi bi-clock me-1"></i> 
                                                    Dernière interaction: {{ $ticket->latestMessage ? $ticket->latestMessage->created_at->format('d/m/Y H:i') : $ticket->created_at->format('d/m/Y H:i') }}
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <i class="bi bi-ticket"></i>
                            </div>
                            <h5>Aucun ticket</h5>
                            <p class="text-muted">Vous n'avez pas encore créé de ticket de support.</p>
                            <a href="{{ route('support.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-2"></i> Créer mon premier ticket
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mt-5" id="contact-info">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Nous contacter</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-4 mb-md-0">
                            <div class="text-center">
                                <i class="bi bi-telephone-fill text-primary mb-3" style="font-size: 2rem;"></i>
                                <h5>Téléphone</h5>
                                <p class="mb-0">+225 07 07 07 07 07</p>
                                <p class="text-muted small">Lun-Ven: 8h-18h</p>
                            </div>
                        </div>
                        <div class="col-md-4 mb-4 mb-md-0">
                            <div class="text-center">
                                <i class="bi bi-envelope-fill text-primary mb-3" style="font-size: 2rem;"></i>
                                <h5>Email</h5>
                                <p class="mb-0">support@klinklin.com</p>
                                <p class="text-muted small">Réponse sous 24-48h</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center">
                                <i class="bi bi-chat-dots-fill text-primary mb-3" style="font-size: 2rem;"></i>
                                <h5>Chat en ligne</h5>
                                <p class="mb-0">Disponible sur notre site</p>
                                <p class="text-muted small">7j/7 de 8h à 22h</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Animations pour les cartes
        const cards = document.querySelectorAll('.support-card');
        cards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-5px)';
                this.style.boxShadow = '0 10px 20px rgba(0,0,0,0.1)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = '0 0.125rem 0.25rem rgba(0,0,0,0.075)';
            });
        });
    });
</script>
@endpush 