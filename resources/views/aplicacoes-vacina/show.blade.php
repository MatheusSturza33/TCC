@extends('layouts.app')
@section('title', $title)
@section('topbar_title', 'Detalhes da aplicação')
@section('content')
<div class="page-head"><div><h1>Aplicação #{{ $item->id }}</h1><div class="muted">{{ $item->vacina?->nome }} em {{ $item->animal?->tatuagem }}</div></div><div class="actions"><a class="btn btn-secondary" href="{{ route('aplicacoes-vacina.index') }}">Voltar</a><a class="btn btn-primary" href="{{ route('aplicacoes-vacina.edit', $item) }}">Editar</a></div></div>
<div class="card"><div class="detail-grid"><div class="detail"><strong>Vacina</strong>{{ $item->vacina?->nome }}</div><div class="detail"><strong>Animal</strong>{{ $item->animal?->tatuagem }}</div><div class="detail"><strong>Data da aplicação</strong>{{ $item->data_aplicacao?->format('d/m/Y') }}</div><div class="detail"><strong>Próxima dose</strong>{{ $item->proxima_dose?->format('d/m/Y') ?? 'Não programada' }}</div><div class="detail"><strong>Lote</strong>{{ $item->lote_vacina ?: 'Não informado' }}</div><div class="detail"><strong>Responsável</strong>{{ $item->responsavel?->name ?? 'Não informado' }}</div></div></div>
@endsection
