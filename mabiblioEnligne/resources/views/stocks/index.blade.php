@extends('layout')

@section('content')
<div class="page-content fade-in-up">
    <div class="col-md-12">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Gestion du Stock</div>
                <div class="ibox-tools">
                    <form method="GET" action="{{ route('stocks.index') }}" class="form-inline">
                        <input type="number" name="quantite" class="form-control form-control-sm mr-2" placeholder="Quantité minimale" value="{{ request('quantite') }}">
                        <button type="submit" class="btn btn-primary btn-sm">Rechercher</button>
                    </form>
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
                                <th>Ouvrage</th>
                                <th>Quantité</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($stocks as $stock)
                            <tr>
                                <td>{{ $stock->id }}</td>
                                <td>{{ $stock->ouvrage->titre ?? '-' }}</td>
                                <td>{{ $stock->quantite }}</td>
                                <td>
                                    <!-- Boutons d'action pour ajouter/retirer du stock -->
                                    <a href="{{ route('stocks.ajouter', $stock->id) }}" 
                                       onclick="event.preventDefault(); 
                                                document.getElementById('ajouter-form-{{ $stock->id }}').submit();" 
                                       class="btn btn-success btn-sm">
                                        Ajouter
                                    </a>
                                    <form id="ajouter-form-{{ $stock->id }}" 
                                          action="{{ route('stocks.ajouter', $stock->id) }}" 
                                          method="POST" 
                                          style="display: none;">
                                        @csrf
                                    </form>
                                    
                                    <a href="{{ route('stocks.retirer', $stock->id) }}" 
                                       onclick="event.preventDefault(); 
                                                if(confirm('Retirer un article?')) { 
                                                  document.getElementById('retirer-form-{{ $stock->id }}').submit(); 
                                                }" 
                                       class="btn btn-danger btn-sm">
                                        Retirer
                                    </a>
                                    <form id="retirer-form-{{ $stock->id }}" 
                                          action="{{ route('stocks.retirer', $stock->id) }}" 
                                          method="POST" 
                                          style="display: none;">
                                        @csrf
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center">Aucun stock trouvé.</td>
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