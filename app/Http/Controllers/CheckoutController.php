<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function index()
    {
        //Se entrou no checkout e não está logado, será redirecionado para a rota de login
        if(!auth()->check()){
            return redirect()->route('login');
        }
        //Chama a function que cria uma nova sessão do PagSeguro
        $this->makePagSeguroSession();

        //Mostra o codigo da sessão
        var_dump(session()->get('pagseguro_session_code'));
        //remove a chave da sessão
        //session()->forget('pagseguro_session_code');

        return view('checkout');
    }

    private function makePagSeguroSession()
    {
        //Verificar se não existe uma session code
        if(!session()->has('pagseguro_session_code')){
            //Se não existir, criar uma session nova através da API do PagSeguro pela $sessionCode
            $sessionCode = \PagSeguro\Services\Session::create(
                \PagSeguro\Configuration\Configure::getAccountCredentials()
            );
            //Jogar essa API nova na session atual (Para manter apenas uma session aberta do PagSeguro)
            session()->put('pagseguro_session_code', $sessionCode->getResult());
        }
    }
}