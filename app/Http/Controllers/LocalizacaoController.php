<?php

namespace App\Http\Controllers;

use App\Models\Localizacao;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class LocalizacaoController extends Controller
{
    public function index()
    {
        $items = Localizacao::query()
            ->withCount('animais')
            ->orderBy('tipo')
            ->orderBy('nome')
            ->get();

        return view('localizacoes.index', [
            'items' => $items,
            'columns' => $this->columns(),
            'title' => 'Localizações',
            'route' => 'localizacoes',
        ]);
    }

    public function create()
    {
        return view('localizacoes.form', $this->formData());
    }

    public function store(Request $request)
    {
        Localizacao::create($request->validate($this->rules()));

        return redirect()->route('localizacoes.index')->with('success', 'Localização cadastrada com sucesso.');
    }

    public function show(Localizacao $localizacao)
    {
        $localizacao->loadCount('animais');

        return view('localizacoes.show', [
            'item' => $localizacao,
            'title' => 'Detalhes da localização',
            'route' => 'localizacoes',
        ]);
    }

    public function edit(Localizacao $localizacao)
    {
        return view('localizacoes.form', $this->formData($localizacao));
    }

    public function update(Request $request, Localizacao $localizacao)
    {
        $data = $request->validate($this->rules($localizacao));
        $this->validarCapacidade($data['capacidade'] ?? null, $localizacao);
        $localizacao->update($data);

        return redirect()->route('localizacoes.index')->with('success', 'Localização atualizada com sucesso.');
    }

    public function destroy(Localizacao $localizacao)
    {
        if ($localizacao->animais()->exists()) {
            return redirect()->route('localizacoes.index')->with('error', 'Não é possível excluir uma localização que possui animais vinculados.');
        }

        $localizacao->delete();

        return redirect()->route('localizacoes.index')->with('success', 'Localização excluída com sucesso.');
    }

    private function formData(?Localizacao $localizacao = null): array
    {
        return [
            'item' => $localizacao,
            'fields' => $this->fields(),
            'title' => $localizacao ? 'Editar localização' : 'Nova localização',
            'route' => 'localizacoes',
            'action' => $localizacao ? route('localizacoes.update', $localizacao) : route('localizacoes.store'),
            'method' => $localizacao ? 'PUT' : 'POST',
        ];
    }

    private function fields(): array
    {
        return [
            ['name' => 'nome', 'label' => 'Nome da localização', 'type' => 'text', 'required' => true],
            ['name' => 'tipo', 'label' => 'Tipo', 'type' => 'select', 'required' => true, 'options' => [
                'maternidade' => 'Maternidade',
                'gestacao' => 'Gestação',
                'creche' => 'Creche',
                'terminacao' => 'Terminação',
                'quarentena' => 'Quarentena',
                'outro' => 'Outro',
            ]],
            ['name' => 'capacidade', 'label' => 'Capacidade de animais', 'type' => 'number', 'required' => false],
        ];
    }

    private function columns(): array
    {
        return [
            ['key' => 'nome', 'label' => 'Nome'],
            ['key' => 'tipo', 'label' => 'Tipo'],
            ['key' => 'capacidade', 'label' => 'Capacidade'],
            ['key' => 'animais_count', 'label' => 'Animais alojados'],
        ];
    }

    private function rules(?Localizacao $localizacao = null): array
    {
        return [
            'nome' => [
                'required',
                'string',
                'max:255',
                Rule::unique('localizacoes', 'nome')->ignore($localizacao),
            ],
            'tipo' => ['required', Rule::in(['maternidade', 'gestacao', 'creche', 'terminacao', 'quarentena', 'outro'])],
            'capacidade' => 'nullable|integer|min:1|max:100000',
        ];
    }

    private function validarCapacidade(?int $capacidade, Localizacao $localizacao): void
    {
        $ocupacao = $localizacao->animais()->count();

        if ($capacidade !== null && $capacidade < $ocupacao) {
            throw ValidationException::withMessages([
                'capacidade' => "A capacidade não pode ser menor que a ocupação atual de {$ocupacao} animal(is).",
            ]);
        }
    }
}
