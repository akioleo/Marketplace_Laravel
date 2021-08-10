<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
                // //Irá no db principal na tabela users e inserir os dados do array
        // \DB::table('users')->insert(
        //     [
        //         'name' => 'Administrator',
        //         'email' => 'admin@admin.com',
        //         'email_verified_at' => now(),
        //         'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        //         'remember_token' => 'aaaaaaa',
        //     ]
        // );

            //Model com 40 usuários
            //User chama a ligação store(1:1), criando store, automaticamente irá relacionar um usuário com id à ela
            //Make irá criar um objeto Store com as informações fakes
            // user -> store: Cria uma loja para cada usuário
            factory(\App\User::class, 40)->create()-> each(function($user){
            $user -> store()->save(factory(\App\Store::class)->make());
            //OBS: Método save trabalha com objetos, create com arrays
        });
    }
}
