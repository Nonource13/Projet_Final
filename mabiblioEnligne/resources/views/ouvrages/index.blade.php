<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width initial-scale=1.0">
    <title>Ouvrages | Livres Gourmands</title>
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
                            <div class="ibox-title">Liste des Ouvrages</div>
                            <div class="ibox-tools">
                                <a href="{{ route('ouvrages.create') }}" class="btn btn-success btn-sm">
                                    <i class="fa fa-plus"></i> Ajouter un ouvrage
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
                                            <th>Image</th>
                                            <th>Titre</th>
                                            <th>Auteur</th>
                                            <th>Catégorie</th>
                                            <th>Prix</th>
                                            <th>Date Publication</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($ouvrages as $ouvrage)
                                            <tr>
                                                <td>{{ $ouvrage->id }}</td>
                                                <td>
                                                    @if($ouvrage->images->count() > 0)
                                                        <img src="{{ asset('images/livres/' . $ouvrage->images->first()->chemin_image) }}" 
                                                             alt="{{ $ouvrage->titre }}" 
                                                             style="width: 50px; height: 70px; object-fit: cover;" 
                                                             class="img-thumbnail">
                                                    @else
                                                        <span class="badge badge-secondary">Pas d'image</span>
                                                    @endif
                                                </td>
                                                <td>{{ $ouvrage->titre }}</td>
                                                <td>{{ $ouvrage->auteur }}</td>
                                                <td>{{ $ouvrage->categorie->nom ?? '-' }}</td>
                                                <td>{{ $ouvrage->prix }} $</td>
                                                <td>{{ $ouvrage->date_publication }}</td>
                                                <td>
                                                    <a href="{{ route('ouvrages.edit', $ouvrage->id) }}" class="btn btn-info btn-sm">
                                                        <i class="fa fa-edit"></i> Modifier
                                                    </a>
                                                    <form action="{{ route('ouvrages.destroy', $ouvrage->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Confirmer la suppression ?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-danger btn-sm">
                                                            <i class="fa fa-trash"></i> Supprimer
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

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
    <!-- PAGE LEVEL SCRIPTS-->
    <script src="{{ url('./assets/js/scripts/dashboard_1_demo.js') }}" type="text/javascript"></script>

</body>
</html>
