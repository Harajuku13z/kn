<div class="message {{ $message->is_from_admin ? 'message-admin' : 'message-user' }} {{ $message->is_auto_reply ? 'message-auto' : '' }}">
    <div class="message-content">
        {!! $message->message !!}
    </div>
    <div class="message-time">
        {{ $message->created_at->format('d/m/Y H:i') }}
        @if($message->is_auto_reply)
            <span class="badge bg-warning text-dark ms-2">Réponse automatique</span>
        @endif
    </div>
</div> 