@extends('layout')

@section('content')
<div class="page-content fade-in-up">
    <div class="col-md-12">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Gestion des Clients</div>
                <div class="ibox-tools">
                    <a href="{{ route('clients.create') }}" class="btn btn-primary btn-sm">Nouveau Client</a>
                </div>
            </div>
            <div class="ibox-body">
                @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="thead-dark">
                            <tr>
                                <th>ID</th>
                                <th>Nom & Prénom</th>
                                <th>Email</th>
                                <th>Téléphone</th>
                                <th>Ville</th>
                                <th>Date d'inscription</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($clients as $client)
                            <tr>
                                <td>{{ $client->id }}</td>
                                <td>{{ $client->nom }} {{ $client->prenom }}</td>
                                <td>{{ $client->email }}</td>
                                <td>{{ $client->telephone ?? '-' }}</td>
                                <td>{{ $client->ville ?? '-' }}</td>
                                <td>{{ $client->date_inscription ? date('Y-m-d', strtotime($client->date_inscription)) : date('Y-m-d', strtotime($client->created_at)) }}</td>
                                <td>
                                    <span class="badge {{ $client->actif ? 'badge-success' : 'badge-danger' }}">
                                        {{ $client->actif ? 'Actif' : 'Inactif' }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('clients.show', $client->id) }}" class="btn btn-info btn-sm mb-1">Détails</a>
                                    <a href="{{ route('clients.edit', $client->id) }}" class="btn btn-primary btn-sm mb-1">Modifier</a>
                                    <form action="{{ route('clients.toggle-status', $client->id) }}" method="POST" class="d-inline mb-1">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn {{ $client->actif ? 'btn-warning' : 'btn-success' }} btn-sm">
                                            {{ $client->actif ? 'Désactiver' : 'Activer' }}
                                        </button>
                                    </form>
                                    <form action="{{ route('clients.destroy', $client->id) }}" method="POST" class="d-inline mb-1" onsubmit="return confirm('Confirmer la suppression de ce client ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center">Aucun client enregistré.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
