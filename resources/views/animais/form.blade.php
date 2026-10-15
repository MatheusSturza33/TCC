@extends('layouts.app')

@section('title', $title)
@section('topbar_title', 'Cadastro de animais')

@section('content')
<div class="page-head">
    <div>
        <h1>{{ $title }}</h1>
        <div class="muted">Informe os dados de identificação, origem e composição racial.</div>
    </div>
    <a class="btn btn-secondary" href="{{ route('animais.index') }}">Voltar</a>
</div>

<form method="POST" action="{{ $action }}">
    @csrf
    @if($method === 'PUT')
        @method('PUT')
    @endif

    <div class="card">
        <div class="card-header">
            <div class="card-icon">01</div>
            <div class="card-title">Identificação do animal</div>
        </div>
        <div class="form-grid">
            <div class="field">
                <label for="tatuagem">Tatuagem *</label>
                <input id="tatuagem" name="tatuagem" value="{{ old('tatuagem', $item->tatuagem ?? '') }}" required>
                @error('tatuagem')<span class="error">{{ $message }}</span>@enderror
            </div>
            <div class="field">
                <label for="mossa">Mossa</label>
                <input id="mossa" name="mossa" value="{{ old('mossa', $item->mossa ?? '') }}">
                @error('mossa')<span class="error">{{ $message }}</span>@enderror
            </div>
            <div class="field">
                <label for="brinco">Brinco</label>
                <input id="brinco" name="brinco" value="{{ old('brinco', $item->brinco ?? '') }}">
                @error('brinco')<span class="error">{{ $message }}</span>@enderror
            </div>
            <div class="field">
                <label for="chip">Chip</label>
                <input id="chip" name="chip" value="{{ old('chip', $item->chip ?? '') }}">
                @error('chip')<span class="error">{{ $message }}</span>@enderror
            </div>
            <div class="field">
                <label for="sexo">Sexo *</label>
                <select id="sexo" name="sexo" required>
                    <option value="">Selecione</option>
                    <option value="M" @selected(old('sexo', $item->sexo ?? '') === 'M')>Macho</option>
                    <option value="F" @selected(old('sexo', $item->sexo ?? '') === 'F')>Fêmea</option>
                </select>
                @error('sexo')<span class="error">{{ $message }}</span>@enderror
            </div>
            <div class="field">
                <label for="tipo">Tipo *</label>
                <select id="tipo" name="tipo" required>
                    <option value="">Selecione</option>
                    @foreach(['matriz' => 'Matriz', 'reprodutor' => 'Reprodutor'] as $value => $label)
                        <option value="{{ $value }}" @selected(old('tipo', $item->tipo ?? '') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('tipo')<span class="error">{{ $message }}</span>@enderror
            </div>
            <div class="field">
                <label for="situacao">Situação *</label>
                <select id="situacao" name="situacao" required>
                    <option value="">Selecione</option>
                    @foreach(['ativo' => 'Ativo', 'inativo' => 'Inativo', 'vendido' => 'Vendido', 'morto' => 'Morto'] as $value => $label)
                        <option value="{{ $value }}" @selected(old('situacao', $item->situacao ?? 'ativo') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('situacao')<span class="error">{{ $message }}</span>@enderror
            </div>
            <div class="field">
                <label for="estado_reprodutivo">Estado reprodutivo</label>
                <select id="estado_reprodutivo" name="estado_reprodutivo">
                    <option value="">Não informado</option>
                    @foreach(['vazia' => 'Vazia', 'gestante' => 'Gestante', 'lactante' => 'Lactante', 'desmamada' => 'Desmamada', 'nao_aplicavel' => 'Não aplicável'] as $value => $label)
                        <option value="{{ $value }}" @selected(old('estado_reprodutivo', $item->estado_reprodutivo ?? '') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('estado_reprodutivo')<span class="error">{{ $message }}</span>@enderror
            </div>
            <div class="field">
                <label for="data_nascimento">Data de nascimento</label>
                <input id="data_nascimento" type="date" name="data_nascimento" value="{{ old('data_nascimento', isset($item->data_nascimento) ? $item->data_nascimento->format('Y-m-d') : '') }}">
                @error('data_nascimento')<span class="error">{{ $message }}</span>@enderror
            </div>
            <div class="field">
                <label for="id_localizacao_atual">Localização atual</label>
                <select id="id_localizacao_atual" name="id_localizacao_atual">
                    <option value="">Não informado</option>
                    @foreach($localizacoes as $localizacao)
                        <option value="{{ $localizacao->id }}" @selected((string) old('id_localizacao_atual', $item->id_localizacao_atual ?? '') === (string) $localizacao->id)>{{ $localizacao->nome }} ({{ ucfirst($localizacao->tipo) }})</option>
                    @endforeach
                </select>
                @error('id_localizacao_atual')<span class="error">{{ $message }}</span>@enderror
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="card-icon">02</div>
            <div class="card-title">Origem e filiação</div>
        </div>
        <div class="form-grid">
            <div class="field">
                <label for="pai_nome">Nome do pai</label>
                <input id="pai_nome" name="pai_nome" value="{{ old('pai_nome', $item->pai_nome ?? '') }}" placeholder="Informe o nome ou identificação">
                @error('pai_nome')<span class="error">{{ $message }}</span>@enderror
            </div>
            <div class="field">
                <label for="mae_nome">Nome da mãe</label>
                <input id="mae_nome" name="mae_nome" value="{{ old('mae_nome', $item->mae_nome ?? '') }}" placeholder="Informe o nome ou identificação">
                @error('mae_nome')<span class="error">{{ $message }}</span>@enderror
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="card-icon">03</div>
            <div>
                <div class="card-title">Composição racial</div>
                <div class="muted">Adicione uma ou mais raças já cadastradas e informe o percentual de cada uma.</div>
            </div>
        </div>
        <div class="card-body">
            <div id="racas-list">
                @foreach($composicao as $index => $componente)
                    <div class="race-row" data-race-row>
                        <div class="field">
                            <label>Raça *</label>
                            <select name="racas[{{ $index }}][raca_id]" required>
                                <option value="">Selecione uma raça</option>
                                @foreach($racas as $raca)
                                    <option value="{{ $raca->id }}" @selected((string) ($componente['raca_id'] ?? '') === (string) $raca->id)>{{ $raca->nome }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="field">
                            <label>Percentual (%) *</label>
                            <input type="number" name="racas[{{ $index }}][percentual]" value="{{ $componente['percentual'] ?? '' }}" min="0.01" max="100" step="0.01" placeholder="Ex.: 50,00" required>
                        </div>
                        <button class="btn btn-danger remove-race" type="button" @if(count($composicao) === 1) disabled @endif>Remover</button>
                    </div>
                @endforeach
            </div>
            <div class="race-footer">
                <button class="btn btn-secondary" id="add-race" type="button">+ Adicionar raça</button>
                <strong>Total informado: <span id="race-total">0,00</span>%</strong>
            </div>
            @error('racas')<span class="error">{{ $message }}</span>@enderror
            @error('racas.*.raca_id')<span class="error">{{ $message }}</span>@enderror
            @error('racas.*.percentual')<span class="error">{{ $message }}</span>@enderror
                <div class="muted race-help">A soma dos percentuais deve ser exatamente 100%. Os leitões serão registrados posteriormente no fluxo de parto.</div>
        </div>
    </div>

    <div class="form-actions">
        <button class="btn btn-primary" type="submit">Salvar animal</button>
        <a class="btn btn-secondary" href="{{ route('animais.index') }}">Cancelar</a>
    </div>
</form>

<script>
(() => {
    const list = document.getElementById('racas-list');
    const addButton = document.getElementById('add-race');
    const total = document.getElementById('race-total');
    const options = @json($racas->map(fn ($raca) => ['id' => $raca->id, 'nome' => $raca->nome])->values());
    let index = {{ count($composicao) }};

    const updateTotal = () => {
        const values = [...list.querySelectorAll('input[type="number"]')]
            .map(input => Number(input.value) || 0);
        total.textContent = values.reduce((sum, value) => sum + value, 0).toFixed(2).replace('.', ',');
    };

    const updateRemoveButtons = () => {
        const buttons = [...list.querySelectorAll('.remove-race')];
        buttons.forEach(button => button.disabled = buttons.length === 1);
    };

    const addRace = () => {
        const row = document.createElement('div');
        row.className = 'race-row';
        row.dataset.raceRow = '';
        row.innerHTML = `
            <div class="field">
                <label>Raça *</label>
                <select name="racas[${index}][raca_id]" required>
                    <option value="">Selecione uma raça</option>
                    ${options.map(option => `<option value="${option.id}">${option.nome}</option>`).join('')}
                </select>
            </div>
            <div class="field">
                <label>Percentual (%) *</label>
                <input type="number" name="racas[${index}][percentual]" min="0.01" max="100" step="0.01" placeholder="Ex.: 50,00" required>
            </div>
            <button class="btn btn-danger remove-race" type="button">Remover</button>`;
        list.appendChild(row);
        index++;
        updateRemoveButtons();
    };

    addButton.addEventListener('click', addRace);
    list.addEventListener('click', event => {
        if (event.target.classList.contains('remove-race')) {
            event.target.closest('[data-race-row]').remove();
            updateRemoveButtons();
            updateTotal();
        }
    });
    list.addEventListener('input', updateTotal);
    updateRemoveButtons();
    updateTotal();
})();
</script>
@endsection

<style>
    .race-row{display:grid;grid-template-columns:minmax(0,1fr) 220px auto;align-items:end;gap:12px;padding:12px 0;border-bottom:1px solid var(--border)}
    .race-row:last-child{border-bottom:0}
    .race-footer{display:flex;align-items:center;justify-content:space-between;gap:12px;margin-top:14px}
    .race-help{margin-top:12px}
    @media(max-width:620px){.race-row{grid-template-columns:1fr}.race-footer{align-items:flex-start;flex-direction:column}}
</style>
