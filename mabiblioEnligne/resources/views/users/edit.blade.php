@extends('layout')

@section('content')
<div class="page-content fade-in-up">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="ibox">
                <div class="ibox-head">
                    <div class="ibox-title">Modifier l'Utilisateur</div>
                    <div class="ibox-tools">
                        <a href="{{ route('users.index') }}" class="btn btn-secondary btn-sm">Retour à la liste</a>
                    </div>
                </div>
                <div class="ibox-body">
                    <!-- Affichage des erreurs de validation -->
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form action="{{ route('users.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-group">
                            <label for="name">Nom</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="role">Rôle</label>
                            <select class="form-control" id="role" name="role" required>
                                <option value="">-- Sélectionner un rôle --</option>
                                <option value="admin" {{ (old('role', $user->role) == 'admin') ? 'selected' : '' }}>Administrateur</option>
                                <option value="manager" {{ (old('role', $user->role) == 'manager') ? 'selected' : '' }}>Gestionnaire</option>
                                <option value="editor" {{ (old('role', $user->role) == 'editor') ? 'selected' : '' }}>Éditeur</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Mettre à jour l'utilisateur</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
