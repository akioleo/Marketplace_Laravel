<?php

namespace App\Payment\PagSeguro;

class CreditCard 

{
    private $items;
    private $user;
    private $cardInfo;
    private $reference;
    
    //Construtor para poder receber as informações private
    public function __construct($items, $user, $cardInfo, $reference)
    {
        $this->items = $items;
        $this->user = $user;
        $this->cardInfo = $cardInfo;
        $this->reference = $reference;
    }

    public function doPayment()
    {
        //Instantiate a new direct payment request, using Credit Card
        $creditCard = new \PagSeguro\Domains\Requests\DirectPayment\CreditCard();

        /**
         * @todo Change the receiver Email
         */
        $creditCard->setReceiverEmail(env('PAGSEGURO_EMAIL'));
        $creditCard->setReference($this->reference);
        $creditCard->setCurrency("BRL");


        //CARRINHO
        $cartItems = session()->get('cart');

        foreach($this->items as $item) {
            $creditCard->addItems()->withParameters(
                $this->reference,
                $item['name'],
                $item['amount'],
                $item['price']
            );
        }

        // USUÁRIO
        //Passar o user que está no objeto
        $user = $this->user;
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
        $creditCard->setSender()->setHash($this->cardInfo['hash']);
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
        //$cardInfo virá as informações do front e o token do checkout.blade na function processPayment
        $creditCard->setToken($this->cardInfo['card_token']);
        //explode irá quebrar em duas casas, a casa 0 será a quantity, e a casa 1 installmentAmount
        list($quantity, $installmentAmount) = explode('|', $this->cardInfo['installment']);

        //Garantir que sempre terá duas casas decimais do valor total e casa do milhar (mesmo não tendo tem que passar pelo menos vazio )
        $installmentAmount = number_format($installmentAmount, 2, '.', '');

        $creditCard->setInstallment()->withParameters($quantity, $installmentAmount);
        //Poderia expor pro usuário a data de nascimento do dono do cartão
        $creditCard->setHolder()->setBirthdate('01/10/1979');
        //O nome poderá ser do form ou do usuário autenticado ($user->name) ou ($this->cardInfo['card_name'])
        $creditCard->setHolder()->setName($this->cardInfo['card_name']); // Equals in Credit Card

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

        return $result;
    }
}