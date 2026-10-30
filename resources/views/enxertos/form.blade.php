@extends('layouts.app')
@section('title', $title)
@section('topbar_title', 'Registrar enxerto')
@section('content')
@php
    $errorBag = $errors ?? new \Illuminate\Support\MessageBag();
    $leitaoId = old('animal_id');
    $origemId = old('mae_origem_id');
    $adotivaId = old('mae_adotiva_id');
    $partoOrigemId = old('parto_origem_id');
    $partoAdotivoId = old('parto_adotivo_id');
    $data = old('data_enxerto', date('Y-m-d'));
@endphp
<div class="page-head"><div><h1>Registrar enxerto</h1><div class="muted">Retire o leitão da ninhada de origem e coloque-o na ninhada da mãe adotiva.</div></div><a class="btn btn-secondary" href="{{ route('enxertos.index') }}">Voltar</a></div>
<div class="card"><form method="POST" action="{{ $action }}">@csrf
<div class="form-grid">
<div class="field full"><label for="animal_id">Leitão recusado *</label><select id="animal_id" name="animal_id" required><option value="">Selecione o leitão</option>@foreach($leitoes as $leitao)<option value="{{ $leitao->id }}" @selected((string) $leitaoId === (string) $leitao->id)>#{{ $leitao->id }} — {{ $leitao->tatuagem ?: 'Sem tatuagem' }}{{ $leitao->mae ? ' — mãe atual: '.$leitao->mae->tatuagem : '' }}</option>@endforeach</select>@if($errorBag->has('animal_id'))<span class="error">{{ $errorBag->first('animal_id') }}</span>@endif</div>
<div class="field"><label for="mae_origem_id">Mãe de origem *</label><select id="mae_origem_id" name="mae_origem_id" required><option value="">Selecione a mãe de origem</option>@foreach($matrizes as $matriz)<option value="{{ $matriz->id }}" @selected((string) $origemId === (string) $matriz->id)>#{{ $matriz->id }} — {{ $matriz->tatuagem ?: 'Sem tatuagem' }}</option>@endforeach</select>@if($errorBag->has('mae_origem_id'))<span class="error">{{ $errorBag->first('mae_origem_id') }}</span>@endif</div>
<div class="field"><label for="parto_origem_id">Ninhada de origem *</label><select id="parto_origem_id" name="parto_origem_id" required><option value="">Selecione a ninhada</option>@foreach($partos as $parto)<option value="{{ $parto->id }}" data-matriz="{{ $parto->matriz_id }}" @selected((string) $partoOrigemId === (string) $parto->id)>Parto #{{ $parto->id }} — matriz {{ $parto->matriz?->tatuagem ?: '#'.$parto->matriz_id }} — {{ $parto->dt_inicio?->format('d/m/Y') }}</option>@endforeach</select>@if($errorBag->has('parto_origem_id'))<span class="error">{{ $errorBag->first('parto_origem_id') }}</span>@endif</div>
<div class="field"><label for="mae_adotiva_id">Mãe adotiva *</label><select id="mae_adotiva_id" name="mae_adotiva_id" required><option value="">Selecione a mãe adotiva</option>@foreach($matrizes as $matriz)<option value="{{ $matriz->id }}" @selected((string) $adotivaId === (string) $matriz->id)>#{{ $matriz->id }} — {{ $matriz->tatuagem ?: 'Sem tatuagem' }}</option>@endforeach</select>@if($errorBag->has('mae_adotiva_id'))<span class="error">{{ $errorBag->first('mae_adotiva_id') }}</span>@endif</div>
<div class="field"><label for="parto_adotivo_id">Ninhada adotiva *</label><select id="parto_adotivo_id" name="parto_adotivo_id" required><option value="">Selecione a ninhada</option>@foreach($partos as $parto)<option value="{{ $parto->id }}" data-matriz="{{ $parto->matriz_id }}" @selected((string) $partoAdotivoId === (string) $parto->id)>Parto #{{ $parto->id }} — matriz {{ $parto->matriz?->tatuagem ?: '#'.$parto->matriz_id }} — {{ $parto->dt_inicio?->format('d/m/Y') }}</option>@endforeach</select>@if($errorBag->has('parto_adotivo_id'))<span class="error">{{ $errorBag->first('parto_adotivo_id') }}</span>@endif</div>
<div class="field"><label for="data_enxerto">Data do enxerto *</label><input id="data_enxerto" type="date" name="data_enxerto" value="{{ $data }}" required>@if($errorBag->has('data_enxerto'))<span class="error">{{ $errorBag->first('data_enxerto') }}</span>@endif</div>
<div class="field full"><label for="motivo">Motivo da recusa</label><textarea id="motivo" name="motivo" maxlength="1000">{{ old('motivo') }}</textarea>@if($errorBag->has('motivo'))<span class="error">{{ $errorBag->first('motivo') }}</span>@endif</div>
</div><div class="alert">Ao salvar, o leitão será retirado da ninhada de origem, incluído na ninhada adotiva e terá a mãe atualizada para a matriz adotiva. O registro original ficará preservado no histórico de enxertos.</div><div class="form-actions"><button class="btn btn-primary" type="submit">Confirmar enxerto</button><a class="btn btn-secondary" href="{{ route('enxertos.index') }}">Cancelar</a></div>
</form></div>
<script>
(function () {
    function filtrar(matrizId, seletor) {
        const select = document.getElementById(seletor);
        const valorAtual = select.value;
        Array.from(select.options).forEach(function (option) {
            if (!option.value) return;
            option.hidden = matrizId && option.dataset.matriz !== matrizId;
        });
        if (select.selectedOptions[0] && select.selectedOptions[0].hidden) select.value = '';
        if (!matrizId && valorAtual) select.value = valorAtual;
    }
    document.getElementById('mae_origem_id').addEventListener('change', function () { filtrar(this.value, 'parto_origem_id'); });
    document.getElementById('mae_adotiva_id').addEventListener('change', function () { filtrar(this.value, 'parto_adotivo_id'); });
    filtrar(document.getElementById('mae_origem_id').value, 'parto_origem_id');
    filtrar(document.getElementById('mae_adotiva_id').value, 'parto_adotivo_id');
})();
</script>
@endsection
