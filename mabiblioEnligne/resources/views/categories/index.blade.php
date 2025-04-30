@extends('layout') {{-- On utilise le layout principal --}}

@section('content') {{-- Début de la section content qui sera injectée dans @yield('content') du layout --}}
<div class="page-content fade-in-up">
    <div class="col-md-12">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Liste des Catégories</div>
                <div class="ibox-tools">
                    <a href="{{ route('categories.create') }}" class="btn btn-primary btn-sm">
                        <i class="fa fa-plus"></i> Ajouter une catégorie
                    </a>
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
                                <th>Nom</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories as $categorie)
                                <tr>
                                    <td>{{ $categorie->id }}</td>
                                    <td>{{ $categorie->nom }}</td>
                                    <td>
                                        <a href="{{ route('categories.edit', $categorie->id) }}" class="btn btn-warning btn-sm">
                                            <i class="fa fa-edit"></i> Modifier
                                        </a>
                                        <form action="{{ route('categories.destroy', $categorie->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Confirmer la suppression ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-sm">
                                                <i class="fa fa-trash"></i> Supprimer
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center">Aucune catégorie enregistrée.</td>
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
