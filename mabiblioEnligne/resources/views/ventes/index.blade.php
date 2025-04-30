@extends('layout')

@section('content')
<div class="page-content fade-in-up">
    <div class="col-md-12">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Liste des Ventes</div>
                <div class="ibox-tools">
                    <a href="{{ route('ventes.create') }}" class="btn btn-primary btn-sm">Nouvelle vente</a>
                </div>
            </div>
            <div class="ibox-body">
                @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="table-responsive">
                    <form method="GET" action="{{ route('ventes.index') }}" class="form-inline mb-3">
                        <input type="date" name="date_debut" class="form-control mr-2" value="{{ request('date_debut') }}">
                        <input type="date" name="date_fin" class="form-control mr-2" value="{{ request('date_fin') }}">
                        <button type="submit" class="btn btn-primary">Filtrer</button>
                    </form>
                    <table class="table table-bordered table-striped">
                        <thead class="thead-dark">
                            <tr>
                                <th>ID</th>
                                <th>Ouvrage</th>
                                <th>Quantité</th>
                                <th>Date de vente</th>
                                <th>Prix unitaire</th>
                                <th>Montant total</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ventes as $vente)
                            <tr>
                                <td>{{ $vente->id }}</td>
                                <td>{{ $vente->ouvrage->titre ?? '-' }}</td>
                                <td>{{ $vente->quantite }}</td>
                                <td>{{ isset($vente->date_vente) ? date('Y-m-d', strtotime($vente->date_vente)) : $vente->created_at->format('Y-m-d') }}</td>
                                <td>{{ number_format($vente->ouvrage->prix ?? 0, 2) }} $</td>
                                <td>{{ number_format($vente->prix_total ?? 0, 2) }} $</td>
                                <td>
                                    <form action="{{ route('ventes.destroy', $vente->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Confirmer la suppression ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">Aucune vente enregistrée.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <tfoot>
                        <tr>
                            <td colspan="4"><strong>Total</strong></td>
                            <td><strong>-</strong></td>
                            <td><strong>{{ number_format($ventes->sum('prix_total'), 2) }} $</strong></td>
                            <td></td>
                        </tr>
                    </tfoot>

                    <!-- Graphiques de ventes -->
                    <div class="mt-5">
                        <h4 class="mb-4">Tableaux de bord des ventes</h4>
                        
                        <div class="row">
                            <!-- Graphique des ventes par ouvrage -->
                            <div class="col-md-6">
                                <div class="ibox">
                                    <div class="ibox-head">
                                        <div class="ibox-title">Ventes par ouvrage</div>
                                    </div>
                                    <div class="ibox-body">
                                        <canvas id="ventesPar_ouvrage_chart" height="300"></canvas>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Graphique de répartition des quantités vendues -->
                            <div class="col-md-6">
                                <div class="ibox">
                                    <div class="ibox-head">
                                        <div class="ibox-title">Répartition des quantités vendues</div>
                                    </div>
                                    <div class="ibox-body">
                                        <canvas id="quantites_vendues_chart" height="300"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Graphique de l'évolution des ventes dans le temps -->
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="ibox">
                                    <div class="ibox-head">
                                        <div class="ibox-title">Évolution des ventes dans le temps</div>
                                    </div>
                                    <div class="ibox-body">
                                        <canvas id="evolution_ventes_chart" height="200"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-5">
                        <h4>Analyse des ventes par Ouvrage</h4>

                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Ouvrage</th>
                                    <th>Quantité totale vendue</th>
                                    <th>Revenu total ($)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($ventesParOuvrage as $ouvrage)
                                <tr>
                                    <td>{{ $ouvrage['titre'] }}</td>
                                    <td>{{ $ouvrage['quantite_totale'] }}</td>
                                    <td>{{ number_format($ouvrage['revenu_total'], 2) }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center">Pas encore de ventes.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@php
// Préparation des données pour les graphiques
$titresOuvrages = [];
$quantitesVendues = [];
$revenusVentes = [];
$couleurs = [
    'rgba(75, 192, 192, 0.7)',
    'rgba(54, 162, 235, 0.7)',
    'rgba(255, 99, 132, 0.7)',
    'rgba(255, 206, 86, 0.7)',
    'rgba(153, 102, 255, 0.7)',
    'rgba(255, 159, 64, 0.7)',
    'rgba(199, 199, 199, 0.7)',
    'rgba(83, 102, 255, 0.7)',
    'rgba(40, 159, 64, 0.7)',
    'rgba(210, 199, 199, 0.7)',
];

foreach($ventesParOuvrage as $index => $ouvrage) {
    $titresOuvrages[] = $ouvrage['titre'];
    $quantitesVendues[] = $ouvrage['quantite_totale'];
    $revenusVentes[] = $ouvrage['revenu_total'];
}

// Données pour le graphique d'évolution dans le temps
$ventesParDate = [];
$dates = [];
$ventesQuantiteParDate = [];
$ventesRevenusParDate = [];

foreach($ventes as $vente) {
    $date = isset($vente->date_vente) 
        ? date('Y-m-d', strtotime($vente->date_vente)) 
        : $vente->created_at->format('Y-m-d');
    
    if (!isset($ventesParDate[$date])) {
        $ventesParDate[$date] = [
            'quantite' => 0,
            'revenus' => 0
        ];
        $dates[] = $date;
    }
    
    $ventesParDate[$date]['quantite'] += $vente->quantite;
    $ventesParDate[$date]['revenus'] += $vente->prix_total;
}

// Trier les dates
sort($dates);

// Organiser les données par date
foreach($dates as $date) {
    $ventesQuantiteParDate[] = $ventesParDate[$date]['quantite'];
    $ventesRevenusParDate[] = $ventesParDate[$date]['revenus'];
}
@endphp

<!-- Script pour les graphiques - inclus directement dans la page -->
<script type="text/javascript">
document.addEventListener('DOMContentLoaded', function() {
    // Vérifier si Chart est disponible
    if (typeof Chart === 'undefined') {
        console.error('Chart.js n\'est pas chargé. Chargement de la bibliothèque...');
        var script = document.createElement('script');
        script.src = 'https://cdn.jsdelivr.net/npm/chart.js';
        script.onload = initCharts;
        document.head.appendChild(script);
    } else {
        initCharts();
    }

    function initCharts() {
        // Les données pour les graphiques
        var titresOuvrages = @json($titresOuvrages);
        var quantitesVendues = @json($quantitesVendues);
        var revenusVentes = @json($revenusVentes);
        var dates = @json($dates);
        var ventesQuantiteParDate = @json($ventesQuantiteParDate);
        var ventesRevenusParDate = @json($ventesRevenusParDate);
        var couleurs = @json($couleurs);

        // Graphique des ventes par ouvrage
        var ctxVentesParOuvrage = document.getElementById('ventesPar_ouvrage_chart');
        if (ctxVentesParOuvrage) {
            new Chart(ctxVentesParOuvrage, {
                type: 'bar',
                data: {
                    labels: titresOuvrages,
                    datasets: [{
                        label: 'Revenus ($)',
                        data: revenusVentes,
                        backgroundColor: couleurs,
                        borderColor: couleurs,
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        // Graphique des quantités vendues
        var ctxQuantitesVendues = document.getElementById('quantites_vendues_chart');
        if (ctxQuantitesVendues) {
            new Chart(ctxQuantitesVendues, {
                type: 'pie',
                data: {
                    labels: titresOuvrages,
                    datasets: [{
                        label: 'Quantités vendues',
                        data: quantitesVendues,
                        backgroundColor: couleurs,
                        borderColor: 'rgba(255, 255, 255, 1)',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true
                }
            });
        }

        // Graphique d'évolution des ventes dans le temps
        var ctxEvolutionVentes = document.getElementById('evolution_ventes_chart');
        if (ctxEvolutionVentes) {
            new Chart(ctxEvolutionVentes, {
                type: 'line',
                data: {
                    labels: dates,
                    datasets: [
                        {
                            label: 'Quantités vendues',
                            data: ventesQuantiteParDate,
                            borderColor: 'rgba(75, 192, 192, 1)',
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            borderWidth: 2,
                            fill: true
                        },
                        {
                            label: 'Revenus ($)',
                            data: ventesRevenusParDate,
                            borderColor: 'rgba(255, 99, 132, 1)',
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            borderWidth: 2,
                            fill: true
                        }
                    ]
                },
                options: {
                    responsive: true
                }
            });
        }
    }
});
</script>
@endsection