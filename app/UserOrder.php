<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserOrder extends Model
{
    protected $fillable = ['reference', 'pagseguro_status', 'pagseguro_code', 'store_id', 'items'];
    
    public function user()
    {
        //Pedido pertence a um usuário (saber qual usuário o pedido pertence)
        return $this->belongsTo(User::class);
    }
    
    public function store()
    {
        //Pedido pertence a uma loja (saber qual loja o pedido pertence)
        return $this->belongsTo(Store::class);
    }
}