<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width initial-scale=1.0">
    <title>Ajouter un Ouvrage | Livres Gourmands</title>
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
    <div class="page-wrapper">

        <!-- START HEADER-->
        @include('inclusion.headerpage')
        <!-- END HEADER-->

        <!-- START SIDEBAR-->
        @include('inclusion.sidebarpage')
        <!-- END SIDEBAR-->

        <div class="content-wrapper">
            <!-- START PAGE CONTENT-->
            <div class="page-content fade-in-up">
                
                <div class="col-md-12">
                    <div class="ibox">
                        <div class="ibox-head">
                            <div class="ibox-title">Ajouter un Ouvrage</div>
                            <div class="ibox-tools">
                                <a href="{{ route('ouvrages.index') }}" class="btn btn-secondary btn-sm">Retour à la liste</a>
                            </div>
                        </div>
                        <div class="ibox-body">
                            <form action="{{ route('ouvrages.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <!-- Champ pour télécharger une image -->
                                <div class="form-group">
                                    <label for="image">Image du livre</label>
                                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                                    <small class="form-text text-muted">Formats acceptés: JPG, PNG, GIF (max 2MB)</small>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 form-group">
                                        <label>Titre</label>
                                        <input class="form-control" type="text" name="titre" placeholder="Titre de l'ouvrage" required>
                                    </div>
                                    <div class="col-sm-6 form-group">
                                        <label>Auteur</label>
                                        <input class="form-control" type="text" name="auteur" placeholder="Nom de l'auteur" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea class="form-control" name="description" rows="3" placeholder="Brève description"></textarea>
                                </div>

                                <div class="row">
                                    <div class="col-sm-6 form-group">
                                        <label>Niveau d'expertise</label>
                                        <select class="form-control" name="niveau_expertise" required>
                                            <option value="débutant">Débutant</option>
                                            <option value="amateur">Amateur</option>
                                            <option value="chef">Chef</option>
                                        </select>
                                    </div>

                                    <div class="col-sm-6 form-group">
                                        <label>Prix</label>
                                        <input class="form-control" type="number" step="0.01" name="prix" placeholder="Prix en $" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-6 form-group">
                                        <label>Date de publication</label>
                                        <input class="form-control" type="date" name="date_publication" required>
                                    </div>

                                    <div class="col-sm-6 form-group">
                                        <label>Catégorie</label>
                                        <select class="form-control" name="categorie_id" required>
                                            @foreach($categories as $categorie)
                                                <option value="{{ $categorie->id }}">{{ $categorie->nom }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <button class="btn btn-primary" type="submit">Enregistrer</button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>

            </div>
            <!-- END PAGE CONTENT-->
        </div>

        @include('inclusion.footerpage')
    </div>

    <!-- BEGIN PAGA BACKDROPS-->
    <div class="sidenav-backdrop backdrop"></div>
    <div class="preloader-backdrop">
        <div class="page-preloader">Loading</div>
    </div>
    <!-- END PAGA BACKDROPS-->

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
