<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KLIN KLIN - Récupération de mot de passe</title>
    <!-- Google Fonts - Montserrat -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body, html {
            height: 100%;
            overflow-x: hidden;
            font-family: 'Montserrat', sans-serif;
        }
        
        .purple-bg {
            background-color: #461871;
            color: white;
        }
        
        .logo {
            max-width: 150px;
            margin-bottom: 20px;
        }
        
        .card-img {
            max-width: 100%;
            height: auto;
            margin-bottom: 0;
        }
        
        .ceo-message {
            font-style: italic;
            font-size: 1.1rem;
            color: #333;
            font-family: 'Montserrat', sans-serif;
            font-weight: 500;
        }
        
        .btn-purple {
            background-color: #461871;
            color: white;
            border-radius: 20px;
            padding: 8px 20px;
            font-family: 'Montserrat', sans-serif;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-purple:hover {
            background-color: #5a2090;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(70, 24, 113, 0.2);
        }
        
        .btn-outline-purple {
            color: #461871;
            border-color: #461871;
            border-radius: 20px;
            padding: 8px 20px;
            font-family: 'Montserrat', sans-serif;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-outline-purple:hover {
            background-color: #461871;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(70, 24, 113, 0.2);
        }
        
        .btn-home {
            border-radius: 20px;
            padding: 8px 20px;
            position: absolute;
            top: 20px;
            left: 20px;
            z-index: 100;
            background-color: #8dffc8;
            color: #333;
            border: none;
            font-family: 'Montserrat', sans-serif;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-home:hover {
            background-color: #7aedb7;
            color: #333;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(141, 255, 200, 0.3);
        }
        
        .form-container {
            max-width: 400px;
            margin: 0 auto;
        }
        
        .form-control {
            border-radius: 10px;
            margin-bottom: 15px;
            font-family: 'Montserrat', sans-serif;
            padding: 12px 15px;
            border: 1px solid #e0e0e0;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: #461871;
            box-shadow: 0 0 0 0.2rem rgba(70, 24, 113, 0.25);
        }
        
        .content-center {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100%;
            padding: 2rem;
        }
        
        .col-left {
            position: fixed;
            width: 50%;
            left: 0;
            top: 0;
            bottom: 0;
        }
        
        .col-right {
            margin-left: 50%;
            min-height: 100%;
            overflow-y: auto;
        }
        
        .auth-links {
            margin-top: 20px;
            font-family: 'Montserrat', sans-serif;
        }
        
        .auth-links a {
            color: #461871;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .auth-links a:hover {
            color: #5a2090;
            text-decoration: none;
        }
        
        .icon-lock {
            font-size: 48px;
            color: #461871;
            margin-bottom: 20px;
        }
        
        h1, h2, h3, h4, h5, h6, p, a, span, div, button, input, label {
            font-family: 'Montserrat', sans-serif;
        }
        
        /* For mobile devices */
        @media (max-width: 767px) {
            .row {
                flex-direction: column;
            }
            .col-left {
                display: none;
            }
            .col-right {
                margin-left: 0;
                min-height: 100vh;
                padding-top: 80px;
                padding-bottom: 30px;
            }
            .btn-home {
                top: 15px;
                left: 15px;
            }
            body {
                padding-top: 0;
            }
            .form-container {
                width: 90%;
                max-width: 400px;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid h-100">
        <a href="{{ url('/') }}" class="btn btn-home">
            <i class="fas fa-home"></i> Retour à l'accueil
        </a>
        <div class="row h-100">
            <!-- Bloc gauche - Violet avec image et message du CEO en bas -->
            <div class="col-md-6 purple-bg content-center col-left d-none d-md-flex">
                <div class="text-center col-md-9 mx-auto">
                    <img src="{{ asset('img/card.png') }}" alt="Card Image" class="card-img mb-0">
                    <div class="bg-white text-dark p-4 rounded">
                        <h4 class="mb-2 ceo-message">"Notre vision est de créer un service de blanchisserie moderne, efficace et respectueux de l'environnement."</h4>
                        <p class="mb-0 text-muted">Direction KLIN KLIN</p>
                    </div>
                </div>
            </div>
            
            <!-- Bloc droit - Formulaire de mot de passe oublié -->
            <div class="col-md-6 col-12 bg-white content-center col-right">
                <div class="form-container">
                    <div class="text-center mb-4">
                        <img src="{{ asset('img/logo.png') }}" alt="Logo" class="logo">
                        <i class="fas fa-lock icon-lock"></i>
                        <h2 class="mb-3 fw-bold">Mot de passe oublié ?</h2>
                        <p class="text-muted mb-4">Entrez votre adresse e-mail et nous vous enverrons un lien pour réinitialiser votre mot de passe.</p>
                    </div>
                    
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ url('password/email') }}">
                        @csrf
                        <div class="mb-4">
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="Adresse e-mail" value="{{ old('email') }}" required autocomplete="email" autofocus>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-purple">Envoyer le lien de réinitialisation</button>
                        </div>
                    </form>
                    
                    <div class="auth-links text-center">
                        <p class="mt-4">Retour à la <a href="{{ url('login') }}">connexion</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 