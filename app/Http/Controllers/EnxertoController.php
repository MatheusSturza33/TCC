<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use App\Models\Enxerto;
use App\Models\Parto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class EnxertoController extends Controller
{
    public function index()
    {
        $items = Enxerto::query()
            ->with(['animal', 'maeOrigem', 'maeAdotiva', 'partoOrigem', 'partoAdotivo'])
            ->orderByDesc('data_enxerto')
            ->orderByDesc('id')
            ->get();

        return view('enxertos.index', [
            'items' => $items,
            'title' => 'Enxertos',
        ]);
    }

    public function create()
    {
        return view('enxertos.form', $this->formData());
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules());

        if ((int) $data['mae_origem_id'] === (int) $data['mae_adotiva_id']) {
            throw ValidationException::withMessages([
                'mae_adotiva_id' => 'A mãe adotiva deve ser diferente da mãe de origem.',
            ]);
        }

        $leitao = Animal::query()
            ->where('id', $data['animal_id'])
            ->where('tipo', 'leitao')
            ->where('situacao', 'ativo')
            ->first();

        $maeOrigem = Animal::query()
            ->where('id', $data['mae_origem_id'])
            ->where('sexo', 'F')
            ->where('tipo', 'matriz')
            ->first();

        $maeAdotiva = Animal::query()
            ->where('id', $data['mae_adotiva_id'])
            ->where('sexo', 'F')
            ->where('tipo', 'matriz')
            ->where('situacao', 'ativo')
            ->first();

        if (! $leitao) {
            throw ValidationException::withMessages(['animal_id' => 'O animal selecionado precisa ser um leitão ativo.']);
        }

        if (! $maeOrigem) {
            throw ValidationException::withMessages(['mae_origem_id' => 'A mãe de origem precisa ser uma matriz.']);
        }

        if (! $maeAdotiva) {
            throw ValidationException::withMessages(['mae_adotiva_id' => 'A mãe adotiva precisa ser uma matriz ativa.']);
        }

        $partoOrigem = Parto::query()
            ->whereKey($data['parto_origem_id'])
            ->where('matriz_id', $maeOrigem->id)
            ->where('situacao', 'realizado')
            ->first();

        $partoAdotivo = Parto::query()
            ->whereKey($data['parto_adotivo_id'])
            ->where('matriz_id', $maeAdotiva->id)
            ->where('situacao', 'realizado')
            ->first();

        if (! $partoOrigem) {
            throw ValidationException::withMessages(['parto_origem_id' => 'A ninhada de origem não pertence à matriz informada ou não foi realizada.']);
        }

        if (! $partoAdotivo) {
            throw ValidationException::withMessages(['parto_adotivo_id' => 'A ninhada adotiva não pertence à matriz informada ou não foi realizada.']);
        }

        if (! $partoOrigem->animais()->whereKey($leitao->id)->exists()) {
            throw ValidationException::withMessages(['animal_id' => 'O leitão não está vinculado à ninhada de origem selecionada.']);
        }

        if ($partoAdotivo->animais()->whereKey($leitao->id)->exists()) {
            throw ValidationException::withMessages(['animal_id' => 'O leitão já está vinculado à ninhada adotiva selecionada.']);
        }

        DB::transaction(function () use ($data, $leitao, $partoOrigem, $partoAdotivo, $maeAdotiva): void {
            $partoOrigem->animais()->detach($leitao->id);
            $partoAdotivo->animais()->attach($leitao->id, [
                'situacao_ao_nascer' => 'vivo',
            ]);

            $leitao->update([
                'id_mae' => $maeAdotiva->id,
                'id_localizacao_atual' => $maeAdotiva->id_localizacao_atual,
            ]);

            Enxerto::create([
                'animal_id' => $leitao->id,
                'mae_origem_id' => $data['mae_origem_id'],
                'mae_adotiva_id' => $data['mae_adotiva_id'],
                'parto_origem_id' => $partoOrigem->id,
                'parto_adotivo_id' => $partoAdotivo->id,
                'data_enxerto' => $data['data_enxerto'],
                'motivo' => $data['motivo'] ?? null,
                'usuario_id' => auth()->id(),
            ]);
        });

        return redirect()->route('enxertos.index')->with('success', 'Leitão transferido para a ninhada adotiva com sucesso.');
    }

    public function show(Enxerto $enxerto)
    {
        $enxerto->load(['animal', 'maeOrigem', 'maeAdotiva', 'partoOrigem', 'partoAdotivo', 'usuario']);

        return view('enxertos.show', [
            'item' => $enxerto,
            'title' => 'Detalhes do enxerto',
        ]);
    }

    private function formData(): array
    {
        return [
            'leitoes' => Animal::query()
                ->where('tipo', 'leitao')
                ->where('situacao', 'ativo')
                ->with('mae')
                ->orderBy('tatuagem')
                ->get(),
            'matrizes' => Animal::query()
                ->where('sexo', 'F')
                ->where('tipo', 'matriz')
                ->orderBy('tatuagem')
                ->get(),
            'partos' => Parto::query()
                ->where('situacao', 'realizado')
                ->with('matriz')
                ->orderByDesc('dt_inicio')
                ->get(),
            'title' => 'Registrar enxerto',
            'action' => route('enxertos.store'),
            'method' => 'POST',
        ];
    }

    private function rules(): array
    {
        return [
            'animal_id' => 'required|integer|exists:animais,id',
            'mae_origem_id' => [
                'required',
                'integer',
                Rule::exists('animais', 'id')->where(fn ($query) => $query->where('sexo', 'F')->where('tipo', 'matriz')),
            ],
            'mae_adotiva_id' => [
                'required',
                'integer',
                Rule::exists('animais', 'id')->where(fn ($query) => $query->where('sexo', 'F')->where('tipo', 'matriz')->where('situacao', 'ativo')),
            ],
            'parto_origem_id' => 'required|integer|exists:partos,id',
            'parto_adotivo_id' => 'required|integer|exists:partos,id',
            'data_enxerto' => 'required|date',
            'motivo' => 'nullable|string|max:1000',
        ];
    }
}
