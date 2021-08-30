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

        $cartItems = array_map(function($line){
            return $line['amount'] * $line['price'];
        //Pegando da sessão o cart com os itens
        }, session()->get('cart'));

        //Somar todos os valores
        $cartItems = array_sum($cartItems);

        //Mostra o codigo da sessão
        //var_dump(session()->get('pagseguro_session_code'));

        //remove a chave da sessão
        //session()->forget('pagseguro_session_code');
        
        //Manda para a view checkout o item cartItems
        return view('checkout', compact('cartItems'));
    }

    //Processar o checkout
    public function proccess(Request $request)
    {
        //Tudo que vier na requisição estará nessa variável
        $dataPost = $request->all();
        $reference = 'XPTO';
        //Instantiate a new direct payment request, using Credit Card
        $creditCard = new \PagSeguro\Domains\Requests\DirectPayment\CreditCard();

        /**
         * @todo Change the receiver Email
         */
        $creditCard->setReceiverEmail(env('PAGSEGURO_EMAIL'));
        $creditCard->setReference($reference);
        $creditCard->setCurrency("BRL");


        //CARRINHO
        $cartItems = session()->get('cart');

        foreach($cartItems as $item) {
            $creditCard->addItems()->withParameters(
                $reference,
                $item['name'],
                $item['amount'],
                $item['price']
            );
        }

        // USUÁRIO
        $user = auth()->user();
        //Se estiver em sandbox, o email será test@sandbox.pagseguro.com.br, caso contrário, será o email do user
        $email = env('PAGSEGURO_ENV') == 'sandbox' ? 'test@sandbox.pagseguro.com.br' : $user->email;
        $creditCard->setSender()->setName($user->name);
        $creditCard->setSender()->setEmail($email);
        $creditCard->setSender()->setPhone()->withParameters(
            11,
            56273440
        );

        $creditCard->setSender()->setDocument()->withParameters(
            'CPF',
            '28671224309'
        );
        //chave 'hash' é importada do checkout.blade.php da function processPayment
        $creditCard->setSender()->setHash($dataPost['hash']);
        $creditCard->setSender()->setIp('127.0.0.0');

        // ENTREGA 
        $creditCard->setShipping()->setAddress()->withParameters(
            'Av. Brig. Faria Lima',
            '1384',
            'Jardim Paulistano',
            '01452002',
            'São Paulo',
            'SP',
            'BRA',
            'apto. 114'
        );
        // ENDEREÇO DO CARTÃO
        $creditCard->setBilling()->setAddress()->withParameters(
            'Av. Brig. Faria Lima',
            '1384',
            'Jardim Paulistano',
            '01452002',
            'São Paulo',
            'SP',
            'BRA',
            'apto. 114'
        );

        // CREDIT CARD
        //$dataPost virá as informações do front e o token do checkout.blade na function processPayment
        $creditCard->setToken($dataPost['card_token']);
        //explode irá quebrar em duas casas, a casa 0 será a quantity, e a casa 1 installmentAmount
        list($quantity, $installmentAmount) = explode('|', $dataPost['installment']);

        //Garantir que sempre terá duas casas decimais do valor total e casa do milhar (mesmo não tendo tem que passar pelo menos vazio )
        $installmentAmount = number_format($installmentAmount, 2, '.', '');

        $creditCard->setInstallment()->withParameters($quantity, $installmentAmount);
        //Poderia expor pro usuário a data de nascimento do dono do cartão
        $creditCard->setHolder()->setBirthdate('01/10/1979');
        //O nome poderá ser do form ou do usuário autenticado ($user->name)
        $creditCard->setHolder()->setName($dataPost['card_name']); // Equals in Credit Card

        $creditCard->setHolder()->setPhone()->withParameters(
            11,
            56273440
        );

        $creditCard->setHolder()->setDocument()->withParameters(
            'CPF',
            '28671224309'
        );

        $creditCard->setMode('DEFAULT');

        $result = $creditCard->register(
            \PagSeguro\Configuration\Configure::getAccountCredentials()
        );

        var_dump($result);
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