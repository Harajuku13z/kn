@if(auth()->check())
    <li class="nav-item dropdown">
        @php
            $notifications = auth()->user()->unreadNotifications()->take(5)->get();
            $notificationCount = auth()->user()->unreadNotifications()->count();
        @endphp
        <a class="nav-link dropdown-toggle" href="#" id="notificationsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fas fa-bell"></i>
            @if($notificationCount > 0)
                <span class="badge rounded-pill bg-danger">{{ $notificationCount }}</span>
            @endif
        </a>
        <ul class="dropdown-menu dropdown-menu-end notification-dropdown" aria-labelledby="notificationsDropdown" style="width: 300px; max-height: 400px; overflow-y: auto;">
            <li class="dropdown-header d-flex justify-content-between align-items-center">
                <span>Notifications ({{ $notificationCount }})</span>
                @if($notificationCount > 0)
                    <form action="{{ route('notifications.mark-all-read') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-link btn-sm p-0 text-muted">Tout marquer comme lu</button>
                    </form>
                @endif
            </li>
            
            @if($notifications->isEmpty())
                <li><div class="dropdown-item text-center text-muted py-2">Aucune notification</div></li>
            @else
                @foreach($notifications as $notification)
                    <li>
                        <a class="dropdown-item d-flex align-items-center py-2" href="{{ route('notifications.read', $notification->id) }}">
                            <div class="flex-shrink-0 me-2">
                                <i class="fas {{ $notification->data['icon'] ?? 'fa-bell' }} text-{{ $notification->data['color'] ?? 'primary' }}"></i>
                            </div>
                            <div class="flex-grow-1">
                                <p class="mb-0 fw-semibold">{{ $notification->data['title'] ?? 'Mise à jour de commande' }}</p>
                                <small class="text-muted">{{ $notification->data['message'] ?? 'Notification' }}</small>
                                <small class="d-block text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                            </div>
                        </a>
                    </li>
                @endforeach
            @endif
            
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item text-center" href="{{ route('notifications.index') }}">Voir toutes les notifications</a></li>
        </ul>
    </li>
@endif 