@extends('layouts.app')

@section('title', $title)
@section('topbar_title', 'Cadastro de raças')

@section('content')
<div class="page-head">
    <div><h1>{{ $title }}</h1><div class="muted">Cadastre a raça para utilizá-la na composição dos animais.</div></div>
    <a class="btn btn-secondary" href="{{ route('racas.index') }}">Voltar</a>
</div>

<div class="card">
    <div class="card-header"><div class="card-icon">01</div><div class="card-title">Informações da raça</div></div>
    <form method="POST" action="{{ $action }}">
        @csrf
        @if($method === 'PUT') @method('PUT') @endif
        <div class="form-grid">
            <div class="field full">
                <label for="nome">Nome da raça *</label>
                <input id="nome" name="nome" value="{{ old('nome', $item->nome ?? '') }}" placeholder="Ex.: Duroc" required autofocus>
                @error('nome')<span class="error">{{ $message }}</span>@enderror
            </div>
            <div class="field full">
                <label for="observacoes">Observações</label>
                <textarea id="observacoes" name="observacoes" placeholder="Informações adicionais sobre a raça">{{ old('observacoes', $item->observacoes ?? '') }}</textarea>
                @error('observacoes')<span class="error">{{ $message }}</span>@enderror
            </div>
        </div>
        <div class="form-actions"><button class="btn btn-primary" type="submit">Salvar raça</button><a class="btn btn-secondary" href="{{ route('racas.index') }}">Cancelar</a></div>
    </form>
</div>
@endsection
