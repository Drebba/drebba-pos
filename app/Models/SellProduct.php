<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class SellProduct extends Model
{
    use SoftDeletes;

    public function product()
    {
        return $this->belongsTo(Product::class)
            ->with('tax')
            ->with('unit')
            ->withTrashed();
    }

    protected $fillable = [
        'purchase_price','sell_price','tax_percentage','tax_amount','quantity','total_price'
    ];
}
