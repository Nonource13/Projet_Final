@extends('front.layout')

@section('title', 'Modifier la liste - ' . $giftList->title . ' - Livres Gourmands')

@section('content')
<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('front.index') }}">Accueil</a></li>
            <li class="breadcrumb-item"><a href="{{ route('gift-lists.index') }}">Mes listes de cadeaux</a></li>
            <li class="breadcrumb-item"><a href="{{ route('gift-lists.show', $giftList->id) }}">{{ $giftList->title }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">Modifier</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-light">
                    <h2 class="mb-0 h5">Modifier la liste de cadeaux</h2>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('gift-lists.update', $giftList->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="title" class="form-label">Titre de la liste <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $giftList->title) }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $giftList->description) }}</textarea>
                            <small class="form-text text-muted">Une description qui aide vos proches à comprendre l'occasion.</small>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="occasion" class="form-label">Occasion</label>
                                <select class="form-select" id="occasion" name="occasion">
                                    <option value="">-- Sélectionner une occasion --</option>
                                    <option value="Anniversaire" {{ old('occasion', $giftList->occasion) == 'Anniversaire' ? 'selected' : '' }}>Anniversaire</option>
                                    <option value="Mariage" {{ old('occasion', $giftList->occasion) == 'Mariage' ? 'selected' : '' }}>Mariage</option>
                                    <option value="Noël" {{ old('occasion', $giftList->occasion) == 'Noël' ? 'selected' : '' }}>Noël</option>
                                    <option value="Naissance" {{ old('occasion', $giftList->occasion) == 'Naissance' ? 'selected' : '' }}>Naissance</option>
                                    <option value="Crémaillère" {{ old('occasion', $giftList->occasion) == 'Crémaillère' ? 'selected' : '' }}>Crémaillère</option>
                                    <option value="Autre" {{ old('occasion', $giftList->occasion) == 'Autre' ? 'selected' : '' }}>Autre</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="event_date" class="form-label">Date de l'événement</label>
                                <input type="date" class="form-control" id="event_date" name="event_date" value="{{ old('event_date', $giftList->event_date ? date('Y-m-d', strtotime($giftList->event_date)) : '') }}">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="expiry_date" class="form-label">Date d'expiration de la liste</label>
                            <input type="date" class="form-control" id="expiry_date" name="expiry_date" value="{{ old('expiry_date', $giftList->expiry_date ? date('Y-m-d', strtotime($giftList->expiry_date)) : '') }}">
                            <small class="form-text text-muted">Après cette date, la liste ne sera plus accessible. Laissez vide pour aucune expiration.</small>
                        </div>
                        
                        <div class="mb-4 form-check">
                            <input type="checkbox" class="form-check-input" id="is_public" name="is_public" value="1" {{ old('is_public', $giftList->is_public) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_public">Liste publique</label>
                            <div class="form-text">
                                <small>Une liste publique peut être consultée par toute personne possédant le lien ou le code d'accès.</small>
                                <small>Une liste privée n'est visible que par vous.</small>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('gift-lists.show', $giftList->id) }}" class="btn btn-outline-secondary">Annuler</a>
                            <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
