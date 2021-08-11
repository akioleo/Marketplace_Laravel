@extends('layouts.front')


@section('content')
    <div class="row">
        <div class="col-12">
            <h2>Carrinho de Compras</h2>
            <hr>
        </div>
        <div class="col-12">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Produto</th>
                        <th>Preço</th>
                        <th>Quantidade</th>
                        <th>Subtotal</th>
                        <th>Ação</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Inicia a variável $total com valor 0 --}}
                    @php $total = 0; @endphp

                    @foreach($cart as $c)
                    <tr>
                        {{-- Como temos um array na sessão, deve chamar com "chaves" colchetes --}}
                        <td>{{$c['name']}}</td>
                        {{-- 2 casas decimais, separada por ',' e milhar '.' --}}
                        <td>R$ {{number_format($c['price'], 2, ',', '.')}}</td>
                        <td>{{$c['amount']}}</td>
                        

                        {{-- Calcula o subtotal de cada item, depois somar ao $total --}}
                        @php
                            $subtotal = $c['price'] * $c['amount'];
                            $total += $subtotal;
                        @endphp


                        {{-- O preço vezes a quantidade de itens --}}
                        <td>{{number_format(($c['price'] * $c['amount']), 2, ',', '.')}}</td>

                        <td>
                            <a href="" class="btn btn-sm btn-danger">Remover</a>
                        </td>
                    </tr>
                    @endforeach
                    <tr>
                        {{-- colspan pra aumentar o tamanho da barra --}}
                        <td colspan="3">Total:</td>
                        <td colspan="2">R$ {{number_format($total, 2, ',', '.')}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection