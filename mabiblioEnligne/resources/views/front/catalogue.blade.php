@extends('front.layout')

@section('title', 'Catalogue - Livres Gourmands')

@section('content')
    <div class="container py-5">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('front.index') }}">Accueil</a></li>
                <li class="breadcrumb-item active" aria-current="page">Catalogue</li>
            </ol>
        </nav>

        <div class="row">
            <!-- Filters Sidebar -->
            <div class="col-lg-3 mb-4">
                <div class="card">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Filtres</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('front.catalogue') }}" method="GET">
                            <!-- Search -->
                            <div class="mb-3">
                                <label for="search" class="form-label">Recherche</label>
                                <input type="text" class="form-control" id="search" name="search" value="{{ request('search') }}" placeholder="Titre, auteur...">
                            </div>
                            
                            <!-- Categories Filter -->
                            <div class="mb-3">
                                <label for="categorie_id" class="form-label">Catégorie</label>
                                <select class="form-select" id="categorie_id" name="categorie_id">
                                    <option value="">Toutes les catégories</option>
                                    @foreach($categories as $categorie)
                                        <option value="{{ $categorie->id }}" {{ request('categorie_id') == $categorie->id ? 'selected' : '' }}>
                                            {{ $categorie->nom }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <!-- Expertise Level Filter -->
                            <div class="mb-3">
                                <label for="niveau_expertise" class="form-label">Niveau d'expertise</label>
                                <select class="form-select" id="niveau_expertise" name="niveau_expertise">
                                    <option value="">Tous les niveaux</option>
                                    <option value="débutant" {{ request('niveau_expertise') == 'débutant' ? 'selected' : '' }}>Débutant</option>
                                    <option value="amateur" {{ request('niveau_expertise') == 'amateur' ? 'selected' : '' }}>Amateur</option>
                                    <option value="chef" {{ request('niveau_expertise') == 'chef' ? 'selected' : '' }}>Chef</option>
                                </select>
                            </div>
                            
                            <!-- Sort By -->
                            <div class="mb-3">
                                <label for="sort" class="form-label">Trier par</label>
                                <select class="form-select" id="sort" name="sort">
                                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Plus récents</option>
                                    <option value="prix_asc" {{ request('sort') == 'prix_asc' ? 'selected' : '' }}>Prix croissant</option>
                                    <option value="prix_desc" {{ request('sort') == 'prix_desc' ? 'selected' : '' }}>Prix décroissant</option>
                                    <option value="titre" {{ request('sort') == 'titre' ? 'selected' : '' }}>Titre</option>
                                </select>
                            </div>
                            
                            <button type="submit" class="btn btn-primary w-100">Appliquer les filtres</button>
                            
                            @if(request()->anyFilled(['search', 'categorie_id', 'niveau_expertise', 'sort']))
                                <a href="{{ route('front.catalogue') }}" class="btn btn-outline-secondary w-100 mt-2">Réinitialiser</a>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Books Grid -->
            <div class="col-lg-9">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1>Catalogue</h1>
                    <p class="mb-0">{{ $ouvrages->total() }} résultat(s)</p>
                </div>
                
                <div class="row">
                    @forelse($ouvrages as $livre)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card book-card h-100">
                                @if($livre->images->count() > 0)
                                    <img src="{{ asset('images/livres/' . $livre->images->first()->chemin_image) }}" class="card-img-top" alt="{{ $livre->titre }}" style="height: 250px; object-fit: cover;">
                                @else
                                    <img src="{{ asset('images/livres/default.jpg') }}" class="card-img-top" alt="{{ $livre->titre }}" style="height: 250px; object-fit: cover;">
                                @endif
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
                                    <div class="d-flex mt-3">
                                        <a href="{{ route('front.detail', $livre->id) }}" class="btn btn-outline-primary flex-grow-1 me-2">Détails</a>
                                        <button class="btn btn-primary"><i class="fas fa-shopping-cart"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="alert alert-info">
                                Aucun livre ne correspond à vos critères de recherche.
                            </div>
                        </div>
                    @endforelse
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $ouvrages->appends(request()->except('page'))->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
