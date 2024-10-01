<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    use HasFactory;

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function product(){
        return $this->belongsTo(Product::class);
    }

    public function tax(){
        return $this->belongsTo(Tax::class);
    }

    public function unit(){
        return $this->belongsTo(Unit::class);
    }
}
