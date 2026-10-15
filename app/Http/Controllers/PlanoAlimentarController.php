<?php

namespace App\Http\Controllers;

use App\Models\PlanoAlimentar;
use Illuminate\Http\Request;

class PlanoAlimentarController extends Controller
{
    public function index()
    {
        $items = PlanoAlimentar::query()->withCount(['periodos', 'matrizes'])->orderBy('nome')->get();

        return view('planos-alimentares.index', ['items' => $items, 'fields' => $this->fields(), 'columns' => $this->columns(), 'title' => 'Fichas alimentares', 'route' => 'planos-alimentares']);
    }

    public function create()
    {
        return view('planos-alimentares.form', $this->formData());
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules());
        $data['usuario_id'] = auth()->id();
        PlanoAlimentar::create($data);

        return redirect()->route('planos-alimentares.index')->with('success', 'Ficha alimentar criada com sucesso.');
    }

    public function show(PlanoAlimentar $planoAlimentar)
    {
        $planoAlimentar->load(['periodos' => fn ($query) => $query->orderBy('dia_inicio')])->loadCount('matrizes');

        return view('planos-alimentares.show', ['item' => $planoAlimentar, 'fields' => $this->fields(), 'title' => 'Ficha alimentar', 'route' => 'planos-alimentares']);
    }

    public function edit(PlanoAlimentar $planoAlimentar)
    {
        return view('planos-alimentares.form', $this->formData($planoAlimentar));
    }

    public function update(Request $request, PlanoAlimentar $planoAlimentar)
    {
        $planoAlimentar->update($request->validate($this->rules()));

        return redirect()->route('planos-alimentares.index')->with('success', 'Ficha alimentar atualizada com sucesso.');
    }

    public function destroy(PlanoAlimentar $planoAlimentar)
    {
        $planoAlimentar->delete();

        return redirect()->route('planos-alimentares.index')->with('success', 'Ficha alimentar excluída com sucesso.');
    }

    private function formData(?PlanoAlimentar $item = null): array
    {
        return [
            'item' => $item,
            'title' => $item ? 'Editar ficha alimentar' : 'Nova ficha alimentar',
            'route' => 'planos-alimentares',
            'action' => $item ? route('planos-alimentares.update', $item) : route('planos-alimentares.store'),
            'method' => $item ? 'PUT' : 'POST',
            'fields' => $this->fields(),
        ];
    }

    private function fields(): array
    {
        return [
            ['name' => 'nome', 'label' => 'Nome', 'type' => 'text', 'required' => true],
            ['name' => 'observacoes', 'label' => 'Observações', 'type' => 'textarea', 'required' => false, 'full' => true],
            ['name' => 'ativo', 'label' => 'Ativa', 'type' => 'select', 'required' => true, 'options' => ['1' => 'Sim', '0' => 'Não']],
        ];
    }

    private function columns(): array
    {
        return [
            ['key' => 'nome', 'label' => 'Nome'],
            ['key' => 'observacoes', 'label' => 'Observações'],
            ['key' => 'periodos_count', 'label' => 'Módulos'],
            ['key' => 'matrizes_count', 'label' => 'Animais'],
            ['key' => 'ativo', 'label' => 'Ativa'],
        ];
    }

    private function rules(): array
    {
        return [
            'nome' => 'required|string|max:255',
            'observacoes' => 'nullable|string|max:1000',
            'ativo' => 'required|boolean',
        ];
    }
}
