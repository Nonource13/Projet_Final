@extends('front.layout')

@section('title', $ouvrage->titre . ' - Livres Gourmands')

@section('content')
    <div class="container py-5">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('front.index') }}">Accueil</a></li>
                <li class="breadcrumb-item"><a href="{{ route('front.catalogue') }}">Catalogue</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $ouvrage->titre }}</li>
            </ol>
        </nav>

        <div class="row">
            <!-- Book Image -->
            <div class="col-md-4 mb-4">
                <div class="card">
                    @if($ouvrage->images->count() > 0)
                        <img src="{{ asset('images/livres/' . $ouvrage->images->first()->chemin_image) }}" class="card-img-top book-detail-img" alt="{{ $ouvrage->titre }}" style="height: 400px; object-fit: cover;">
                    @else
                        <img src="{{ asset('images/livres/default.jpg') }}" class="card-img-top book-detail-img" alt="{{ $ouvrage->titre }}" style="height: 400px; object-fit: cover;">
                    @endif
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            @if($ouvrage->stock && $ouvrage->stock->quantite > 0)
                                <form action="{{ route('cart.add') }}" method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $ouvrage->id }}">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-shopping-cart me-2"></i>Ajouter au panier
                                    </button>
                                </form>
                                <button class="btn btn-outline-primary">
                                    <i class="fas fa-heart me-2"></i>Ajouter à ma liste
                                </button>
                            @else
                                <button class="btn btn-secondary" disabled>
                                    <i class="fas fa-shopping-cart me-2"></i>Indisponible
                                </button>
                                <button class="btn btn-outline-primary">
                                    <i class="fas fa-bell me-2"></i>M'alerter de la disponibilité
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Book Details -->
            <div class="col-md-8">
                <h1 class="mb-2">{{ $ouvrage->titre }}</h1>
                <p class="fs-5 mb-3">par <strong>{{ $ouvrage->auteur }}</strong></p>
                
                <div class="d-flex align-items-center mb-3">
                    <span class="badge bg-secondary me-2">{{ $ouvrage->categorie->nom ?? 'Non classé' }}</span>
                    <span class="badge bg-primary">Niveau: {{ ucfirst($ouvrage->niveau_expertise ?? 'Tous niveaux') }}</span>
                    
                    @if($ouvrage->commentaires->count() > 0)
                        <div class="ms-3">
                            <div class="star-rating">
                                @php
                                    $avg_rating = $ouvrage->commentaires->avg('note');
                                    $full_stars = floor($avg_rating);
                                    $half_star = $avg_rating - $full_stars > 0.3 ? 1 : 0;
                                    $empty_stars = 5 - $full_stars - $half_star;
                                @endphp
                                
                                @for($i = 0; $i < $full_stars; $i++)
                                    <i class="fas fa-star"></i>
                                @endfor
                                
                                @if($half_star)
                                    <i class="fas fa-star-half-alt"></i>
                                @endif
                                
                                @for($i = 0; $i < $empty_stars; $i++)
                                    <i class="far fa-star"></i>
                                @endfor
                                
                                <span class="ms-1 text-muted">({{ $ouvrage->commentaires->count() }} avis)</span>
                            </div>
                        </div>
                    @endif
                </div>
                
                <div class="mb-3">
                    <h4 class="book-price mb-2">{{ number_format($ouvrage->prix, 2) }} €</h4>
                    <p class="mb-0">
                        <span class="text-{{ $ouvrage->stock && $ouvrage->stock->quantite > 0 ? 'success' : 'danger' }} fw-bold">
                            <i class="fas fa-circle me-1" style="font-size: 0.5rem;"></i>
                            {{ $ouvrage->stock && $ouvrage->stock->quantite > 0 ? 'En stock' : 'Épuisé' }}
                        </span>
                        @if($ouvrage->stock && $ouvrage->stock->quantite > 0)
                            <span class="text-muted ms-2">({{ $ouvrage->stock->quantite }} exemplaires disponibles)</span>
                        @endif
                    </p>
                </div>
                
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Description</h5>
                    </div>
                    <div class="card-body">
                        <p>{{ $ouvrage->description ?? 'Aucune description disponible pour cet ouvrage.' }}</p>
                    </div>
                </div>
                
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Détails</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Date de publication:</strong> {{ $ouvrage->date_publication ? date('d/m/Y', strtotime($ouvrage->date_publication)) : 'Non spécifiée' }}</p>
                                <p><strong>Catégorie:</strong> {{ $ouvrage->categorie->nom ?? 'Non classée' }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Auteur:</strong> {{ $ouvrage->auteur }}</p>
                                <p><strong>Niveau d'expertise:</strong> {{ ucfirst($ouvrage->niveau_expertise ?? 'Tous niveaux') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Reviews Section -->
        <div class="row mt-5">
            <div class="col-12">
                <h2 class="mb-4">Avis des clients ({{ $ouvrage->commentaires->where('valide', true)->count() }})</h2>
                
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                
                @forelse($ouvrage->commentaires->where('valide', true) as $commentaire)
                    <div class="review-card mb-4 p-3 border rounded">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5 class="mb-0">{{ $commentaire->auteur }}</h5>
                            <span class="text-muted">{{ $commentaire->created_at->format('d/m/Y') }}</span>
                        </div>
                        <div class="star-rating mb-2">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="{{ $i <= $commentaire->note ? 'fas' : 'far' }} fa-star"></i>
                            @endfor
                        </div>
                        <p>{{ $commentaire->contenu }}</p>
                    </div>
                @empty
                    <div class="alert alert-info">
                        Aucun avis client validé pour le moment. Soyez le premier à évaluer ce livre !
                    </div>
                @endforelse
                
                <div class="card mt-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Laisser un avis</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('commentaires.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="ouvrage_id" value="{{ $ouvrage->id }}">
                            
                            <div class="mb-3">
                                <label for="auteur" class="form-label">Votre nom</label>
                                <input type="text" class="form-control" id="auteur" name="auteur" required>
                                @error('auteur')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Note</label>
                                <div class="star-rating-input mb-2">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="note" id="note1" value="1" required>
                                        <label class="form-check-label" for="note1">1 <i class="fas fa-star"></i></label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="note" id="note2" value="2">
                                        <label class="form-check-label" for="note2">2 <i class="fas fa-star"></i></label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="note" id="note3" value="3">
                                        <label class="form-check-label" for="note3">3 <i class="fas fa-star"></i></label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="note" id="note4" value="4">
                                        <label class="form-check-label" for="note4">4 <i class="fas fa-star"></i></label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="note" id="note5" value="5">
                                        <label class="form-check-label" for="note5">5 <i class="fas fa-star"></i></label>
                                    </div>
                                </div>
                                @error('note')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="contenu" class="form-label">Votre avis</label>
                                <textarea class="form-control" id="contenu" name="contenu" rows="4" required></textarea>
                                @error('contenu')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="alert alert-info">
                                <small>Votre avis sera publié après validation par notre équipe éditoriale.</small>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">Soumettre mon avis</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Similar Books Section -->
        <div class="row mt-5">
            <div class="col-12">
                <h2 class="mb-4">Livres similaires</h2>
                
                <div class="row">
                    @forelse($similaires as $livre)
                        <div class="col-md-6 col-lg-3 mb-4">
                            <div class="card book-card h-100">
                                <img src="{{ asset('images/livres/default.jpg') }}" class="card-img-top" alt="{{ $livre->titre }}">
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
                        <div class="col-12">
                            <div class="alert alert-info">
                                Aucun livre similaire trouvé.
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
