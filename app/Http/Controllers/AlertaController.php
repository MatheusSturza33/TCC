<?php

namespace App\Http\Controllers;

use App\Models\Alerta;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AlertaController extends Controller
{
    public function index()
    {
        $items = Alerta::query()->orderByRaw("CASE situacao WHEN 'pendente' THEN 1 WHEN 'concluido' THEN 2 ELSE 3 END")->orderBy('data_prevista')->get();

        return view('alertas.index', ['items' => $items, 'fields' => $this->fields(), 'columns' => $this->columns(), 'title' => 'Alertas', 'route' => 'alertas']);
    }

    public function create()
    {
        return view('alertas.form', $this->formData());
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules());
        $data['usuario_id'] = auth()->id();
        Alerta::create($data);

        return redirect()->route('alertas.index')->with('success', 'Alerta criado com sucesso.');
    }

    public function show(Alerta $alerta)
    {
        $alerta->load('usuario');

        return view('alertas.show', ['item' => $alerta, 'fields' => $this->fields(), 'title' => 'Alerta', 'route' => 'alertas']);
    }

    public function edit(Alerta $alerta)
    {
        return view('alertas.form', $this->formData($alerta));
    }

    public function update(Request $request, Alerta $alerta)
    {
        $alerta->update($request->validate($this->rules()));

        return redirect()->route('alertas.index')->with('success', 'Alerta atualizado com sucesso.');
    }

    public function destroy(Alerta $alerta)
    {
        $alerta->delete();

        return redirect()->route('alertas.index')->with('success', 'Alerta excluído com sucesso.');
    }

    private function formData(?Alerta $alerta = null): array
    {
        return [
            'item' => $alerta,
            'title' => $alerta ? 'Editar alerta' : 'Novo alerta',
            'route' => 'alertas',
            'action' => $alerta ? route('alertas.update', $alerta) : route('alertas.store'),
            'method' => $alerta ? 'PUT' : 'POST',
            'fields' => $this->fields(),
        ];
    }

    private function fields(): array
    {
        return [
            ['name' => 'tipo', 'label' => 'Tipo', 'type' => 'select', 'required' => true, 'options' => ['parto' => 'Parto', 'desmame' => 'Desmame', 'vacina' => 'Vacina', 'consanguinidade' => 'Consanguinidade', 'outro' => 'Outro']],
            ['name' => 'referencia_id', 'label' => 'ID da referência', 'type' => 'number', 'required' => false],
            ['name' => 'data_prevista', 'label' => 'Data prevista', 'type' => 'date', 'required' => true],
            ['name' => 'situacao', 'label' => 'Situação', 'type' => 'select', 'required' => true, 'options' => ['pendente' => 'Pendente', 'concluido' => 'Concluído', 'cancelado' => 'Cancelado']],
        ];
    }

    private function columns(): array
    {
        return [
            ['key' => 'tipo', 'label' => 'Tipo'],
            ['key' => 'referencia_id', 'label' => 'Referência'],
            ['key' => 'data_prevista', 'label' => 'Data prevista'],
            ['key' => 'situacao', 'label' => 'Situação'],
        ];
    }

    private function rules(): array
    {
        return [
            'tipo' => ['required', Rule::in(['parto', 'desmame', 'vacina', 'consanguinidade', 'outro'])],
            'referencia_id' => 'nullable|integer|min:1',
            'data_prevista' => 'required|date',
            'situacao' => ['required', Rule::in(['pendente', 'concluido', 'cancelado'])],
        ];
    }
}
