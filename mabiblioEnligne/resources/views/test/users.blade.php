@extends('front.layout')

@section('title', 'Liste des utilisateurs')

@section('content')
<div class="container py-5">
    <h1 class="mb-4">Liste des utilisateurs et leurs rôles</h1>
    
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    
    <div class="mb-4">
        <a href="{{ route('test.create-users') }}" class="btn btn-primary">Créer utilisateurs de test</a>
    </div>
    
    <div class="card">
        <div class="card-header bg-light">
            <h5 class="mb-0">Utilisateurs</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Rôle</th>
                            <th>Créé le</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td><span class="badge bg-info">{{ $user->role }}</span></td>
                                <td>{{ $user->created_at ? $user->created_at->format('d/m/Y H:i') : 'N/A' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="mt-4">
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0">Informations de connexion pour les tests</h5>
            </div>
            <div class="card-body">
                <h6>Administrateur</h6>
                <ul>
                    <li>Email: admin2@test.com</li>
                    <li>Mot de passe: admin123</li>
                </ul>
                
                <h6>Éditeur</h6>
                <ul>
                    <li>Email: editeur2@test.com</li>
                    <li>Mot de passe: editeur123</li>
                </ul>
                
                <h6>Gestionnaire</h6>
                <ul>
                    <li>Email: gestionnaire2@test.com</li>
                    <li>Mot de passe: gestionnaire123</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
