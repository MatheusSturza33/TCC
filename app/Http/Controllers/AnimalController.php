<?php
namespace App\Http\Controllers;
use App\Models\Animal;
use Illuminate\Http\Request;

class AnimalController extends Controller
{
    private function fields(): array
    {
        return [
            ['name'=>'tatuagem','label'=>'Tatuagem','type'=>'text','required'=>true],
            ['name'=>'mossa','label'=>'Mossa','type'=>'text','required'=>false],
            ['name'=>'brinco','label'=>'Brinco','type'=>'text','required'=>false],
            ['name'=>'chip','label'=>'Chip','type'=>'text','required'=>false],
            ['name'=>'sexo','label'=>'Sexo','type'=>'select','required'=>true,'options'=>['M'=>'Macho','F'=>'Fêmea']],
            ['name'=>'tipo','label'=>'Tipo','type'=>'text','required'=>true],
            ['name'=>'situacao','label'=>'Situação','type'=>'text','required'=>true],
            ['name'=>'estado_reprodutivo','label'=>'Estado reprodutivo','type'=>'text','required'=>false],
            ['name'=>'data_nascimento','label'=>'Data de nascimento','type'=>'date','required'=>false],
            ['name'=>'id_pai','label'=>'ID do pai','type'=>'number','required'=>false],
            ['name'=>'id_mae','label'=>'ID da mãe','type'=>'number','required'=>false],
            ['name'=>'id_localizacao_atual','label'=>'ID da localização','type'=>'number','required'=>false],
        ];
    }
    private function columns(): array { return array_map(fn($f) => ['key'=>$f['name'],'label'=>$f['label']], $this->fields()); }
    public function index() { $items = Animal::query()->orderByDesc('id')->get(); return view('animais.index',['items'=>$items,'fields'=>$this->fields(),'columns'=>$this->columns(),'title'=>'Animal','route'=>'animals']); }
    public function create() { return view('animais.form',['fields'=>$this->fields(),'title'=>'Novo Animal','route'=>'animals','action'=>route('animals.store'),'method'=>'POST']); }
    public function store(Request $request) { $data=$request->validate($this->rules()); Animal::create($data); return redirect()->route('animals.index')->with('success','Animal criado com sucesso.'); }
    public function show(Animal $animal) { return view('animais.show',['item'=>$animal,'fields'=>$this->fields(),'title'=>'Animal','route'=>'animals']); }
    public function edit(Animal $animal) { return view('animais.form',['item'=>$animal,'fields'=>$this->fields(),'title'=>'Editar Animal','route'=>'animals','action'=>route('animals.update',$animal),'method'=>'PUT']); }
    public function update(Request $request, Animal $animal) { $data=$request->validate($this->rules()); $animal->update($data); return redirect()->route('animals.index')->with('success','Animal atualizado com sucesso.'); }
    public function destroy(Animal $animal) { $animal->delete(); return redirect()->route('animals.index')->with('success','Animal excluído com sucesso.'); }
    private function rules(): array { return [
            'tatuagem'=>'required|string|max:1000',
            'mossa'=>'nullable|string|max:1000',
            'brinco'=>'nullable|string|max:1000',
            'chip'=>'nullable|string|max:1000',
            'sexo'=>'required|in:M,F',
            'tipo'=>'required|string|max:1000',
            'situacao'=>'required|string|max:1000',
            'estado_reprodutivo'=>'nullable|string|max:1000',
            'data_nascimento'=>'nullable|date',
            'id_pai'=>'nullable|integer',
            'id_mae'=>'nullable|integer',
            'id_localizacao_atual'=>'nullable|integer',
        ];
    }
}
