@extends('layout')

@section('content')
<div class="page-content fade-in-up">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="ibox">
                <div class="ibox-head">
                    <div class="ibox-title">Ajouter un Client</div>
                    <div class="ibox-tools">
                        <a href="{{ route('clients.index') }}" class="btn btn-secondary btn-sm">Retour à la liste</a>
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

                    <form action="{{ route('clients.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nom">Nom</label>
                                    <input type="text" class="form-control" id="nom" name="nom" value="{{ old('nom') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="prenom">Prénom</label>
                                    <input type="text" class="form-control" id="prenom" name="prenom" value="{{ old('prenom') }}" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="telephone">Téléphone</label>
                                    <input type="text" class="form-control" id="telephone" name="telephone" value="{{ old('telephone') }}">
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="adresse">Adresse</label>
                            <textarea class="form-control" id="adresse" name="adresse" rows="2">{{ old('adresse') }}</textarea>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="ville">Ville</label>
                                    <input type="text" class="form-control" id="ville" name="ville" value="{{ old('ville') }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="code_postal">Code Postal</label>
                                    <input type="text" class="form-control" id="code_postal" name="code_postal" value="{{ old('code_postal') }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="pays">Pays</label>
                                    <input type="text" class="form-control" id="pays" name="pays" value="{{ old('pays', 'France') }}">
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Créer le client</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
