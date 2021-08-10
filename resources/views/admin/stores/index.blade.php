@extends('layouts.app')

@section('content')    
{{-- Se não possui loja, aparecer o botão de "CRIAR LOJA" --}}
    @if(!$store)
    <a href="{{route('admin.stores.create')}}" class="btn btn-sm btn-success">CRIAR LOJA</a>
    @else
    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Loja</th>
                <th>Total de Produtos</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{$store->id}}</td>
                <td>{{$store->name}}</td>
                {{-- Retornará uma collection dos produtos, e o count é pra retornar só quantos possui --}}
                <td>{{$store->products->count()}}</td>
                <td>
                <div class="btn-group">
                    <a href="{{route('admin.stores.edit', ['store' => $store->id])}}" class="btn btn-sm btn-primary">EDITAR</a>
                    <form action="{{route('admin.stores.destroy', ['store' => $store->id])}}" method="post"> 
                        @csrf
                        @method("DELETE")
                        <button type="submit" class="btn btn-sm btn-danger">REMOVER</button>
                    </form>
                    </div>
                </td>
            </tr> 
        </tbody>
    </table>
    @endif
@endsection