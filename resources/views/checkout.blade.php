@extends('layouts.front')

@section('stylesheets')
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
@endsection

@section('content')

    <div class="container">
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-12 msg">

                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <h2>Dados para Pagamento</h2>
                    <hr>
                </div>
            </div>
            <form action="" method="post">
                <div class="row">
                    <div class="col-md-12 form-group">
                        <label>Nome no Cartão</label>
                        <input type="text" class="form-control" name="card_name">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 form-group">
                        <label>Número do Cartão <span class="brand"></span></label>
                        <input type="text" class="form-control" name="card_number">
                        <input type="hidden" name="card_brand">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 form-group">
                        <label>Mês de Expiração</label>
                        <input type="text" class="form-control" name="card_month">
                    </div>

                    <div class="col-md-4 form-group">
                        <label>Ano de Expiração</label>
                        <input type="text" class="form-control" name="card_year">
                    </div>

                </div>

                <div class="row">
                    <div class="col-md-5 form-group">
                        <label>Código de Segurança</label>
                        <input type="text" class="form-control" name="card_cvv">
                    </div>

                    <div class="col-md-12 installments form-group"></div>
                </div>

                <button class="btn btn-success btn-lg processCheckout">Efetuar Pagamento</button>
            </form>
        </div>
    </div>

@endsection


@section('scripts')
    <!-- LIB do Pagseguro em JS -->
    <script type="text/javascript" src="https://stc.sandbox.pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.directpayment.js"></script>
    <script src="{{asset('assets/js/jquery.ajax.js')}}"></script>
    <!-- Utilizando os métodos desse pacote JS -->
    <script>
        //Chamando a sessão que está ativa (que está na chave 'pagseguro_session_code')
        const sessionId = '{{session()->get('pagseguro_session_code')}}';
        //Passando para o pacote JS do próprio PagSeguro esse ID
        PagSeguroDirectPayment.setSessionId(sessionId);
    </script>

    <!-- Pegando bandeira do cartão -->
    <script>
        //Buscar pelo querySelector que permite fazer buscas no DOM (Modelo de Objeto de Documento)
        //Pegar o elemento que vem do card_number
        let cardNumber = document.querySelector('input[name=card_number]');
        //Buscar na view o span que possui a classe brand
        let spanBrand = document.querySelector('span.brand');
        //Tudo que o usuário digitar, executa o callback (no console do inspecionar)
        cardNumber.addEventListener('keyup', function(){
            if(cardNumber.value.length >=6) {
                //Chamando do PagSeguroDirectPayment o método getBrand é utilizado para verificar qual a bandeira do cartão que está sendo digitada
                PagSeguroDirectPayment.getBrand({
                    //Informar para o PagSeguroDirectPayment os 6 primeiros dígitos do cartão
                    cardBin: cardNumber.value.substr(0,6),
                    //Manipular o retorno 
                    //Se der sucesso
                    success: function(res){
                        //Dentro da chave brand, buscar pela chave name (que no exemplo possui "VISA") e jogará pra dentro do span no form de Número do Cartão
                        let imgFlag = `<img src="https://stc.pagseguro.uol.com.br/public/img/payment-methods-flags/68x30/${res.brand.name}.png">`;
                        //Irá imprimir no HTML a imgFlag
                        spanBrand.innerHTML = imgFlag;  
                                     
                        document.querySelector('input[name=card_brand]').value = res.brand.name;
                        //Chamando a função de parcelamento passando o valor e o nome da bandeira do cartão
                        getInstallments(40, res.brand.name);
                    },
                    //Se der erro
                    error: function(err) {
                        console.log(err);
                    },
                    //Chamar um estado após logo após toda a requisição ter sido executada 
                    complete: function(res) {
                        //console.log('Complete: ', res);
                    }
                });
            }
        });
        //processCheckout é a classe do botão de submit
        //Ao clicar no botão, irá realizar a busca do token 
        let submitButton = document.querySelector('button.processCheckout');
        
        submitButton.addEventListener('click', function(event){
            //Para não executar o evento padrão de submit do botão 
            event.preventDefault();
            //Gerando card token
            PagSeguroDirectPayment.createCardToken ({
                cardNumber: document.querySelector('input[name=card_number]').value,
                brand:      document.querySelector('input[name=card_brand]').value, 
                cvv:        document.querySelector('input[name=card_cvv]').value,
                expirationMonth: document.querySelector('input[name=card_month]').value,
                expirationYear:  document.querySelector('input[name=card_year]').value,
                success: function(res) {
                    processPayment(res.card.token)
                }
            });
        });


        function processPayment(token) {
            //o data que será usado abaixo no $.ajax
            let data = {
                //token irá receber do parâmetro
                token: token,
                //getSenderHash retorna um hash que identifica o usuário na requisição (pagamento)
                hash: PagSeguroDirectPayment.getSenderHash(),
                //select_installments é o select com o parcelamento
                installment: document.querySelector('select_installments').value
            };

            $.ajax({
                type: 'POST',
                url: '',
                data: data,
                dataType: 'json',
                success: function(res) {
                    console.log(res);
                }
            });
        }

        
        //Função para parcelamento
        //amount - total da compra / brand - bandeira(nome) do cartão 
        function getInstallments(amount, brand) {
            //PagSeguroDirectPayment = classe JS do pagseguro
            //método getInstallments está dentro do DirectPayment
            PagSeguroDirectPayment.getInstallments({
                //Passando o amount que vai pegar do parâmetro amount
                amount: amount,
                //Passando o brand que vai pegar do parâmetro brand
                brand: brand,
                //Quantidade de parcelas que você assume o juros, ou seja, se for 3, assume 3 parcelas sem juros
                maxInstallmentNoInterest: 0,
                success: function(res) {
                    //drawSelectInstallments = Desenhe Select de Parcelas 
                    //Chamando do res a chave installments 
                    //Pegando da chave installments a chave Visa 
                    //[brand] vai trazer o valor dinâmico (visa, master etc..) vindo do getBrand
                    let selectInstallments = drawSelectInstallments(res.installments[brand]);
                    //Jogando todo o select desenhado para a div installments
                    document.querySelector('div.installments').innerHTML = selectInstallments;
                },
                error: function(err) {
                    
                },
                complete: function(res) {

                },
            })
        }


    //installments é o array com os parcelamentos (ex: 9x = {9})
    function drawSelectInstallments(installments) {
        let select = '<label>Opções de Parcelamento:</label>';
        select += '<select class="form-control select_installments">';
        for(let l of installments) { 
            //Quantidade e total da parcela | visualmente pro usuário {2}x de R${40} Total {80}
            select += `<option value="${l.quantity}|${l.installmentAmount}">${l.quantity}x de ${l.installmentAmount} - Total fica ${l.totalAmount}</option>`;
        }
        select += '</select>';
        return select;
    }


    </script>
@endsection