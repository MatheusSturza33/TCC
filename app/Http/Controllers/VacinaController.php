<?php
namespace App\Http\Controllers;
use App\Models\Vacina;
use Illuminate\Http\Request;

class VacinaController extends Controller
{
    private function fields(): array
    {
        return [
            ['name'=>'nome','label'=>'Nome','type'=>'text','required'=>true],
            ['name'=>'categoria_animal','label'=>'Categoria animal','type'=>'text','required'=>true],
            ['name'=>'fase_aplicacao','label'=>'Fase de aplicação','type'=>'text','required'=>false],
            ['name'=>'intervalo_reforco','label'=>'Intervalo de reforço (dias)','type'=>'number','required'=>false],
            ['name'=>'ativa','label'=>'Ativa','type'=>'select','required'=>true,'options'=>['1'=>'Sim','0'=>'Não']],
        ];
    }
    private function columns(): array { return array_map(fn($f) => ['key'=>$f['name'],'label'=>$f['label']], $this->fields()); }
    public function index() { $items = Vacina::query()->orderByDesc('id')->get(); return view('vacinas.index',['items'=>$items,'fields'=>$this->fields(),'columns'=>$this->columns(),'title'=>'Vacina','route'=>'vacinas']); }
    public function create() { return view('vacinas.form',['fields'=>$this->fields(),'title'=>'Novo Vacina','route'=>'vacinas','action'=>route('vacinas.store'),'method'=>'POST']); }
    public function store(Request $request) { $data=$request->validate($this->rules()); Vacina::create($data); return redirect()->route('vacinas.index')->with('success','Vacina criado com sucesso.'); }
    public function show(Vacina $vacina) { return view('vacinas.show',['item'=>$vacina,'fields'=>$this->fields(),'title'=>'Vacina','route'=>'vacinas']); }
    public function edit(Vacina $vacina) { return view('vacinas.form',['item'=>$vacina,'fields'=>$this->fields(),'title'=>'Editar Vacina','route'=>'vacinas','action'=>route('vacinas.update',$vacina),'method'=>'PUT']); }
    public function update(Request $request, Vacina $vacina) { $data=$request->validate($this->rules()); $vacina->update($data); return redirect()->route('vacinas.index')->with('success','Vacina atualizado com sucesso.'); }
    public function destroy(Vacina $vacina) { $vacina->delete(); return redirect()->route('vacinas.index')->with('success','Vacina excluído com sucesso.'); }
    private function rules(): array { return [
            'nome'=>'required|string|max:1000',
            'categoria_animal'=>'required|string|max:1000',
            'fase_aplicacao'=>'nullable|string|max:1000',
            'intervalo_reforco'=>'nullable|integer',
            'ativa'=>'required|in:0,1',
        ];
    }
}
