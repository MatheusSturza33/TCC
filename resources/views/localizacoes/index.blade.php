@extends('layouts.app')

@section('title', $title)
@section('topbar_title', 'Localizações')

@section('content')
<div class="page-head">
    <div>
        <h1>Localizações</h1>
        <div class="muted">Gerencie os galpões, setores e baias da granja.</div>
    </div>
    <a class="btn btn-primary" href="{{ route('localizacoes.create') }}">Nova localização</a>
</div>

<div class="card">
    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Tipo</th>
                    <th>Capacidade</th>
                    <th>Animais alojados</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                    <tr>
                        <td>{{ $item->nome }}</td>
                        <td>{{ ucfirst($item->tipo) }}</td>
                        <td>{{ $item->capacidade ?? 'Não informada' }}</td>
                        <td>{{ $item->animais_count }}</td>
                        <td>
                            <div class="actions">
                                <a class="btn btn-secondary" href="{{ route('localizacoes.show', $item) }}">Ver</a>
                                <a class="btn btn-secondary" href="{{ route('localizacoes.edit', $item) }}">Editar</a>
                                <form method="POST" action="{{ route('localizacoes.destroy', $item) }}" onsubmit="return confirm('Excluir esta localização?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger" type="submit">Excluir</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="muted">Nenhuma localização cadastrada.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
