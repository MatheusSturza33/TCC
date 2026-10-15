@extends('layouts.app')

@section('title', $title)
@section('topbar_title', 'Detalhes da raça')

@section('content')
<div class="page-head"><div><h1>{{ $item->nome }}</h1><div class="muted">Detalhes da raça cadastrada.</div></div><div class="actions"><a class="btn btn-secondary" href="{{ route('racas.index') }}">Voltar</a><a class="btn btn-primary" href="{{ route('racas.edit', $item) }}">Editar</a></div></div>
<div class="card"><div class="card-header"><div class="card-icon">01</div><div class="card-title">Informações</div></div><div class="detail-grid"><div class="detail"><strong>Nome</strong>{{ $item->nome }}</div><div class="detail"><strong>Animais associados</strong>{{ $item->animais_count }}</div><div class="detail full"><strong>Observações</strong>{{ $item->observacoes ?: 'Nenhuma observação informada.' }}</div></div></div>
@endsection
