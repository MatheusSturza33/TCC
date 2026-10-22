@extends('layouts.app')
@section('title', $title)
@section('topbar_title', 'Cadastro de pesagem')
@section('content')
@php
    $errorBag = $errors ?? new \Illuminate\Support\MessageBag();
    $animalId = old('animal_id', $item->animal_id ?? '');
    $fase = old('fase', $item->fase ?? '');
    $data = old('data', $item?->data?->format('Y-m-d') ?? '');
    $peso = old('peso_kg', $item->peso_kg ?? '');
@endphp
<div class="page-head"><div><h1>{{ $title }}</h1><div class="muted">Registre o peso. O GP e o GMD são calculados automaticamente.</div></div><a class="btn btn-secondary" href="{{ route('pesagens.index') }}">Voltar</a></div>
<div class="card"><form method="POST" action="{{ $action }}">
    @csrf
    @if($method === 'PUT') @method('PUT') @endif
    <div class="form-grid">
        <div class="field"><label for="animal_id">Animal *</label><select id="animal_id" name="animal_id" required><option value="">Selecione</option>@foreach($animais as $animal)<option value="{{ $animal->id }}" @selected((string) $animalId === (string) $animal->id)>#{{ $animal->id }} — {{ $animal->tatuagem ?: 'Sem tatuagem' }}</option>@endforeach</select>@if($errorBag->has('animal_id'))<span class="error">{{ $errorBag->first('animal_id') }}</span>@endif</div>
        <div class="field"><label for="fase">Fase *</label><select id="fase" name="fase" required><option value="">Selecione</option><option value="maternidade" @selected($fase === 'maternidade')>Maternidade</option><option value="creche" @selected($fase === 'creche')>Creche</option><option value="terminacao" @selected($fase === 'terminacao')>Terminação</option><option value="reproducao" @selected($fase === 'reproducao')>Reprodução</option><option value="outro" @selected($fase === 'outro')>Outro</option></select>@if($errorBag->has('fase'))<span class="error">{{ $errorBag->first('fase') }}</span>@endif</div>
        <div class="field"><label for="data">Data *</label><input id="data" type="date" name="data" value="{{ $data }}" required>@if($errorBag->has('data'))<span class="error">{{ $errorBag->first('data') }}</span>@endif</div>
        <div class="field"><label for="peso_kg">Peso (kg) *</label><input id="peso_kg" type="number" name="peso_kg" value="{{ $peso }}" min="0.001" max="1000" step="0.001" required>@if($errorBag->has('peso_kg'))<span class="error">{{ $errorBag->first('peso_kg') }}</span>@endif</div>
    </div>
    @if(isset($item))<div class="detail-grid" style="margin-top:1rem"><div class="detail"><strong>Ganho de peso calculado</strong><span>{{ $item->gp_kg ?? 'Ainda não calculado' }} kg</span></div><div class="detail"><strong>GMD calculado</strong><span>{{ $item->gmd_kg ?? 'Ainda não calculado' }} kg/dia</span></div></div>@endif
    <div class="form-actions"><button class="btn btn-primary" type="submit">Salvar pesagem</button><a class="btn btn-secondary" href="{{ route('pesagens.index') }}">Cancelar</a></div>
</form></div>
@endsection
