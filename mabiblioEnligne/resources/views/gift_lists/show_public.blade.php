@extends('front.layout')

@section('title', $giftList->title . ' - Liste de cadeaux - Livres Gourmands')

@section('content')
<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('front.index') }}">Accueil</a></li>
            <li class="breadcrumb-item active" aria-current="page">Liste de cadeaux</li>
        </ol>
    </nav>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card mb-4">
                <div class="card-header bg-light text-center">
                    <h1 class="h3 mb-0">{{ $giftList->title }}</h1>
                </div>
                <div class="card-body">
                    @if($giftList->description)
                        <p class="lead text-center mb-4">{{ $giftList->description }}</p>
                    @endif
                    
                    <div class="row mb-4">
                        <div class="col-md-6 text-center text-md-start">
                            <p class="mb-1"><strong>Occasion :</strong> {{ $giftList->occasion ?? 'Non spécifiée' }}</p>
                            @if($giftList->event_date)
                                <p class="mb-1"><strong>Date de l'événement :</strong> {{ date('d/m/Y', strtotime($giftList->event_date)) }}</p>
                            @endif
                        </div>
                        <div class="col-md-6 text-center text-md-end">
                            <p class="mb-1"><strong>Liste créée par :</strong> {{ $giftList->user->name }}</p>
                            <p class="mb-1"><strong>Créée le :</strong> {{ date('d/m/Y', strtotime($giftList->created_at)) }}</p>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <h5 class="text-center mb-4">Ouvrages souhaités</h5>
                    
                    @if($giftList->ouvrages->count() > 0)
                        <div class="row">
                            @foreach($giftList->ouvrages as $ouvrage)
                                <div class="col-md-6 mb-4">
                                    <div class="card h-100 {{ $ouvrage->pivot->is_reserved ? 'border-success' : '' }}">
                                        @if($ouvrage->pivot->is_reserved)
                                            <div class="card-header bg-success text-white">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span>Réservé</span>
                                                    <span>par {{ $ouvrage->pivot->reserved_by }}</span>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-4 mb-3 mb-md-0">
                                                    <img src="{{ asset('images/livres/default.jpg') }}" alt="{{ $ouvrage->titre }}" class="img-fluid rounded">
                                                </div>
                                                <div class="col-md-8">
                                                    <h5 class="card-title">{{ $ouvrage->titre }}</h5>
                                                    <p class="text-muted mb-2">{{ $ouvrage->auteur }}</p>
                                                    <p class="book-price mb-2">{{ number_format($ouvrage->prix, 2) }} €</p>
                                                    <div class="mb-2">
                                                        <span class="badge bg-secondary">{{ $ouvrage->categorie->nom ?? 'Non classé' }}</span>
                                                        <span class="badge bg-info">{{ ucfirst($ouvrage->niveau_expertise ?? 'Tous niveaux') }}</span>
                                                    </div>
                                                    <div class="mb-3">
                                                        <strong>Priorité : </strong>
                                                        @for($i = 1; $i <= $ouvrage->pivot->priority; $i++)
                                                            <i class="fas fa-star text-warning"></i>
                                                        @endfor
                                                        @for($i = $ouvrage->pivot->priority + 1; $i <= 5; $i++)
                                                            <i class="far fa-star text-muted"></i>
                                                        @endfor
                                                    </div>
                                                    <div>
                                                        <strong>Quantité : </strong>{{ $ouvrage->pivot->quantity }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer">
                                            <div class="d-flex justify-content-between">
                                                <a href="{{ route('front.detail', $ouvrage->id) }}" class="btn btn-outline-primary">
                                                    <i class="fas fa-eye me-1"></i>Détails
                                                </a>
                                                
                                                @if(!$ouvrage->pivot->is_reserved)
                                                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#reserveModal-{{ $ouvrage->id }}">
                                                        <i class="fas fa-gift me-1"></i>Réserver
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Modal de réservation -->
                                @if(!$ouvrage->pivot->is_reserved)
                                    <div class="modal fade" id="reserveModal-{{ $ouvrage->id }}" tabindex="-1" aria-labelledby="reserveModalLabel-{{ $ouvrage->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="reserveModalLabel-{{ $ouvrage->id }}">Réserver "{{ $ouvrage->titre }}"</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('gift-lists.reserve-item', ['code' => $giftList->access_code, 'ouvrageId' => $ouvrage->id]) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <p>En réservant cet ouvrage, vous indiquez aux autres personnes que vous prévoyez de l'offrir.</p>
                                                        <div class="mb-3">
                                                            <label for="reserved_by" class="form-label">Votre nom <span class="text-danger">*</span></label>
                                                            <input type="text" class="form-control" id="reserved_by" name="reserved_by" required>
                                                            <div class="form-text">Ce nom sera affiché à côté de l'article réservé.</div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                        <button type="submit" class="btn btn-success">Confirmer la réservation</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle me-2"></i>Cette liste de cadeaux est vide pour le moment.
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Commentaires et partage -->
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Partager cette liste</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <div class="input-group">
                                <span class="input-group-text">Code</span>
                                <input type="text" class="form-control" value="{{ $giftList->access_code }}" readonly id="access_code">
                                <button class="btn btn-outline-secondary" type="button" onclick="copyToClipboard('access_code')">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text">URL</span>
                                <input type="text" class="form-control" value="{{ route('gift-lists.public', $giftList->access_code) }}" readonly id="share_url">
                                <button class="btn btn-outline-secondary" type="button" onclick="copyToClipboard('share_url')">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <p>Partagez cette liste via</p>
                            <div class="btn-group">
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('gift-lists.public', $giftList->access_code)) }}" target="_blank" class="btn btn-outline-primary">
                                    <i class="fab fa-facebook-f me-1"></i>Facebook
                                </a>
                                <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('gift-lists.public', $giftList->access_code)) }}&text=Liste%20de%20cadeaux%20de%20{{ urlencode($giftList->user->name) }}%20:%20{{ urlencode($giftList->title) }}" target="_blank" class="btn btn-outline-primary">
                                    <i class="fab fa-twitter me-1"></i>Twitter
                                </a>
                                <a href="mailto:?subject=Liste%20de%20cadeaux%20de%20{{ urlencode($giftList->user->name) }}&body=Voici%20ma%20liste%20de%20cadeaux%20:%20{{ urlencode(route('gift-lists.public', $giftList->access_code)) }}" class="btn btn-outline-primary">
                                    <i class="fas fa-envelope me-1"></i>Email
                                </a>
                                <a href="https://api.whatsapp.com/send?text=Liste%20de%20cadeaux%20de%20{{ urlencode($giftList->user->name) }}%20:%20{{ urlencode($giftList->title) }}%20{{ urlencode(route('gift-lists.public', $giftList->access_code)) }}" target="_blank" class="btn btn-outline-primary">
                                    <i class="fab fa-whatsapp me-1"></i>WhatsApp
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    function copyToClipboard(elementId) {
        const element = document.getElementById(elementId);
        element.select();
        document.execCommand('copy');
        alert('Copié dans le presse-papiers !');
    }
</script>
@endsection
@endsection
