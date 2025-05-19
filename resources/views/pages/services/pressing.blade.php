@extends('layouts.app')

@section('title', 'Service de Pressing')

@section('content')
   
    <!-- Service Description -->
    <section class="py-5">
        <div class="container">
            <div class="row align-items-center">
            <div class="col-lg-6">
                    <div class="rounded-4 overflow-hidden shadow">
                        <img src="{{ asset('img/pressing.png') }}" alt="Service de pressing" class="img-fluid w-100" style="object-fit: cover; height: 450px;">
                    </div>
                </div>
                <div class="col-lg-6 mb-4 mb-lg-0"> <br><br>
                    <h2 class="section-title">Un pressing de qualité pour vos vêtements</h2>
                    
                    <div class="service-features mb-4">
                        <div class="feature-item d-flex align-items-start mb-3">
                            <i class="fas fa-check-circle text-primary mt-1 me-3"></i>
                            <div>
                                <h4 class="h5">Nettoyage à sec professionnel</h4>
                                <p>Notre technologie de pointe assure un nettoyage en profondeur de vos vêtements délicats sans les endommager.</p>
                            </div>
                        </div>
                        <div class="feature-item d-flex align-items-start mb-3">
                            <i class="fas fa-check-circle text-primary mt-1 me-3"></i>
                            <div>
                                <h4 class="h5">Traitement des taches tenaces</h4>
                                <p>Nos experts savent traiter efficacement les taches difficiles sur tous types de tissus, même les plus sensibles.</p>
                            </div>
                        </div>
                        <div class="feature-item d-flex align-items-start">
                            <i class="fas fa-check-circle text-primary mt-1 me-3"></i>
                            <div>
                                <h4 class="h5">Finition impeccable</h4>
                                <p>Après le nettoyage, vos vêtements sont soigneusement repassés et préparés pour vous être rendus dans un état impeccable.</p>
                            </div>
                        </div>
                    </div>
                    
                </div>
                
            </div>
        </div>
    </section>

    <!-- Types de Vêtements Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-lg-8 mx-auto">
                    <h2 class="section-title">Vêtements adaptés au pressing</h2>
                    <p class="section-description">Notre service s'occupe de tous vos textiles spéciaux avec une expertise professionnelle</p>
                </div>
            </div>
            
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm service-card">
                        <div class="card-body p-4">
                            <div class="text-center mb-4">
                                <div class="icon-circle mb-3 mx-auto">
                                    <i class="fas fa-shirt fa-2x"></i>
                                </div>
                                <h3 class="card-title h4">Tenues de cérémonie</h3>
                            </div>
                            <p class="card-text">Costumes, smoking, robes de soirée et tenues de gala nettoyés avec le plus grand soin pour préserver leur élégance.</p>
                            <ul class="list-unstyled mt-3 mb-0">
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Préservation des détails délicats</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Respect des broderies et ornements</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Traitement adapté aux tissus luxueux</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm service-card">
                        <div class="card-body p-4">
                            <div class="text-center mb-4">
                                <div class="icon-circle mb-3 mx-auto">
                                    <i class="fas fa-mitten fa-2x"></i>
                                </div>
                                <h3 class="card-title h4">Textiles délicats</h3>
                            </div>
                            <p class="card-text">Soie, cachemire, laine, velours et autres tissus précieux qui nécessitent un traitement spécifique sans eau.</p>
                            <ul class="list-unstyled mt-3 mb-0">
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Respect des fibres sensibles</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Nettoyage doux et efficace</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Préservation des couleurs et textures</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm service-card">
                        <div class="card-body p-4">
                            <div class="text-center mb-4">
                                <div class="icon-circle mb-3 mx-auto">
                                    <i class="fas fa-couch fa-2x"></i>
                                </div>
                                <h3 class="card-title h4">Articles spéciaux</h3>
                            </div>
                            <p class="card-text">Manteaux, vestes, cuir, daim, rideaux et autres articles volumineux ou spécifiques qui nécessitent un traitement professionnel.</p>
                            <ul class="list-unstyled mt-3 mb-0">
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Traitement adapté à chaque matière</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Entretien spécifique du cuir et daim</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Nettoyage en profondeur des textiles</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Process Section -->
    <section class="py-5">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-lg-8 mx-auto">
                    <h2 class="section-title">Notre processus de pressing</h2>
                    <p class="section-description">Une méthode professionnelle en 4 étapes pour des résultats impeccables</p>
                </div>
            </div>
            
            <div class="row g-4">
                <div class="col-md-3">
                    <div class="process-step text-center">
                        <div class="process-number mb-3">1</div>
                        <div class="process-icon mb-3">
                            <i class="fas fa-search fa-2x text-primary"></i>
                        </div>
                        <h3 class="h5 mb-3">Inspection</h3>
                        <p>Examen minutieux de chaque vêtement pour identifier les taches, défauts et particularités à traiter avec soin.</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="process-step text-center">
                        <div class="process-number mb-3">2</div>
                        <div class="process-icon mb-3">
                            <i class="fas fa-vial fa-2x text-primary"></i>
                        </div>
                        <h3 class="h5 mb-3">Pré-traitement</h3>
                        <p>Application de solutions spécifiques sur les taches difficiles avant le processus de nettoyage principal.</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="process-step text-center">
                        <div class="process-number mb-3">3</div>
                        <div class="process-icon mb-3">
                            <i class="fas fa-temperature-low fa-2x text-primary"></i>
                        </div>
                        <h3 class="h5 mb-3">Nettoyage à sec</h3>
                        <p>Nettoyage avec des solvants spéciaux qui dissolvent efficacement les saletés sans endommager les fibres délicates.</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="process-step text-center">
                        <div class="process-number mb-3">4</div>
                        <div class="process-icon mb-3">
                            <i class="fas fa-iron fa-2x text-primary"></i>
                        </div>
                        <h3 class="h5 mb-3">Finition</h3>
                        <p>Repassage précis, mise en forme et conditionnement soigné pour une présentation impeccable de vos vêtements.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@section('styles')
