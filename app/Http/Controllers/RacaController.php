<?php

namespace App\Http\Controllers;

use App\Models\Raca;
use Illuminate\Http\Request;

class RacaController extends Controller
{
    public function index()
    {
        $items = Raca::query()
            ->withCount('animais')
            ->orderBy('nome')
            ->get();

        return view('racas.index', [
            'items' => $items,
            'title' => 'Raças',
        ]);
    }

    public function create()
    {
        return view('racas.form', [
            'title' => 'Nova raça',
            'action' => route('racas.store'),
            'method' => 'POST',
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules());
        Raca::create($data);

        return redirect()
            ->route('racas.index')
            ->with('success', 'Raça criada com sucesso.');
    }

    public function show(Raca $raca)
    {
        $raca->loadCount('animais');

        return view('racas.show', [
            'item' => $raca,
            'title' => 'Raça',
        ]);
    }

    public function edit(Raca $raca)
    {
        return view('racas.form', [
            'item' => $raca,
            'title' => 'Editar raça',
            'action' => route('racas.update', $raca),
            'method' => 'PUT',
        ]);
    }

    public function update(Request $request, Raca $raca)
    {
        $data = $request->validate($this->rules());
        $raca->update($data);

        return redirect()
            ->route('racas.index')
            ->with('success', 'Raça atualizada com sucesso.');
    }

    public function destroy(Raca $raca)
    {
        $raca->delete();

        return redirect()
            ->route('racas.index')
            ->with('success', 'Raça excluída com sucesso.');
    }

    private function rules(): array
    {
        return [
            'nome' => 'required|string|max:255',
            'observacoes' => 'nullable|string|max:1000',
        ];
    }
}
