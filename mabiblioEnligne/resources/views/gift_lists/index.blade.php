@extends('front.layout')

@section('title', 'Mes listes de cadeaux - Livres Gourmands')

@section('content')
<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('front.index') }}">Accueil</a></li>
            <li class="breadcrumb-item active" aria-current="page">Mes listes de cadeaux</li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Mes listes de cadeaux</h1>
        <a href="{{ route('gift-lists.create') }}" class="btn btn-primary">
            <i class="fas fa-plus-circle me-2"></i>Créer une nouvelle liste
        </a>
    </div>

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

    @if(count($giftLists) > 0)
        <div class="row">
            @foreach($giftLists as $list)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">{{ $list->title }}</h5>
                            <span class="badge {{ $list->is_public ? 'bg-success' : 'bg-secondary' }}">
                                {{ $list->is_public ? 'Publique' : 'Privée' }}
                            </span>
                        </div>
                        <div class="card-body">
                            @if($list->description)
                                <p>{{ Str::limit($list->description, 100) }}</p>
                            @endif
                            
                            <div class="mb-3">
                                <small class="text-muted">Occasion : {{ $list->occasion ?? 'Non spécifiée' }}</small>
                            </div>
                            
                            @if($list->event_date)
                                <div class="mb-3">
                                    <small class="text-muted">Date de l'événement : {{ date('d/m/Y', strtotime($list->event_date)) }}</small>
                                </div>
                            @endif
                            
                            <div class="d-flex align-items-center mb-3">
                                <span class="me-2">Code d'accès :</span>
                                <span class="badge bg-primary me-2">{{ $list->access_code }}</span>
                                <button class="btn btn-sm btn-outline-secondary" onclick="copyToClipboard('{{ $list->access_code }}')">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                            
                            <div class="d-flex align-items-center">
                                <span class="me-2">Lien de partage :</span>
                                <button class="btn btn-sm btn-outline-secondary" onclick="copyToClipboard('{{ route('gift-lists.public', $list->access_code) }}')">
                                    <i class="fas fa-copy"></i> Copier le lien
                                </button>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('gift-lists.show', $list->id) }}" class="btn btn-outline-primary">
                                    <i class="fas fa-eye me-1"></i>Voir
                                </a>
                                <div>
                                    <a href="{{ route('gift-lists.edit', $list->id) }}" class="btn btn-outline-secondary me-2">
                                        <i class="fas fa-edit me-1"></i>Modifier
                                    </a>
                                    <form action="{{ route('gift-lists.destroy', $list->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette liste ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger">
                                            <i class="fas fa-trash me-1"></i>Supprimer
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="card p-5 text-center">
            <div class="mb-4">
                <i class="fas fa-gift fa-4x text-muted"></i>
            </div>
            <h3>Vous n'avez pas encore de liste de cadeaux</h3>
            <p class="text-muted mb-4">Créez votre première liste de cadeaux et partagez-la avec vos proches.</p>
            <a href="{{ route('gift-lists.create') }}" class="btn btn-primary">Créer ma première liste</a>
        </div>
    @endif
</div>

@section('scripts')
<script>
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(function() {
            alert('Copié dans le presse-papiers !');
        }, function() {
            alert('Échec de la copie.');
        });
    }
</script>
@endsection
@endsection
