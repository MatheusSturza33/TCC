<?php

namespace App\Http\Controllers;

use App\Models\PeriodoPlano;
use App\Models\PlanoAlimentar;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PeriodoPlanoController extends Controller
{
    public function index()
    {
        $items = PeriodoPlano::query()->with('planoAlimentar')->orderBy('plano_alimentar_id')->orderBy('dia_inicio')->get();

        return view('periodos-plano.index', ['items' => $items, 'fields' => $this->fields(), 'columns' => $this->columns(), 'title' => 'Módulos das fichas alimentares', 'route' => 'periodos-plano']);
    }

    public function create()
    {
        return view('periodos-plano.form', $this->formData());
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules());
        $this->validarConflito($data);
        PeriodoPlano::create($data);

        return redirect()->route('periodos-plano.index')->with('success', 'Módulo alimentar criado com sucesso.');
    }

    public function show(PeriodoPlano $periodoPlano)
    {
        $periodoPlano->load('planoAlimentar');

        return view('periodos-plano.show', ['item' => $periodoPlano, 'fields' => $this->fields(), 'title' => 'Módulo alimentar', 'route' => 'periodos-plano']);
    }

    public function edit(PeriodoPlano $periodoPlano)
    {
        return view('periodos-plano.form', $this->formData($periodoPlano));
    }

    public function update(Request $request, PeriodoPlano $periodoPlano)
    {
        $data = $request->validate($this->rules());
        $this->validarConflito($data, $periodoPlano);
        $periodoPlano->update($data);

        return redirect()->route('periodos-plano.index')->with('success', 'Módulo alimentar atualizado com sucesso.');
    }

    public function destroy(PeriodoPlano $periodoPlano)
    {
        $periodoPlano->delete();

        return redirect()->route('periodos-plano.index')->with('success', 'Módulo alimentar excluído com sucesso.');
    }

    private function formData(?PeriodoPlano $item = null): array
    {
        $planos = PlanoAlimentar::query()->where('ativo', true)->orderBy('nome')->get();
        $fields = $this->fields();
        $fields[0]['options'] = $planos->pluck('nome', 'id')->all();

        return [
            'item' => $item,
            'title' => $item ? 'Editar módulo alimentar' : 'Novo módulo alimentar',
            'route' => 'periodos-plano',
            'action' => $item ? route('periodos-plano.update', $item) : route('periodos-plano.store'),
            'method' => $item ? 'PUT' : 'POST',
            'planos' => $planos,
            'fields' => $fields,
        ];
    }

    private function validarConflito(array $data, ?PeriodoPlano $atual = null): void
    {
        $conflito = PeriodoPlano::query()
            ->where('plano_alimentar_id', $data['plano_alimentar_id'])
            ->when($atual, fn ($query) => $query->where('id', '!=', $atual->id))
            ->where('dia_inicio', '<=', $data['dia_fim'])
            ->where('dia_fim', '>=', $data['dia_inicio'])
            ->exists();

        if ($conflito) {
            throw ValidationException::withMessages([
                'dia_inicio' => 'Este período se sobrepõe a outro módulo da mesma ficha alimentar.',
            ]);
        }
    }

    private function rules(): array
    {
        return [
            'plano_alimentar_id' => 'required|integer|exists:planos_alimentares,id',
            'dia_inicio' => 'required|integer|min:0|max:365',
            'dia_fim' => 'required|integer|gte:dia_inicio|max:365',
            'quantidade_kg_dia' => 'required|numeric|gt:0|max:100',
        ];
    }

    private function fields(): array
    {
        return [
            ['name' => 'plano_alimentar_id', 'label' => 'Ficha alimentar', 'type' => 'select', 'required' => true, 'options' => []],
            ['name' => 'dia_inicio', 'label' => 'Dia inicial', 'type' => 'number', 'required' => true],
            ['name' => 'dia_fim', 'label' => 'Dia final', 'type' => 'number', 'required' => true],
            ['name' => 'quantidade_kg_dia', 'label' => 'Quantidade (kg/dia)', 'type' => 'number', 'required' => true],
        ];
    }

    private function columns(): array
    {
        return [
            ['key' => 'planoAlimentar.nome', 'label' => 'Ficha alimentar'],
            ['key' => 'dia_inicio', 'label' => 'Dia inicial'],
            ['key' => 'dia_fim', 'label' => 'Dia final'],
            ['key' => 'quantidade_kg_dia', 'label' => 'kg/dia'],
        ];
    }
}
