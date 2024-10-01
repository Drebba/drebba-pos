<?php

namespace App\Http\Middleware\Scope;

use App\Models\Customer;
use App\Models\Employee;
use App\Models\Expense;
use App\Models\PaymentFromCustomer;
use App\Models\PaymentToSupplier;
use App\Models\Purchase;
use App\Models\PurchaseProduct;
use App\Models\Requisition;
use App\Models\Sell;
use App\Models\SellProduct;
use App\User;
use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WebRoueScopeMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()){
            if (!Auth::user()->can('access_to_all_branch')) {
                Sell::addGlobalScope('branch', function (Builder $builder) {
                    $builder->where('business_id',  Auth::user()->business_id);
                });

                SellProduct::addGlobalScope('branch', function (Builder $builder) {
                    $builder->where('business_id',  Auth::user()->business_id);
                });

                Purchase::addGlobalScope('branch', function (Builder $builder) {
                    $builder->where('business_id',  Auth::user()->business_id);
                });

                PurchaseProduct::addGlobalScope('branch', function (Builder $builder) {
                    $builder->where('business_id',  Auth::user()->business_id);
                });

                Requisition::addGlobalScope('branch', function (Builder $builder) {
                    $builder->where('requisition_from', Auth::user()->business_id);
                    $builder->orWhere('requisition_to', Auth::user()->business_id);
                });

                Expense::addGlobalScope('branch', function (Builder $builder) {
                    $builder->where('business_id',  Auth::user()->business_id);
                });


                PaymentToSupplier::addGlobalScope('branch', function (Builder $builder) {
                    $builder->where('business_id',  Auth::user()->business_id);
                });

                PaymentFromCustomer::addGlobalScope('branch', function (Builder $builder) {
                    $builder->where('business_id',  Auth::user()->business_id);
                });

                Employee::addGlobalScope('branch', function (Builder $builder) {
                    $builder->where('business_id',  Auth::user()->business_id);
                });
            }


            Sell::addGlobalScope('withAndOrder', function (Builder $builder) {
                $builder->with('customer', 'branch');
                $builder->orderByDesc('id');
            });

            SellProduct::addGlobalScope('withAndOrder', function (Builder $builder) {
                $builder->orderByDesc('id');
            });

            Purchase::addGlobalScope('withAndOrder', function (Builder $builder) {
                $builder->with('supplier', 'branch');
                $builder->orderByDesc('id');
            });

            PurchaseProduct::addGlobalScope('withAndOrder', function (Builder $builder) {
                $builder->orderByDesc('id');
            });

            Requisition::addGlobalScope('withAndOrder', function (Builder $builder) {
                $builder->with('requisitionTo', 'requisitionFrom');
                $builder->orderByDesc('id');
            });

            Expense::addGlobalScope('withAndOrder', function (Builder $builder) {
                $builder->with('expenseCategory', 'branch');
                $builder->orderByDesc('id');
            });


            PaymentToSupplier::addGlobalScope('withAndOrder', function (Builder $builder) {
                $builder->with('supplier', 'branch');
                $builder->orderByDesc('id');
            });

            PaymentFromCustomer::addGlobalScope('withAndOrder', function (Builder $builder) {
                $builder->with('customer', 'branch');
                $builder->orderByDesc('id');
            });

            Employee::addGlobalScope('withAndOrder', function (Builder $builder) {
                $builder->with('user', 'branch', 'department', 'designation');
                $builder->orderByDesc('id');
            });
        }

        return $next($request);
    }
}
