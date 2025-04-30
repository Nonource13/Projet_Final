<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Livres Gourmands - Votre librairie culinaire en ligne')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #5D4037;
            --secondary-color: #8D6E63;
            --accent-color: #FF5722;
            --light-color: #f8f9fa;
            --dark-color: #343a40;
        }
        
        body {
            font-family: 'Roboto', sans-serif;
            color: #333;
            background-color: #f9f5f0;
        }
        
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Playfair Display', serif;
        }
        
        .navbar {
            background-color: var(--primary-color);
        }
        
        .navbar-brand {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            font-size: 1.5rem;
        }
        
        .navbar-dark .navbar-nav .nav-link {
            color: rgba(255, 255, 255, 0.8);
        }
        
        .navbar-dark .navbar-nav .nav-link:hover {
            color: #fff;
        }
        
        .hero-section {
            background: linear-gradient(rgba(93, 64, 55, 0.7), rgba(93, 64, 55, 0.7)), url('/img/hero-bg.jpg');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 5rem 0;
            margin-bottom: 3rem;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }
        
        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .card {
            border: none;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
            margin-bottom: 2rem;
        }
        
        .card:hover {
            transform: translateY(-5px);
        }
        
        .book-card .card-img-top {
            height: 250px;
            object-fit: cover;
        }
        
        .book-price {
            font-weight: bold;
            color: var(--accent-color);
        }
        
        .book-category {
            background-color: var(--secondary-color);
            color: white;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            font-size: 0.8rem;
        }
        
        .book-expertise {
            font-size: 0.8rem;
            color: var(--secondary-color);
        }
        
        footer {
            background-color: var(--primary-color);
            color: white;
            padding: 3rem 0;
            margin-top: 3rem;
        }
        
        .footer-links a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
        }
        
        .footer-links a:hover {
            color: white;
            text-decoration: underline;
        }
        
        .social-links a {
            color: white;
            font-size: 1.5rem;
            margin-right: 1rem;
        }
        
        .social-links a:hover {
            color: var(--accent-color);
        }
        
        .search-form {
            max-width: 300px;
        }
        
        /* Custom styles for search bar in navbar */
        .navbar .search-form {
            max-width: 250px;
        }
        
        /* Style for book detail page */
        .book-detail-img {
            max-height: 400px;
            object-fit: contain;
        }
        
        .review-card {
            border-left: 4px solid var(--primary-color);
            padding-left: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .star-rating .fas {
            color: #FFD700;
        }
        
        .breadcrumb-item a {
            color: var(--secondary-color);
            text-decoration: none;
        }
        
        .breadcrumb-item.active {
            color: var(--primary-color);
        }
    </style>
    
    @yield('styles')
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark">
            <div class="container">
                <a class="navbar-brand" href="{{ route('front.index') }}">Livres Gourmands</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('front.index') ? 'active' : '' }}" href="{{ route('front.index') }}">Accueil</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('front.catalogue') ? 'active' : '' }}" href="{{ route('front.catalogue') }}">Catalogue</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('front.about') ? 'active' : '' }}" href="{{ route('front.about') }}">À propos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('front.contact') ? 'active' : '' }}" href="{{ route('front.contact') }}">Contact</a>
                        </li>
                    </ul>
                    
                    <form class="d-flex search-form me-3" action="{{ route('front.recherche') }}" method="GET">
                        <div class="input-group">
                            <input class="form-control" type="search" placeholder="Rechercher..." name="q" aria-label="Rechercher">
                            <button class="btn btn-outline-light" type="submit"><i class="fas fa-search"></i></button>
                        </div>
                    </form>
                    
                    <ul class="navbar-nav">
                        @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}"><i class="fas fa-user"></i> Connexion</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}"><i class="fas fa-user-plus"></i> Inscription</a>
                        </li>
                        @else
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user-circle"></i> {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="#"><i class="fas fa-user"></i> Mon profil</a></li>
                                <li><a class="dropdown-item" href="#"><i class="fas fa-shopping-bag"></i> Mes commandes</a></li>
                                <li><a class="dropdown-item" href="{{ route('gift-lists.index') }}"><i class="fas fa-gift"></i> Mes listes de cadeaux</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item"><i class="fas fa-sign-out-alt"></i> Déconnexion</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                        @endguest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('cart.index') }}">
                                <i class="fas fa-shopping-cart"></i> Panier
                                @if(session()->has('cart') && count(session()->get('cart')) > 0)
                                    <span class="badge bg-danger rounded-pill">{{ count(session()->get('cart')) }}</span>
                                @endif
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    
    <main>
        @yield('content')
    </main>
    
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4 mb-md-0">
                    <h5>Livres Gourmands</h5>
                    <p>Votre librairie culinaire en ligne, spécialisée dans les livres de cuisine pour tous les niveaux.</p>
                    <div class="social-links mt-3">
                        <a href="#" target="_blank"><i class="fab fa-facebook"></i></a>
                        <a href="#" target="_blank"><i class="fab fa-twitter"></i></a>
                        <a href="#" target="_blank"><i class="fab fa-instagram"></i></a>
                        <a href="#" target="_blank"><i class="fab fa-pinterest"></i></a>
                    </div>
                </div>
                <div class="col-md-4 mb-4 mb-md-0">
                    <h5>Liens rapides</h5>
                    <ul class="list-unstyled footer-links">
                        <li><a href="{{ route('front.index') }}">Accueil</a></li>
                        <li><a href="{{ route('front.catalogue') }}">Catalogue</a></li>
                        <li><a href="{{ route('front.about') }}">À propos</a></li>
                        <li><a href="{{ route('front.contact') }}">Contact</a></li>
                        <li><a href="#">Conditions générales</a></li>
                        <li><a href="#">Politique de confidentialité</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Contact</h5>
                    <address>
                        <p><i class="fas fa-map-marker-alt me-2"></i> 123 Rue de la Cuisine, Montréal, QC H2X 1S1, Canada</p>
                        <p><i class="fas fa-phone me-2"></i> +1 514-123-4567</p>
                        <p><i class="fas fa-envelope me-2"></i> contact@livresgourmands.ca</p>
                    </address>
                </div>
            </div>
            <hr class="mt-4 mb-4" style="border-color: rgba(255, 255, 255, 0.2);">
            <div class="row">
                <div class="col-12 text-center">
                    <p class="mb-0">&copy; {{ date('Y') }} Livres Gourmands. Tous droits réservés.</p>
                </div>
            </div>
        </div>
    </footer>
    
    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    @yield('scripts')
</body>
</html>
