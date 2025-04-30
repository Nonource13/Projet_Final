@extends('layout')

@section('content')
<div class="page-content fade-in-up">
    <div class="col-md-12">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Liste des Commentaires</div>
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
                                <th>Ouvrage</th>
                                <th>Auteur</th>
                                <th>Contenu</th>
                                <th>Note</th>
                                <th>Validé</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($commentaires as $commentaire)
                                <tr>
                                    <td>{{ $commentaire->id }}</td>
                                    <td>{{ $commentaire->ouvrage->titre ?? '-' }}</td>
                                    <td>{{ $commentaire->auteur }}</td>
                                    <td>{{ $commentaire->contenu }}</td>
                                    <td>{{ $commentaire->note }}</td>
                                    <td>{{ $commentaire->valide ? 'Oui' : 'Non' }}</td>
                                    <td>
                                        @if(!$commentaire->valide)
                                            <form action="{{ route('commentaires.valider', $commentaire->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-success btn-sm">Valider</button>
                                            </form>
                                        @endif
                                        <form action="{{ route('commentaires.supprimer', $commentaire->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Confirmer la suppression ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">Aucun commentaire trouvé.</td>
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