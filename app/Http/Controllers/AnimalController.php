<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use App\Models\Localizacao;
use App\Models\Raca;
use App\Services\CalcularComposicaoRacialService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class AnimalController extends Controller
{
    public function index()
    {
        $items = Animal::query()
            ->with(['pai', 'mae', 'racas'])
            ->orderByDesc('id')
            ->get();

        return view('animais.index', [
            'items' => $items,
            'title' => 'Animais',
            'route' => 'animais',
        ]);
    }

    public function create()
    {
        return view('animais.form', $this->formData());
    }

    public function store(
        Request $request,
        CalcularComposicaoRacialService $calcularComposicaoRacial
    ) {
        $data = $request->validate($this->rules());
        $this->validarTotalRacial($data['racas']);
        $racas = $data['racas'];
        unset($data['racas']);

        DB::transaction(function () use ($data, $racas, $calcularComposicaoRacial): void {
            $animal = Animal::create($data);
            $this->salvarRacas($animal, $racas);
            $calcularComposicaoRacial->sincronizar($animal);
        });

        return redirect()
            ->route('animais.index')
            ->with('success', 'Animal criado com sucesso.');
    }

    public function show(Animal $animal)
    {
        $animal->load(['pai', 'mae', 'localizacaoAtual', 'racas']);

        return view('animais.show', [
            'item' => $animal,
            'title' => 'Animal',
            'route' => 'animais',
        ]);
    }

    public function edit(Animal $animal)
    {
        $animal->load('racas');

        return view('animais.form', $this->formData($animal));
    }

    public function update(
        Request $request,
        Animal $animal,
        CalcularComposicaoRacialService $calcularComposicaoRacial
    ) {
        $data = $request->validate($this->rules($animal));
        $this->validarTotalRacial($data['racas']);
        $racas = $data['racas'];
        unset($data['racas']);

        DB::transaction(function () use ($animal, $data, $racas, $calcularComposicaoRacial): void {
            $animal->update($data);
            $this->salvarRacas($animal, $racas);
            $calcularComposicaoRacial->sincronizar($animal->fresh());
        });

        return redirect()
            ->route('animais.index')
            ->with('success', 'Animal atualizado com sucesso.');
    }

    public function destroy(Animal $animal)
    {
        $animal->delete();

        return redirect()
            ->route('animais.index')
            ->with('success', 'Animal excluído com sucesso.');
    }

    private function formData(?Animal $animal = null): array
    {
        $racas = old('racas');

        if ($racas === null && $animal !== null) {
            $racas = $animal->racas->map(fn ($raca) => [
                'raca_id' => $raca->id,
                'percentual' => $raca->pivot->percentual,
            ])->values()->all();
        }

        return [
            'item' => $animal,
            'title' => $animal ? 'Editar animal' : 'Novo animal',
            'route' => 'animais',
            'action' => $animal ? route('animais.update', $animal) : route('animais.store'),
            'method' => $animal ? 'PUT' : 'POST',
            'racas' => Raca::query()->orderBy('nome')->get(),
            'localizacoes' => Localizacao::query()->orderBy('nome')->get(),
            'composicao' => $racas ?: [['raca_id' => '', 'percentual' => '']],
        ];
    }

    private function salvarRacas(Animal $animal, array $racas): void
    {
        $animal->racas()->sync(
            collect($racas)->mapWithKeys(fn (array $raca) => [
                $raca['raca_id'] => ['percentual' => $raca['percentual']],
            ])->all()
        );
    }

    private function validarTotalRacial(array $racas): void
    {
        $total = round(collect($racas)->sum('percentual'), 2);

        if ($total !== 100.0) {
            throw ValidationException::withMessages([
                'racas' => "A soma dos percentuais das raças deve ser 100%. Total informado: {$total}%.",
            ]);
        }
    }

    private function rules(?Animal $animal = null): array
    {
        return [
            'tatuagem' => 'required|string|max:1000',
            'mossa' => 'nullable|string|max:1000',
            'brinco' => 'nullable|string|max:1000',
            'chip' => 'nullable|string|max:1000',
            'sexo' => ['required', Rule::in(['M', 'F'])],
            'tipo' => ['required', Rule::in(['matriz', 'reprodutor'])],
            'situacao' => ['required', Rule::in(['ativo', 'inativo', 'vendido', 'morto'])],
            'estado_reprodutivo' => ['nullable', Rule::in(['vazia', 'gestante', 'lactante', 'desmamada', 'nao_aplicavel'])],
            'data_nascimento' => 'nullable|date',
            'pai_nome' => 'nullable|string|max:255',
            'mae_nome' => 'nullable|string|max:255',
            'id_localizacao_atual' => 'nullable|integer|exists:localizacoes,id',
            'racas' => 'required|array|min:1',
            'racas.*.raca_id' => 'required|integer|distinct|exists:racas,id',
            'racas.*.percentual' => 'required|numeric|min:0.01|max:100',
        ];
    }
}
