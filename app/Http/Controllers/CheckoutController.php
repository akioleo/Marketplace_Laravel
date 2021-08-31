<?php

namespace App\Http\Controllers;

use App\Payment\PagSeguro\CreditCard;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function index()
    {
        //Se entrou no checkout e não está logado, será redirecionado para a rota de login
        if(!auth()->check()){
            return redirect()->route('login');
        }
        //Se for para checkout sem ter carrinho, redireciona para home da loja
        if(!session()->has('cart')) return redirect()->route('home');

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
       try {
            //Tudo que vier na requisição estará nessa variável
            $dataPost = $request->all();
            //Usuário autenticado
            $user = auth()->user();
            //cartItems está na sessão do cart
            $cartItems = session()->get('cart');
            //Passando o reference no Controller e no Payment somente instanciará
            $reference = 'XPTO';

            //Em creditCardPayment terá as 4 informações vinda do CreditCard.php        
            $creditCardPayment = new CreditCard($cartItems, $user, $dataPost, $reference);
            $result = $creditCardPayment->doPayment();
            //Registra o pedido do usuário após o pagamento
            //O return do result acima já manda pro userOrders
            $userOrder = [
                'reference' => $reference,
                'pagseguro_code' => $result->getCode(),
                'pagseguro_status' => $result->getStatus(),
                'items' => serialize($cartItems),
                'store_id' => 50,  
            ];
            
            //Referencia do usuário vai chegar por meio da ligação do cliente autenticado
            $user->orders()->create($userOrder);

            session()->forget('cart');
            session()->forget('pagseguro_session_code');

            //Faz um retorno json devido ao type no ajax (o handle res vai receber esse array)
            return response()->json([
                'data' => [
                    'status' => true,
                    'message' => 'Pedido criado com sucesso!', 
                    'order' => $reference
                ]
            ]);
       } catch (\Exception $e){
            //Exigir mensagem real do erro porém só em ambiente de desenvolvimento
            $message = env('APP_DEBUG') ? $e->getMessage() : 'Erro ao processar pedido!';
            return response()->json([
                'data' => [
                    'status' => false,
                    'message' => $message
                ]
            ], 401);
       }
    }

    public function thanks() 
    {
        return view('thanks');    
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