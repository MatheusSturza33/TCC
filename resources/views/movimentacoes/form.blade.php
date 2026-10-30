@extends('layouts.app')
@section('title', $title)
@section('topbar_title', 'Nova movimentação')
@section('content')
@php
    $errorBag = $errors ?? new \Illuminate\Support\MessageBag();
    $tipo = old('tipo_movimentacao', 'individual');
    $selecionados = old('animal_ids', []);
    $selecionados = is_array($selecionados) ? $selecionados : [$selecionados];
    $data = old('data', date('Y-m-d'));
@endphp
<div class="page-head"><div><h1>Nova movimentação</h1><div class="muted">Escolha um animal ou selecione vários para movimentar um lote.</div></div><a class="btn btn-secondary" href="{{ route('movimentacoes.index') }}">Voltar</a></div>
<div class="card"><form method="POST" action="{{ $action }}" id="movimentacao-form">@csrf
<div class="form-grid">
<div class="field"><label for="tipo_movimentacao">Tipo de movimentação *</label><select id="tipo_movimentacao" name="tipo_movimentacao" required><option value="individual" @selected($tipo === 'individual')>Animal individual</option><option value="lote" @selected($tipo === 'lote')>Lote de animais</option></select>@if($errorBag->has('tipo_movimentacao'))<span class="error">{{ $errorBag->first('tipo_movimentacao') }}</span>@endif</div>
<div class="field"><label for="data">Data *</label><input id="data" type="date" name="data" value="{{ $data }}" required>@if($errorBag->has('data'))<span class="error">{{ $errorBag->first('data') }}</span>@endif</div>
<div class="field full" id="animal-individual-field"><label for="animal_id">Animal *</label><select id="animal_id" name="animal_id"><option value="">Selecione o animal</option>@foreach($animais as $animal)<option value="{{ $animal->id }}" @selected((string) old('animal_id') === (string) $animal->id)>#{{ $animal->id }} — {{ $animal->tatuagem ?: 'Sem tatuagem' }}{{ $animal->localizacaoAtual ? ' — '.$animal->localizacaoAtual->nome : ' — Sem localização' }}</option>@endforeach</select>@if($errorBag->has('animal_id'))<span class="error">{{ $errorBag->first('animal_id') }}</span>@endif</div>
<div class="field full" id="animal-lote-field" style="display:none"><label>Animais do lote *</label><div class="detail" style="max-height:240px;overflow:auto">@foreach($animais as $animal)<label style="display:block;font-weight:400;margin-bottom:7px"><input type="checkbox" name="animal_ids[]" value="{{ $animal->id }}" @checked(in_array($animal->id, $selecionados))> #{{ $animal->id }} — {{ $animal->tatuagem ?: 'Sem tatuagem' }}{{ $animal->localizacaoAtual ? ' — '.$animal->localizacaoAtual->nome : ' — Sem localização' }}</label>@endforeach</div>@if($errorBag->has('animal_ids'))<span class="error">{{ $errorBag->first('animal_ids') }}</span>@endif</div>
<div class="field" id="lote-field" style="display:none"><label for="lote_identificacao">Identificação do lote *</label><input id="lote_identificacao" type="text" name="lote_identificacao" value="{{ old('lote_identificacao') }}" maxlength="255" placeholder="Ex.: Desmame 30/10/2026">@if($errorBag->has('lote_identificacao'))<span class="error">{{ $errorBag->first('lote_identificacao') }}</span>@endif</div>
<div class="field"><label for="localizacao_destino_id">Local de destino *</label><select id="localizacao_destino_id" name="localizacao_destino_id" required><option value="">Selecione o local</option>@foreach($localizacoes as $localizacao)<option value="{{ $localizacao->id }}" @selected((string) old('localizacao_destino_id') === (string) $localizacao->id)>{{ $localizacao->nome }} — {{ ucfirst($localizacao->tipo) }} ({{ $localizacao->animais_count }}{{ $localizacao->capacidade !== null ? '/'.$localizacao->capacidade : '' }})</option>@endforeach</select>@if($errorBag->has('localizacao_destino_id'))<span class="error">{{ $errorBag->first('localizacao_destino_id') }}</span>@endif</div>
<div class="field"><label for="evento_origem">Motivo ou etapa *</label><select id="evento_origem" name="evento_origem" required><option value="">Selecione</option><option value="desmame" @selected(old('evento_origem') === 'desmame')>Desmame</option><option value="descreche" @selected(old('evento_origem') === 'descreche')>Descreche</option><option value="terminacao" @selected(old('evento_origem') === 'terminacao')>Entrada na terminação</option><option value="transferencia" @selected(old('evento_origem') === 'transferencia')>Transferência</option><option value="outro" @selected(old('evento_origem') === 'outro')>Outro</option></select>@if($errorBag->has('evento_origem'))<span class="error">{{ $errorBag->first('evento_origem') }}</span>@endif</div>
</div><div class="alert">A capacidade do local será verificada antes da movimentação. O sistema atualiza automaticamente a localização atual de cada animal.</div><div class="form-actions"><button class="btn btn-primary" type="submit">Realizar movimentação</button><a class="btn btn-secondary" href="{{ route('movimentacoes.index') }}">Cancelar</a></div>
</form></div>
<script>
(function () {
    const tipo = document.getElementById('tipo_movimentacao');
    const individual = document.getElementById('animal-individual-field');
    const lote = document.getElementById('animal-lote-field');
    const loteField = document.getElementById('lote-field');
    const animal = document.getElementById('animal_id');
    const atualizar = function () {
        const emLote = tipo.value === 'lote';
        individual.style.display = emLote ? 'none' : '';
        lote.style.display = emLote ? '' : 'none';
        loteField.style.display = emLote ? '' : 'none';
        animal.disabled = emLote;
    };
    tipo.addEventListener('change', atualizar);
    atualizar();
})();
</script>
@endsection
