<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
//Importando Classe/Model Product
use App\Product;
use App\Http\Requests\ProductRequest;

class ProductController extends Controller
{
    //Atributo privado que vai receber o valor do parâmetro no construtor
    private $product;

    //Product: tipo do parâmetro construtor
    public function __construct(Product $product)
    {
        //product depois do this é o atributo privado acima, que vai receber o instanciamento (new Product()) ao chamar a classe ProductController
        $this -> product = $product;

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //Faz a ligação de store -> user
        //Objeto loja com os dados da loja autenticada
        $userStore = auth()->user()->store;
        //Busca por produtos da loja 
        $products = $userStore->products()->paginate(10);

        return view('admin.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //Vai retornar das categorias apenas id e name
        $categories = \App\Category::all(['id', 'name']);
        //Está mandando as categorias para o products create
        //Pode selecionar as categorias  
        return view('admin.products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
        $data = $request->all();
        //Vai ter a loja deste usuário por meio da autenticação (chamando o OBJETO store)
        //O products existe no objeto 'store', então para retornar o objeto loja, retorna um atributo
        //esta acessando a loja do usuario autenticado
        $store = auth()->user()->store;
        //Por meio dessa loja, eu crio um produto pra essa loja
        //método create retorna objeto com as informações do produto, armazenando na variável $product 
        $product = $store ->products() -> create($data);
        //Faz a ligação de $produtc com categories e faz o save com sync 
        $product-> categories() -> sync($data['categories']);

        if($request->hasfile('photos')){
            //dentro de $images terá as fotos pós upload e faz a inserção 
            //Coluna em Product_images na tabela é image (na migration)
            $images = $this->imageUpload($request, 'image');
            //$images é o resultado da function UploadImages
            $product ->photos()->createMany($images);

        }

        flash('Produto Criado com Sucesso!') ->success();
        return redirect()->route('admin.products.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($product)
    {
        //Utilizando o método construtor
        $product = $this -> product -> findOrFail($product);
        $categories = \App\Category::all(['id', 'name']);
        //Vai para a view Edit
        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $product
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequest $request, $product)
    {
        //Recebe os dados
        $data = $request->all();
        //Passar o id 
        $product = $this->product->find($product);
        //Passa $data para o update
        $product->update($data);
        $product-> categories() -> sync($data['categories']);

        if($request->hasfile('photos')){
            //dentro de $images terá as fotos pós upload e faz a inserção 
            //Coluna em Product_images na tabela é image (na migration)
            $images = $this->imageUpload($request, 'image');
            //$images é o resultado da function UploadImages
            $product ->photos()->createMany($images);

        }

        flash('Produto Atualizado com Sucesso!') ->success();
        return redirect()->route('admin.products.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($product)
    {
        //Buscar pelo produto pelo id e depois deletar
        $product = $this->product->find($product);
        $product->delete();

        //Mensagem flash
        flash('Produto Removido com Sucesso!') ->success();
        return redirect()->route('admin.products.index');
    }

    private function imageUpload(Request $request, $imageColumn)
    {
        $images = $request->file('photos');

        $uploadedImages = [];

        foreach($images as $image){
                $uploadedImages[] = [$imageColumn =>$image->store('products', 'public')];
        }
        return $uploadedImages;
    }
}
