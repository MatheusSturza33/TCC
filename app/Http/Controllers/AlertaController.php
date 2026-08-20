<?php
namespace App\Http\Controllers;
use App\Models\Alerta;
use Illuminate\Http\Request;

class AlertaController extends Controller
{
    private function fields(): array
    {
        return [
            ['name'=>'tipo','label'=>'Tipo','type'=>'text','required'=>true],
            ['name'=>'referencia_id','label'=>'ID da referência','type'=>'number','required'=>false],
            ['name'=>'data_prevista','label'=>'Data prevista','type'=>'date','required'=>true],
            ['name'=>'situacao','label'=>'Situação','type'=>'text','required'=>true],
            ['name'=>'usuario_id','label'=>'ID do usuário','type'=>'number','required'=>false],
        ];
    }
    private function columns(): array { return array_map(fn($f) => ['key'=>$f['name'],'label'=>$f['label']], $this->fields()); }
    public function index() { $items = Alerta::query()->orderByDesc('id')->get(); return view('alertas.index',['items'=>$items,'fields'=>$this->fields(),'columns'=>$this->columns(),'title'=>'Alerta','route'=>'alertas']); }
    public function create() { return view('alertas.form',['fields'=>$this->fields(),'title'=>'Novo Alerta','route'=>'alertas','action'=>route('alertas.store'),'method'=>'POST']); }
    public function store(Request $request) { $data=$request->validate($this->rules()); Alerta::create($data); return redirect()->route('alertas.index')->with('success','Alerta criado com sucesso.'); }
    public function show(Alerta $alerta) { return view('alertas.show',['item'=>$alerta,'fields'=>$this->fields(),'title'=>'Alerta','route'=>'alertas']); }
    public function edit(Alerta $alerta) { return view('alertas.form',['item'=>$alerta,'fields'=>$this->fields(),'title'=>'Editar Alerta','route'=>'alertas','action'=>route('alertas.update',$alerta),'method'=>'PUT']); }
    public function update(Request $request, Alerta $alerta) { $data=$request->validate($this->rules()); $alerta->update($data); return redirect()->route('alertas.index')->with('success','Alerta atualizado com sucesso.'); }
    public function destroy(Alerta $alerta) { $alerta->delete(); return redirect()->route('alertas.index')->with('success','Alerta excluído com sucesso.'); }
    private function rules(): array { return [
            'tipo'=>'required|string|max:1000',
            'referencia_id'=>'nullable|integer',
            'data_prevista'=>'required|date',
            'situacao'=>'required|string|max:1000',
            'usuario_id'=>'nullable|integer',
        ];
    }
}
