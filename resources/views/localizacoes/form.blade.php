@extends('layouts.app')

@section('title', $title)
@section('topbar_title', 'Cadastro de localização')

@section('content')
@php
    $errorBag = $errors ?? new \Illuminate\Support\MessageBag();
    $nome = old('nome', $item->nome ?? '');
    $tipo = old('tipo', $item->tipo ?? '');
    $capacidade = old('capacidade', $item->capacidade ?? '');
@endphp

<div class="page-head">
    <div>
        <h1>{{ $title }}</h1>
        <div class="muted">Informe os dados do local onde os animais serão alojados.</div>
    </div>
    <a class="btn btn-secondary" href="{{ route('localizacoes.index') }}">Voltar</a>
</div>

<div class="card">
    <form method="POST" action="{{ $action }}">
        @csrf
        @if($method === 'PUT')
            @method('PUT')
        @endif

        <div class="form-grid">
            <div class="field">
                <label for="nome">Nome da localização *</label>
                <input id="nome" type="text" name="nome" value="{{ $nome }}" maxlength="255" required>
                @if($errorBag->has('nome'))
                    <span class="error">{{ $errorBag->first('nome') }}</span>
                @endif
            </div>

            <div class="field">
                <label for="tipo">Tipo *</label>
                <select id="tipo" name="tipo" required>
                    <option value="">Selecione</option>
                    <option value="maternidade" @selected($tipo === 'maternidade')>Maternidade</option>
                    <option value="gestacao" @selected($tipo === 'gestacao')>Gestação</option>
                    <option value="creche" @selected($tipo === 'creche')>Creche</option>
                    <option value="terminacao" @selected($tipo === 'terminacao')>Terminação</option>
                    <option value="quarentena" @selected($tipo === 'quarentena')>Quarentena</option>
                    <option value="outro" @selected($tipo === 'outro')>Outro</option>
                </select>
                @if($errorBag->has('tipo'))
                    <span class="error">{{ $errorBag->first('tipo') }}</span>
                @endif
            </div>

            <div class="field">
                <label for="capacidade">Capacidade de animais</label>
                <input id="capacidade" type="number" name="capacidade" value="{{ $capacidade }}" min="1" max="100000">
                <div class="muted">Deixe em branco se o local não tiver capacidade definida.</div>
                @if($errorBag->has('capacidade'))
                    <span class="error">{{ $errorBag->first('capacidade') }}</span>
                @endif
            </div>
        </div>

        <div class="form-actions">
            <button class="btn btn-primary" type="submit">Salvar localização</button>
            <a class="btn btn-secondary" href="{{ route('localizacoes.index') }}">Cancelar</a>
        </div>
    </form>
</div>
@endsection
