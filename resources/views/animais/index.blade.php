@extends('layouts.app')

@section('title', $title)
@section('topbar_title', 'Animais')

@section('content')
<div class="page-head">
    <div>
        <h1>{{ $title }}</h1>
        <div class="muted">Cadastre e acompanhe os animais do rebanho.</div>
    </div>
    <a class="btn btn-primary" href="{{ route('animais.create') }}">Novo animal</a>
</div>

<div class="card">
    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th><th>Tatuagem</th><th>Sexo</th><th>Tipo</th><th>Raças</th><th>Pai / Mãe</th><th>Situação</th><th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                    <tr>
                        <td>#{{ $item->id }}</td>
                        <td><strong>{{ $item->tatuagem }}</strong></td>
                        <td>{{ $item->sexo === 'M' ? 'Macho' : 'Fêmea' }}</td>
                        <td>{{ ['matriz' => 'Matriz', 'reprodutor' => 'Reprodutor'][$item->tipo] ?? $item->tipo }}</td>
                        <td>
                            @forelse($item->racas as $raca)
                                <span class="badge badge-green">{{ $raca->nome }} {{ number_format($raca->pivot->percentual, 2, ',', '.') }}%</span>
                            @empty
                                <span class="muted">Não informada</span>
                            @endforelse
                        </td>
                        <td><div>{{ $item->pai_nome ?: '—' }}</div><div class="muted">{{ $item->mae_nome ?: '—' }}</div></td>
                        <td><span class="badge {{ $item->situacao === 'ativo' ? 'badge-green' : 'badge-gray' }}">{{ ucfirst($item->situacao) }}</span></td>
                        <td>
                            <div class="actions">
                                <a class="btn btn-secondary" href="{{ route('animais.show', $item) }}">Ver</a>
                                <a class="btn btn-secondary" href="{{ route('animais.edit', $item) }}">Editar</a>
                                <form method="POST" action="{{ route('animais.destroy', $item) }}" onsubmit="return confirm('Excluir este animal?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger" type="submit">Excluir</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="muted">Nenhum animal encontrado.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
