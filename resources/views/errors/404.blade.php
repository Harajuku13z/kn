@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="error-container text-center py-5">
                <div class="error-message">Oups ! La page que vous recherchez n'existe pas.</div>
                <img src="{{ asset('images/404-illustration.svg') }}" alt="404 Illustration" class="error-illustration">
                <p class="text-muted mb-4">La page que vous essayez d'accéder n'existe pas ou a été déplacée.</p>
                <a href="{{ route('home') }}" class="btn btn-primary home-button">
                    <i class="fas fa-home me-2"></i>Retour à l'accueil
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    .error-container {
        padding: 3rem 1rem;
    }
    .error-message {
        font-size: 1.5rem;
        color: #2C3E50;
        margin-bottom: 2rem;
    }
    .home-button {
        padding: 0.8rem 2rem;
        font-size: 1.1rem;
        transition: all 0.3s ease;
        background-color: #FF6B6B;
        border-color: #FF6B6B;
        color: white;
    }
    .home-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        background-color: #FF5252;
        border-color: #FF5252;
        color: white;
    }
    .error-illustration {
        max-width: 300px;
        margin: 2rem auto;
    }
    .text-muted {
        color: #7F8C8D !important;
    }
</style>
@endsection 