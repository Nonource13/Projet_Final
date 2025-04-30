@extends('front.layout')

@section('title', 'À propos - Livres Gourmands')

@section('content')
    <div class="container py-5">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('front.index') }}">Accueil</a></li>
                <li class="breadcrumb-item active" aria-current="page">À propos</li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-lg-8 mx-auto">
                <h1 class="text-center mb-4">À propos de Livres Gourmands</h1>
                
                <div class="card mb-5">
                    <div class="card-body">
                        <h2 class="h4 mb-3">Notre histoire</h2>
                        <p>Fondée en 2015 par une équipe de passionnés de gastronomie et de littérature, Livres Gourmands est devenue une référence dans la vente de livres de cuisine en ligne.</p>
                        <p>Notre ambition est de rendre la cuisine accessible à tous, du débutant enthousiaste au chef expérimenté, en proposant une sélection pointue d'ouvrages culinaires issus du monde entier.</p>
                    </div>
                </div>
                
                <div class="card mb-5">
                    <div class="card-body">
                        <h2 class="h4 mb-3">Notre mission</h2>
                        <p>Chez Livres Gourmands, nous croyons que la cuisine est un art qui se partage. Notre mission est triple :</p>
                        <ul>
                            <li>Sélectionner avec soin les meilleurs livres de cuisine pour tous les niveaux d'expertise</li>
                            <li>Rendre accessibles ces connaissances culinaires au plus grand nombre</li>
                            <li>Créer une communauté de partage autour de la passion de la cuisine</li>
                        </ul>
                    </div>
                </div>
                
                <div class="card mb-5">
                    <div class="card-body">
                        <h2 class="h4 mb-3">Notre équipe</h2>
                        <p>Notre équipe est composée de passionnés de cuisine, de littérature et de web. Chacun apporte son expertise pour vous offrir la meilleure expérience possible :</p>
                        
                        <div class="row mt-4">
                            <div class="col-md-4 text-center mb-4">
                                <div class="rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 120px; height: 120px; background-color: var(--primary-color); color: white;">
                                    <i class="fas fa-user fa-3x"></i>
                                </div>
                                <h5>Sophie Martin</h5>
                                <p class="text-muted">Fondatrice & Chef</p>
                            </div>
                            <div class="col-md-4 text-center mb-4">
                                <div class="rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 120px; height: 120px; background-color: var(--primary-color); color: white;">
                                    <i class="fas fa-user fa-3x"></i>
                                </div>
                                <h5>Thomas Dubois</h5>
                                <p class="text-muted">Directeur Éditorial</p>
                            </div>
                            <div class="col-md-4 text-center mb-4">
                                <div class="rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 120px; height: 120px; background-color: var(--primary-color); color: white;">
                                    <i class="fas fa-user fa-3x"></i>
                                </div>
                                <h5>Julie Leroy</h5>
                                <p class="text-muted">Responsable Web</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-body">
                        <h2 class="h4 mb-3">Nos valeurs</h2>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="d-flex">
                                    <div class="me-3">
                                        <i class="fas fa-check-circle fa-2x" style="color: var(--primary-color);"></i>
                                    </div>
                                    <div>
                                        <h5>Qualité</h5>
                                        <p>Nous sélectionnons rigoureusement chaque ouvrage pour sa qualité éditoriale et pratique.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex">
                                    <div class="me-3">
                                        <i class="fas fa-users fa-2x" style="color: var(--primary-color);"></i>
                                    </div>
                                    <div>
                                        <h5>Accessibilité</h5>
                                        <p>Nous croyons que l'art culinaire doit être accessible à tous, quel que soit votre niveau.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex">
                                    <div class="me-3">
                                        <i class="fas fa-leaf fa-2x" style="color: var(--primary-color);"></i>
                                    </div>
                                    <div>
                                        <h5>Durabilité</h5>
                                        <p>Nous nous engageons à réduire notre empreinte environnementale à chaque étape.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex">
                                    <div class="me-3">
                                        <i class="fas fa-heart fa-2x" style="color: var(--primary-color);"></i>
                                    </div>
                                    <div>
                                        <h5>Passion</h5>
                                        <p>Nous partageons la passion de la cuisine et de la transmission du savoir culinaire.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
