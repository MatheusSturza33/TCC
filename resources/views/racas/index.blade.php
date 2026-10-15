@extends('layouts.app')

@section('title', $title)
@section('topbar_title', 'Raças')

@section('content')
<div class="page-head">
    <div><h1>{{ $title }}</h1><div class="muted">Gerencie as raças disponíveis para o rebanho.</div></div>
    <a class="btn btn-primary" href="{{ route('racas.create') }}">Nova raça</a>
</div>

<div class="card">
    <div class="table-wrap">
        <table class="table">
            <thead><tr><th>ID</th><th>Nome</th><th>Observações</th><th>Animais associados</th><th>Ações</th></tr></thead>
            <tbody>
                @forelse($items as $item)
                    <tr>
                        <td>#{{ $item->id }}</td>
                        <td><strong>{{ $item->nome }}</strong></td>
                        <td>{{ $item->observacoes ?: '—' }}</td>
                        <td><span class="badge badge-green">{{ $item->animais_count }}</span></td>
                        <td><div class="actions"><a class="btn btn-secondary" href="{{ route('racas.show', $item) }}">Ver</a><a class="btn btn-secondary" href="{{ route('racas.edit', $item) }}">Editar</a><form method="POST" action="{{ route('racas.destroy', $item) }}" onsubmit="return confirm('Excluir esta raça?')">@csrf @method('DELETE')<button class="btn btn-danger" type="submit">Excluir</button></form></div></td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="muted">Nenhuma raça cadastrada.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
