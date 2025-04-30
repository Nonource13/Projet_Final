@extends('front.layout')

@section('title', 'Livres Gourmands - Votre librairie culinaire en ligne')

@section('content')
    <!-- Hero Section -->
    <section class="hero-section text-center">
        <div class="container">
            <h1 class="display-4 fw-bold mb-4">Découvrez l'art de la cuisine</h1>
            <p class="lead mb-5">Une sélection des meilleurs livres de cuisine pour tous les niveaux d'expertise.</p>
            <a href="{{ route('front.catalogue') }}" class="btn btn-light btn-lg">Explorer le catalogue</a>
        </div>
    </section>

    <!-- Nouveautés Section -->
    <section class="container mb-5">
        <div class="row">
            <div class="col-12">
                <h2 class="text-center mb-4">Nos dernières nouveautés</h2>
                <div class="row">
                    @forelse($nouveautes as $livre)
                        <div class="col-md-4 col-lg-2">
                            <div class="card book-card h-100">
                                <div class="position-relative">
                                    @if($livre->images->count() > 0)
                                        @php
                                            $imageFile = $livre->images->first()->chemin_image;
                                            $imageExists = file_exists(public_path('images/livres/' . $imageFile));
                                        @endphp
                                        <img src="{{ asset('images/livres/' . $imageFile) }}" class="card-img-top" alt="{{ $livre->titre }}" style="height: 250px; object-fit: cover;">
                                    @else
                                        <img src="{{ asset('images/livres/default.jpg') }}" class="card-img-top" alt="{{ $livre->titre }}" style="height: 250px; object-fit: cover;">
                                    @endif
                                    <span class="position-absolute top-0 end-0 badge bg-danger m-2">Nouveau</span>
                                </div>
                                <div class="card-body d-flex flex-column">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="book-category">{{ $livre->categorie->nom ?? 'Non classé' }}</span>
                                        <span class="book-expertise">{{ ucfirst($livre->niveau_expertise ?? 'Tous niveaux') }}</span>
                                    </div>
                                    <h5 class="card-title">{{ Str::limit($livre->titre, 40) }}</h5>
                                    <p class="card-text text-muted">{{ $livre->auteur }}</p>
                                    <div class="d-flex justify-content-between mt-auto">
                                        <span class="book-price">{{ number_format($livre->prix, 2) }} €</span>
                                        <span class="text-{{ $livre->stock && $livre->stock->quantite > 0 ? 'success' : 'danger' }}">
                                            <i class="fas fa-circle" style="font-size: 0.5rem;"></i>
                                            {{ $livre->stock && $livre->stock->quantite > 0 ? 'En stock' : 'Épuisé' }}
                                        </span>
                                    </div>
                                    <a href="{{ route('front.detail', $livre->id) }}" class="btn btn-outline-primary mt-3">Voir détails</a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center">
                            <p>Aucune nouveauté pour le moment.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="container mb-5">
        <div class="row">
            <div class="col-12">
                <h2 class="text-center mb-4">Explorez nos catégories</h2>
                <div class="row">
                    @foreach($categories as $categorie)
                        <div class="col-md-4 mb-4">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <h3 class="card-title">{{ $categorie->nom }}</h3>
                                    <p class="card-text">{{ $categorie->ouvrages_count }} ouvrages disponibles</p>
                                    <a href="{{ route('front.catalogue', ['categorie_id' => $categorie->id]) }}" class="btn btn-outline-primary">Découvrir</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="container mb-5">
        <div class="row">
            <div class="col-12">
                <h2 class="text-center mb-4">Pourquoi choisir Livres Gourmands ?</h2>
                <div class="row text-center">
                    <div class="col-md-4 mb-4">
                        <div class="p-4">
                            <i class="fas fa-book fa-3x mb-3" style="color: var(--primary-color);"></i>
                            <h4>Sélection de qualité</h4>
                            <p>Des livres soigneusement sélectionnés par nos experts culinaires pour tous les niveaux.</p>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="p-4">
                            <i class="fas fa-truck fa-3x mb-3" style="color: var(--primary-color);"></i>
                            <h4>Livraison rapide</h4>
                            <p>Livraison gratuite en France métropolitaine pour toute commande supérieure à 35€.</p>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="p-4">
                            <i class="fas fa-gift fa-3x mb-3" style="color: var(--primary-color);"></i>
                            <h4>Listes cadeaux</h4>
                            <p>Créez votre liste de cadeaux et partagez-la facilement avec vos proches.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
