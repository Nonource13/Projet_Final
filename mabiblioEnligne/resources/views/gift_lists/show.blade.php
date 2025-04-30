@extends('front.layout')

@section('title', $giftList->title . ' - Liste de cadeaux - Livres Gourmands')

@section('content')
<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('front.index') }}">Accueil</a></li>
            <li class="breadcrumb-item"><a href="{{ route('gift-lists.index') }}">Mes listes de cadeaux</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $giftList->title }}</li>
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

    <div class="row mb-4">
        <div class="col-md-8">
            <h1>{{ $giftList->title }}</h1>
            @if($giftList->description)
                <p class="lead">{{ $giftList->description }}</p>
            @endif
        </div>
        <div class="col-md-4 text-md-end">
            <a href="{{ route('gift-lists.edit', $giftList->id) }}" class="btn btn-outline-primary me-2">
                <i class="fas fa-edit me-1"></i>Modifier
            </a>
            <form action="{{ route('gift-lists.destroy', $giftList->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette liste ?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger">
                    <i class="fas fa-trash me-1"></i>Supprimer
                </button>
            </form>
        </div>
    </div>

    <div class="row">
        <!-- Informations sur la liste -->
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Informations</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <p class="mb-1"><strong>Occasion :</strong> {{ $giftList->occasion ?? 'Non spécifiée' }}</p>
                        @if($giftList->event_date)
                            <p class="mb-1"><strong>Date de l'événement :</strong> {{ date('d/m/Y', strtotime($giftList->event_date)) }}</p>
                        @endif
                        <p class="mb-1"><strong>Statut :</strong> <span class="badge {{ $giftList->is_public ? 'bg-success' : 'bg-secondary' }}">{{ $giftList->is_public ? 'Publique' : 'Privée' }}</span></p>
                        @if($giftList->expiry_date)
                            <p class="mb-1"><strong>Expire le :</strong> {{ date('d/m/Y', strtotime($giftList->expiry_date)) }}</p>
                        @endif
                        <p class="mb-1"><strong>Créée le :</strong> {{ date('d/m/Y', strtotime($giftList->created_at)) }}</p>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <h6>Partager cette liste</h6>
                        <div class="input-group mb-2">
                            <span class="input-group-text">Code</span>
                            <input type="text" class="form-control" value="{{ $giftList->access_code }}" readonly id="access_code">
                            <button class="btn btn-outline-secondary" type="button" onclick="copyToClipboard('access_code')">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                        <div class="input-group">
                            <span class="input-group-text">URL</span>
                            <input type="text" class="form-control" value="{{ route('gift-lists.public', $giftList->access_code) }}" readonly id="share_url">
                            <button class="btn btn-outline-secondary" type="button" onclick="copyToClipboard('share_url')">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                    </div>

                    <form action="{{ route('gift-lists.regenerate-code', $giftList->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-secondary" onclick="return confirm('Générer un nouveau code rendra les anciens liens invalides. Continuer ?')">
                            <i class="fas fa-sync-alt me-1"></i>Générer un nouveau code
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Liste des ouvrages -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Ouvrages dans la liste ({{ $giftList->ouvrages->count() }})</h5>
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addItemModal">
                        <i class="fas fa-plus-circle me-1"></i>Ajouter un ouvrage
                    </button>
                </div>
                <div class="card-body">
                    @if($giftList->ouvrages->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Ouvrage</th>
                                        <th>Quantité</th>
                                        <th>Priorité</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($giftList->ouvrages as $ouvrage)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ asset('images/livres/default.jpg') }}" alt="{{ $ouvrage->titre }}" class="img-thumbnail me-2" style="width: 50px;">
                                                    <div>
                                                        <strong>{{ $ouvrage->titre }}</strong>
                                                        <div class="text-muted small">{{ $ouvrage->auteur }}</div>
                                                        <div class="text-primary">{{ number_format($ouvrage->prix, 2) }} €</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $ouvrage->pivot->quantity }}</td>
                                            <td>
                                                @for($i = 1; $i <= $ouvrage->pivot->priority; $i++)
                                                    <i class="fas fa-star text-warning"></i>
                                                @endfor
                                                @for($i = $ouvrage->pivot->priority + 1; $i <= 5; $i++)
                                                    <i class="far fa-star text-muted"></i>
                                                @endfor
                                            </td>
                                            <td>
                                                @if($ouvrage->pivot->is_reserved)
                                                    <span class="badge bg-success">Réservé par {{ $ouvrage->pivot->reserved_by }}</span>
                                                @else
                                                    <span class="badge bg-light text-dark">Disponible</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('front.detail', $ouvrage->id) }}" class="btn btn-outline-primary" title="Voir le détail">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <form action="{{ route('gift-lists.remove-item', ['listId' => $giftList->id, 'ouvrageId' => $ouvrage->id]) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-outline-danger" title="Retirer de la liste" onclick="return confirm('Retirer cet ouvrage de la liste ?')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-book fa-3x text-muted mb-3"></i>
                            <h5>Aucun ouvrage dans cette liste</h5>
                            <p class="text-muted">Ajoutez des ouvrages à votre liste pour que vos proches puissent vous les offrir.</p>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addItemModal">
                                <i class="fas fa-plus-circle me-1"></i>Ajouter un ouvrage
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Ajouter un ouvrage -->
<div class="modal fade" id="addItemModal" tabindex="-1" aria-labelledby="addItemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addItemModalLabel">Ajouter un ouvrage à la liste</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Formulaire de recherche -->
                <div class="mb-4">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Rechercher un ouvrage..." id="search-input">
                        <button class="btn btn-outline-secondary" type="button" id="search-button">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>

                <!-- Résultats de recherche (à remplir en JS) -->
                <div id="search-results" class="mb-3">
                    <p class="text-center text-muted">Recherchez un ouvrage pour l'ajouter à votre liste</p>
                </div>

                <!-- Formulaire d'ajout (à afficher après sélection) -->
                <form action="{{ route('gift-lists.add-item', $giftList->id) }}" method="POST" id="add-item-form" class="d-none">
                    @csrf
                    <input type="hidden" name="ouvrage_id" id="selected-ouvrage-id">
                    
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Quantité</label>
                        <input type="number" class="form-control" id="quantity" name="quantity" value="1" min="1">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Priorité</label>
                        <div class="rating">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="priority" id="priority1" value="1" checked>
                                <label class="form-check-label" for="priority1">1 <i class="fas fa-star text-warning"></i></label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="priority" id="priority2" value="2">
                                <label class="form-check-label" for="priority2">2 <i class="fas fa-star text-warning"></i></label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="priority" id="priority3" value="3">
                                <label class="form-check-label" for="priority3">3 <i class="fas fa-star text-warning"></i></label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="priority" id="priority4" value="4">
                                <label class="form-check-label" for="priority4">4 <i class="fas fa-star text-warning"></i></label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="priority" id="priority5" value="5">
                                <label class="form-check-label" for="priority5">5 <i class="fas fa-star text-warning"></i></label>
                            </div>
                        </div>
                        <small class="form-text text-muted">1 = Priorité basse, 5 = Priorité très haute</small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="submit" form="add-item-form" class="btn btn-primary" id="add-item-button" disabled>Ajouter à la liste</button>
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

    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('search-input');
        const searchButton = document.getElementById('search-button');
        const searchResults = document.getElementById('search-results');
        const addItemForm = document.getElementById('add-item-form');
        const selectedOuvrageId = document.getElementById('selected-ouvrage-id');
        const addItemButton = document.getElementById('add-item-button');

        // Fonction de recherche simulée (à remplacer par une vraie requête AJAX)
        function searchOuvrages(query) {
            // Simulation de résultats de recherche
            if (query.length < 3) {
                searchResults.innerHTML = '<p class="text-center text-muted">Entrez au moins 3 caractères</p>';
                return;
            }

            // En production, remplacer par une vraie requête AJAX vers le serveur
            searchResults.innerHTML = '<p class="text-center"><i class="fas fa-spinner fa-spin"></i> Recherche en cours...</p>';
            
            // Simulation de délai pour l'exemple
            setTimeout(() => {
                // Cette partie devrait être remplacée par le résultat de la requête AJAX
                const mockResults = [
                    { id: 1, titre: 'La cuisine française', auteur: 'Jean Martin', prix: 29.99, image: 'default.jpg' },
                    { id: 2, titre: 'Pâtisserie facile', auteur: 'Marie Dupont', prix: 24.50, image: 'default.jpg' },
                    { id: 3, titre: 'Le grand livre des desserts', auteur: 'Pierre Durand', prix: 34.90, image: 'default.jpg' }
                ];
                
                displaySearchResults(mockResults);
            }, 500);
        }

        // Afficher les résultats de recherche
        function displaySearchResults(results) {
            if (results.length === 0) {
                searchResults.innerHTML = '<p class="text-center text-muted">Aucun résultat trouvé</p>';
                return;
            }

            let html = '<div class="list-group">';
            results.forEach(ouvrage => {
                html += `
                <button type="button" class="list-group-item list-group-item-action" data-id="${ouvrage.id}">
                    <div class="d-flex align-items-center">
                        <img src="{{ asset('images/livres') }}/${ouvrage.image}" alt="${ouvrage.titre}" class="img-thumbnail me-3" style="width: 60px;">
                        <div>
                            <h6 class="mb-1">${ouvrage.titre}</h6>
                            <p class="mb-1 text-muted">${ouvrage.auteur}</p>
                            <p class="mb-0 text-primary">${ouvrage.prix.toFixed(2)} €</p>
                        </div>
                    </div>
                </button>
                `;
            });
            html += '</div>';
            searchResults.innerHTML = html;

            // Ajouter les écouteurs d'événements pour la sélection d'un ouvrage
            const itemButtons = searchResults.querySelectorAll('.list-group-item');
            itemButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.dataset.id;
                    selectedOuvrageId.value = id;
                    
                    // Marquer l'élément sélectionné
                    itemButtons.forEach(btn => btn.classList.remove('active'));
                    this.classList.add('active');
                    
                    // Afficher le formulaire d'ajout
                    addItemForm.classList.remove('d-none');
                    
                    // Activer le bouton d'ajout
                    addItemButton.disabled = false;
                });
            });
        }

        // Écouteurs d'événements
        searchButton.addEventListener('click', function() {
            searchOuvrages(searchInput.value);
        });

        searchInput.addEventListener('keyup', function(e) {
            if (e.key === 'Enter') {
                searchOuvrages(searchInput.value);
            }
        });
    });
</script>
@endsection
@endsection
