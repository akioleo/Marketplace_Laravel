<?php

use Illuminate\Database\Seeder;

class StoreTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Todas as lojas (all)
        //Buscando as lojas que tem no banco e fazendo loop delas, e pra cada loja chama método products
        $stores = \App\Store::all();
        //Ligação store com products, chamando factory pro model Product
        //$store -> $products: Adicionar um produto para cada loja
        foreach($stores as $store)
        {
            $store->products()->save(factory(\App\Product::class)->make());
        }
    }
}
