@extends('layouts.app')

@section('title', $title)
@section('topbar_title', 'Detalhes da localização')

@section('content')
<div class="page-head">
    <div>
        <h1>{{ $item->nome }}</h1>
        <div class="muted">Informações e ocupação da localização.</div>
    </div>
    <div class="actions">
        <a class="btn btn-secondary" href="{{ route('localizacoes.index') }}">Voltar</a>
        <a class="btn btn-primary" href="{{ route('localizacoes.edit', $item) }}">Editar</a>
    </div>
</div>

<div class="card">
    <div class="detail-grid">
        <div class="detail">
            <strong>Nome</strong>
            <span>{{ $item->nome }}</span>
        </div>
        <div class="detail">
            <strong>Tipo</strong>
            <span>{{ ucfirst($item->tipo) }}</span>
        </div>
        <div class="detail">
            <strong>Capacidade</strong>
            <span>{{ $item->capacidade ?? 'Não informada' }}</span>
        </div>
        <div class="detail">
            <strong>Animais alojados</strong>
            <span>{{ $item->animais_count }}</span>
        </div>
    </div>
</div>
@endsection
