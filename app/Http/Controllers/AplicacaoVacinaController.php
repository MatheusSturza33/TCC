<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use App\Models\AplicacaoVacina;
use App\Models\Vacina;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AplicacaoVacinaController extends Controller
{
    public function index()
    {
        $items = AplicacaoVacina::query()
            ->with(['vacina', 'animal'])
            ->orderByDesc('data_aplicacao')
            ->orderByDesc('id')
            ->get();

        return view('aplicacoes-vacina.index', ['items' => $items, 'title' => 'Aplicações de vacinas']);
    }

    public function create()
    {
        return view('aplicacoes-vacina.form', $this->formData());
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules());
        $data['proxima_dose'] = $this->calcularProximaDose($data['vacina_id'], $data['data_aplicacao']);
        $data['responsavel_id'] = Auth::id();
        AplicacaoVacina::create($data);

        return redirect()->route('aplicacoes-vacina.index')->with('success', 'Aplicação registrada com sucesso.');
    }

    public function show(AplicacaoVacina $aplicacaoVacina)
    {
        $aplicacaoVacina->load(['vacina', 'animal', 'responsavel']);

        return view('aplicacoes-vacina.show', ['item' => $aplicacaoVacina, 'title' => 'Aplicação de vacina']);
    }

    public function edit(AplicacaoVacina $aplicacaoVacina)
    {
        return view('aplicacoes-vacina.form', $this->formData($aplicacaoVacina));
    }

    public function update(Request $request, AplicacaoVacina $aplicacaoVacina)
    {
        $data = $request->validate($this->rules());
        $data['proxima_dose'] = $this->calcularProximaDose($data['vacina_id'], $data['data_aplicacao']);
        $aplicacaoVacina->update($data);

        return redirect()->route('aplicacoes-vacina.index')->with('success', 'Aplicação atualizada com sucesso.');
    }

    public function destroy(AplicacaoVacina $aplicacaoVacina)
    {
        $aplicacaoVacina->delete();

        return redirect()->route('aplicacoes-vacina.index')->with('success', 'Aplicação excluída com sucesso.');
    }

    private function formData(?AplicacaoVacina $item = null): array
    {
        return [
            'item' => $item,
            'title' => $item ? 'Editar aplicação' : 'Nova aplicação de vacina',
            'action' => $item ? route('aplicacoes-vacina.update', $item) : route('aplicacoes-vacina.store'),
            'method' => $item ? 'PUT' : 'POST',
            'vacinas' => Vacina::query()->where('ativa', true)->orderBy('nome')->get(),
            'animais' => Animal::query()->where('situacao', 'ativo')->orderBy('tatuagem')->get(),
        ];
    }

    private function calcularProximaDose(int $vacinaId, string $data): ?string
    {
        $intervalo = (int) Vacina::whereKey($vacinaId)->value('intervalo_reforco');

        return $intervalo > 0 ? Carbon::parse($data)->addDays($intervalo)->toDateString() : null;
    }

    private function rules(): array
    {
        return [
            'vacina_id' => 'required|integer|exists:vacinas,id',
            'animal_id' => 'required|integer|exists:animais,id',
            'data_aplicacao' => 'required|date',
            'lote_vacina' => 'nullable|string|max:255',
        ];
    }
}
