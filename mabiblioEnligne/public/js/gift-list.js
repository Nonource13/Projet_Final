/**
 * Fonctions JavaScript pour la gestion des listes de cadeaux
 */

// Fonction pour rechercher des ouvrages via AJAX
function searchOuvrages(query) {
    const searchResults = document.getElementById('search-results');
    
    if (query.length < 3) {
        searchResults.innerHTML = '<p class="text-center text-muted">Entrez au moins 3 caractères</p>';
        return;
    }
    
    searchResults.innerHTML = '<p class="text-center"><i class="fas fa-spinner fa-spin"></i> Recherche en cours...</p>';
    
    // Requête AJAX pour chercher les ouvrages
    fetch(`/api/ouvrages/search?q=${encodeURIComponent(query)}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Erreur réseau lors de la recherche');
            }
            return response.json();
        })
        .then(data => {
            displaySearchResults(data);
        })
        .catch(error => {
            console.error('Erreur:', error);
            searchResults.innerHTML = '<p class="text-center text-danger">Une erreur est survenue lors de la recherche</p>';
        });
}

// Fonction pour afficher les résultats de recherche
function displaySearchResults(results) {
    const searchResults = document.getElementById('search-results');
    const addItemForm = document.getElementById('add-item-form');
    const selectedOuvrageId = document.getElementById('selected-ouvrage-id');
    const addItemButton = document.getElementById('add-item-button');
    
    if (results.length === 0) {
        searchResults.innerHTML = '<p class="text-center text-muted">Aucun résultat trouvé</p>';
        return;
    }

    let html = '<div class="list-group">';
    results.forEach(ouvrage => {
        html += `
        <button type="button" class="list-group-item list-group-item-action" data-id="${ouvrage.id}">
            <div class="d-flex align-items-center">
                <img src="/images/livres/${ouvrage.image || 'default.jpg'}" alt="${ouvrage.titre}" class="img-thumbnail me-3" style="width: 60px;">
                <div>
                    <h6 class="mb-1">${ouvrage.titre}</h6>
                    <p class="mb-1 text-muted">${ouvrage.auteur}</p>
                    <p class="mb-0 text-primary">${parseFloat(ouvrage.prix).toFixed(2)} €</p>
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

// Initialiser les événements lorsque le DOM est chargé
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-input');
    const searchButton = document.getElementById('search-button');
    
    if (searchInput && searchButton) {
        // Écouteurs d'événements
        searchButton.addEventListener('click', function() {
            searchOuvrages(searchInput.value);
        });

        searchInput.addEventListener('keyup', function(e) {
            if (e.key === 'Enter') {
                searchOuvrages(searchInput.value);
            }
        });
    }
    
    // Fonction pour copier dans le presse-papiers
    window.copyToClipboard = function(elementId) {
        const element = document.getElementById(elementId);
        element.select();
        document.execCommand('copy');
        alert('Copié dans le presse-papiers !');
    };
});
