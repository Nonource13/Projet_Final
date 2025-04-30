@extends('front.layout')

@section('title', 'Contact - Livres Gourmands')

@section('content')
    <div class="container py-5">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('front.index') }}">Accueil</a></li>
                <li class="breadcrumb-item active" aria-current="page">Contact</li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-lg-8 mx-auto">
                <h1 class="text-center mb-4">Contactez-nous</h1>
                
                <div class="row mb-5">
                    <div class="col-md-4 text-center mb-4 mb-md-0">
                        <div class="rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px; background-color: var(--primary-color); color: white;">
                            <i class="fas fa-map-marker-alt fa-2x"></i>
                        </div>
                        <h5>Adresse</h5>
                        <p>123 Rue de la Cuisine<br>75001 Paris, France</p>
                    </div>
                    <div class="col-md-4 text-center mb-4 mb-md-0">
                        <div class="rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px; background-color: var(--primary-color); color: white;">
                            <i class="fas fa-phone fa-2x"></i>
                        </div>
                        <h5>Téléphone</h5>
                        <p>+33 1 23 45 67 89</p>
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px; background-color: var(--primary-color); color: white;">
                            <i class="fas fa-envelope fa-2x"></i>
                        </div>
                        <h5>Email</h5>
                        <p>contact@livresgourmands.net</p>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-body">
                        <h2 class="h4 mb-4 text-center">Envoyez-nous un message</h2>
                        
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                        
                        <form action="#" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="nom" class="form-label">Nom</label>
                                    <input type="text" class="form-control" id="nom" name="nom" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="sujet" class="form-label">Sujet</label>
                                <input type="text" class="form-control" id="sujet" name="sujet" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="message" class="form-label">Message</label>
                                <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Envoyer</button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <div class="card mt-5">
                    <div class="card-body">
                        <h2 class="h4 mb-4 text-center">Où nous trouver</h2>
                        <div class="ratio ratio-16x9">
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2624.142047342937!2d2.3364506760332837!3d48.86603857111455!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47e66e1f06e2b70f%3A0x40b82c3688c9460!2s1st%20arrondissement%20of%20Paris%2C%20Paris%2C%20France!5e0!3m2!1sen!2sus!4v1682516567278!5m2!1sen!2sus" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
