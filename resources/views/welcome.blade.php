@extends('layouts.front')


@section('content')
    {{-- No front.blade.php tem a classe front configurado com margem de 40px --}}
    <div class="row front">
        @foreach($products as $key=> $product)
            <div class="col-md-4">
            <div class="card" style="width: 100%;">
                {{-- Se retornar mais de "1", é verdadeiro --}}
                @if($product->photos->count())
                    {{-- o asset é para exibir a imagem, concatenando o storage com a imagem --}}
                    {{-- de photos, irá pegar a primeira(first) e dessa foto, pega a chave image --}}
                    <img src="{{asset('storage/' . $product->photos->first()->image)}}" alt="" class='card-img-top'>
                @else
                    {{-- Se não existir uma imagem no banco, carregará a foto "no-photo" --}}
                    <img src="{{asset('assets/img/no-photo.jpg')}}" alt="" class='card-img-top'>
                @endif
                <div class="card-body">
                    <h2 class="card-title">{{$product->name}}</h2>
                    <p clas="card-text">{{$product->description}}</p>
                    <h3>
                        R$ {{number_format($product->price, '2', ',', '.')}}
                    </h3>
                    {{-- Slug como parâmetro da rota product.single --}}
                    <a href="{{route('product.single', ['slug' => $product->slug])}}" class="btn btn-success">
                        Ver produto
                    </a>
                </div>
            </div>  
            </div>
        {{-- Se a key for divisível por 3, vai fechar uma linha(row) e abrir outra  --}}
        @if(($key + 1) % 3 ==0) </div><div class="row front">@endif
        @endforeach
    </div>
@endsection