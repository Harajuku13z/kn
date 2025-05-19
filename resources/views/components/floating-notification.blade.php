@props(['id' => null, 'type' => 'info', 'title' => 'Notification', 'message' => '', 'icon' => null, 'actionUrl' => null, 'actionText' => null, 'time' => null])

@php
    // Simplifier la logique des icônes
    $defaultIcon = 'bi-bell-fill';
    
    // Assigner les icônes en fonction du type
    if ($type === 'success') {
        $defaultIcon = 'bi-check-circle-fill';
    } elseif ($type === 'danger' || $type === 'error') {
        $defaultIcon = 'bi-exclamation-octagon-fill';
    } elseif ($type === 'warning') {
        $defaultIcon = 'bi-exclamation-triangle-fill';
    } elseif ($type === 'address') {
        $defaultIcon = 'bi-geo-alt-fill';
    } elseif ($type === 'profile') {
        $defaultIcon = 'bi-person-fill';
    } elseif ($type === 'order') {
        $defaultIcon = 'bi-box-seam-fill';
    } elseif ($type === 'subscription') {
        $defaultIcon = 'bi-star-fill';
    } elseif ($type === 'info') {
        $defaultIcon = 'bi-info-circle-fill';
    }
    
    // Utiliser l'icône fournie ou celle par défaut
    $iconClass = $icon ?? $defaultIcon;
@endphp

<div id="{{ $id ?? 'floating-notification-'.uniqid() }}" 
     class="floating-notification toast shadow-sm position-relative" 
     role="alert" 
     aria-live="assertive" 
     aria-atomic="true" 
     data-bs-delay="8000"
     style="min-width: 350px; z-index: 1999; background-color: #f2eef5; color: #333;">
    <div class="toast-header border-0" style="background-color: #f2eef5;">
        <i class="bi {{ $iconClass }} me-2 text-primary"></i>
        <strong class="me-auto">{{ $title }}</strong>
        @if($time)
            <small class="text-muted">{{ $time }}</small>
        @endif
        <button type="button" class="btn-close close-notification" data-bs-dismiss="toast" aria-label="Close" style="z-index: 2010;" onclick="event.stopPropagation();"></button>
    </div>
    <div class="toast-body">
        <p class="mb-0">{{ $message }}</p>
        <div class="mt-3 text-end">
            @if($actionText && $actionUrl)
                <a href="{{ $actionUrl }}" class="btn btn-sm btn-primary" data-no-ajax style="z-index: 2005; position: relative;" onclick="event.stopPropagation();">{{ $actionText }}</a>
            @endif
        </div>
    </div>
    
    @if($id)
        <a href="#" 
           class="stretched-link"
           onclick="event.preventDefault(); playNotificationSound(); handleNotificationClick('{{ $id }}', '{{ $actionUrl }}');"
           style="z-index: 5;"></a>
    @endif
</div>

<!-- Audio pour la notification -->
<audio id="notification-sound" preload="auto" style="display: none;">
    <source src="{{ asset('sounds/notification.mp3') }}" type="audio/mpeg">
</audio>

<script>
    // Jouer un son de notification
    function playNotificationSound() {
        const audio = document.getElementById('notification-sound');
        if (audio) {
            audio.play().catch(error => {
                console.log('Impossible de jouer le son: ', error);
            });
        }
    }
    
    function markNotificationRead(id) {
        if (id) {
            // Utiliser navigator.sendBeacon pour une requête asynchrone 
            // qui se terminera même si l'utilisateur navigue ailleurs
            const url = '{{ url("notifications") }}/' + id + '/read-ajax';
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            // Créer les données pour sendBeacon
            const formData = new FormData();
            formData.append('_token', csrfToken);
            
            // Envoyer la requête
            navigator.sendBeacon(url, formData);
        }
    }
    
    function handleNotificationClick(id, actionUrl) {
        // Marquer comme lu
        markNotificationRead(id);
        
        // Si URL d'action existe, rediriger
        if (actionUrl && actionUrl !== '#') {
            window.location.href = actionUrl;
        } else {
            // Fermer la notification
            const toastEl = document.getElementById(id);
            if (toastEl) {
                const toast = bootstrap.Toast.getInstance(toastEl);
                if (toast) {
                    toast.hide();
                }
            }
        }
    }
    
    // S'assurer que le bouton de fermeture fonctionne
    document.addEventListener('DOMContentLoaded', function() {
        // Jouer le son quand la notification est affichée
        playNotificationSound();
        
        // Ajouter un gestionnaire d'événement pour le bouton de fermeture
        document.querySelectorAll('.close-notification').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                const toastEl = this.closest('.toast');
                const toast = bootstrap.Toast.getInstance(toastEl);
                if (toast) {
                    toast.hide();
                }
            });
        });
    });
</script>

<style>
    /* Styles responsive pour les notifications flottantes */
    @media (max-width: 576px) {
        .floating-notification {
            min-width: 100% !important;
            max-width: 100% !important;
            width: 100% !important;
            margin: 0 auto !important;
            border-radius: 0 !important;
            bottom: 0 !important;
            position: fixed !important;
            left: 0 !important;
            right: 0 !important;
        }
        
        .floating-notifications-container {
            width: 100% !important;
            padding: 0 !important;
            max-width: 100% !important;
            bottom: 0 !important;
            top: auto !important;
            position: fixed !important;
        }
    }
    
    /* Style pour que le bouton de fermeture soit plus visible */
    .btn-close {
        background-color: rgba(255, 255, 255, 0.8) !important;
        padding: 0.5rem !important;
        border-radius: 50% !important;
        opacity: 0.8 !important;
        z-index: 2010 !important;
        position: relative !important;
    }
    
    .btn-close:hover {
        opacity: 1 !important;
        background-color: white !important;
    }
</style> 