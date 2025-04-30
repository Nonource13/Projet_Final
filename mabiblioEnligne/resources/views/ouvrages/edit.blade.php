@extends('layout')

@section('content')
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width initial-scale=1.0">
    <title>Modifier Ouvrage | Livres Gourmands</title>
    <!-- GLOBAL MAINLY STYLES-->
    <link href="{{ url('./assets/vendors/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ url('./assets/vendors/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" />
    <link href="{{ url('./assets/vendors/themify-icons/css/themify-icons.css') }}" rel="stylesheet" />
    <!-- PLUGINS STYLES-->
    <link href="{{ url('./assets/vendors/jvectormap/jquery-jvectormap-2.0.3.css') }}" rel="stylesheet" />
    <!-- THEME STYLES-->
    <link href="{{ url('assets/css/main.min.css') }}" rel="stylesheet" />
</head>

<body class="fixed-navbar">

    <div class="page-content fade-in-up">
        <div class="col-md-8 offset-md-2">
            <div class="ibox">
                <div class="ibox-head">
                    <div class="ibox-title">Modifier un Ouvrage</div>
                </div>
                <div class="ibox-body">
                    <form action="{{ route('ouvrages.update', $ouvrage->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Affichage de l'image actuelle si elle existe -->
                        <div class="form-group">
                            <label>Image actuelle</label>
                            <div class="mb-3">
                                @if($ouvrage->images->count() > 0)
                                    <img src="{{ asset('images/livres/' . $ouvrage->images->first()->chemin_image) }}" 
                                         alt="{{ $ouvrage->titre }}" 
                                         style="max-width: 200px; max-height: 280px;" 
                                         class="img-thumbnail">
                                @else
                                    <div class="alert alert-info">Aucune image associée à ce livre</div>
                                @endif
                            </div>
                        </div>

                        <!-- Champ pour télécharger une nouvelle image -->
                        <div class="form-group">
                            <label for="image">Nouvelle image</label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*">
                            <small class="form-text text-muted">Formats acceptés: JPG, PNG, GIF (max 2MB)</small>
                        </div>

                        <!-- Titre (modifiable pour tout le monde) -->
                        <div class="form-group">
                            <label for="titre">Titre</label>
                            <input type="text" class="form-control" id="titre" name="titre" value="{{ old('titre', $ouvrage->titre) }}" required>
                        </div>

                        <!-- Auteur (modifiable pour tout le monde) -->
                        <div class="form-group">
                            <label for="auteur">Auteur</label>
                            <input type="text" class="form-control" id="auteur" name="auteur" value="{{ old('auteur', $ouvrage->auteur) }}" required>
                        </div>

                        <!-- AJOUT: Champ Description pour l'Éditeur -->
                        <!-- Ce champ permet à l'Éditeur de rédiger/modifier les descriptions des ouvrages -->
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="4">{{ old('description', $ouvrage->description) }}</textarea>
                        </div>

                        <!-- AJOUT: Champ Niveau d'expertise pour l'Éditeur -->
                        <!-- Ce champ permet à l'Éditeur d'affecter un niveau d'expertise (débutant, amateur, chef) -->
                        <div class="form-group">
                            <label for="niveau_expertise">Niveau d'expertise</label>
                            <select class="form-control" id="niveau_expertise" name="niveau_expertise">
                                <option value="débutant" {{ $ouvrage->niveau_expertise == 'débutant' ? 'selected' : '' }}>Débutant</option>
                                <option value="amateur" {{ $ouvrage->niveau_expertise == 'amateur' ? 'selected' : '' }}>Amateur</option>
                                <option value="chef" {{ $ouvrage->niveau_expertise == 'chef' ? 'selected' : '' }}>Chef</option>
                            </select>
                        </div>

                        <!-- Prix (gestionnaire uniquement) -->
                        <!-- <div class="form-group">
                            <label for="prix">Prix</label>
                            <input type="number" step="0.01" class="form-control" id="prix" name="prix"
                                value="{{ old('prix', $ouvrage->prix) }}"
                                @if(Auth::check() && Auth::user()->role == 'editeur') readonly @endif>
                        </div> -->

                        <!-- Catégorie (gestionnaire uniquement) -->
                        <div class="form-group">
                            <label for="categorie">Catégorie</label>
                            <select class="form-control" id="categorie" name="categorie_id"
                                @if(Auth::check() && Auth::user()->role == 'editeur') disabled @endif>
                                @foreach($categories as $categorie)
                                <option value="{{ $categorie->id }}" {{ $ouvrage->categorie_id == $categorie->id ? 'selected' : '' }}>
                                    {{ $categorie->nom }}
                                </option>
                                @endforeach
                            </select>
                        </div>


                        <!-- Bouton de soumission -->
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Mettre à jour</button>
                            <a href="{{ route('ouvrages.index') }}" class="btn btn-secondary">Annuler</a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- BEGIN PAGE BACKDROPS-->
    <div class="sidenav-backdrop backdrop"></div>
    <div class="preloader-backdrop">
        <div class="page-preloader">Loading</div>
    </div>
    <!-- END PAGE BACKDROPS-->

    <!-- CORE PLUGINS-->
    <script src="{{ url('./assets/vendors/jquery/dist/jquery.min.js') }}" type="text/javascript"></script>
    <script src="{{ url('./assets/vendors/popper.js/dist/umd/popper.min.js') }}" type="text/javascript"></script>
    <script src="{{ url('./assets/vendors/bootstrap/dist/js/bootstrap.min.js') }}" type="text/javascript"></script>
    <script src="{{ url('./assets/vendors/metisMenu/dist/metisMenu.min.js') }}" type="text/javascript"></script>
    <script src="{{ url('./assets/vendors/jquery-slimscroll/jquery.slimscroll.min.js') }}" type="text/javascript"></script>
    <!-- PAGE LEVEL PLUGINS-->
    <script src="{{ url('./assets/vendors/chart.js/dist/Chart.min.js') }}" type="text/javascript"></script>
    <script src="{{ url('./assets/vendors/jvectormap/jquery-jvectormap-2.0.3.min.js') }}" type="text/javascript"></script>
    <script src="{{ url('./assets/vendors/jvectormap/jquery-jvectormap-world-mill-en.js') }}" type="text/javascript"></script>
    <script src="{{ url('./assets/vendors/jvectormap/jquery-jvectormap-us-aea-en.js') }}" type="text/javascript"></script>
    <!-- CORE SCRIPTS-->
    <script src="{{ url('assets/js/app.min.js') }}" type="text/javascript"></script>

</body>

</html>
@endsection