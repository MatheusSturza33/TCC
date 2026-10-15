@extends('layouts.app')
@section('title', $title)
@section('topbar_title', 'Aplicações de vacinas')
@section('content')
<div class="page-head"><div><h1>{{ $title }}</h1><div class="muted">Registre doses aplicadas e acompanhe os reforços.</div></div><a class="btn btn-primary" href="{{ route('aplicacoes-vacina.create') }}">Nova aplicação</a></div>
<div class="card"><div class="table-wrap"><table class="table"><thead><tr><th>Vacina</th><th>Animal</th><th>Aplicação</th><th>Próxima dose</th><th>Lote</th><th>Ações</th></tr></thead><tbody>@forelse($items as $item)<tr><td>{{ $item->vacina?->nome }}</td><td>{{ $item->animal?->tatuagem }}</td><td>{{ $item->data_aplicacao?->format('d/m/Y') }}</td><td>{{ $item->proxima_dose?->format('d/m/Y') ?? 'Não programada' }}</td><td>{{ $item->lote_vacina ?: '—' }}</td><td><div class="actions"><a class="btn btn-secondary" href="{{ route('aplicacoes-vacina.show', $item) }}">Ver</a><a class="btn btn-secondary" href="{{ route('aplicacoes-vacina.edit', $item) }}">Editar</a><form method="POST" action="{{ route('aplicacoes-vacina.destroy', $item) }}" onsubmit="return confirm('Excluir esta aplicação?')">@csrf @method('DELETE')<button class="btn btn-danger">Excluir</button></form></div></td></tr>@empty<tr><td colspan="6" class="muted">Nenhuma aplicação registrada.</td></tr>@endforelse</tbody></table></div></div>
@endsection
