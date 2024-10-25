<?php

namespace App\Http\Controllers\Backend;

use App\Models\Branch;
use App\Models\Expense;
use App\Models\PaymentFromCustomer;
use App\Models\PaymentToSupplier;
use App\Models\Purchase;
use App\Models\Sell;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProfitLoosReportController extends Controller
{


    public function index(Request $request){
        $business_id = Auth::user()->business_id;

        if ($request->search_type != 'year'){
            $start_date = Carbon::parse($request->month??today())->startOfMonth($request->month)->format('Y-m-d');
            $end_date = Carbon::parse($request->month??today())->endOfMonth($request->month)->format('Y-m-d');

            $profit_info = [];
            foreach (CarbonPeriod::create($start_date, $end_date) as $key => $date) {
                $profit_info[$key]['date'] = Carbon::parse($date)->format(get_option('app_date_format'));
                $profit_info[$key]['income'] = $this->incomeByDate($date, $business_id);
                $profit_info[$key]['expense'] = $this->expenseByDate($date, $business_id);

                $profit_info[$key]['profit_loss'] = $this->incomeByDate($date, $business_id) - $this->expenseByDate($date, $business_id);
            }
        }else{
            $year = $request->year ? $request->year : Carbon::now()->format('Y');
            $profit_info = [];

            for ($i=0; $i < 12 ; $i++){
                $month_name = $this->monthName($i);

                $profit_info[$i]['date'] = $month_name;
                $profit_info[$i]['income'] = $this->incomeByMonth($year, $i+1, $business_id);
                $profit_info[$i]['expense'] = $this->expenseByMonth($year, $i+1, $business_id);
                $profit_info[$i]['profit_loss'] = $this->incomeByMonth($year, $i+1, $business_id) - $this->expenseByMonth($year, $i+1, $business_id);
            }
        }


        return view('backend.report.profit.index',[
            'profit_info' => $profit_info,
        ]);
    }

    private function expenseByDate($date, $business_id){
        $expense =  Auth::user()->business->expense()->where('expense_date', $date)->sum('amount');
        $supplier_payment =  Auth::user()->business->paymenttosupplier()->where('payment_date', $date)->sum('amount');

        return  $expense + $supplier_payment;
    }

    private function incomeByDate($date, $business_id){
        $sell =  Auth::user()->business->sell()->where('sell_date', $date)->sum('paid_amount');
        $payment_from_customer = Auth::user()->business->paymentfromcustomer()->where('payment_date', $date)->sum('amount');

        return  $sell + $payment_from_customer;
    }

    private function monthName($i){
        $dateObj   = Carbon::createFromFormat('!m', $i+1);
        $month_name = $dateObj->format('F');

        return $month_name;
    }

    private function incomeByMonth($year, $month, $business_id){
        $sell =  Sell::whereIn('business_id', $business_id)->whereYear('sell_date', '=', $year)
            ->whereMonth('sell_date', '=', $month)
            ->sum('paid_amount');

        $payment_from_customer = PaymentFromCustomer::whereIn('business_id', $business_id)->whereYear('payment_date', '=', $year)
            ->whereMonth('payment_date', '=', $month)
            ->sum('amount');

        return  $sell + $payment_from_customer;
    }

    private function expenseByMonth($year, $month, $business_id){
        $expense =  Expense::whereIn('business_id', $business_id)->whereYear('expense_date', '=', $year)
            ->whereMonth('expense_date', '=', $month)
            ->sum('amount');

        $supplier_payment = PaymentToSupplier::whereIn('business_id', $business_id)->whereYear('payment_date', '=', $year)
            ->whereMonth('payment_date', '=', $month)
            ->sum('amount');

        return  $expense + $supplier_payment;
    }
}