<style>
    .text-primary {
        color: #461871 !important;
    }
    
    .service-card {
        transition: transform 0.3s ease;
        border-radius: 15px;
        overflow: hidden;
    }
    
    .service-card:hover {
        transform: translateY(-10px);
    }
    
    .icon-circle {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background-color: #f0f8ff;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #461871;
    }
    
    .process-step {
        padding: 2rem;
        border-radius: 15px;
        background-color: white;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        height: 100%;
        transition: transform 0.3s ease;
        position: relative;
    }
    
    .process-step:hover {
        transform: translateY(-10px);
    }
    
    .process-number {
        position: absolute;
        top: 15px;
        right: 15px;
        width: 30px;
        height: 30px;
        background-color: #461871;
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
    }
    
    .process-icon {
        color: #461871;
    }
    
    .testimonial-card {
        background: white;
        border-radius: 15px;
        padding: 30px;
        margin-bottom: 30px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        transition: transform 0.3s ease;
        height: 100%;
    }
    
    .testimonial-card:hover {
        transform: translateY(-10px);
    }
    
    .testimonial-rating {
        color: #ffc107;
    }
    
    .testimonial-text {
        color: #333;
        font-size: 16px;
        line-height: 1.6;
        margin-bottom: 20px;
        font-style: italic;
    }
    
    .author-info h4 {
        margin: 0;
        color: #461871;
        font-size: 18px;
        font-weight: 600;
    }
    
    .author-info span {
        color: #666;
        font-size: 14px;
    }
    
    .pricing-table {
        overflow: hidden;
    }
    
    .pricing-table th,
    .pricing-table td {
        padding: 15px;
    }
    
    .btn-primary-custom, .btn-secondary-custom {
        display: inline-block;
        text-decoration: none;
    }
</style>
@endsection 