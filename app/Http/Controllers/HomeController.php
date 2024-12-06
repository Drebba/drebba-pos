<?php

namespace App\Http\Controllers;

use App\Models\Business;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Toastr;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class HomeController extends Controller
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


        $collection = collect(Auth::user()->business->product);
        $low_stock_products = $collection->sortBy('current_stock_quantity')->take(10);
        $trending_products = $collection->sortByDesc('total_sell_qty')->take(10);


        return view('backend/dashboard',[
            'low_stock_products' => $low_stock_products->values()->all(),
            'trending_products' => $trending_products->values()->all(),
        ]);
    }

    public function dashboardSellPurchaseData(){

            $business_id = Auth::user()->business_id;
            $sell_of_this_month = Auth::user()->business->sell()->whereMonth('sell_date', Carbon::now()->format('m-Y'))->sum('grand_total_price');
            $total_sell = Auth::user()->business->sell()->sum('grand_total_price');
            $purchase_of_this_month = Auth::user()->business->purchase()->whereMonth('purchase_date', Carbon::now()->format('m-Y'))->sum('total_amount');
            $total_purchase = Auth::user()->business->purchase()->sum('total_amount');


        $data = [];
        $data['sell_of_this_month'] = get_option('app_currency') . ' ' . number_format($sell_of_this_month, 2);
        $data['total_sell'] = get_option('app_currency') . ' ' . number_format($total_sell, 2);
        $data['purchase_of_this_month'] = get_option('app_currency') . ' ' .number_format($purchase_of_this_month, 2);
        $data['total_purchase'] = get_option('app_currency') . ' ' .number_format($total_purchase, 2);

        return $data;
    }


    public function last10DaysProfitLoss(){
        $profit_info = [];
        $key = 0;
        for ($i=9; $i >= 0 ; $i--) {
            $date = Carbon::now()->subDay($i)->format('Y-m-d');
            $profit_info[$key]['date'] = Carbon::parse($date)->format(get_option('app_date_format'));
            $profit_info[$key]['income'] = number_format($this->incomeByDate($date), 2);
            $profit_info[$key]['expense'] = number_format($this->expenseByDate($date), 2);
            $profit_info[$key]['profit_loss'] = number_format(($this->incomeByDate($date) - $this->expenseByDate($date)), 2);
            $key++;
        }

        return $profit_info;
    }

    private function expenseByDate($date){

            $business_id = Auth::user()->business_id;

            $expense =  Auth::user()->business->expense()->where('expense_date', $date)->sum('amount');
            $supplier_payment = Auth::user()->business->paymenttosupplier()->where('payment_date', $date)->sum('amount');

        return  $expense + $supplier_payment;
    }

    private function incomeByDate($date){
            $sell =  Auth::user()->business->sell()->where('sell_date', $date)->sum('paid_amount');
            $payment_from_customer = Auth::user()->business->paymentfromcustomer()->where('payment_date', $date)->sum('amount');
        return  $sell + $payment_from_customer;
    }


    public function menu($uuid)  {
        $business=Business::where('uuid',$uuid)->firstOrFail();
        $menus=$business->product;
        return view('menu',compact('business','menus'));
    }



}
