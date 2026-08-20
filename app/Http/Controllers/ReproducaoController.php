<?php
namespace App\Http\Controllers;
use App\Models\Reproducao;
use Illuminate\Http\Request;

class ReproducaoController extends Controller
{
    private function fields(): array
    {
        return [
            ['name'=>'femea_id','label'=>'ID da fêmea','type'=>'number','required'=>true],
            ['name'=>'grupo_monta_id','label'=>'ID do grupo de monta','type'=>'number','required'=>false],
            ['name'=>'data_primeiro_servico','label'=>'Data do primeiro serviço','type'=>'date','required'=>true],
            ['name'=>'previsao_parto','label'=>'Previsão de parto','type'=>'date','required'=>false],
            ['name'=>'situacao','label'=>'Situação','type'=>'text','required'=>true],
            ['name'=>'reprodutor_id','label'=>'ID do reprodutor','type'=>'number','required'=>false],
            ['name'=>'usuario_id','label'=>'ID do usuário','type'=>'number','required'=>false],
        ];
    }
    private function columns(): array { return array_map(fn($f) => ['key'=>$f['name'],'label'=>$f['label']], $this->fields()); }
    public function index() { $items = Reproducao::query()->orderByDesc('id')->get(); return view('reproducoes.index',['items'=>$items,'fields'=>$this->fields(),'columns'=>$this->columns(),'title'=>'Reprodução','route'=>'reproducoes']); }
    public function create() { return view('reproducoes.form',['fields'=>$this->fields(),'title'=>'Novo Reprodução','route'=>'reproducoes','action'=>route('reproducoes.store'),'method'=>'POST']); }
    public function store(Request $request) { $data=$request->validate($this->rules()); Reproducao::create($data); return redirect()->route('reproducoes.index')->with('success','Reprodução criado com sucesso.'); }
    public function show(Reproducao $reproducao) { return view('reproducoes.show',['item'=>$reproducao,'fields'=>$this->fields(),'title'=>'Reprodução','route'=>'reproducoes']); }
    public function edit(Reproducao $reproducao) { return view('reproducoes.form',['item'=>$reproducao,'fields'=>$this->fields(),'title'=>'Editar Reprodução','route'=>'reproducoes','action'=>route('reproducoes.update',$reproducao),'method'=>'PUT']); }
    public function update(Request $request, Reproducao $reproducao) { $data=$request->validate($this->rules()); $reproducao->update($data); return redirect()->route('reproducoes.index')->with('success','Reprodução atualizado com sucesso.'); }
    public function destroy(Reproducao $reproducao) { $reproducao->delete(); return redirect()->route('reproducoes.index')->with('success','Reprodução excluído com sucesso.'); }
    private function rules(): array { return [
            'femea_id'=>'required|integer',
            'grupo_monta_id'=>'nullable|integer',
            'data_primeiro_servico'=>'required|date',
            'previsao_parto'=>'nullable|date',
            'situacao'=>'required|string|max:1000',
            'reprodutor_id'=>'nullable|integer',
            'usuario_id'=>'nullable|integer',
        ];
    }
}
