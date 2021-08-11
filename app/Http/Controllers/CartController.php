<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        //Receber o carrinho na variável $cart
        //Se tem um carrinho na sessão(has), pega o carrinho, caso contrário, manda um array vazio
        $cart = session()->has('cart') ? session()->get('cart') : [];
        return view('cart', compact('cart'));
    }

    public function add(Request $request)
    {
        //pegar a chave product da request (através de um array, criando a sessão)
        $product = $request -> get('product');

        //Verificar se existe sessão para os produtos
        if(session()-> has('cart')){
            //Existindo, adiciono mais itens no final deste array
            //Pega a chave desejada 'cart' e o valor que irá adicionar '$product'
            session()->push('cart', $product);
        } else {
            //Inicia um array vazio em $products passando o produto antes do if(que mandou no single view)
            $products[] = $product;
            //Inicia a sessão 'cart' com esse produto no array $products
            session()->put('cart', $products);
        }

        flash('Produto Adicionado no Carrinho!')->success();
        //Rota product.single passando o array 'slug' chamando o $product lá de cima a chave slug
        return redirect()->route('product.single', ['slug' => $product['slug']]);
    }
}
