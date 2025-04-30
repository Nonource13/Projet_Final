<footer class="page-footer bg-light pt-4 mt-5 border-top">
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-3">
                <h5 class="mb-2">Livres Gourmands</h5>
                <p class="small mb-0">Votre librairie culinaire en ligne, spécialisée dans les livres de cuisine pour tous les niveaux.</p>
            </div>
            <div class="col-md-4 mb-3">
                <h6 class="mb-2">Liens rapides</h6>
                <ul class="list-unstyled small">
                    <li><a href="{{ route('front.index') }}">Accueil</a></li>
                    <li><a href="{{ route('front.catalogue') }}">Catalogue</a></li>
                    <li><a href="{{ route('front.about') }}">À propos</a></li>
                    <li><a href="{{ route('front.contact') }}">Contact</a></li>
                    <li><a href="{{ route('front.conditions') }}">Conditions générales</a></li>
                    <li><a href="{{ route('front.privacy') }}">Politique de confidentialité</a></li>
                </ul>
            </div>
            <div class="col-md-4 mb-3">
                <h6 class="mb-2">Contact</h6>
                <ul class="list-unstyled small mb-2">
                    <li><i class="fas fa-map-marker-alt me-2"></i>123 Rue de la Cuisine, Montréal, QC H2X 1S1, Canada</li>
                    <li><i class="fas fa-phone me-2"></i>+1 514-123-4567</li>
                    <li><i class="fas fa-envelope me-2"></i>contact@livresgourmands.ca</li>
                </ul>
            </div>
        </div>
        <div class="text-center py-2 border-top small text-muted">
            {{ date('Y') }} <b>Livres Gourmands</b> - Tous droits réservés.
        </div>
    </div>
</footer>