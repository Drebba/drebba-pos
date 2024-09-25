<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequisitionProduct extends Model
{
    protected $fillable = [
        'quantity'
    ];


    public function product()
    {
        return $this->belongsTo(Product::class)
            ->with('unit')
            ->withTrashed();
    }

}
