@extends('layouts.app')
@section('title', $title)
@section('topbar_title', 'Partos')
@section('content')
<div class="page-head"><div><h1>Partos</h1><div class="muted">Registre o parto e a ninhada com os dados individuais de cada leitão.</div></div><a class="btn btn-primary" href="{{ route('partos.create') }}">Cadastrar parto</a></div>
<div class="card"><div class="table-wrap"><table class="table"><thead><tr><th>Data</th><th>Matriz</th><th>Total nascidos</th><th>Vivos</th><th>Natimortos</th><th>Mumificados</th><th>Ações</th></tr></thead><tbody>
@forelse($items as $item)<tr><td>{{ $item->dt_inicio?->format('d/m/Y H:i') }}</td><td>#{{ $item->matriz_id }} — {{ $item->matriz?->tatuagem ?: 'Sem tatuagem' }}</td><td>{{ $item->total_nascidos }}</td><td>{{ $item->total_vivos }}</td><td>{{ $item->total_natimortos }}</td><td>{{ $item->total_mumificados }}</td><td><a class="btn btn-secondary" href="{{ route('partos.show', $item) }}">Ver ninhada</a></td></tr>@empty<tr><td colspan="7" class="muted">Nenhum parto cadastrado.</td></tr>@endforelse
</tbody></table></div></div>
@endsection
