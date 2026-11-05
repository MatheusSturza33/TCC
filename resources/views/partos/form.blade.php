@extends('layouts.app')
@section('title', $title)
@section('topbar_title', 'Cadastrar parto')
@section('content')
@php
    $errorBag = $errors ?? new \Illuminate\Support\MessageBag();
    $linhas = old('leitoes', [['tatuagem' => '', 'sexo' => '', 'peso_kg' => '', 'situacao_ao_nascer' => 'vivo']]);
@endphp
<div class="page-head"><div><h1>Cadastrar parto</h1><div class="muted">Informe os totais e cadastre cada leitão nascido na tabela abaixo.</div></div><a class="btn btn-secondary" href="{{ route('partos.index') }}">Voltar</a></div>
<div class="card"><form method="POST" action="{{ $action }}" id="parto-form">@csrf
<div class="form-grid">
<div class="field"><label for="reproducao_id">Reprodução confirmada *</label><select id="reproducao_id" name="reproducao_id" required><option value="">Selecione</option>@foreach($reproducoes as $reproducao)<option value="{{ $reproducao->id }}" data-matriz="{{ $reproducao->femea_id }}" @selected((string) old('reproducao_id') === (string) $reproducao->id)>#{{ $reproducao->id }} — matriz {{ $reproducao->femea?->tatuagem ?: '#'.$reproducao->femea_id }} — previsão {{ $reproducao->previsao_parto?->format('d/m/Y') ?: 'sem previsão' }}</option>@endforeach</select>@if($errorBag->has('reproducao_id'))<span class="error">{{ $errorBag->first('reproducao_id') }}</span>@endif</div>
<div class="field"><label for="matriz_id">Matriz *</label><select id="matriz_id" name="matriz_id" required><option value="">Selecione</option>@foreach($matrizes as $matriz)<option value="{{ $matriz->id }}" @selected((string) old('matriz_id') === (string) $matriz->id)>#{{ $matriz->id }} — {{ $matriz->tatuagem ?: 'Sem tatuagem' }}</option>@endforeach</select>@if($errorBag->has('matriz_id'))<span class="error">{{ $errorBag->first('matriz_id') }}</span>@endif</div>
<div class="field"><label for="dt_inicio">Data e hora do parto *</label><input id="dt_inicio" type="datetime-local" name="dt_inicio" value="{{ old('dt_inicio') }}" required>@if($errorBag->has('dt_inicio'))<span class="error">{{ $errorBag->first('dt_inicio') }}</span>@endif</div>
<div class="field"><label for="tipo">Tipo de parto *</label><select id="tipo" name="tipo" required><option value="normal" @selected(old('tipo', 'normal') === 'normal')>Normal</option><option value="cesariana" @selected(old('tipo') === 'cesariana')>Cesariana</option><option value="aborto" @selected(old('tipo') === 'aborto')>Aborto</option></select></div>
<div class="field"><label for="total_nascidos">Total de leitões nascidos *</label><input id="total_nascidos" type="number" name="total_nascidos" value="{{ old('total_nascidos', count($linhas)) }}" min="1" max="100" required></div>
<div class="field"><label for="total_vivos">Total de leitões vivos *</label><input id="total_vivos" type="number" name="total_vivos" value="{{ old('total_vivos', 1) }}" min="0" max="100" required></div>
<div class="field"><label for="total_natimortos">Total natimortos *</label><input id="total_natimortos" type="number" name="total_natimortos" value="{{ old('total_natimortos', 0) }}" min="0" max="100" required></div>
<div class="field"><label for="total_mumificados">Total mumificados *</label><input id="total_mumificados" type="number" name="total_mumificados" value="{{ old('total_mumificados', 0) }}" min="0" max="100" required></div>
</div>
<div class="card-header"><span class="card-icon">+</span><span class="card-title">Leitões da ninhada</span><button class="btn btn-primary" type="button" id="adicionar-leitao" style="margin-left:auto">Adicionar leitão</button></div>
<div class="table-wrap"><table class="table"><thead><tr><th>#</th><th>Tatuagem *</th><th>Sexo *</th><th>Peso (kg)</th><th>Situação ao nascer *</th><th></th></tr></thead><tbody id="leitoes-body">@foreach($linhas as $index => $leitao)<tr><td class="numero">{{ $index + 1 }}</td><td><input type="text" name="leitoes[{{ $index }}][tatuagem]" value="{{ $leitao['tatuagem'] ?? '' }}" maxlength="255" required></td><td><select name="leitoes[{{ $index }}][sexo]" required><option value="">Selecione</option><option value="M" @selected(($leitao['sexo'] ?? '') === 'M')>Macho</option><option value="F" @selected(($leitao['sexo'] ?? '') === 'F')>Fêmea</option></select></td><td><input type="number" name="leitoes[{{ $index }}][peso_kg]" value="{{ $leitao['peso_kg'] ?? '' }}" min="0.001" max="10" step="0.001"></td><td><select name="leitoes[{{ $index }}][situacao_ao_nascer]" required><option value="vivo" @selected(($leitao['situacao_ao_nascer'] ?? 'vivo') === 'vivo')>Vivo</option><option value="natimorto" @selected(($leitao['situacao_ao_nascer'] ?? '') === 'natimorto')>Natimorto</option><option value="mumificado" @selected(($leitao['situacao_ao_nascer'] ?? '') === 'mumificado')>Mumificado</option></select></td><td><button type="button" class="btn btn-danger remover-leitao">Remover</button></td></tr>@endforeach</tbody></table></div>
<div class="alert" style="margin:15px">A quantidade de linhas e as situações dos leitões precisam corresponder aos totais informados. Leitões vivos serão cadastrados como ativos; natimortos e mumificados serão cadastrados como mortos.</div>
<div class="form-actions"><button class="btn btn-primary" type="submit">Salvar parto e leitões</button><a class="btn btn-secondary" href="{{ route('partos.index') }}">Cancelar</a></div>
</form></div>
<script>
(function () {
    const body = document.getElementById('leitoes-body');
    let index = {{ count($linhas) }};
    function atualizarNumeros() { body.querySelectorAll('tr').forEach((row, numero) => row.querySelector('.numero').textContent = numero + 1); document.getElementById('total_nascidos').value = body.querySelectorAll('tr').length; }
    function adicionar(dados = {}) {
        const i = index++;
        const row = document.createElement('tr');
        row.innerHTML = `<td class="numero"></td><td><input type="text" name="leitoes[${i}][tatuagem]" maxlength="255" required value="${dados.tatuagem || ''}"></td><td><select name="leitoes[${i}][sexo]" required><option value="">Selecione</option><option value="M">Macho</option><option value="F">Fêmea</option></select></td><td><input type="number" name="leitoes[${i}][peso_kg]" min="0.001" max="10" step="0.001" value="${dados.peso_kg || ''}"></td><td><select name="leitoes[${i}][situacao_ao_nascer]" required><option value="vivo">Vivo</option><option value="natimorto">Natimorto</option><option value="mumificado">Mumificado</option></select></td><td><button type="button" class="btn btn-danger remover-leitao">Remover</button></td>`;
        body.appendChild(row); atualizarNumeros();
    }
    document.getElementById('adicionar-leitao').addEventListener('click', () => adicionar());
    body.addEventListener('click', event => { if (event.target.classList.contains('remover-leitao') && body.querySelectorAll('tr').length > 1) { event.target.closest('tr').remove(); atualizarNumeros(); } });
    atualizarNumeros();
})();
</script>
@endsection
