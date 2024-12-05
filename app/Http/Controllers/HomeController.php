<?php

namespace App\Http\Controllers;

use App\Exports\DatabaseExport;
use App\Models\Business;
use App\Models\Expense;
use App\Models\PaymentFromCustomer;
use App\Models\PaymentToSupplier;
use App\Models\Purchase;
use App\Models\Sell;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Toastr;
use Auth;
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
            $sell_of_this_month = Sell::where('business_id', $business_id)->whereMonth('sell_date', Carbon::now()->format('m-Y'))->sum('grand_total_price');
            $total_sell = Sell::where('business_id', $business_id)->sum('grand_total_price');
            $purchase_of_this_month = Purchase::where('business_id', $business_id)->whereMonth('purchase_date', Carbon::now()->format('m-Y'))->sum('total_amount');
            $total_purchase = Purchase::where('business_id', $business_id)->sum('total_amount');


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
        if (Auth::user()->can('access_to_all_branch')){
            $expense =  Expense::where('expense_date', $date)->sum('amount');
            $supplier_payment = PaymentToSupplier::where('payment_date', $date)->sum('amount');
        }else{
            $business_id = Auth::user()->business_id;

            $expense =  Expense::where('business_id', $business_id)->where('expense_date', $date)->sum('amount');
            $supplier_payment = PaymentToSupplier::where('business_id', $business_id)->where('payment_date', $date)->sum('amount');
        }

        return  $expense + $supplier_payment;
    }

    private function incomeByDate($date){
        if (Auth::user()->can('access_to_all_branch')){
            $sell =  Sell::where('sell_date', $date)->sum('paid_amount');
            $payment_from_customer = PaymentFromCustomer::where('payment_date', $date)->sum('amount');
        }else{
            $business_id = Auth::user()->business_id;

            $sell =  Sell::where('business_id', $business_id)->where('sell_date', $date)->sum('paid_amount');
            $payment_from_customer = PaymentFromCustomer::where('business_id', $business_id)->where('payment_date', $date)->sum('amount');
        }

        return  $sell + $payment_from_customer;
    }


    public function menu($uuid)  {
        $business=Business::where('uuid',$uuid)->firstOrFail();
        $menus=$business->product;
        return view('menu',compact('business','menus'));
    }


    public function backup(){
        if (!Auth::user()->can('manage_backup')){
            abort(403);
        }
        return view('backup',compact('backup'));
        $business_id = Auth::user()->business_id;
        return Excel::download(new DatabaseExport($business_id), 'database_export.xlsx');


    }
}
