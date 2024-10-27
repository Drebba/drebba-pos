<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    use HasFactory;

    public function sell()
    {
        return $this->belongsTo(Sell::class, 'current_sell_id','id');
    }
}
