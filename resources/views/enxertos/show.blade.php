@extends('layouts.app')
@section('title', $title)
@section('topbar_title', 'Detalhes do enxerto')
@section('content')
<div class="page-head"><div><h1>Enxerto #{{ $item->id }}</h1><div class="muted">Histórico da transferência do leitão entre ninhadas.</div></div><a class="btn btn-secondary" href="{{ route('enxertos.index') }}">Voltar</a></div>
<div class="card"><div class="detail-grid"><div class="detail"><strong>Leitão</strong><span>#{{ $item->animal_id }} — {{ $item->animal?->tatuagem ?: 'Sem tatuagem' }}</span></div><div class="detail"><strong>Data do enxerto</strong><span>{{ $item->data_enxerto?->format('d/m/Y') }}</span></div><div class="detail"><strong>Mãe de origem</strong><span>#{{ $item->mae_origem_id }} — {{ $item->maeOrigem?->tatuagem ?: 'Sem tatuagem' }}</span></div><div class="detail"><strong>Ninhada de origem</strong><span>Parto #{{ $item->parto_origem_id }}</span></div><div class="detail"><strong>Mãe adotiva</strong><span>#{{ $item->mae_adotiva_id }} — {{ $item->maeAdotiva?->tatuagem ?: 'Sem tatuagem' }}</span></div><div class="detail"><strong>Ninhada adotiva</strong><span>Parto #{{ $item->parto_adotivo_id }}</span></div><div class="detail full"><strong>Motivo da recusa</strong><span>{{ $item->motivo ?: 'Não informado' }}</span></div><div class="detail"><strong>Registrado por</strong><span>{{ $item->usuario?->name ?: 'Não informado' }}</span></div></div></div>
<div class="alert">O leitão foi vinculado à ninhada adotiva e o campo de mãe atual do animal foi atualizado para a matriz adotiva.</div>
@endsection
