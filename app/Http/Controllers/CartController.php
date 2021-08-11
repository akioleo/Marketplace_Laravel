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
            
            //Pega os produtos da sessão
            $products = session()-> get('cart');
            //Dos itens da sessão, está pegando somente a coluna "slug" para fazer o in_array abaixo
            $productsSlugs = array_column($products, 'slug');

            //Se o slug existir dentro de $productsSlug (Se dentro da sessão já tiver um produto que tenha o mesmo slug que está mandando novamente, executar o if)
            //Este $product é o produto que pegou da requisição antes do if
            if(in_array($product['slug'], $productsSlugs)){
                //Pega o products alterado pelo Array_map no método productIncrement 
                $products = $this->productIncrement($product['slug'], $product['amount'], $products);
                //Sobrescreve a sessão
                //OBS: O $products de cima
                session()->put('cart', $products);
            } else {
                //Se não tiver duplicidade, envia o push normalmente
                //Pega a chave desejada 'cart' e o valor que irá adicionar '$product'
                session()->push('cart', $product);
            }
        
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

    //$slug está passando o slug da URL 
    public function remove ($slug)
    {
        //Se não possui sessão, nao entra no remove (redireciona direto para o index)
        if(!session()->has('cart'))
            return redirect()->route('cart.index');
        //Pegando os produtos da sessão
        $products = session()->get('cart');
        //Filtro para remover o item selecionado pelo usuário
        //Espera o array a ser filtrado ($products acima) e function anônima que possui a condição
        //Pra cada linha do array, receberá em $line (que vai ser o produto)
        $products = array_filter($products, function($line) use($slug){
            //Retornar somente a linha do array na chave Slug que não seja igual ao Slug informado na URL
            //O slug do produto é igual ao slug da linha, e retorna falso, sendo falso é removido pelo array filter
            return $line['slug'] != $slug;
        });
        //Sobrescrevendo o cart com o array filtrado
        session()->put('cart', $products);
        return redirect()->route('cart.index');
    }

    public function cancel()
    {
        session()->forget('cart');

        flash('Compra Cancelada!')->success();
        return redirect()->route('cart.index');
    }

    private function productIncrement($slug, $amount, $products)
    {
        //Array map vai alterar linha por linha do array, por meio da função anônima (dos $products da sessão passados pelo método add)
        //Para utilizar os parâmetros do escopo externo, deve utilizá-los com 'use'
        $products = array_map(function($line) use($slug, $amount){
            //Se slug passado pelo productIncrement for igual ao slug do produto da sessão, pega o amount da linha (que já vai ter uma quantidade) e somar com o amount vindos da nova adição
            //Verifica qual a linha que está na sessão de $products que é igual ao slug que veio na requisição
            if($slug == $line['slug']) {
                //pega dessa linha o 'amount' e soma ao que veio da duplicidade
                $line['amount'] += $amount;
            }
            //retornará a linha alterada com a quantidade incrementada 
            return $line;
            //Altera no $products a linha alterada
        }, $products);
        //retorna o novo produto
        return $products;
    }
}
