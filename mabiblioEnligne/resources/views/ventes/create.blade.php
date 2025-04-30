@extends('layout')

@section('content')
<div class="page-content fade-in-up">
    <div class="col-md-8 offset-md-2">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Nouvelle Vente</div>
            </div>
            <div class="ibox-body">
                <form action="{{ route('ventes.store') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label for="ouvrage_id">Ouvrage</label>
                        <select name="ouvrage_id" id="ouvrage_id" class="form-control" required>
                            <option value="">-- Sélectionner un ouvrage --</option>
                            @foreach($ouvrages as $ouvrage)
                                <option value="{{ $ouvrage->id }}">{{ $ouvrage->titre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="quantite">Quantité</label>
                        <input type="number" name="quantite" id="quantite" class="form-control" min="1" required>
                    </div>

                    <div class="form-group">
                        <label for="prix_unitaire">Prix Unitaire ($)</label>
                        <input type="number" step="0.01" name="prix_unitaire" id="prix_unitaire" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                        <a href="{{ route('ventes.index') }}" class="btn btn-secondary">Annuler</a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection
