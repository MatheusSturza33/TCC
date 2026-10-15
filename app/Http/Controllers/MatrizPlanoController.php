<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use App\Models\MatrizPlano;
use App\Models\PlanoAlimentar;
use Illuminate\Http\Request;

class MatrizPlanoController extends Controller
{
    public function index()
    {
        $items = MatrizPlano::query()->with(['animal', 'planoAlimentar'])->orderByDesc('data_inicio')->get();

        return view('matriz-plano.index', ['items' => $items, 'fields' => $this->fields(), 'columns' => $this->columns(), 'title' => 'Fichas atribuídas', 'route' => 'matriz-plano']);
    }

    public function create()
    {
        return view('matriz-plano.form', $this->formData());
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules());
        $data['usuario_id'] = auth()->id();
        MatrizPlano::create($data);

        return redirect()->route('matriz-plano.index')->with('success', 'Ficha atribuída ao animal com sucesso.');
    }

    public function show(MatrizPlano $matrizPlano)
    {
        $matrizPlano->load(['animal', 'planoAlimentar']);

        return view('matriz-plano.show', ['item' => $matrizPlano, 'fields' => $this->fields(), 'title' => 'Ficha atribuída', 'route' => 'matriz-plano']);
    }

    public function edit(MatrizPlano $matrizPlano)
    {
        return view('matriz-plano.form', $this->formData($matrizPlano));
    }

    public function update(Request $request, MatrizPlano $matrizPlano)
    {
        $matrizPlano->update($request->validate($this->rules()));

        return redirect()->route('matriz-plano.index')->with('success', 'Atribuição atualizada com sucesso.');
    }

    public function destroy(MatrizPlano $matrizPlano)
    {
        $matrizPlano->delete();

        return redirect()->route('matriz-plano.index')->with('success', 'Atribuição excluída com sucesso.');
    }

    private function formData(?MatrizPlano $item = null): array
    {
        $animais = Animal::query()->where('situacao', 'ativo')->orderBy('tatuagem')->get();
        $planos = PlanoAlimentar::query()->where('ativo', true)->orderBy('nome')->get();
        $fields = $this->fields();
        $fields[0]['options'] = $animais->mapWithKeys(fn ($animal) => [$animal->id => "#{$animal->id} — {$animal->tatuagem}"])->all();
        $fields[1]['options'] = $planos->pluck('nome', 'id')->all();

        return [
            'item' => $item,
            'title' => $item ? 'Editar ficha atribuída' : 'Atribuir ficha alimentar',
            'route' => 'matriz-plano',
            'action' => $item ? route('matriz-plano.update', $item) : route('matriz-plano.store'),
            'method' => $item ? 'PUT' : 'POST',
            'animais' => $animais,
            'planos' => $planos,
            'fields' => $fields,
        ];
    }

    private function fields(): array
    {
        return [
            ['name' => 'animal_id', 'label' => 'Animal', 'type' => 'select', 'required' => true, 'options' => []],
            ['name' => 'plano_alimentar_id', 'label' => 'Ficha alimentar', 'type' => 'select', 'required' => true, 'options' => []],
            ['name' => 'data_inicio', 'label' => 'Data de início', 'type' => 'date', 'required' => true],
            ['name' => 'data_fim', 'label' => 'Data de fim', 'type' => 'date', 'required' => false],
        ];
    }

    private function columns(): array
    {
        return [
            ['key' => 'animal.tatuagem', 'label' => 'Animal'],
            ['key' => 'planoAlimentar.nome', 'label' => 'Ficha alimentar'],
            ['key' => 'data_inicio', 'label' => 'Início'],
            ['key' => 'data_fim', 'label' => 'Fim'],
        ];
    }

    private function rules(): array
    {
        return [
            'animal_id' => 'required|integer|exists:animais,id',
            'plano_alimentar_id' => 'required|integer|exists:planos_alimentares,id',
            'data_inicio' => 'required|date',
            'data_fim' => 'nullable|date|after_or_equal:data_inicio',
        ];
    }
}
