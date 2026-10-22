@extends('layouts.app')
@section('title', $title)
@section('topbar_title', 'Reprodução')
@section('content')
<div class="page-head"><div><h1>Reprodução</h1><div class="muted">Lance tentativas e confirme as gestações das matrizes.</div></div><a class="btn btn-primary" href="{{ route('reproducoes.create') }}">Lançar tentativa</a></div>
<div class="card"><div class="table-wrap"><table class="table"><thead><tr><th>Matriz</th><th>Reprodutor</th><th>Data da tentativa</th><th>Previsão de parto</th><th>Situação</th><th>Ações</th></tr></thead><tbody>
@forelse($items as $item)
<tr><td>#{{ $item->femea_id }} — {{ $item->femea?->tatuagem ?: 'Sem tatuagem' }}</td><td>{{ $item->reprodutor?->tatuagem ? '#'.$item->reprodutor_id.' — '.$item->reprodutor->tatuagem : 'Não informado' }}</td><td>{{ $item->data_primeiro_servico?->format('d/m/Y') }}</td><td>{{ $item->previsao_parto?->format('d/m/Y') ?: '—' }}</td><td>{{ ['aberta' => 'Aberta', 'confirmada' => 'Gestante', 'parto' => 'Parto realizado', 'cancelada' => 'Cancelada'][$item->situacao] ?? $item->situacao }}</td><td><div class="actions"><a class="btn btn-secondary" href="{{ route('reproducoes.show', $item) }}">Ver</a>@if($item->situacao === 'aberta')<a class="btn btn-secondary" href="{{ route('reproducoes.edit', $item) }}">Editar</a><form method="POST" action="{{ route('reproducoes.confirmar', $item) }}" onsubmit="return confirm('Confirmar que a matriz está grávida?')">@csrf<button class="btn btn-primary" type="submit">Confirmar gestação</button></form><form method="POST" action="{{ route('reproducoes.cancelar', $item) }}" onsubmit="return confirm('Cancelar esta tentativa?')">@csrf<button class="btn btn-danger" type="submit">Cancelar</button></form>@endif</div></td></tr>
@empty<tr><td colspan="6" class="muted">Nenhuma tentativa de reprodução cadastrada.</td></tr>@endforelse
</tbody></table></div></div>
@endsection
