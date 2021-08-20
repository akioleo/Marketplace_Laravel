<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/admin/stores';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //Só consegue fazer logout se estiver logado
        $this->middleware('guest')->except('logout');
    }
    
    //Método é chamado após o usuário fazer a autenticação
    protected function authenticated(Request $request, $user)
    {
        //Se a sessão possui um carrinho
        if(session()->has('cart')){
            //Manda para a tela de checkout
            return redirect()->route('checkout.index');
        }
        //Se não tiver, vai para o administrativo(padrão)
        return null;
    }
}