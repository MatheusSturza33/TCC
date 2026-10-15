@extends('layouts.app')

@section('title', $title)

@section('content')
<div class="page-head">
    <div>
        <h1>{{ $title }}</h1>
        <div class="muted">Preencha os dados abaixo.</div>
    </div>
    <a class="btn btn-secondary" href="{{ route($route . '.index') }}">Voltar</a>
</div>

<div class="card">
    <form method="POST" action="{{ $action }}">
        @csrf
        @if($method === 'PUT')
            @method('PUT')
        @endif

        <div class="form-grid">
            @foreach($fields as $field)
                @php
                    $currentValue = data_get($item ?? null, $field['name']);
                    $value = old($field['name'], $currentValue instanceof \Carbon\CarbonInterface ? $currentValue->format('Y-m-d') : $currentValue);
                    $errorBag = $errors ?? new \Illuminate\Support\MessageBag();
                @endphp
                <div class="field {{ ($field['full'] ?? false) ? 'full' : '' }}">
                    <label for="{{ $field['name'] }}">{{ $field['label'] }}</label>
                    @if($field['type'] === 'select')
                        <select id="{{ $field['name'] }}" name="{{ $field['name'] }}" @required($field['required'] ?? false)>
                            <option value="">Selecione</option>
                            @foreach($field['options'] as $optionValue => $optionLabel)
                                <option value="{{ $optionValue }}" @selected((string) $value === (string) $optionValue)>{{ $optionLabel }}</option>
                            @endforeach
                        </select>
                    @elseif($field['type'] === 'textarea')
                        <textarea id="{{ $field['name'] }}" name="{{ $field['name'] }}" @required($field['required'] ?? false)>{{ $value }}</textarea>
                    @else
                        <input id="{{ $field['name'] }}" type="{{ $field['type'] }}" name="{{ $field['name'] }}" value="{{ $value }}" @required($field['required'] ?? false)>
                    @endif
                    @if($errorBag->has($field['name']))
                        <span class="error">{{ $errorBag->first($field['name']) }}</span>
                    @endif
                </div>
            @endforeach
        </div>

        <div class="form-actions">
            <button class="btn btn-primary" type="submit">Salvar</button>
            <a class="btn btn-secondary" href="{{ route($route . '.index') }}">Cancelar</a>
        </div>
    </form>
</div>
@endsection
