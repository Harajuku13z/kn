@if ($notifications->isEmpty())
    <div class="card shadow-sm notification-empty-card-redesigned">
        <div class="card-body text-center p-5">
            <div class="display-1 mb-4 text-muted">
                <i class="bi bi-bell-slash"></i>
            </div>
            <h3 class="mb-3">Aucune notification</h3>
            <p class="text-muted mb-0">
                Vous n'avez pas de notification pour le moment.
                <br>Revenez ici plus tard pour voir vos mises à jour.
            </p>
        </div>
    </div>
@else
    <div class="notifications-list-inner">
        @foreach ($notifications as $notification)
        <div class="card mb-3 shadow-sm notification-item {{ $notification->read_at ? 'read' : 'unread' }} border-{{ $notification->data['type'] ?? 'klin-primary' }}" id="notification-{{ $notification->id }}">
            <div class="card-body p-3">
                <div class="d-flex align-items-start">
                    <div class="notification-status-indicator me-2">
                        @if (!$notification->read_at)
                            <span class="notification-unread-dot"></span>
                        @endif
                    </div>
                    <div class="notification-item-icon fs-4 me-3">
                        <i class="bi {{ $notification->data['icon'] ?? 'bi-bell' }} text-{{ $notification->data['color'] ?? 'primary' }}"></i>
                    </div>
                    <div class="notification-content flex-grow-1">
                        <h5 class="notification-title mb-1 fs-6 fw-semibold">
                            {{ $notification->data['title'] ?? 'Mise à jour de commande' }}
                        </h5>
                        <div class="notification-message mb-2 text-secondary">
                            {{ $notification->data['message'] ?? 'Contenu de la notification' }}
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="notification-date text-muted">
                                <i class="bi bi-clock me-1"></i>
                                {{ $notification->created_at->diffForHumans() }}
                            </div>
                            <div class="notification-actions">
                                @if (!$notification->read_at)
                                <a href="{{ route('notifications.read', $notification->id) }}" class="btn btn-sm btn-light mark-as-read-btn" onclick="event.stopPropagation(); markAsRead(event, '{{ $notification->id }}');">
                                    <i class="bi bi-check-lg me-1"></i> Marquer comme lu
                                </a>
                                @endif
                                @if (isset($notification->data['action_url']) && $notification->data['action_url'] != '#')
                                <a href="{{ $notification->data['action_url'] }}" class="btn btn-sm btn-primary klin-btn action-link ms-2" onclick="event.stopPropagation();">
                                    <i class="bi bi-arrow-right me-1"></i> {{ $notification->data['action_text'] ?? 'Voir détails' }}
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-4 d-flex justify-content-center">
        {{ $notifications->links() }}
    </div>
    
    <script>
    function markAsRead(event, id) {
        event.preventDefault();
        
        // Envoyer la requête AJAX pour marquer comme lu
        fetch('{{ url("notifications") }}/' + id + '/read-ajax', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Mettre à jour l'interface
                const notificationItem = document.getElementById('notification-' + id);
                if (notificationItem) {
                    notificationItem.classList.remove('unread');
                    notificationItem.classList.add('read');
                    
                    // Masquer le bouton "Marquer comme lu"
                    const readBtn = notificationItem.querySelector('.mark-as-read-btn');
                    if (readBtn) {
                        readBtn.style.display = 'none';
                    }
                    
                    // Enlever le point rouge
                    const dot = notificationItem.querySelector('.notification-unread-dot');
                    if (dot) {
                        dot.remove();
                    }
                    
                    // Mettre à jour les compteurs
                    updateNotificationCounters(data.unread_count, data.read_count);
                }
            }
        })
        .catch(error => {
            console.error('Erreur lors du marquage de la notification:', error);
        });
    }
    
    function updateNotificationCounters(unreadCount, readCount) {
        // Mettre à jour les compteurs dans la page
        const totalCount = unreadCount + readCount;
        const totalElement = document.getElementById('notifications-count-total');
        const unreadElement = document.getElementById('notifications-count-unread');
        const readElement = document.getElementById('notifications-count-read');
        
        if (totalElement) totalElement.textContent = totalCount;
        if (unreadElement) unreadElement.textContent = unreadCount;
        if (readElement) readElement.textContent = readCount;
    }
    </script>
@endif 