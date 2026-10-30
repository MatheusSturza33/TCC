@extends('layouts.app')
@section('title', $title)
@section('topbar_title', 'Enxertos')
@section('content')
<div class="page-head"><div><h1>Enxertos</h1><div class="muted">Registre quando um leitão é recusado e passa para outra ninhada.</div></div><a class="btn btn-primary" href="{{ route('enxertos.create') }}">Registrar enxerto</a></div>
<div class="card"><div class="table-wrap"><table class="table"><thead><tr><th>Data</th><th>Leitão</th><th>Mãe de origem</th><th>Mãe adotiva</th><th>Ninhada adotiva</th><th>Ações</th></tr></thead><tbody>
@forelse($items as $item)<tr><td>{{ $item->data_enxerto?->format('d/m/Y') }}</td><td>#{{ $item->animal_id }} — {{ $item->animal?->tatuagem ?: 'Sem tatuagem' }}</td><td>#{{ $item->mae_origem_id }} — {{ $item->maeOrigem?->tatuagem ?: 'Sem tatuagem' }}</td><td>#{{ $item->mae_adotiva_id }} — {{ $item->maeAdotiva?->tatuagem ?: 'Sem tatuagem' }}</td><td>{{ $item->partoAdotivo?->id ? '#'.$item->partoAdotivo->id : '—' }}</td><td><a class="btn btn-secondary" href="{{ route('enxertos.show', $item) }}">Ver</a></td></tr>@empty<tr><td colspan="6" class="muted">Nenhum enxerto registrado.</td></tr>@endforelse
</tbody></table></div></div>
@endsection
