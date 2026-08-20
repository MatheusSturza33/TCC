<?php
namespace App\Http\Controllers;
use App\Models\Enxerto;
use Illuminate\Http\Request;

class EnxertoController extends Controller
{
    private function fields(): array
    {
        return [
            ['name'=>'animal_id','label'=>'ID do leitão','type'=>'number','required'=>true],
            ['name'=>'mae_origem_id','label'=>'ID da mãe de origem','type'=>'number','required'=>true],
            ['name'=>'mae_adotiva_id','label'=>'ID da mãe adotiva','type'=>'number','required'=>true],
            ['name'=>'data_enxerto','label'=>'Data do enxerto','type'=>'date','required'=>true],
            ['name'=>'motivo','label'=>'Motivo','type'=>'textarea','required'=>false,'full'=>true],
            ['name'=>'usuario_id','label'=>'ID do usuário','type'=>'number','required'=>false],
        ];
    }
    private function columns(): array { return array_map(fn($f) => ['key'=>$f['name'],'label'=>$f['label']], $this->fields()); }
    public function index() { $items = Enxerto::query()->orderByDesc('id')->get(); return view('enxertos.index',['items'=>$items,'fields'=>$this->fields(),'columns'=>$this->columns(),'title'=>'Enxerto','route'=>'enxertos']); }
    public function create() { return view('enxertos.form',['fields'=>$this->fields(),'title'=>'Novo Enxerto','route'=>'enxertos','action'=>route('enxertos.store'),'method'=>'POST']); }
    public function store(Request $request) { $data=$request->validate($this->rules()); Enxerto::create($data); return redirect()->route('enxertos.index')->with('success','Enxerto criado com sucesso.'); }
    public function show(Enxerto $enxerto) { return view('enxertos.show',['item'=>$enxerto,'fields'=>$this->fields(),'title'=>'Enxerto','route'=>'enxertos']); }
    public function edit(Enxerto $enxerto) { return view('enxertos.form',['item'=>$enxerto,'fields'=>$this->fields(),'title'=>'Editar Enxerto','route'=>'enxertos','action'=>route('enxertos.update',$enxerto),'method'=>'PUT']); }
    public function update(Request $request, Enxerto $enxerto) { $data=$request->validate($this->rules()); $enxerto->update($data); return redirect()->route('enxertos.index')->with('success','Enxerto atualizado com sucesso.'); }
    public function destroy(Enxerto $enxerto) { $enxerto->delete(); return redirect()->route('enxertos.index')->with('success','Enxerto excluído com sucesso.'); }
    private function rules(): array { return [
            'animal_id'=>'required|integer',
            'mae_origem_id'=>'required|integer',
            'mae_adotiva_id'=>'required|integer',
            'data_enxerto'=>'required|date',
            'motivo'=>'nullable|string|max:1000',
            'usuario_id'=>'nullable|integer',
        ];
    }
}
