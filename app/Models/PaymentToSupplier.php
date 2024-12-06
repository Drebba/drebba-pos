<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class PaymentToSupplier extends Model
{

    protected $fillable = ['payment_date','supplier_id','amount','note'];

    protected $dates = ['payment_date'];



    public function supplier()
    {
        return $this->belongsTo(Supplier::class)->withTrashed();
    }

}
