@extends('layouts.app')

@section('title', 'Lavage Éco-Responsable Premium | KLIN KLIN')

@section('content')
    <div class="hero-section py-5 text-center bg-light">
        <div class="container">
            <h1 class="mb-4 text-primary fw-bold">Lavage Éco-Responsable Premium</h1>
            <p class="lead text-muted mx-auto mb-5" style="max-width: 700px;">Un lavage expert et écologique pour votre linge.</p>
            <div class="d-flex justify-content-center gap-3">
                <a href="#process" class="btn btn-primary rounded-pill px-4 py-2">Notre processus</a>
                <a href="#expertise" class="btn btn-outline-primary rounded-pill px-4 py-2">Notre expertise textile</a>
            </div>
        </div>
    </div>

    <section id="expertise" class="py-5 bg-white">
        <div class="container">
            <h2 class="mb-3 text-primary fw-bold text-center">Notre Expertise par Matière</h2>
            <p class="lead text-muted text-center">Des soins spécifiques pour chaque type de tissu.</p>
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
                <div class="col">
                    <div class="p-4 text-center shadow-sm rounded-4 border-0">
                        <div class="mb-3 rounded-circle p-3 mx-auto bg-primary-light text-primary" style="width: 70px; height: 70px; display: flex; align-items: center; justify-content: center;"><i class="fas fa-seedling fa-2x"></i></div>
                        <h3 class="h5 mb-2">Coton & Lin</h3>
                        <p class="text-muted small">Fibres naturelles : lavage doux.</p>
                    </div>
                </div>
                <div class="col">
                    <div class="p-4 text-center shadow-sm rounded-4 border-0">
                        <div class="mb-3 rounded-circle p-3 mx-auto bg-info-light text-info" style="width: 70px; height: 70px; display: flex; align-items: center; justify-content: center;"><i class="fas fa-atom fa-2x"></i></div>
                        <h3 class="h5 mb-2">Synthétiques</h3>
                        <p class="text-muted small">Polyester, nylon : soin spécifique.</p>
                    </div>
                </div>
                <div class="col">
                    <div class="p-4 text-center shadow-sm rounded-4 border-0">
                        <div class="mb-3 rounded-circle p-3 mx-auto bg-warning-light text-warning" style="width: 70px; height: 70px; display: flex; align-items: center; justify-content: center;"><i class="fas fa-feather fa-2x"></i></div>
                        <h3 class="h5 mb-2">Laine & Soie</h3>
                        <p class="text-muted small">Fibres délicates : traitement expert.</p>
                    </div>
                </div>
                <div class="col">
                    <div class="p-4 text-center shadow-sm rounded-4 border-0">
                        <div class="mb-3 rounded-circle p-3 mx-auto bg-secondary-light text-secondary" style="width: 70px; height: 70px; display: flex; align-items: center; justify-content: center;"><i class="fas fa-user-tie fa-2x"></i></div>
                        <h3 class="h5 mb-2">Techniques & Délicats</h3>
                        <p class="text-muted small">Vêtements spéciaux : protection maximale.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="process" class="py-5 bg-light">
        <div class="container">
            <h2 class="mb-3 text-primary fw-bold text-center">Notre Processus Éco-Responsable</h2>
            <p class="lead text-muted text-center">Un système de lavage efficace et durable.</p>
            <div class="row g-4">
                <div class="col-md-3">
                    <div class="p-4 text-center bg-white rounded-4 shadow-sm h-100">
                        <div class="mb-3 text-primary"><i class="fas fa-tags fa-3x"></i></div>
                        <h3 class="h6 fw-bold mb-2">1. Analyse & Tri</h3>
                        <p class="text-muted small">Tri par type et couleur.</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="p-4 text-center bg-white rounded-4 shadow-sm h-100">
                        <div class="mb-3 text-success"><i class="fas fa-flask fa-3x"></i></div>
                        <h3 class="h6 fw-bold mb-2">2. Pré-Traitement Éco</h3>
                        <p class="text-muted small">Solutions biodégradables.</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="p-4 text-center bg-white rounded-4 shadow-sm h-100">
                        <div class="mb-3 text-info"><i class="fas fa-water fa-3x"></i></div>
                        <h3 class="h6 fw-bold mb-2">3. Lavage Basse Température</h3>
                        <p class="text-muted small">Économie d'énergie.</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="p-4 text-center bg-white rounded-4 shadow-sm h-100">
                        <div class="mb-3 text-warning"><i class="fas fa-wind fa-3x"></i></div>
                        <h3 class="h6 fw-bold mb-2">4. Séchage Doux</h3>
                        <p class="text-muted small">Préservation de la qualité.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-6 px-4" style="background-color: #f9fbe7;">
        <div class="container">
            <br><br><br>
            <div class="row mb-5 text-center">
                <div class="col-lg-8 mx-auto">
                    <h2 class="display-5 fw-bold mb-3 text-success">Votre Impact Positif</h2>
                    <p class="lead text-muted text-center">Contribuez à un avenir plus propre.</p>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="p-4 text-center bg-white rounded-4 shadow-sm border border-light">
                        <div class="mb-4 text-primary rounded-circle mx-auto" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; background-color: rgba(0, 0, 0, 0.05);"><i class="fas fa-droplet fa-2x"></i></div>
                        <h3 class="h5 mb-3 text-success">Économie d'Eau</h3>
                        <p class="mb-4 small text-muted">Moins d'eau utilisée vs lavage standard.</p>
                        <div class="small text-success fw-bold">-65%</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-4 text-center bg-white rounded-4 shadow-sm border border-light">
                        <div class="mb-4 text-warning rounded-circle mx-auto" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; background-color: rgba(0, 0, 0, 0.05);"><i class="fas fa-bolt fa-2x"></i></div>
                        <h3 class="h5 mb-3 text-success">Économie d'Énergie</h3>
                        <p class="mb-4 small text-muted">Consommation réduite par cycle.</p>
                        <div class="small text-warning fw-bold">-40%</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-4 text-center bg-white rounded-4 shadow-sm border border-light">
                        <div class="mb-4 text-success rounded-circle mx-auto" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; background-color: rgba(0, 0, 0, 0.05);"><i class="fas fa-leaf fa-2x"></i></div>
                        <h3 class="h5 mb-3 text-success">Biodégradable</h3>
                        <p class="mb-4 small text-muted">Produits respectueux de l'environnement.</p>
                        <div class="small text-success fw-bold">100%</div>
                    </div>
                </div>
            </div>
        </div>
        <br><br><br>
    </section>
@endsection

@section('styles')
<style>
    .hero-section {
        background-image: url('{{ asset('img/eco-washing-hero-small.jpg') }}');
        background-size: cover;
        background-position: center;
    }
    @media (min-width: 992px) {
        .hero-section {
            background-image: url('{{ asset('img/eco-washing-hero.jpg') }}');
        }
    }
    .bg-primary-light { background-color: #e0f2f7; }
    .text-primary { color: #1e88e5; }
    .bg-info-light { background-color: #e0f7fa; }
    .text-info { color: #00bcd4; }
    .bg-warning-light { background-color: #fffde7; }
    .text-warning { color: #ffc107; }
    .bg-secondary-light { background-color: #f5f5f5; }
    .text-secondary { color: #6c757d; }
</style>
@endsection

@section('scripts')
<script>
    // Aucun code JavaScript spécifique à cette page pour le moment.
</script>
@endsection