@extends('layout')

@section('content')
<div class="page-content fade-in-up">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="ibox">
                <div class="ibox-head">
                    <div class="ibox-title">Détails du Client</div>
                    <div class="ibox-tools">
                        <a href="{{ route('clients.index') }}" class="btn btn-secondary btn-sm">Retour à la liste</a>
                        <a href="{{ route('clients.edit', $client->id) }}" class="btn btn-primary btn-sm">Modifier</a>
                    </div>
                </div>
                <div class="ibox-body">
                    @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <h5 class="font-weight-bold">
                                {{ $client->nom }} {{ $client->prenom }}
                                <span class="badge {{ $client->actif ? 'badge-success' : 'badge-danger' }} ml-2">
                                    {{ $client->actif ? 'Actif' : 'Inactif' }}
                                </span>
                            </h5>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Informations de contact</h6>
                                </div>
                                <div class="card-body">
                                    <p><strong>Email:</strong> {{ $client->email }}</p>
                                    <p><strong>Téléphone:</strong> {{ $client->telephone ?? 'Non renseigné' }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Informations du compte</h6>
                                </div>
                                <div class="card-body">
                                    <p><strong>Date d'inscription:</strong> {{ $client->date_inscription ? date('d/m/Y', strtotime($client->date_inscription)) : date('d/m/Y', strtotime($client->created_at)) }}</p>
                                    <p><strong>Identifiant:</strong> #{{ $client->id }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Adresse</h6>
                        </div>
                        <div class="card-body">
                            <p>{{ $client->adresse ?? 'Adresse non renseignée' }}</p>
                            <p>{{ $client->code_postal ?? '' }} {{ $client->ville ?? '' }}</p>
                            <p>{{ $client->pays ?? '' }}</p>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <form action="{{ route('clients.toggle-status', $client->id) }}" method="POST" class="d-inline me-2">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn {{ $client->actif ? 'btn-warning' : 'btn-success' }}">
                                    {{ $client->actif ? 'Désactiver le compte' : 'Activer le compte' }}
                                </button>
                            </form>
                            <form action="{{ route('clients.destroy', $client->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce client ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Supprimer le client</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
