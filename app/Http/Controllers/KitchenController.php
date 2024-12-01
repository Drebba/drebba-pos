<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Expense;
use App\Models\PaymentFromCustomer;
use App\Models\PaymentToSupplier;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sell;
use App\Models\SellsTarget;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class KitchenController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        if(!Auth::user()->can('manage_kitchen')){
            abort(403);
        }
        if(request()->query('api')){
      $orders=Auth::user()->business->sell()->whereDate('sell_date',today())->where('status',0)->latest()->get();
      $orders = $orders->map(function ($order) {
        return [
            'id' => $order->id,
            'invoice_id' => $order->invoice_id,
            'orderMode'=>$order->orderMode->name,
            'table'=>$order->table?->name??null,
            'products' => $order->sellProducts->map(function ($product) {
                return [
                    'product_id' => $product->id,
                    'name' => $product->product->title,
                    'qty' => $product->quantity,
                ];
            })->toArray(),
        ];
    });
    return response()->json($orders);
        }
      return view('kitchen');

    }

}
