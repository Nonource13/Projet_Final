@extends('layout')

@section('content')
<div class="page-content fade-in-up">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="ibox">
                <div class="ibox-head">
                    <div class="ibox-title">Modifier le Client</div>
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

                    <form action="{{ route('clients.update', $client->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nom">Nom</label>
                                    <input type="text" class="form-control" id="nom" name="nom" value="{{ old('nom', $client->nom) }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="prenom">Prénom</label>
                                    <input type="text" class="form-control" id="prenom" name="prenom" value="{{ old('prenom', $client->prenom) }}" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $client->email) }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="telephone">Téléphone</label>
                                    <input type="text" class="form-control" id="telephone" name="telephone" value="{{ old('telephone', $client->telephone) }}">
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="adresse">Adresse</label>
                            <textarea class="form-control" id="adresse" name="adresse" rows="2">{{ old('adresse', $client->adresse) }}</textarea>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="ville">Ville</label>
                                    <input type="text" class="form-control" id="ville" name="ville" value="{{ old('ville', $client->ville) }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="code_postal">Code Postal</label>
                                    <input type="text" class="form-control" id="code_postal" name="code_postal" value="{{ old('code_postal', $client->code_postal) }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="pays">Pays</label>
                                    <input type="text" class="form-control" id="pays" name="pays" value="{{ old('pays', $client->pays) }}">
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="form-check">
                                <input type="hidden" name="actif" value="0">
                                <input class="form-check-input" type="checkbox" id="actif" name="actif" value="1" {{ $client->actif ? 'checked' : '' }}>
                                <label class="form-check-label" for="actif">
                                    Compte actif
                                </label>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Mettre à jour le client</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
