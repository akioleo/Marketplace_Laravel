<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreRequest;
use App\Traits\UploadTrait;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StoreController extends Controller
{
    use UploadTrait;
    //Vai permitir usar o imageUpload na classe
	public function __construct()
	{
        //Verificar se já possui uma loja
		$this->middleware('user.has.store')->only(['create', 'store']);
	}

    public function index()
    {
        //Através da autenticação, verifica o usuário e liga ele à loja (1:1)
    	$store = auth()->user()->store;
        //Passando para view index na variável 'store'
    	return view('admin.stores.index', compact('store'));
    }

    public function create()
    {
        //Quando ainda não possui autenticação, é feito dessa maneira
        //Todos os usuários, porém pegando apenas o ID e o nome deles
    	$users = \App\User::all(['id', 'name']);
        //Passando para view create (admin/stores) na variável 'users'
    	return view('admin.stores.create', compact('users'));
    }

    public function store(StoreRequest $request)
    {
        //Informação que veio na request para a variável $data
    	$data = $request->all();
        //Salvará na variável user de acordo com a autenticação
		$user = auth()->user();

		if($request->hasFile('logo')) {
            //Inserindo uma chave(imageUpload, a imagem inserida $request) para a coluna 'logo'
            //Já passa o file 'logo' pronto para a trait
			$data['logo'] = $this->imageUpload($request->file('logo'));
		}
        $data['slug'] = Str::slug($data['name'], '-'); 
        //Criar uma loja por meio desse usuário (1:1 Usuário-loja)
        //Chamar método create(com os dados)
    	$store = $user->store()->create($data);

	    flash('Loja Criada com Sucesso')->success();
	    return redirect()->route('admin.stores.index');
    }
    //Recebendo o parâmetro da url {store}
    public function edit($store)
    {
    	$store = \App\Store::find($store);

    	return view('admin.stores.edit', compact('store'));
    }
    //Como no update irá receber dados, precisa injetar o request, recebendo o id da loja que irá atualizar
    public function update(StoreRequest $request, $store)
    {
    	$data = $request->all();
        //Essa busca do find vai trazer a logo
	    $store = \App\Store::find($store);

        if($request->hasFile('logo')) {
            if(Storage::disk('public')->exists($store->logo)){
                Storage::disk('public')->delete($store->logo);
            }
            //Inserindo uma chave(imageUpload, a imagem inserida "$request") para a coluna 'logo' 
            //Já passa o file 'logo' pronto para a trait
            //Atualização no banco
            $data['logo'] = $this->imageUpload($request->file('logo'));
        }
        $data['slug'] = Str::slug($data['name'], '-'); 
    	$store->update($data);
       //Chama a função flash com método success que irá printar a mensagem na cor verde
    	flash('Loja Atualizada com Sucesso')->success();
        //Redireciona para o index, passando o apelido no método route
    	return redirect()->route('admin.stores.index');
    }

    public function destroy($store)
    {
        //Pegando o conteúdo (store) dinâmico pela url 
		$store = \App\Store::find($store);
		$store->delete();

        //Depois que remover, será redirecionado para o link (parâmetro)
	    flash('Loja Removida com Sucesso')->success();
		return redirect()->route('admin.stores.index');
    }
}