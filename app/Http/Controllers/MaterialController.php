<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\Request;

class MaterialController extends Controller {

    private $path = "fotos/materials";

    public function index() {
        $data = Material::orderBy('nome')->get();
        return view('material.index', compact('data'));
    }

    public function create() {
        return view('material.create');
    }

    public function store(Request $request) {
        // php artisan storage:link
        // Colocar os arquivos de imagem dentro da pasta "/storage/app/public"

        $regras = [
            'nome' => 'required|max:100|min:10',
            'descricao' => 'required|max:100|min:20',
            'foto' => 'required'
        ];

        $msgs = [
            "required" => "O preenchimento do campo [:attribute] é obrigatório!",
            "max" => "O campo [:attribute] possui tamanho máximo de [:max] caracteres!",
            "min" => "O campo [:attribute] possui tamanho mínimo de [:min] caracteres!",
        ];

        $request->validate($regras, $msgs);

        if($request->hasFile('foto')) {

            // Insert no Banco
            $reg = new Material();
            $reg->nome = $request->nome;
            $reg->descricao = $request->descricao;
            $reg->save();    

            // Upload da Foto
            $id = $reg->id;
            $extensao_arq = $request->file('foto')->getClientOriginalExtension();
            $nome_arq = $id.'_'.time().'.'.$extensao_arq;
            $request->file('foto')->storeAs("public/$this->path", $nome_arq);
            $reg->foto = $this->path."/".$nome_arq;
            $reg->save();
        }
        
        return redirect()->route('material.index');
    }

    public function show($id) {
        
    }

    public function edit($id) {
        $dados = Material::find($id); 

        if(!isset($dados)){
            return"<h1> ID: $id não encontrado</h1>";
        }
        return view('material.edit', compact('dados')); 
    }

    public function update(Request $request, $id) {
        $regras = [
            'nome' => 'required|max:100|min:10',
            'descricao' => 'required|max:100|min:20',
            'foto' => 'required'
        ];

        $msgs = [
            "required" => "O preenchimento do campo [:attribute] é obrigatório!",
            "max" => "O campo [:attribute] possui tamanho máximo de [:max] caracteres!",
            "min" => "O campo [:attribute] possui tamanho mínimo de [:min] caracteres!",
        ];

        $request->validate($regras, $msgs);

        $reg = Material::find($id); 

        if($request->hasFile('foto')) {

            // Insert no Banco
            
            $reg->nome = $request->nome;
            $reg->descricao = $request->descricao;
            $reg->save();    

            // Upload da Foto
            $id = $reg->id;
            $extensao_arq = $request->file('foto')->getClientOriginalExtension();
            $nome_arq = $id.'_'.time().'.'.$extensao_arq;
            $request->file('foto')->storeAs("public/$this->path", $nome_arq);
            $reg->foto = $this->path."/".$nome_arq;
            $reg->save();
        }
        
        return redirect()->route('material.index');
    }

    public function destroy($id) {
        $reg = Material::find($id); 

        $reg->delete(); 
         return redirect(route('material.index'));

    }
}
