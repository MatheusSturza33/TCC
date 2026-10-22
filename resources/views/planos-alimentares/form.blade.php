@extends('layouts.app')
@section('title', $title)
@section('topbar_title', 'Ficha alimentar')
@section('content')
@php
    $errorBag = $errors ?? new \Illuminate\Support\MessageBag();
    $nome = old('nome', $item->nome ?? '');
    $observacoes = old('observacoes', $item->observacoes ?? '');
    $ativo = old('ativo', isset($item) ? (int) $item->ativo : 1);
@endphp
<div class="page-head"><div><h1>{{ $title }}</h1><div class="muted">Cadastre a ficha e depois inclua seus módulos alimentares.</div></div><a class="btn btn-secondary" href="{{ route('planos-alimentares.index') }}">Voltar</a></div>
<div class="card"><form method="POST" action="{{ $action }}">
    @csrf
    @if($method === 'PUT') @method('PUT') @endif
    <div class="form-grid">
        <div class="field"><label for="nome">Nome da ficha *</label><input id="nome" type="text" name="nome" value="{{ $nome }}" maxlength="255" required>@if($errorBag->has('nome'))<span class="error">{{ $errorBag->first('nome') }}</span>@endif</div>
        <div class="field"><label for="ativo">Situação *</label><select id="ativo" name="ativo" required><option value="1" @selected((string) $ativo === '1')>Ativa</option><option value="0" @selected((string) $ativo === '0')>Inativa</option></select>@if($errorBag->has('ativo'))<span class="error">{{ $errorBag->first('ativo') }}</span>@endif</div>
        <div class="field full"><label for="observacoes">Observações</label><textarea id="observacoes" name="observacoes" maxlength="1000">{{ $observacoes }}</textarea>@if($errorBag->has('observacoes'))<span class="error">{{ $errorBag->first('observacoes') }}</span>@endif</div>
    </div>
    <div class="form-actions"><button class="btn btn-primary" type="submit">Salvar ficha</button><a class="btn btn-secondary" href="{{ route('planos-alimentares.index') }}">Cancelar</a></div>
</form></div>
@endsection
