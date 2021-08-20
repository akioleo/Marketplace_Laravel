<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Product;
//Apontando para o método HomeController no método index
Route::get('/', 'HomeController@index') -> name('home');
Route::get('/product/{slug}', 'HomeController@single') ->name('product.single');

Route::prefix('cart')->name('cart.')->group(function(){
    Route::get('/', 'CartController@index')->name('index');
    Route::post('add', 'CartController@add')->name('add');
    Route::get('remove/{slug}', 'CartController@remove')->name('remove');
    Route::get('cancel', 'CartController@cancel')->name('cancel');
});

Route::prefix('checkout')->name('checkout.')->group(function(){
    Route::get('/', 'CheckoutController@index')->name('index');
});


//Chamar o middleware por array, passando qual middleware quer utilizar para autenticação 
Route::group(['middleware' => ['auth']], function(){
    Route::prefix('admin')->name('admin.')->namespace('Admin')->group(function(){
        //     Route::prefix('stores')->name('stores.')->group(function(){
        //         //Primeiro parâmetro é o "link" e o segundo é a chamada do Controller
        //         //Uma contrabarra e escape
        //         //index é o método dentro de Admin/StoreController
        //         Route::get('/', 'StoreController@index') -> name('index');
        //         //Vai apontar no método create no StoreController
        //         Route::get('/create', 'StoreController@create') -> name('create');
        //         //Post virá do nosso formulário create(view)
        //         Route::post('/store', 'StoreController@store') -> name('store');
        //         //Passando parâmetro store dinâmico (irá colocar o id da loja que deseja buscar/atualizar)
        //         Route::get('/{store}/edit', 'StoreController@edit') -> name('edit');
        //         //O update vai a atualização. Irá apontar para o método update
        //         Route::post('/update/{store}', 'StoreController@update') -> name('update');
        //         //Rota de REMOÇÃO de loja
        //         Route::get('/destroy/{store}', 'StoreController@destroy') -> name('destroy');
        //     }); 
            //Basicamente o meio criado acima é o que o laravel faz com resource
            Route::resource('stores', 'StoreController');
            Route::resource('products', 'ProductController');
            Route::resource('categories', 'CategoryController');
        
            //Apelido photo.remove
            Route::post('photos/remove', 'ProductPhotoController@removePhoto')->name('photo.remove');
        });
});

Auth::routes();

//Route::get('/home', 'HomeController@index')->name('home'); //-> middleware('auth'); 




Route::get('/model', function () {
    // $products = \App\Product::all();  // Traduzido em Select * from products (irá procurar pela tabela no plural de Product)
    // return $products;
    // //Método find pega o usuário pelo ID e passa o retorno para o $user
    // $user = new \App::find(1);
    // // Atualizará o nome do usuário
    // $user -> name = 'Usuário Teste Editado';
    // $user -> save();
    
    //O método ALL serializa TODOS os dados (converte para JSON)
    // return App\User::all();
    // //Find retorna em JSON um elemento específico
    // return App\User::find(3);
    //SELECT * FROM users WHERE name = 'Prof. Joyce Goodwin III'
    // return App\User::where('name', 'Prof. Joyce Goodwin III') -> get();
    //First (em que vai pegar oprimeiro resultado com aquele nome)
    //return App\User::where('name', 'Prof. Joyce Goodwin III') -> first();

//------------------------------------------------------------------------------------------------------------------

    // MASS ASSIGNMENT - Atribuição em massa
    // $user = \App\User::create([
    //     'name' => 'Nanderson Castro',
    //     'email' => 'nanderson@gmail.com',
    //     'password' => bcrypt('12345678')
    // ]);

    // // MASS UPDATE 
    // $user = \App\User::find(41);
    //     $user -> update([
    //         'name' => 'Atualizado com Mass Update'
    // ]); //retorna true ou false
    // return \App\User::all();

//------------------------------------------------------------------------------------------------------------------

    //PEGAR A LOJA DE UM USUÁRIO
    // $user = \App\User::find(5);
    // return $user ->store;

    //PEGAR OS PRODUTOS DE UMA LOJA (como é uma relação de "muitos", retornará a coleção inteira)
    //-> count(); Irá verificar quantos elementos daquela collection
    //products() -> where('id', 9)->get(); Verifica o item específico daquela collection
    // Quando não possui return ($loja->products()), nos entrega o objeto HasMany 
    // $loja = \App\Store::find(1);
    // return $loja->products;             

    //PEGAR AS LOJAS DE UMA CATEGORIA
    //Muitos pra muitos retorna Collection
    // $categoria = \App\Category::find(1);
    // $categoria -> products;   //Já traria todos os produtos dessa categoria(1)

//------------------------------------------------------------------------------------------------------------------

    // // //CRIAR UMA LOJA PARA UM USUARIO
    // // //Irá vincular a loja nova ao user_id (10)
    // $user = \App\User::find(10);
    // // //Como está criando um array, é create (na factory é um objeto)
    // // //$store para visualizarmos a loja
    // // //Abaixo, estamos criando o relacionamento de user com store ($user -> store())
    // $store = $user->store()->create([
    // 'name' => 'Loja Teste',
    // 'description' => 'Loja teste de produtos de informática',
    // 'mobile_phone' => 'xx-xxxx-xxxx',
    // 'phone' => 'xx-xxxx-xxxx',
    // 'slug' => 'loja-teste'
    // ]);
    // //$store foi criada para ter a visualização da loja
    // dd($store);


    // //CRIAR UM PRODUTO PARA UMA LOJA
    // //Criando produto para a loja 41
    // $store = \App\Store::find(41);
    // //Abaixo faz o relacionamento entre store e products, criando um produto
    // $product = $store->products()->create([
    //     'name' => 'Notebook Dell',
    //     'description' => 'Core i7 / 16gb RAM / RTX 2060',
    //     'body' => 'Qualquer coisa...',
    //     'price' => 8099.99,
    //     'slug' => 'notebook-dell'
    // ]);
    // //$product foi criado para ter a visualização do produto
    // dd($product);


    //CRIAR UMA CATEGORIA
    // \App\Category::create([
    //     'name' => 'Games',
    //     'description' => null,
    //     'slug' => 'games'
    // ]);
    // \App\Category::create([
    //     'name' => 'Notebooks',
    //     'description' => null,
    //     'slug' => 'notebooks'
    // ]);

    // return \App\Category::all();


    //ADICIONAR UM PRODUTO PARA UMA CATEGORIA (OU VICE-VERSA)
    //Pegar o produto 42
//     $product = \App\Product::find(42);
//     //product acessa a ligação e usa o método attach
//     //Attach irá adicionar o produto 49 a categoria de id 1
//     //Caso queira remover, Detach 
//     // dd($product ->categories()->attach([1]));
//     //Irá remover da categoria 1 e manter apenas ao 2
//     dd($product ->categories()->sync([2]));

// //------------------------------------------------------------------------------------------------------------------
//     return \App\User::all();




    // $product = \App\Product::find(49);
    // return $product->categories;
});