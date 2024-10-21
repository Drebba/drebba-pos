<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

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

    public function supplier(){
        return $this->hasMany(Supplier::class);
    }

    public function purchase(){
        return $this->hasMany(Purchase::class);
    }

    public function purchaseProduct(){
        return $this->hasMany(PurchaseProduct::class);
    }

    public function customer(){
        return $this->hasMany(Customer::class);
    }

    public function sell(){
        return $this->hasMany(Sell::class);
    }
    public function sellProduct(){
        return $this->hasMany(SellProduct::class);
    }

    public function expenseCategory(){
        return $this->hasMany(ExpenseCategory::class);
    }

    public function expense(){
        return $this->hasMany(Expense::class);
    }

    public function paymenttosupplier(){
        return $this->hasMany(PaymentToSupplier::class);
    }

    public function paymentfromcustomer(){
        return $this->hasMany(PaymentToSupplier::class);
    }

    public function department(){
        return $this->hasMany(Department::class);
    }


    public function designation(){
        return $this->hasMany(Designation::class);
    }

    public function role(){
        return $this->hasMany(Role::class);
    }

    public function employee(){
        return $this->hasMany(Employee::class);
    }
}
