<?php

namespace App\Http\Controllers;

use App\Models\Vacina;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class VacinaController extends Controller
{
    public function index()
    {
        $items = Vacina::query()->withCount('aplicacoes')->orderBy('nome')->get();

        return view('vacinas.index', [
            'items' => $items,
            'fields' => $this->fields(),
            'columns' => $this->columns(),
            'title' => 'Vacinas',
            'route' => 'vacinas',
        ]);
    }

    public function create()
    {
        return view('vacinas.form', $this->formData());
    }

    public function store(Request $request)
    {
        Vacina::create($request->validate($this->rules()));

        return redirect()->route('vacinas.index')->with('success', 'Vacina cadastrada com sucesso.');
    }

    public function show(Vacina $vacina)
    {
        $vacina->loadCount('aplicacoes');

        return view('vacinas.show', ['item' => $vacina, 'fields' => $this->fields(), 'title' => 'Vacina', 'route' => 'vacinas']);
    }

    public function edit(Vacina $vacina)
    {
        return view('vacinas.form', $this->formData($vacina));
    }

    public function update(Request $request, Vacina $vacina)
    {
        $vacina->update($request->validate($this->rules()));

        return redirect()->route('vacinas.index')->with('success', 'Vacina atualizada com sucesso.');
    }

    public function destroy(Vacina $vacina)
    {
        $vacina->delete();

        return redirect()->route('vacinas.index')->with('success', 'Vacina excluída com sucesso.');
    }

    private function formData(?Vacina $vacina = null): array
    {
        return [
            'item' => $vacina,
            'title' => $vacina ? 'Editar vacina' : 'Nova vacina',
            'route' => 'vacinas',
            'action' => $vacina ? route('vacinas.update', $vacina) : route('vacinas.store'),
            'method' => $vacina ? 'PUT' : 'POST',
            'fields' => $this->fields(),
        ];
    }

    private function fields(): array
    {
        return [
            ['name' => 'nome', 'label' => 'Nome', 'type' => 'text', 'required' => true],
            ['name' => 'categoria_animal', 'label' => 'Categoria animal', 'type' => 'select', 'required' => true, 'options' => ['matriz' => 'Matriz', 'reprodutor' => 'Reprodutor', 'leitao' => 'Leitão', 'todos' => 'Todos']],
            ['name' => 'fase_aplicacao', 'label' => 'Fase de aplicação', 'type' => 'text', 'required' => false],
            ['name' => 'intervalo_reforco', 'label' => 'Intervalo de reforço (dias)', 'type' => 'number', 'required' => true],
            ['name' => 'ativa', 'label' => 'Ativa', 'type' => 'select', 'required' => true, 'options' => ['1' => 'Sim', '0' => 'Não']],
        ];
    }

    private function columns(): array
    {
        return [
            ['key' => 'nome', 'label' => 'Nome'],
            ['key' => 'categoria_animal', 'label' => 'Categoria'],
            ['key' => 'fase_aplicacao', 'label' => 'Fase'],
            ['key' => 'intervalo_reforco', 'label' => 'Reforço (dias)'],
            ['key' => 'aplicacoes_count', 'label' => 'Aplicações'],
            ['key' => 'ativa', 'label' => 'Ativa'],
        ];
    }

    private function rules(): array
    {
        return [
            'nome' => 'required|string|max:255',
            'categoria_animal' => ['required', Rule::in(['matriz', 'reprodutor', 'leitao', 'todos'])],
            'fase_aplicacao' => 'nullable|string|max:255',
            'intervalo_reforco' => 'required|integer|min:0|max:3650',
            'ativa' => 'required|boolean',
        ];
    }
}
