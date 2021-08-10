<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $fillable = ['name', 'description', 'slug'];

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value, '-');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'categories_products');
    }
}