@extends('layouts.front')

@section('content')
    <div class="row">
        <div class="col-6">
            {{-- Condicional para saber se possui foto --}}
            @if($product->photos->count())
            {{-- IMAGEM --}}
            <img src="{{asset('storage/' . $product->photos->first()->image)}}" alt="" class='card-img-top'>
            <div class="row" style="margin-top: 20px;">
                @foreach($product->photos as $photo)
                    <div class="col-4">
                        {{-- img-fluid para a imagem ficar responsiva --}}
                        <img src="{{asset('storage/' . $photo->image)}}" alt="" class="img-fluid">
                    </div>
                @endforeach
            </div>
            @else
            {{-- Se não existir uma imagem no banco, carregará a foto "no-photo" --}}
                <img src="{{asset('assets/img/no-photo.jpg')}}" alt="" class='card-img-top'>
            @endif
        </div>

        <div class="col-6">
            <div class="col-md-12">
                <h2>{{$product->name}}</h2>
                <p>
                    {{$product->description}}
                </p>
                <h3>
                    {{-- Primeiro parâmetro, valor booleano inteiro que quer formatar --}}
                    {{-- Segundo parâmetro, quantidade de casas decimais "2" --}}
                    {{-- Caractere decimal "," --}}
                    {{-- Caractere de milhar "." --}}
                R$ {{number_format($product->price, '2', ',', '.')}}
                </h3>
                <span>
                    Loja: {{$product->store->name}}
                </span>
            </div>

            <div class="product-add col-md-12">
                <hr>
                <form action="" method="post">
                    <input type="hidden" name="product[name]" value="{{$product->name}}">
                    <input type="hidden" name="product[slug]" value="{{$product->price}}">
                    <input type="hidden" name="product[slug]" value="{{$product->slug}}">
                    <div class="form-group">
                        <label>Quantidade</label>
                        <input type="number" name="product[amount]" class="form-control col-md-2" value="1">
                    </div>
                    <button class="btn btn-lg btn-danger">Comprar</button>
                </form>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <hr>
            {{$product->body}}
        </div>
    </div>
@endsection