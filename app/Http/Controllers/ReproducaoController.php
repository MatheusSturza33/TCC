<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use App\Models\Reproducao;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ReproducaoController extends Controller
{
    public function index()
    {
        $items = Reproducao::query()
            ->with(['femea', 'reprodutor', 'grupoMonta'])
            ->withCount('servicosReprodutivos')
            ->orderByDesc('data_primeiro_servico')
            ->orderByDesc('id')
            ->get();

        return view('reproducoes.index', [
            'items' => $items,
            'title' => 'Reprodução',
        ]);
    }

    public function create()
    {
        return view('reproducoes.form', $this->formData());
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules());
        $data['situacao'] = 'aberta';
        $data['previsao_parto'] = $this->calcularPrevisaoParto($data['data_primeiro_servico']);
        $data['usuario_id'] = auth()->id();

        Reproducao::create($data);

        return redirect()->route('reproducoes.index')->with('success', 'Tentativa de reprodução lançada com sucesso.');
    }

    public function show(Reproducao $reproducao)
    {
        $reproducao->load([
            'femea',
            'reprodutor',
            'grupoMonta',
            'servicosReprodutivos' => fn ($query) => $query->with('reprodutor')->orderBy('numero_tentativa'),
        ]);

        return view('reproducoes.show', [
            'item' => $reproducao,
            'title' => 'Detalhes da reprodução',
        ]);
    }

    public function edit(Reproducao $reproducao)
    {
        if ($reproducao->situacao !== 'aberta') {
            return redirect()->route('reproducoes.show', $reproducao)
                ->with('error', 'Somente tentativas abertas podem ser editadas.');
        }

        return view('reproducoes.form', $this->formData($reproducao));
    }

    public function update(Request $request, Reproducao $reproducao)
    {
        if ($reproducao->situacao !== 'aberta') {
            return redirect()->route('reproducoes.show', $reproducao)
                ->with('error', 'Somente tentativas abertas podem ser editadas.');
        }

        $data = $request->validate($this->rules());
        $data['previsao_parto'] = $this->calcularPrevisaoParto($data['data_primeiro_servico']);
        $reproducao->update($data);

        return redirect()->route('reproducoes.show', $reproducao)
            ->with('success', 'Tentativa de reprodução atualizada com sucesso.');
    }

    public function confirmar(Reproducao $reproducao)
    {
        if ($reproducao->situacao !== 'aberta') {
            return redirect()->route('reproducoes.show', $reproducao)
                ->with('error', 'Esta reprodução não está mais aberta para confirmação.');
        }

        DB::transaction(function () use ($reproducao): void {
            $reproducao->update([
                'situacao' => 'confirmada',
                'previsao_parto' => $reproducao->previsao_parto
                    ?? $this->calcularPrevisaoParto($reproducao->data_primeiro_servico->format('Y-m-d')),
            ]);

            $reproducao->femea()->update([
                'estado_reprodutivo' => 'gestante',
            ]);
        });

        return redirect()->route('reproducoes.show', $reproducao)
            ->with('success', 'Gestação confirmada. A matriz foi marcada como gestante.');
    }

    public function cancelar(Reproducao $reproducao)
    {
        if ($reproducao->situacao !== 'aberta') {
            return redirect()->route('reproducoes.show', $reproducao)
                ->with('error', 'Somente tentativas abertas podem ser canceladas.');
        }

        $reproducao->update(['situacao' => 'cancelada']);

        return redirect()->route('reproducoes.show', $reproducao)
            ->with('success', 'Tentativa de reprodução cancelada.');
    }

    public function destroy(Reproducao $reproducao)
    {
        $reproducao->delete();

        return redirect()->route('reproducoes.index')->with('success', 'Registro de reprodução excluído com sucesso.');
    }

    private function formData(?Reproducao $reproducao = null): array
    {
        $femeas = Animal::query()
            ->where('sexo', 'F')
            ->where('tipo', 'matriz')
            ->where('situacao', 'ativo')
            ->orderBy('tatuagem')
            ->get();

        $reprodutores = Animal::query()
            ->where('sexo', 'M')
            ->where('tipo', 'reprodutor')
            ->where('situacao', 'ativo')
            ->orderBy('tatuagem')
            ->get();

        return [
            'item' => $reproducao,
            'femeas' => $femeas,
            'reprodutores' => $reprodutores,
            'title' => $reproducao ? 'Editar tentativa de reprodução' : 'Lançar tentativa de reprodução',
            'action' => $reproducao ? route('reproducoes.update', $reproducao) : route('reproducoes.store'),
            'method' => $reproducao ? 'PUT' : 'POST',
        ];
    }

    private function rules(): array
    {
        return [
            'femea_id' => [
                'required',
                'integer',
                Rule::exists('animais', 'id')->where(fn ($query) => $query
                    ->where('sexo', 'F')
                    ->where('tipo', 'matriz')
                    ->where('situacao', 'ativo')),
            ],
            'grupo_monta_id' => 'nullable|integer|exists:grupos_monta,id',
            'data_primeiro_servico' => 'required|date',
            'reprodutor_id' => [
                'nullable',
                'integer',
                Rule::exists('animais', 'id')->where(fn ($query) => $query
                    ->where('sexo', 'M')
                    ->where('tipo', 'reprodutor')
                    ->where('situacao', 'ativo')),
            ],
        ];
    }

    private function calcularPrevisaoParto(string $dataServico): string
    {
        return Carbon::parse($dataServico)->addDays(114)->format('Y-m-d');
    }
}
