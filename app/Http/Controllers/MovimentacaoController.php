<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use App\Models\Localizacao;
use App\Models\Movimentacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class MovimentacaoController extends Controller
{
    public function index()
    {
        $items = Movimentacao::query()
            ->with(['animal', 'localizacaoDestino'])
            ->orderByDesc('data')
            ->orderByDesc('id')
            ->get();

        return view('movimentacoes.index', [
            'items' => $items,
            'title' => 'Movimentações',
        ]);
    }

    public function create()
    {
        return view('movimentacoes.form', $this->formData());
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules());
        $animalIds = $data['tipo_movimentacao'] === 'lote'
            ? array_values(array_unique($data['animal_ids']))
            : [$data['animal_id']];

        $animais = Animal::query()
            ->where('situacao', 'ativo')
            ->whereIn('id', $animalIds)
            ->get();

        if ($animais->count() !== count($animalIds)) {
            throw ValidationException::withMessages([
                'animal_id' => 'Um ou mais animais não existem ou não estão ativos.',
            ]);
        }

        $localizacao = Localizacao::query()
            ->withCount('animais')
            ->findOrFail($data['localizacao_destino_id']);

        $tiposEsperados = [
            'desmame' => 'creche',
            'descreche' => 'terminacao',
            'terminacao' => 'terminacao',
        ];

        if (isset($tiposEsperados[$data['evento_origem']])
            && $localizacao->tipo !== $tiposEsperados[$data['evento_origem']]) {
            throw ValidationException::withMessages([
                'localizacao_destino_id' => 'O local escolhido não corresponde à etapa informada. '
                    .'Desmame deve ir para uma creche e descreche/terminação devem ir para terminação.',
            ]);
        }

        if ($animais->contains(fn (Animal $animal): bool => $animal->id_localizacao_atual === $localizacao->id)) {
            throw ValidationException::withMessages([
                'localizacao_destino_id' => 'Um ou mais animais já estão nessa localização.',
            ]);
        }

        $ocupacaoFinal = $localizacao->animais_count + $animais->count();
        if ($localizacao->capacidade !== null && $ocupacaoFinal > $localizacao->capacidade) {
            throw ValidationException::withMessages([
                'localizacao_destino_id' => "A localização comporta {$localizacao->capacidade} animal(is), mas a movimentação deixaria {$ocupacaoFinal}.",
            ]);
        }

        DB::transaction(function () use ($animais, $data, $localizacao): void {
            foreach ($animais as $animal) {
                Movimentacao::create([
                    'animal_id' => $animal->id,
                    'localizacao_destino_id' => $localizacao->id,
                    'data' => $data['data'],
                    'tipo_movimentacao' => $data['tipo_movimentacao'],
                    'lote_identificacao' => $data['tipo_movimentacao'] === 'lote' ? $data['lote_identificacao'] : null,
                    'evento_origem' => $data['evento_origem'],
                    'usuario_id' => auth()->id(),
                ]);

                $animal->update(['id_localizacao_atual' => $localizacao->id]);
            }
        });

        $quantidade = count($animais);
        $descricao = $data['tipo_movimentacao'] === 'lote' ? 'lote' : 'animal';

        return redirect()->route('movimentacoes.index')
            ->with('success', "Movimentação de {$descricao} realizada para {$quantidade} animal(is).");
    }

    public function show(Movimentacao $movimentacao)
    {
        $movimentacao->load(['animal', 'localizacaoDestino', 'usuario']);

        return view('movimentacoes.show', [
            'item' => $movimentacao,
            'title' => 'Detalhes da movimentação',
        ]);
    }

    private function formData(): array
    {
        return [
            'animais' => Animal::query()
                ->where('situacao', 'ativo')
                ->with('localizacaoAtual')
                ->orderBy('tatuagem')
                ->get(),
            'localizacoes' => Localizacao::query()
                ->withCount('animais')
                ->orderBy('tipo')
                ->orderBy('nome')
                ->get(),
            'title' => 'Nova movimentação',
            'action' => route('movimentacoes.store'),
            'method' => 'POST',
        ];
    }

    private function rules(): array
    {
        return [
            'tipo_movimentacao' => ['required', Rule::in(['individual', 'lote'])],
            'animal_id' => 'required_if:tipo_movimentacao,individual|nullable|integer|exists:animais,id',
            'animal_ids' => 'required_if:tipo_movimentacao,lote|array|min:1',
            'animal_ids.*' => 'integer|exists:animais,id',
            'localizacao_destino_id' => 'required|integer|exists:localizacoes,id',
            'data' => 'required|date',
            'evento_origem' => ['required', Rule::in(['desmame', 'descreche', 'terminacao', 'transferencia', 'outro'])],
            'lote_identificacao' => 'required_if:tipo_movimentacao,lote|nullable|string|max:255',
        ];
    }
}
