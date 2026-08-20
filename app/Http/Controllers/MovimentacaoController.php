<?php
namespace App\Http\Controllers;
use App\Models\Movimentacao;
use Illuminate\Http\Request;

class MovimentacaoController extends Controller
{
    private function fields(): array
    {
        return [
            ['name'=>'animal_id','label'=>'ID do animal','type'=>'number','required'=>true],
            ['name'=>'localizacao_destino_id','label'=>'ID da localização destino','type'=>'number','required'=>true],
            ['name'=>'data','label'=>'Data','type'=>'date','required'=>true],
            ['name'=>'evento_origem','label'=>'Evento de origem','type'=>'text','required'=>false],
        ];
    }
    private function columns(): array { return array_map(fn($f) => ['key'=>$f['name'],'label'=>$f['label']], $this->fields()); }
    public function index() { $items = Movimentacao::query()->orderByDesc('id')->get(); return view('movimentacoes.index',['items'=>$items,'fields'=>$this->fields(),'columns'=>$this->columns(),'title'=>'Movimentação','route'=>'movimentacoes']); }
    public function create() { return view('movimentacoes.form',['fields'=>$this->fields(),'title'=>'Novo Movimentação','route'=>'movimentacoes','action'=>route('movimentacoes.store'),'method'=>'POST']); }
    public function store(Request $request) { $data=$request->validate($this->rules()); Movimentacao::create($data); return redirect()->route('movimentacoes.index')->with('success','Movimentação criado com sucesso.'); }
    public function show(Movimentacao $movimentacao) { return view('movimentacoes.show',['item'=>$movimentacao,'fields'=>$this->fields(),'title'=>'Movimentação','route'=>'movimentacoes']); }
    public function edit(Movimentacao $movimentacao) { return view('movimentacoes.form',['item'=>$movimentacao,'fields'=>$this->fields(),'title'=>'Editar Movimentação','route'=>'movimentacoes','action'=>route('movimentacoes.update',$movimentacao),'method'=>'PUT']); }
    public function update(Request $request, Movimentacao $movimentacao) { $data=$request->validate($this->rules()); $movimentacao->update($data); return redirect()->route('movimentacoes.index')->with('success','Movimentação atualizado com sucesso.'); }
    public function destroy(Movimentacao $movimentacao) { $movimentacao->delete(); return redirect()->route('movimentacoes.index')->with('success','Movimentação excluído com sucesso.'); }
    private function rules(): array { return [
            'animal_id'=>'required|integer',
            'localizacao_destino_id'=>'required|integer',
            'data'=>'required|date',
            'evento_origem'=>'nullable|string|max:1000',
        ];
    }
}
