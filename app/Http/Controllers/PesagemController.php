<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use App\Models\Pesagem;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PesagemController extends Controller
{
    public function index()
    {
        $items = Pesagem::query()->with('animal')->orderByDesc('data')->orderByDesc('id')->get();

        return view('pesagens.index', ['items' => $items, 'fields' => $this->fields(), 'columns' => $this->columns(), 'title' => 'Pesagens', 'route' => 'pesagens']);
    }

    public function create()
    {
        return view('pesagens.form', $this->formData());
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules());
        $data = $this->calcularIndicadores($data);
        $data['usuario_id'] = auth()->id();
        Pesagem::create($data);

        return redirect()->route('pesagens.index')->with('success', 'Pesagem registrada com sucesso.');
    }

    public function show(Pesagem $pesagem)
    {
        $pesagem->load(['animal', 'usuario']);

        return view('pesagens.show', ['item' => $pesagem, 'fields' => $this->fields(), 'title' => 'Pesagem', 'route' => 'pesagens']);
    }

    public function edit(Pesagem $pesagem)
    {
        return view('pesagens.form', $this->formData($pesagem));
    }

    public function update(Request $request, Pesagem $pesagem)
    {
        $data = $request->validate($this->rules());
        $data = $this->calcularIndicadores($data, $pesagem);
        $pesagem->update($data);

        return redirect()->route('pesagens.index')->with('success', 'Pesagem atualizada com sucesso.');
    }

    public function destroy(Pesagem $pesagem)
    {
        $pesagem->delete();

        return redirect()->route('pesagens.index')->with('success', 'Pesagem excluída com sucesso.');
    }

    private function formData(?Pesagem $pesagem = null): array
    {
        $animais = Animal::query()->where('situacao', 'ativo')->orderBy('tatuagem')->get();
        $fields = $this->fields();
        $fields[0]['options'] = $animais->mapWithKeys(fn ($animal) => [$animal->id => "#{$animal->id} — {$animal->tatuagem}"])->all();

        return [
            'item' => $pesagem,
            'title' => $pesagem ? 'Editar pesagem' : 'Nova pesagem',
            'route' => 'pesagens',
            'action' => $pesagem ? route('pesagens.update', $pesagem) : route('pesagens.store'),
            'method' => $pesagem ? 'PUT' : 'POST',
            'animais' => $animais,
            'fields' => $fields,
        ];
    }

    private function calcularIndicadores(array $data, ?Pesagem $atual = null): array
    {
        $anterior = Pesagem::query()
            ->where('animal_id', $data['animal_id'])
            ->where('data', '<=', $data['data'])
            ->when($atual, fn ($query) => $query->where('id', '!=', $atual->id))
            ->orderByDesc('data')
            ->orderByDesc('id')
            ->first();

        $data['gp_kg'] = null;
        $data['gmd_kg'] = null;

        if ($anterior) {
            $ganho = (float) $data['peso_kg'] - (float) $anterior->peso_kg;
            $dias = $anterior->data->diffInDays($data['data']);
            $data['gp_kg'] = round($ganho, 3);
            $data['gmd_kg'] = $dias > 0 ? round($ganho / $dias, 3) : null;
        }

        return $data;
    }

    private function rules(): array
    {
        return [
            'animal_id' => 'required|integer|exists:animais,id',
            'fase' => ['required', Rule::in(['maternidade', 'creche', 'terminacao', 'reproducao', 'outro'])],
            'data' => 'required|date',
            'peso_kg' => 'required|numeric|min:0.001|max:1000',
        ];
    }

    private function fields(): array
    {
        return [
            ['name' => 'animal_id', 'label' => 'Animal', 'type' => 'select', 'required' => true, 'options' => []],
            ['name' => 'fase', 'label' => 'Fase', 'type' => 'select', 'required' => true, 'options' => ['maternidade' => 'Maternidade', 'creche' => 'Creche', 'terminacao' => 'Terminação', 'reproducao' => 'Reprodução', 'outro' => 'Outro']],
            ['name' => 'data', 'label' => 'Data', 'type' => 'date', 'required' => true],
            ['name' => 'peso_kg', 'label' => 'Peso (kg)', 'type' => 'number', 'required' => true],
            ['name' => 'gp_kg', 'label' => 'Ganho de peso (kg)', 'type' => 'number', 'required' => false],
            ['name' => 'gmd_kg', 'label' => 'GMD (kg/dia)', 'type' => 'number', 'required' => false],
        ];
    }

    private function columns(): array
    {
        return [
            ['key' => 'animal.tatuagem', 'label' => 'Animal'],
            ['key' => 'fase', 'label' => 'Fase'],
            ['key' => 'data', 'label' => 'Data'],
            ['key' => 'peso_kg', 'label' => 'Peso (kg)'],
            ['key' => 'gp_kg', 'label' => 'GP (kg)'],
            ['key' => 'gmd_kg', 'label' => 'GMD (kg/dia)'],
        ];
    }
}
