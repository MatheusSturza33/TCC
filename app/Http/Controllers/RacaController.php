<?php
namespace App\Http\Controllers;
use App\Models\Raca;
use Illuminate\Http\Request;

class RacaController extends Controller
{
    private function fields(): array
    {
        return [
            ['name'=>'nome','label'=>'Nome','type'=>'text','required'=>true],
            ['name'=>'observacoes','label'=>'Observações','type'=>'textarea','required'=>false,'full'=>true],
        ];
    }
    private function columns(): array { return array_map(fn($f) => ['key'=>$f['name'],'label'=>$f['label']], $this->fields()); }
    public function index() { $items = Raca::query()->orderByDesc('id')->get(); return view('racas.index',['items'=>$items,'fields'=>$this->fields(),'columns'=>$this->columns(),'title'=>'Raça','route'=>'racas']); }
    public function create() { return view('racas.form',['fields'=>$this->fields(),'title'=>'Novo Raça','route'=>'racas','action'=>route('racas.store'),'method'=>'POST']); }
    public function store(Request $request) { $data=$request->validate($this->rules()); Raca::create($data); return redirect()->route('racas.index')->with('success','Raça criado com sucesso.'); }
    public function show(Raca $raca) { return view('racas.show',['item'=>$raca,'fields'=>$this->fields(),'title'=>'Raça','route'=>'racas']); }
    public function edit(Raca $raca) { return view('racas.form',['item'=>$raca,'fields'=>$this->fields(),'title'=>'Editar Raça','route'=>'racas','action'=>route('racas.update',$raca),'method'=>'PUT']); }
    public function update(Request $request, Raca $raca) { $data=$request->validate($this->rules()); $raca->update($data); return redirect()->route('racas.index')->with('success','Raça atualizado com sucesso.'); }
    public function destroy(Raca $raca) { $raca->delete(); return redirect()->route('racas.index')->with('success','Raça excluído com sucesso.'); }
    private function rules(): array { return [
            'nome'=>'required|string|max:1000',
            'observacoes'=>'nullable|string|max:1000',
        ];
    }
}
