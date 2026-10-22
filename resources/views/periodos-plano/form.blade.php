@extends('layouts.app')
@section('title', $title)
@section('topbar_title', 'Módulo alimentar')
@section('content')
@php
    $errorBag = $errors ?? new \Illuminate\Support\MessageBag();
    $planoId = old('plano_alimentar_id', $item->plano_alimentar_id ?? '');
    $diaInicio = old('dia_inicio', $item->dia_inicio ?? '');
    $diaFim = old('dia_fim', $item->dia_fim ?? '');
    $quantidade = old('quantidade_kg_dia', $item->quantidade_kg_dia ?? '');
@endphp
<div class="page-head"><div><h1>{{ $title }}</h1><div class="muted">Defina o período e a quantidade diária da alimentação.</div></div><a class="btn btn-secondary" href="{{ route('periodos-plano.index') }}">Voltar</a></div>
<div class="card"><form method="POST" action="{{ $action }}">
    @csrf
    @if($method === 'PUT') @method('PUT') @endif
    <div class="form-grid">
        <div class="field full"><label for="plano_alimentar_id">Ficha alimentar *</label><select id="plano_alimentar_id" name="plano_alimentar_id" required><option value="">Selecione</option>@foreach($planos as $plano)<option value="{{ $plano->id }}" @selected((string) $planoId === (string) $plano->id)>{{ $plano->nome }}</option>@endforeach</select>@if($errorBag->has('plano_alimentar_id'))<span class="error">{{ $errorBag->first('plano_alimentar_id') }}</span>@endif</div>
        <div class="field"><label for="dia_inicio">Dia inicial *</label><input id="dia_inicio" type="number" name="dia_inicio" value="{{ $diaInicio }}" min="0" max="365" required>@if($errorBag->has('dia_inicio'))<span class="error">{{ $errorBag->first('dia_inicio') }}</span>@endif</div>
        <div class="field"><label for="dia_fim">Dia final *</label><input id="dia_fim" type="number" name="dia_fim" value="{{ $diaFim }}" min="0" max="365" required>@if($errorBag->has('dia_fim'))<span class="error">{{ $errorBag->first('dia_fim') }}</span>@endif</div>
        <div class="field"><label for="quantidade_kg_dia">Quantidade (kg/dia) *</label><input id="quantidade_kg_dia" type="number" name="quantidade_kg_dia" value="{{ $quantidade }}" min="0.001" max="100" step="0.001" required>@if($errorBag->has('quantidade_kg_dia'))<span class="error">{{ $errorBag->first('quantidade_kg_dia') }}</span>@endif</div>
    </div>
    <div class="form-actions"><button class="btn btn-primary" type="submit">Salvar módulo</button><a class="btn btn-secondary" href="{{ route('periodos-plano.index') }}">Cancelar</a></div>
</form></div>
@endsection
