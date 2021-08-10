<?php

namespace App\Http\Middleware;

use Closure;

class UserHasStoreMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //Se retornar 1, retorna verdadeiro redireciona para index e executa a mensagem flash
        if(auth()->user()->store()->count()){
            //warning mensagem amarela
            flash('Você já possui uma loja!')->warning();
            return redirect()->route('admin.stores.index');
        }
        //Se não tiver loja, a execução continua(que é a pagina de criação de loja)
        return $next($request);
    }
}
