<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KLIN KLIN - Création de compte</title>
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
            max-width: 500px;
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
        
        .divider {
            width: 100%;
            text-align: center;
            border-bottom: 1px solid #e0e0e0;
            line-height: 0.1em;
            margin: 25px 0;
        }
        
        .divider span {
            background: #fff;
            padding: 0 10px;
            color: #6c757d;
            font-size: 14px;
        }
        
        .social-login {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-bottom: 25px;
        }
        
        .btn-social {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background-color: #f8f9fa;
            border: 1px solid #e0e0e0;
            transition: all 0.3s ease;
        }
        
        .btn-social:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        .btn-social i {
            font-size: 18px;
        }
        
        .btn-facebook i {
            color: #4267B2;
        }
        
        .btn-google i {
            color: #DB4437;
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
                max-width: 450px;
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
            
            <!-- Bloc droit - Formulaire d'inscription -->
            <div class="col-md-6 col-12 bg-white content-center col-right">
                <div class="form-container">
                    <div class="text-center mb-4">
                        <img src="{{ asset('img/logo.png') }}" alt="Logo" class="logo">
                        <h2 class="mb-4 fw-bold">Créer un compte</h2>
                    </div>
                    
                    <div class="social-login">
                        <a href="#" class="btn-social btn-facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="btn-social btn-google">
                            <i class="fab fa-google"></i>
                        </a>
                    </div>
                    
                    <div class="divider">
                        <span>OU</span>
                    </div>
                    
                    <form method="POST" action="{{ url('register') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <input type="text" class="form-control @error('firstname') is-invalid @enderror" id="firstname" name="firstname" placeholder="Prénom" value="{{ old('firstname') }}" required autocomplete="firstname" autofocus>
                                @error('firstname')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <input type="text" class="form-control @error('lastname') is-invalid @enderror" id="lastname" name="lastname" placeholder="Nom" value="{{ old('lastname') }}" required autocomplete="lastname">
                                @error('lastname')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="Adresse e-mail" value="{{ old('email') }}" required autocomplete="email">
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <input type="tel" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" placeholder="Numéro de téléphone" value="{{ old('phone') }}" required autocomplete="phone">
                            @error('phone')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Mot de passe" required autocomplete="new-password">
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <input type="password" class="form-control" id="password-confirm" name="password_confirmation" placeholder="Confirmer le mot de passe" required autocomplete="new-password">
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
                            <label class="form-check-label" for="terms">
                                J'accepte les <a href="#">conditions d'utilisation</a> et la <a href="#">politique de confidentialité</a>
                            </label>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-purple">Créer mon compte</button>
                        </div>
                    </form>
                    
                    <div class="auth-links text-center">
                        <p class="mt-3">Déjà un compte? <a href="{{ url('login') }}">Se connecter</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 