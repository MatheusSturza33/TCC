@extends('layouts.app')
@section('title', $title)
@section('topbar_title', 'Atribuição de ficha alimentar')
@section('content')
@php
    $errorBag = $errors ?? new \Illuminate\Support\MessageBag();
    $animalId = old('animal_id', $item->animal_id ?? '');
    $planoId = old('plano_alimentar_id', $item->plano_alimentar_id ?? '');
    $dataInicio = old('data_inicio', $item?->data_inicio?->format('Y-m-d') ?? '');
    $dataFim = old('data_fim', $item?->data_fim?->format('Y-m-d') ?? '');
@endphp
<div class="page-head"><div><h1>{{ $title }}</h1><div class="muted">Vincule uma ficha alimentar a um animal pelo período informado.</div></div><a class="btn btn-secondary" href="{{ route('matriz-plano.index') }}">Voltar</a></div>
<div class="card"><form method="POST" action="{{ $action }}">
    @csrf
    @if($method === 'PUT') @method('PUT') @endif
    <div class="form-grid">
        <div class="field"><label for="animal_id">Animal *</label><select id="animal_id" name="animal_id" required><option value="">Selecione</option>@foreach($animais as $animal)<option value="{{ $animal->id }}" @selected((string) $animalId === (string) $animal->id)>#{{ $animal->id }} — {{ $animal->tatuagem ?: 'Sem tatuagem' }}</option>@endforeach</select>@if($errorBag->has('animal_id'))<span class="error">{{ $errorBag->first('animal_id') }}</span>@endif</div>
        <div class="field"><label for="plano_alimentar_id">Ficha alimentar *</label><select id="plano_alimentar_id" name="plano_alimentar_id" required><option value="">Selecione</option>@foreach($planos as $plano)<option value="{{ $plano->id }}" @selected((string) $planoId === (string) $plano->id)>{{ $plano->nome }}</option>@endforeach</select>@if($errorBag->has('plano_alimentar_id'))<span class="error">{{ $errorBag->first('plano_alimentar_id') }}</span>@endif</div>
        <div class="field"><label for="data_inicio">Data de início *</label><input id="data_inicio" type="date" name="data_inicio" value="{{ $dataInicio }}" required>@if($errorBag->has('data_inicio'))<span class="error">{{ $errorBag->first('data_inicio') }}</span>@endif</div>
        <div class="field"><label for="data_fim">Data de fim</label><input id="data_fim" type="date" name="data_fim" value="{{ $dataFim }}">@if($errorBag->has('data_fim'))<span class="error">{{ $errorBag->first('data_fim') }}</span>@endif</div>
    </div>
    <div class="form-actions"><button class="btn btn-primary" type="submit">Salvar atribuição</button><a class="btn btn-secondary" href="{{ route('matriz-plano.index') }}">Cancelar</a></div>
</form></div>
@endsection
