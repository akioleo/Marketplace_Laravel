<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(\App\Product::class, function (Faker $faker) {
    return [
        'name' => $faker -> name,
        'description' => $faker -> sentence,
        //5 paragrafos e true para retornar string
        'body' => $faker -> paragraph(5, true),
        //2 casas decimais e mínimo tamanho 1 e máximo 10 
        'price' => $faker -> randomFloat(2, 1,10),
        'slug' => $faker -> slug,
    ];
});
