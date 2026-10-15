@extends('layouts.app')

@section('title', $title)
@section('topbar_title', 'Detalhes do animal')

@section('content')
<div class="page-head">
    <div><h1>Animal #{{ $item->id }}</h1><div class="muted">{{ $item->tatuagem }}</div></div>
    <div class="actions"><a class="btn btn-secondary" href="{{ route('animais.index') }}">Voltar</a><a class="btn btn-primary" href="{{ route('animais.edit', $item) }}">Editar</a></div>
</div>

<div class="card">
    <div class="card-header"><div class="card-icon">01</div><div class="card-title">Dados do animal</div></div>
    <div class="detail-grid">
        @foreach([
            'Tatuagem' => $item->tatuagem,
            'Mossa' => $item->mossa,
            'Brinco' => $item->brinco,
            'Chip' => $item->chip,
            'Sexo' => $item->sexo === 'M' ? 'Macho' : 'Fêmea',
            'Tipo' => ['matriz' => 'Matriz', 'reprodutor' => 'Reprodutor'][$item->tipo] ?? $item->tipo,
            'Situação' => ucfirst($item->situacao),
            'Estado reprodutivo' => $item->estado_reprodutivo ? ucfirst($item->estado_reprodutivo) : 'Não informado',
            'Nascimento' => $item->data_nascimento?->format('d/m/Y') ?? 'Não informado',
            'Localização' => $item->localizacaoAtual?->nome ?? 'Não informado',
            'Pai' => $item->pai_nome ?: 'Não informado',
            'Mãe' => $item->mae_nome ?: 'Não informado',
        ] as $label => $value)
            <div class="detail"><strong>{{ $label }}</strong>{{ $value ?: 'Não informado' }}</div>
        @endforeach
    </div>
</div>

<div class="card">
    <div class="card-header"><div class="card-icon">02</div><div class="card-title">Composição racial</div></div>
    <div class="card-body">
        @forelse($item->racas as $raca)
            <div class="detail" style="display:flex;justify-content:space-between;margin-bottom:8px"><span>{{ $raca->nome }}</span><strong>{{ number_format($raca->pivot->percentual, 2, ',', '.') }}%</strong></div>
        @empty
            <div class="muted">Nenhuma raça informada.</div>
        @endforelse
    </div>
</div>
@endsection
