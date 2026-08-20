<?php
namespace App\Http\Controllers;
use App\Models\Pesagem;
use Illuminate\Http\Request;

class PesagemController extends Controller
{
    private function fields(): array
    {
        return [
            ['name'=>'animal_id','label'=>'ID do animal','type'=>'number','required'=>true],
            ['name'=>'fase','label'=>'Fase','type'=>'text','required'=>true],
            ['name'=>'data','label'=>'Data','type'=>'date','required'=>true],
            ['name'=>'peso_kg','label'=>'Peso (kg)','type'=>'number','required'=>true],
            ['name'=>'gp_kg','label'=>'Ganho de peso (kg)','type'=>'number','required'=>false],
            ['name'=>'gmd_kg','label'=>'GMD (kg)','type'=>'number','required'=>false],
            ['name'=>'usuario_id','label'=>'ID do usuário','type'=>'number','required'=>false],
        ];
    }
    private function columns(): array { return array_map(fn($f) => ['key'=>$f['name'],'label'=>$f['label']], $this->fields()); }
    public function index() { $items = Pesagem::query()->orderByDesc('id')->get(); return view('pesagens.index',['items'=>$items,'fields'=>$this->fields(),'columns'=>$this->columns(),'title'=>'Pesagem','route'=>'pesagens']); }
    public function create() { return view('pesagens.form',['fields'=>$this->fields(),'title'=>'Novo Pesagem','route'=>'pesagens','action'=>route('pesagens.store'),'method'=>'POST']); }
    public function store(Request $request) { $data=$request->validate($this->rules()); Pesagem::create($data); return redirect()->route('pesagens.index')->with('success','Pesagem criado com sucesso.'); }
    public function show(Pesagem $pesagem) { return view('pesagens.show',['item'=>$pesagem,'fields'=>$this->fields(),'title'=>'Pesagem','route'=>'pesagens']); }
    public function edit(Pesagem $pesagem) { return view('pesagens.form',['item'=>$pesagem,'fields'=>$this->fields(),'title'=>'Editar Pesagem','route'=>'pesagens','action'=>route('pesagens.update',$pesagem),'method'=>'PUT']); }
    public function update(Request $request, Pesagem $pesagem) { $data=$request->validate($this->rules()); $pesagem->update($data); return redirect()->route('pesagens.index')->with('success','Pesagem atualizado com sucesso.'); }
    public function destroy(Pesagem $pesagem) { $pesagem->delete(); return redirect()->route('pesagens.index')->with('success','Pesagem excluído com sucesso.'); }
    private function rules(): array { return [
            'animal_id'=>'required|integer',
            'fase'=>'required|string|max:1000',
            'data'=>'required|date',
            'peso_kg'=>'required|integer',
            'gp_kg'=>'nullable|integer',
            'gmd_kg'=>'nullable|integer',
            'usuario_id'=>'nullable|integer',
        ];
    }
}
