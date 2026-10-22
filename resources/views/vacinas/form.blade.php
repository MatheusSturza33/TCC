@extends('layouts.app')
@section('title', $title)
@section('topbar_title', 'Cadastro de vacina')
@section('content')
@php
    $errorBag = $errors ?? new \Illuminate\Support\MessageBag();
    $nome = old('nome', $item->nome ?? '');
    $categoria = old('categoria_animal', $item->categoria_animal ?? '');
    $fase = old('fase_aplicacao', $item->fase_aplicacao ?? '');
    $intervalo = old('intervalo_reforco', $item->intervalo_reforco ?? 0);
    $ativa = old('ativa', isset($item) ? (int) $item->ativa : 1);
@endphp
<div class="page-head"><div><h1>{{ $title }}</h1><div class="muted">Cadastre as regras de aplicação e reforço da vacina.</div></div><a class="btn btn-secondary" href="{{ route('vacinas.index') }}">Voltar</a></div>
<div class="card"><form method="POST" action="{{ $action }}">
    @csrf
    @if($method === 'PUT') @method('PUT') @endif
    <div class="form-grid">
        <div class="field"><label for="nome">Nome da vacina *</label><input id="nome" type="text" name="nome" value="{{ $nome }}" maxlength="255" required>@if($errorBag->has('nome'))<span class="error">{{ $errorBag->first('nome') }}</span>@endif</div>
        <div class="field"><label for="categoria_animal">Categoria animal *</label><select id="categoria_animal" name="categoria_animal" required><option value="">Selecione</option><option value="matriz" @selected($categoria === 'matriz')>Matriz</option><option value="reprodutor" @selected($categoria === 'reprodutor')>Reprodutor</option><option value="leitao" @selected($categoria === 'leitao')>Leitão</option><option value="todos" @selected($categoria === 'todos')>Todos</option></select>@if($errorBag->has('categoria_animal'))<span class="error">{{ $errorBag->first('categoria_animal') }}</span>@endif</div>
        <div class="field"><label for="fase_aplicacao">Fase de aplicação</label><input id="fase_aplicacao" type="text" name="fase_aplicacao" value="{{ $fase }}" maxlength="255">@if($errorBag->has('fase_aplicacao'))<span class="error">{{ $errorBag->first('fase_aplicacao') }}</span>@endif</div>
        <div class="field"><label for="intervalo_reforco">Intervalo de reforço (dias) *</label><input id="intervalo_reforco" type="number" name="intervalo_reforco" value="{{ $intervalo }}" min="0" max="3650" required>@if($errorBag->has('intervalo_reforco'))<span class="error">{{ $errorBag->first('intervalo_reforco') }}</span>@endif</div>
        <div class="field"><label for="ativa">Situação *</label><select id="ativa" name="ativa" required><option value="1" @selected((string) $ativa === '1')>Ativa</option><option value="0" @selected((string) $ativa === '0')>Inativa</option></select>@if($errorBag->has('ativa'))<span class="error">{{ $errorBag->first('ativa') }}</span>@endif</div>
    </div>
    <div class="form-actions"><button class="btn btn-primary" type="submit">Salvar vacina</button><a class="btn btn-secondary" href="{{ route('vacinas.index') }}">Cancelar</a></div>
</form></div>
@endsection
