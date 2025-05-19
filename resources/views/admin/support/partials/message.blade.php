@php
    $isAdmin = isset($is_admin) && $is_admin;
    $messageClass = $isAdmin ? 'bg-primary text-white' : 'bg-light';
    $alignment = $isAdmin ? 'justify-content-end' : 'justify-content-start';
    $user = $message->user;
@endphp

<div class="message-wrapper mb-4 d-flex {{ $alignment }}">
    <div class="message {{ $messageClass }} rounded-lg p-3 shadow-sm" style="max-width: 80%;">
        <div class="d-flex align-items-center mb-2">
            @if($user->avatar)
                <img src="{{ $user->avatar }}" alt="{{ $user->name }}" class="rounded-circle mr-2" width="32" height="32">
            @else
                <div class="avatar-placeholder rounded-circle mr-2 d-flex align-items-center justify-content-center" 
                     style="width: 32px; height: 32px; background-color: {{ $isAdmin ? '#fff' : '#e9ecef' }};">
                    <span class="text-{{ $isAdmin ? 'primary' : 'secondary' }} font-weight-bold">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </span>
                </div>
            @endif
            <div class="message-header">
                <strong class="text-{{ $isAdmin ? 'white' : 'dark' }}">{{ $user->name }}</strong>
                <small class="text-{{ $isAdmin ? 'white-50' : 'muted' }} ml-2">
                    {{ $message->created_at->format('d/m/Y H:i') }}
                </small>
            </div>
        </div>
        <div class="message-content">
            {!! nl2br(e($message->message)) !!}
        </div>
    </div>
</div>

<style>
.message-wrapper {
    transition: all 0.3s ease;
}

.message {
    position: relative;
    word-wrap: break-word;
}

.message::before {
    content: '';
    position: absolute;
    top: 15px;
    width: 0;
    height: 0;
    border-style: solid;
}

.message-wrapper.justify-content-end .message::before {
    right: -8px;
    border-width: 8px 0 8px 8px;
    border-color: transparent transparent transparent #007bff;
}

.message-wrapper.justify-content-start .message::before {
    left: -8px;
    border-width: 8px 8px 8px 0;
    border-color: transparent #f8f9fa transparent transparent;
}

.avatar-placeholder {
    background-color: #e9ecef;
    color: #6c757d;
    font-weight: bold;
    text-align: center;
    line-height: 32px;
}
</style> 