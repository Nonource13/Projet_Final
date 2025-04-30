@extends('layout')

@section('content')
<div class="page-content fade-in-up">
    <div class="col-md-8 offset-md-2">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Ajouter un Commentaire</div>
            </div>
            <div class="ibox-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('commentaires.store') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label for="ouvrage_id">Ouvrage</label>
                        <select class="form-control" id="ouvrage_id" name="ouvrage_id" required>
                            <option value="">-- Sélectionnez un ouvrage --</option>
                            @foreach($ouvrages as $ouvrage)
                                <option value="{{ $ouvrage->id }}">{{ $ouvrage->titre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="auteur">Auteur du commentaire</label>
                        <input type="text" class="form-control" id="auteur" name="auteur" value="{{ old('auteur') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="contenu">Contenu du commentaire</label>
                        <textarea class="form-control" id="contenu" name="contenu" rows="4" required>{{ old('contenu') }}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="note">Note (0-5)</label>
                        <input type="number" class="form-control" id="note" name="note" value="{{ old('note') }}" min="0" max="5">
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                        <a href="{{ route('commentaires.index') }}" class="btn btn-secondary">Annuler</a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection
