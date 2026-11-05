<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use App\Models\Parto;
use App\Models\Reproducao;
use App\Services\CalcularComposicaoRacialService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class PartoController extends Controller
{
    public function index()
    {
        $items = Parto::query()
            ->with(['matriz', 'reproducao.reprodutor'])
            ->withCount('animais')
            ->orderByDesc('dt_inicio')
            ->orderByDesc('id')
            ->get();

        return view('partos.index', [
            'items' => $items,
            'title' => 'Partos',
        ]);
    }

    public function create()
    {
        return view('partos.form', $this->formData());
    }

    public function store(
        Request $request,
        CalcularComposicaoRacialService $calcularComposicaoRacial
    )
    {
        $data = $request->validate($this->rules());
        $leitoes = $this->normalizarLeitoes($data['leitoes'] ?? []);
        $totalMortos = collect($leitoes)->whereIn('situacao_ao_nascer', ['natimorto', 'mumificado'])->count();
        $totalVivos = collect($leitoes)->where('situacao_ao_nascer', 'vivo')->count();
        $totalNascidos = count($leitoes);

        if ($totalNascidos !== (int) $data['total_nascidos']) {
            throw ValidationException::withMessages([
                'total_nascidos' => "O total informado deve ser igual à quantidade de leitões cadastrados ({$totalNascidos}).",
            ]);
        }

        if ($totalVivos !== (int) $data['total_vivos']) {
            throw ValidationException::withMessages([
                'total_vivos' => "O total de vivos deve ser igual à quantidade de leitões marcados como vivos ({$totalVivos}).",
            ]);
        }

        $totalMortosInformado = (int) $data['total_natimortos'] + (int) $data['total_mumificados'];
        if ($totalMortos !== $totalMortosInformado) {
            throw ValidationException::withMessages([
                'total_natimortos' => "Natimortos + mumificados deve ser igual à quantidade de leitões mortos ({$totalMortos}).",
            ]);
        }

        if ($totalVivos + $totalMortos !== (int) $data['total_nascidos']) {
            throw ValidationException::withMessages([
                'total_nascidos' => 'A soma de vivos, natimortos e mumificados deve ser igual ao total nascido.',
            ]);
        }

        $reproducao = Reproducao::query()
            ->with(['femea', 'reprodutor'])
            ->whereKey($data['reproducao_id'])
            ->where('situacao', 'confirmada')
            ->first();

        if (! $reproducao) {
            throw ValidationException::withMessages([
                'reproducao_id' => 'Selecione uma reprodução com gestação confirmada.',
            ]);
        }

        if ((int) $reproducao->femea_id !== (int) $data['matriz_id']) {
            throw ValidationException::withMessages([
                'matriz_id' => 'A matriz precisa ser a mesma da reprodução selecionada.',
            ]);
        }

        if ($reproducao->partos()->exists()) {
            throw ValidationException::withMessages([
                'reproducao_id' => 'Esta reprodução já possui um parto cadastrado.',
            ]);
        }

        DB::transaction(function () use ($data, $leitoes, $reproducao, $calcularComposicaoRacial): void {
            $parto = Parto::create([
                'reproducao_id' => $reproducao->id,
                'matriz_id' => $data['matriz_id'],
                'dt_inicio' => $data['dt_inicio'],
                'total_nascidos' => $data['total_nascidos'],
                'tipo' => $data['tipo'],
                'total_vivos' => $data['total_vivos'],
                'total_natimortos' => $data['total_natimortos'],
                'total_mumificados' => $data['total_mumificados'],
                'situacao' => 'realizado',
            ]);

            foreach ($leitoes as $leitao) {
                $animal = Animal::create([
                    'tatuagem' => $leitao['tatuagem'],
                    'sexo' => $leitao['sexo'],
                    'tipo' => 'leitao',
                    'situacao' => $leitao['situacao_ao_nascer'] === 'vivo' ? 'ativo' : 'morto',
                    'data_nascimento' => substr($data['dt_inicio'], 0, 10),
                    'id_pai' => $reproducao->reprodutor_id,
                    'id_mae' => $data['matriz_id'],
                    'id_localizacao_atual' => $reproducao->femea?->id_localizacao_atual,
                ]);

                $calcularComposicaoRacial->sincronizar($animal);

                $parto->animais()->attach($animal->id, [
                    'peso_kg' => $leitao['peso_kg'],
                    'situacao_ao_nascer' => $leitao['situacao_ao_nascer'],
                ]);
            }

            $reproducao->update(['situacao' => 'parto']);
            $reproducao->femea()->update(['estado_reprodutivo' => 'lactante']);
        });

        return redirect()->route('partos.index')->with('success', 'Parto e leitões cadastrados com sucesso.');
    }

    public function show(Parto $parto)
    {
        $parto->load(['matriz', 'reproducao.reprodutor', 'animais']);

        return view('partos.show', [
            'item' => $parto,
            'title' => 'Detalhes do parto',
        ]);
    }

    private function formData(): array
    {
        $reproducoes = Reproducao::query()
            ->with(['femea', 'reprodutor'])
            ->where('situacao', 'confirmada')
            ->whereDoesntHave('partos')
            ->orderBy('previsao_parto')
            ->get();

        return [
            'reproducoes' => $reproducoes,
            'matrizes' => Animal::query()
                ->where('sexo', 'F')
                ->where('tipo', 'matriz')
                ->where('situacao', 'ativo')
                ->orderBy('tatuagem')
                ->get(),
            'title' => 'Cadastrar parto',
            'action' => route('partos.store'),
            'method' => 'POST',
        ];
    }

    private function normalizarLeitoes(array $leitoes): array
    {
        return collect($leitoes)
            ->filter(fn ($leitao): bool => is_array($leitao) && filled($leitao['tatuagem'] ?? null))
            ->map(fn (array $leitao): array => [
                'tatuagem' => trim($leitao['tatuagem']),
                'sexo' => $leitao['sexo'] ?? null,
                'peso_kg' => $leitao['peso_kg'] ?? null,
                'situacao_ao_nascer' => $leitao['situacao_ao_nascer'] ?? null,
            ])->values()->all();
    }

    private function rules(): array
    {
        return [
            'reproducao_id' => 'required|integer|exists:reproducoes,id',
            'matriz_id' => [
                'required',
                'integer',
                Rule::exists('animais', 'id')->where(fn ($query) => $query->where('sexo', 'F')->where('tipo', 'matriz')),
            ],
            'dt_inicio' => 'required|date',
            'tipo' => ['required', Rule::in(['normal', 'cesariana', 'aborto'])],
            'total_nascidos' => 'required|integer|min:1|max:100',
            'total_vivos' => 'required|integer|min:0|max:100',
            'total_natimortos' => 'required|integer|min:0|max:100',
            'total_mumificados' => 'required|integer|min:0|max:100',
            'leitoes' => 'required|array|min:1',
            'leitoes.*.tatuagem' => 'required|string|max:255|distinct',
            'leitoes.*.sexo' => ['required', Rule::in(['M', 'F'])],
            'leitoes.*.peso_kg' => 'nullable|numeric|min:0.001|max:10',
            'leitoes.*.situacao_ao_nascer' => ['required', Rule::in(['vivo', 'natimorto', 'mumificado'])],
        ];
    }
}
