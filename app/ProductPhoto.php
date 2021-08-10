<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductPhoto extends Model
{
    protected $fillable = ['image'];
    public function product()
    {
        //Essa imagem pertence à um produto
        return $this ->belongsTo(Product::class);
    }
}
