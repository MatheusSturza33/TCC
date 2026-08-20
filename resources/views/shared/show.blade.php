@extends('layouts.app')
@section('title', $title)
@section('content')
<div class="page-head"><div><h1>{{ $title }}</h1><div class="muted">Detalhes do registro</div></div><div class="actions"><a class="btn btn-secondary" href="{{ route($route.'.index') }}">Voltar</a><a class="btn btn-primary" href="{{ route($route.'.edit', $item) }}">Editar</a></div></div>
<div class="card"><div class="detail-grid">@foreach($fields as $field)<div class="detail {{ ($field['full'] ?? false) ? 'full' : '' }}"><strong>{{ $field['label'] }}</strong><span>{{ data_get($item, $field['name']) instanceof \Carbon\CarbonInterface ? data_get($item, $field['name'])->format('d/m/Y') : (data_get($item, $field['name']) ?? '—') }}</span></div>@endforeach</div></div>
@endsection