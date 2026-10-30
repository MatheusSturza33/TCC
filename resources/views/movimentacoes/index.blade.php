@extends('layouts.app')
@section('title', $title)
@section('topbar_title', 'Movimentações')
@section('content')
<div class="page-head"><div><h1>Movimentações</h1><div class="muted">Mova animais individualmente ou em lote para desmame, descreche, terminação ou transferência.</div></div><a class="btn btn-primary" href="{{ route('movimentacoes.create') }}">Nova movimentação</a></div>
<div class="card"><div class="table-wrap"><table class="table"><thead><tr><th>Data</th><th>Animal</th><th>Tipo</th><th>Lote</th><th>Destino</th><th>Evento</th><th>Ações</th></tr></thead><tbody>
@forelse($items as $item)
<tr><td>{{ $item->data?->format('d/m/Y') }}</td><td>#{{ $item->animal_id }} — {{ $item->animal?->tatuagem ?: 'Sem tatuagem' }}</td><td>{{ $item->tipo_movimentacao === 'lote' ? 'Lote' : 'Individual' }}</td><td>{{ $item->lote_identificacao ?: '—' }}</td><td>{{ $item->localizacaoDestino?->nome ?: '—' }}</td><td>{{ ['desmame' => 'Desmame', 'descreche' => 'Descreche', 'terminacao' => 'Terminação', 'transferencia' => 'Transferência', 'outro' => 'Outro'][$item->evento_origem] ?? $item->evento_origem }}</td><td><a class="btn btn-secondary" href="{{ route('movimentacoes.show', $item) }}">Ver</a></td></tr>
@empty<tr><td colspan="7" class="muted">Nenhuma movimentação registrada.</td></tr>@endforelse
</tbody></table></div></div>
@endsection
