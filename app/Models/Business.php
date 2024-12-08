<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class Business extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected static function boot()
    {
        parent::boot();
        self::created(function($model){
            //create owner from same email and phone
        $password=uniqid();
        $user=new User();
        $user->name=$model->name;
        $user->email=$model->email;
        $user->password=Hash::make($password);
        $user->role='owner';
        $user->business_id=$model->id;
        $user->save();

        //create employee
        $employee=new Employee();
        $employee->business_id=$model->id;
        $employee->user_id=$user->id;
        $employee->save();

        // attach owner id to busniess
        $model->owner_id=$user->id;
        $model->save();

        // create default customer
        $customer=new Customer();
        $customer->business_id=$model->id;
        $customer->name='Walk In customer';
        $customer->phone='98565656'.rand(1,100);
        $customer->email="email$model->id@gmail.com";
        $customer->save();

        //  // create default supplier Drebba
        //  $customer=new Customer();
        //  $customer->business_id=$model->id;
        //  $customer->name='Walk In customer';
        //  $customer->phone='98565656'.rand(1,100);
        //  $customer->email="email$model->id@gmail.com";
        //  $customer->save();
        });


        //TODO: send email password

    }

    public function user(){
        return $this->hasMany(User::class);
    }

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


    public function role(){
        return $this->hasMany(Role::class);
    }

    public function employee(){
        return $this->hasMany(Employee::class);
    }
    public function table(){
        return $this->hasMany(Table::class);
    }

    const BRT='Biratnagar';
    const KTM='Kathmandu';



    public static function getCity(){
        return [
            self::BRT,
            self::KTM
        ];
    }
}
