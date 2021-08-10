<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marketplace</title>
    {{-- Linkando o CSS no projeto --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" class="">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark" style="margin-bottom: 40px;">
  <div class="container-fluid">
      {{-- Titulo da bar que ao clicar irá para o home --}}
    <a class="navbar-brand" href="{{'home'}}">Marketplace</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
    @auth
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link @if(request()->is('admin/stores*')) active @endif" aria-current="page" href="{{route('admin.stores.index')}}">Lojas</a>
        </li>
        <li class="nav-item">
        {{-- o if no navlink serve para manter ativo apenas quando estiver no respectivo link --}}
        {{-- O asterisco depois da rota significa que todas as páginas que possuem admin/products ficaram ativas (mesmo create, edit.. e não só index) --}}
          <a class="nav-link @if(request()->is('admin/products*')) active @endif" href="{{route('admin.products.index')}}">Produtos</a>
        </li>
        <li class="nav-item">
          <a class="nav-link @if(request()->is('admin/categories*')) active @endif" aria-current="page" href="{{route('admin.categories.index')}}">Categorias</a>
        </li>
      </ul>
      <div class="d-flex">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
                {{-- document.querySelector irá procurar o form que tenha a classe logout e faz o submit --}}
            <a class="nav-link" href="#" onclick="event.preventDefault(); 
                                                        document.querySelector('form.logout').submit();">Sair</a>
            <form action="{{route('logout')}}" class="logout" method="POST" style="display:none;">
                @csrf
            </form>
            </li>
            <li class="nav-item">
                <span class="nav-link">{{auth()->user()->name}}</span>
            </li>
        </ul>
      </div>
    </div>
    @endauth
  </div>
</nav>
    <div class="container">
        @include('flash::message')
        @yield('content')
    </div>
    <script src="https://unpkg.com/blip-chat-widget" type="text/javascript">
</script>
<script src="https://unpkg.com/blip-chat-widget" type="text/javascript">
</script>
{{-- CHATBOT --}}
<script>
    (function () {
        window.onload = function () {
            new BlipChat()
            .withAppKey('YWNhZGVteWFicm9hZDE6ZDYzY2U3YTEtZGIzZC00MjM1LWJlYmQtNzczZTIyNDI5MWU4')
            .withButton({"color":"#2CC3D5","icon":""})
            .withCustomCommonUrl('https://chat.blip.ai/')
            .build();
        }
    })();
</script>                           
</body>
</html>