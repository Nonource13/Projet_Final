@extends('front.layout')

@section('title', 'Résultats de recherche - Livres Gourmands')

@section('content')
    <div class="container py-5">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('front.index') }}">Accueil</a></li>
                <li class="breadcrumb-item active" aria-current="page">Résultats de recherche</li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-12">
                <h1 class="mb-4">Résultats de recherche pour "{{ $search }}"</h1>
                
                <div class="mb-4">
                    <form action="{{ route('front.recherche') }}" method="GET" class="d-flex">
                        <input type="text" name="q" class="form-control me-2" value="{{ $search }}" placeholder="Rechercher un livre...">
                        <button type="submit" class="btn btn-primary">Rechercher</button>
                    </form>
                </div>
                
                <div class="alert alert-info">
                    {{ $ouvrages->total() }} résultat(s) trouvé(s)
                </div>
                
                <div class="row">
                    @forelse($ouvrages as $livre)
                        <div class="col-md-6 col-lg-3 mb-4">
                            <div class="card book-card h-100">
                                {{-- DEBUG : Affichage temporaire des infos image et stock --}}
                                <div style="font-size:12px;color:#c00;background:#ffe;">Image: {{ $livre->image ?? 'Aucune image' }} | Stock: {{ $livre->stock ? $livre->stock->quantite : 'Aucun stock' }}</div>
                                <img src="{{ asset('images/livres/' . ($livre->image ?? 'default.jpg')) }}" class="card-img-top" alt="{{ $livre->titre }}">
                                <div class="card-body d-flex flex-column">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="book-category">{{ $livre->categorie->nom ?? 'Non classé' }}</span>
                                        <span class="book-expertise">{{ ucfirst($livre->niveau_expertise ?? 'Tous niveaux') }}</span>
                                    </div>
                                    <h5 class="card-title">{{ Str::limit($livre->titre, 40) }}</h5>
                                    <p class="card-text text-muted">{{ $livre->auteur }}</p>
                                    <div class="d-flex justify-content-between mt-auto">
                                        <span class="book-price">{{ number_format($livre->prix, 2) }} €</span>
                                        <span class="text-{{ ($livre->stock && $livre->stock->quantite > 0) ? 'success' : 'danger' }}">
                                            <i class="fas fa-circle" style="font-size: 0.5rem;"></i>
                                            {{ ($livre->stock && $livre->stock->quantite > 0) ? 'En stock' : 'Épuisé' }}
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
                            <div class="alert alert-warning">
                                Aucun livre ne correspond à votre recherche "{{ $search }}".
                                <a href="{{ route('front.catalogue') }}" class="alert-link">Voir tout le catalogue</a>
                            </div>
                        </div>
                    @endforelse
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $ouvrages->appends(['q' => $search])->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
