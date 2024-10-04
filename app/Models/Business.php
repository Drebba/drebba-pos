<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    use HasFactory;

    public function category(){
        return $this->hasMany(Category::class);
    }

    public function product(){
        return $this->hasMany(Product::class);
    }

    public function tax(){
        return $this->hasMany(Tax::class);
    }

    public function unit(){
        return $this->hasMany(Unit::class);
    }

}
