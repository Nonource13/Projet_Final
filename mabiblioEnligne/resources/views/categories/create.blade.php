@extends('layout')

@section('content')
<div class="page-content fade-in-up">
    <div class="col-md-8 offset-md-2">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Ajouter une Catégorie</div>
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

                <form action="{{ route('categories.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="nom">Nom de la catégorie</label>
                        <input type="text" class="form-control" id="nom" name="nom" value="{{ old('nom') }}" required>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-success">Ajouter</button>
                        <a href="{{ route('categories.index') }}" class="btn btn-secondary">Annuler</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection
