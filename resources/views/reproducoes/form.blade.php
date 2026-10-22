@extends('layouts.app')
@section('title', $title)
@section('topbar_title', 'Tentativa de reprodução')
@section('content')
@php
    $errorBag = $errors ?? new \Illuminate\Support\MessageBag();
    $femeaId = old('femea_id', $item->femea_id ?? '');
    $reprodutorId = old('reprodutor_id', $item->reprodutor_id ?? '');
    $grupoId = old('grupo_monta_id', $item->grupo_monta_id ?? '');
    $dataServico = old('data_primeiro_servico', $item?->data_primeiro_servico?->format('Y-m-d') ?? '');
@endphp
<div class="page-head">
    <div><h1>{{ $title }}</h1><div class="muted">Registre a tentativa. Depois use “Confirmar gestação” quando a gravidez for constatada.</div></div>
    <a class="btn btn-secondary" href="{{ route('reproducoes.index') }}">Voltar</a>
</div>
<div class="card">
    <form method="POST" action="{{ $action }}">
        @csrf
        @if($method === 'PUT') @method('PUT') @endif
        <div class="form-grid">
            <div class="field">
                <label for="femea_id">Matriz *</label>
                <select id="femea_id" name="femea_id" required>
                    <option value="">Selecione a matriz</option>
                    @foreach($femeas as $femea)
                        <option value="{{ $femea->id }}" @selected((string) $femeaId === (string) $femea->id)>#{{ $femea->id }} — {{ $femea->tatuagem ?: 'Sem tatuagem' }}</option>
                    @endforeach
                </select>
                @if($errorBag->has('femea_id'))<span class="error">{{ $errorBag->first('femea_id') }}</span>@endif
            </div>
            <div class="field">
                <label for="reprodutor_id">Reprodutor</label>
                <select id="reprodutor_id" name="reprodutor_id">
                    <option value="">Selecione o reprodutor</option>
                    @foreach($reprodutores as $reprodutor)
                        <option value="{{ $reprodutor->id }}" @selected((string) $reprodutorId === (string) $reprodutor->id)>#{{ $reprodutor->id }} — {{ $reprodutor->tatuagem ?: 'Sem tatuagem' }}</option>
                    @endforeach
                </select>
                @if($errorBag->has('reprodutor_id'))<span class="error">{{ $errorBag->first('reprodutor_id') }}</span>@endif
            </div>
            <div class="field">
                <label for="grupo_monta_id">Grupo de monta</label>
                <input id="grupo_monta_id" type="number" name="grupo_monta_id" value="{{ $grupoId }}" min="1">
                @if($errorBag->has('grupo_monta_id'))<span class="error">{{ $errorBag->first('grupo_monta_id') }}</span>@endif
            </div>
            <div class="field">
                <label for="data_primeiro_servico">Data da tentativa *</label>
                <input id="data_primeiro_servico" type="date" name="data_primeiro_servico" value="{{ $dataServico }}" required>
                @if($errorBag->has('data_primeiro_servico'))<span class="error">{{ $errorBag->first('data_primeiro_servico') }}</span>@endif
            </div>
        </div>
        <div class="alert" style="margin-top:1rem">Ao salvar, a tentativa ficará com situação <strong>Aberta</strong>. A previsão de parto será calculada para 114 dias após a tentativa.</div>
        <div class="form-actions"><button class="btn btn-primary" type="submit">Lançar tentativa</button><a class="btn btn-secondary" href="{{ route('reproducoes.index') }}">Cancelar</a></div>
    </form>
</div>
@endsection
