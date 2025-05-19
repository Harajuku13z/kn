@extends('layouts.app')

@section('title', 'Service de Repassage | KLIN KLIN')

@section('content')
    <div class="hero-section py-5 text-center bg-light">
        <div class="container">
            <h1 class="mb-4 text-primary fw-bold">Service de Repassage</h1>
            <p class="lead text-muted mx-auto mb-5" style="max-width: 700px;">Un repassage professionnel pour des vêtements impeccables.</p>
            <div class="d-flex justify-content-center gap-3">
                <a href="#pourquoi" class="btn btn-primary rounded-pill px-4 py-2">Pourquoi choisir notre service ?</a>
                <a href="#processus" class="btn btn-outline-primary rounded-pill px-4 py-2">Notre processus de repassage</a>
            </div>
        </div>
    </div>

    <section id="pourquoi" class="py-5 bg-white">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <h2 class="mb-3 text-primary fw-bold">Le repassage par des professionnels</h2>
                    <p class="lead text-muted mb-4">Confiez vos vêtements à notre équipe expérimentée pour un résultat impeccable et un gain de temps considérable.</p>

                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="d-flex align-items-start">
                                <div class="icon-box bg-primary-light text-primary rounded-circle p-3 me-3">
                                    <i class="fas fa-check-circle fa-2x"></i>
                                </div>
                                <div>
                                    <h3 class="h5 mb-2">Repassage de précision</h3>
                                    <p class="text-muted small">Techniques adaptées à chaque tissu pour un rendu parfait.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-start">
                                <div class="icon-box bg-info-light text-info rounded-circle p-3 me-3">
                                    <i class="fas fa-tshirt fa-2x"></i>
                                </div>
                                <div>
                                    <h3 class="h5 mb-2">Tous types de vêtements</h3>
                                    <p class="text-muted small">Du quotidien aux pièces délicates, nous prenons soin de tout.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-start">
                                <div class="icon-box bg-warning-light text-warning rounded-circle p-3 me-3">
                                    <i class="fas fa-box fa-2x"></i>
                                </div>
                                <div>
                                    <h3 class="h5 mb-2">Pliage soigné</h3>
                                    <p class="text-muted small">Vos vêtements sont pliés ou mis sur cintre avec soin.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-start">
                                <div class="icon-box bg-success-light text-success rounded-circle p-3 me-3">
                                    <i class="fas fa-heart fa-2x"></i>
                                </div>
                                <div>
                                    <h3 class="h5 mb-2">Équipement professionnel</h3>
                                    <p class="text-muted small">Qualité supérieure grâce à notre matériel expert.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('simulateur') }}" class="btn btn-primary me-3 rounded-pill px-4 py-2">Simuler un prix</a>
                        <a href="{{ route('contact') }}" class="btn btn-outline-primary rounded-pill px-4 py-2">Nous contacter</a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <img src="{{ asset('img/lavage.png') }}" alt="Service de repassage professionnel" class="img-fluid rounded-4 shadow">
                </div>
            </div>
        </div>
    </section>

    <section id="processus" class="py-5 bg-light">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-lg-8 mx-auto">
                    <h2 class="mb-3 text-primary fw-bold">Notre processus de repassage</h2>
                    <p class="lead text-muted">Un soin méticuleux pour des résultats parfaits à chaque fois.</p>
                </div>
            </div>

            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
                <div class="col" data-aos="fade-up" data-aos-delay="100">
                    <div class="process-step text-center p-4 bg-white rounded-4 shadow-sm h-100">
                        <div class="process-icon mb-3 text-primary">
                            <i class="fas fa-sort fa-3x"></i>
                        </div>
                        <h3 class="h5 fw-bold mb-2">1. Inspection</h3>
                        <p class="text-muted small">Examen détaillé de chaque vêtement.</p>
                    </div>
                </div>
                <div class="col" data-aos="fade-up" data-aos-delay="200">
                    <div class="process-step text-center p-4 bg-white rounded-4 shadow-sm h-100">
                        <div class="process-icon mb-3 text-info">
                            <i class="fas fa-tint fa-3x"></i>
                        </div>
                        <h3 class="h5 fw-bold mb-2">2. Préparation</h3>
                        <p class="text-muted small">Humidification et réglage de la température.</p>
                    </div>
                </div>
                <div class="col" data-aos="fade-up" data-aos-delay="300">
                    <div class="process-step text-center p-4 bg-white rounded-4 shadow-sm h-100">
                        <div class="process-icon mb-3 text-warning">
                            <i class="fas fa-heart fa-3x"></i>
                        </div>
                        <h3 class="h5 fw-bold mb-2">3. Repassage</h3>
                        <p class="text-muted small">Repassage précis et soigné.</p>
                    </div>
                </div>
                <div class="col" data-aos="fade-up" data-aos-delay="400">
                    <div class="process-step text-center p-4 bg-white rounded-4 shadow-sm h-100">
                        <div class="process-icon mb-3 text-success">
                            <i class="fas fa-check-circle fa-3x"></i>
                        </div>
                        <h3 class="h5 fw-bold mb-2">4. Finition</h3>
                        <p class="text-muted small">Inspection finale et pliage/cintrage.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>


   
@endsection

@section('styles')
<style>
    .hero-section {
        background-image: url('{{ asset('img/ironing-hero-small.jpg') }}'); /* Image de fond simplifiée pour le mobile */
        background-size: cover;
        background-position: center;
    }

    @media (min-width: 992px) {
        .hero-section {
            background-image: url('{{ asset('img/ironing-hero.jpg') }}');
        }
    }

    .icon-box {
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .process-step .process-icon {
        color: var(--primary);
        margin-bottom: 1rem;
    }

    .bg-primary-light { background-color: #e0f2f7; }
    .text-primary { color: #1e88e5; }
    .bg-info-light { background-color: #e0f7fa; }
    .text-info { color: #00bcd4; }
    .bg-warning-light { background-color: #fffde7; }
    .text-warning { color: #ffc107; }
    .bg-success-light { background-color: #e8f5e9; }
    .text-success { color: #4caf50; }

    .testimonial-card .testimonial-rating i {
        margin-right: 0.2rem;
    }
</style>
@endsection

@section('scripts')
<script>
    // Scripts spécifiques à la page de repassage (si nécessaire)
</script>
@endsection