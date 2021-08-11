<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;

class HomeController extends Controller
{
    private $product;

    //Product: tipo do parâmetro construtor
    public function __construct(Product $product)
    {
        //product depois do this é o atributo privado acima, que vai receber o instanciamento (new Product()) ao chamar a classe ProductController
        $this -> product = $product;

    }

    public function index()
    {
        //orderBy('id', 'DESC') - Ordenado por ID em ordem descrescente e limitando por 8 na página
        $products = $this->product->limit(8)->orderBy('id', 'DESC')->get();
        return view('welcome', compact('products'));
    }

    public function single($slug) 
    {
        //Pega o produto pelo slug 
        $product = $this->product->whereSlug($slug)->first();
        //Manda o produto para a single por 'product'
        return view ('single', compact('product'));
    }
}
