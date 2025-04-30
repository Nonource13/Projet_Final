<nav class="page-sidebar" id="sidebar">
    <div id="sidebar-collapse">
        <div class="admin-block d-flex align-items-center">
            <div class="admin-avatar">
                <img src="{{ asset('assets/img/admin-avatar.png') }}" width="45" class="rounded-circle" />
            </div>
            <div class="admin-info ml-2">
                @if(Auth::check())
                <div class="font-strong">{{ Auth::user()->name }}</div>
                <small class="text-muted">{{ ucfirst(Auth::user()->role) }}</small>
                @else
                <div class="font-strong">Utilisateur</div>
                <small class="text-muted">Rôle</small>
                @endif
            </div>

        </div>

        <ul class="side-menu metismenu">
            <li class="nav-heading text-uppercase">Dashboard</li>
            <li>
                <a class="active" href="{{ url('/') }}">
                    <i class="sidebar-item-icon fa fa-home"></i>
                    <span class="nav-label">Accueil</span>
                </a>
            </li>

            @if(Auth::check() && (Auth::user()->hasRole('editor') || Auth::user()->hasRole('admin') || Auth::user()->hasRole('manager')))
            <li class="nav-heading text-uppercase mt-3">Éditeur</li>
            <li>
                <a href="javascript:;">
                    <i class="sidebar-item-icon fa fa-pencil-square-o"></i>
                    <span class="nav-label">Gestion Éditeur</span>
                    <i class="fa fa-angle-left arrow"></i>
                </a>
                <ul class="nav-2-level collapse">
                    <li><a href="{{ route('ouvrages.index') }}">Rédiger/Modifier Ouvrages</a></li>
                    <li><a href="{{ route('categories.index') }}">Gérer Catégories</a></li>
                    <li><a href="{{ route('commentaires.index') }}">Valider Commentaires</a></li>
                </ul>
            </li>
            @endif

            @if(Auth::check() && (Auth::user()->hasRole('manager') || Auth::user()->hasRole('admin')))
            <li class="nav-heading text-uppercase mt-3">Gestionnaire</li>
            <li>
                <a href="javascript:;">
                    <i class="sidebar-item-icon fa fa-archive"></i>
                    <span class="nav-label">Gestion Catalogue</span>
                    <i class="fa fa-angle-left arrow"></i>
                </a>
                <ul class="nav-2-level collapse">
                    <li><a href="{{ route('ouvrages.index') }}">Ajouter/Modifier/Supprimer Ouvrages</a></li>
                    <li><a href="{{ route('stocks.index') }}">Gérer Stocks</a></li>
                    <li><a href="{{ route('ventes.index') }}">Suivre Ventes</a></li>
                </ul>
            </li>
            @endif

            @if(Auth::check() && Auth::user()->hasRole('admin'))
            <li class="nav-heading text-uppercase mt-3">Administrateur</li>
            <li>
                <a href="javascript:;">
                    <i class="sidebar-item-icon fa fa-cogs"></i>
                    <span class="nav-label">Gestion Admin</span>
                    <i class="fa fa-angle-left arrow"></i>
                </a>
                <ul class="nav-2-level collapse">
                    <li><a href="{{ route('users.index') }}">Gérer Utilisateurs</a></li>
                    <li><a href="{{ route('clients.index') }}">Gérer Clients</a></li>
                </ul>
            </li>
            @endif
        </ul>
    </div>
</nav>