@extends('layouts.app')

@section('title', 'Simulateur de Prix')

@section('content')
  

    <!-- Simulator Section -->
    <section class="simulator-section py-5">
        <div class="container">
            <h2 class="simulator-title">Estimez le coût de votre linge</h2>
            <p class="simulator-description">
                Sélectionnez vos articles et obtenez une estimation précise basée sur le poids.
                Tarif unique de 500 FCFA par kilogramme.
            </p>
            @include('partials.simulator')
            
        </div>
    </section>
    

@endsection