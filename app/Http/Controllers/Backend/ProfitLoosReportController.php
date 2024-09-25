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
use Auth;

class ProfitLoosReportController extends Controller
{
    public function index(){
        $profit_info = [];
        $key = 0;
        for ($i=29; $i >= 0 ; $i--) {
            $date = Carbon::now()->subDay($i)->format('Y-m-d');

            $sell =  Sell::where('sell_date', $date)->sum('paid_amount');
            $payment_from_customer = PaymentFromCustomer::where('payment_date', $date)->sum('amount');

            $expense =  Expense::where('expense_date', $date)->sum('amount');
            $supplier_payment = PaymentToSupplier::where('payment_date', $date)->sum('amount');


            $profit_info[$key]['date'] = $date;
            $profit_info[$key]['income'] = $sell + $payment_from_customer;
            $profit_info[$key]['expense'] = $expense + $supplier_payment;
            $profit_info[$key]['profit_loss'] = ($sell + $payment_from_customer) - ($expense + $supplier_payment);
            $key++;
        }

        return view('backend.report.profit.index',[
            'branches' => Branch::all(),
            'profit_info' => $profit_info,
        ]);
    }

    public function filter(Request $request){
        $branch_id = $request->branch_id ? [$request->branch_id] : Sell::distinct('branch_id')->pluck('branch_id')->all();

        if ($request->search_type == 'month'){
            $start_date = Carbon::parse($request->month)->startOfMonth($request->month)->format('Y-m-d');
            $end_date = Carbon::parse($request->month)->endOfMonth($request->month)->format('Y-m-d');

            $profit_info = [];
            foreach (CarbonPeriod::create($start_date, $end_date) as $key => $date) {
                $profit_info[$key]['date'] = Carbon::parse($date)->format(get_option('app_date_format'));
                $profit_info[$key]['income'] = $this->incomeByDate($date, $branch_id);
                $profit_info[$key]['expense'] = $this->expenseByDate($date, $branch_id);
                $profit_info[$key]['profit_loss'] = $this->incomeByDate($date, $branch_id) - $this->expenseByDate($date, $branch_id);
            }
        }else{
            $year = $request->year ? $request->year : Carbon::now()->format('Y');
            $profit_info = [];

            for ($i=0; $i < 12 ; $i++){
                $month_name = $this->monthName($i);

                $profit_info[$i]['date'] = $month_name;
                $profit_info[$i]['income'] = $this->incomeByMonth($year, $i+1, $branch_id);
                $profit_info[$i]['expense'] = $this->expenseByMonth($year, $i+1, $branch_id);
                $profit_info[$i]['profit_loss'] = $this->incomeByMonth($year, $i+1, $branch_id) - $this->expenseByMonth($year, $i+1, $branch_id);
            }
        }


        return view('backend.report.profit.filter-result',[
            'branches' => Branch::all(),
            'profit_info' => $profit_info,
        ]);
    }

    private function expenseByDate($date, $branch_id){
        $expense =  Expense::whereIn('branch_id', $branch_id)->where('expense_date', $date)->sum('amount');
        $supplier_payment = PaymentToSupplier::whereIn('branch_id', $branch_id)->where('payment_date', $date)->sum('amount');

        return  $expense + $supplier_payment;
    }

    private function incomeByDate($date, $branch_id){
        $sell =  Sell::whereIn('branch_id', $branch_id)->where('sell_date', $date)->sum('paid_amount');
        $payment_from_customer = PaymentFromCustomer::whereIn('branch_id', $branch_id)->where('payment_date', $date)->sum('amount');

        return  $sell + $payment_from_customer;
    }

    private function monthName($i){
        $dateObj   = Carbon::createFromFormat('!m', $i+1);
        $month_name = $dateObj->format('F');

        return $month_name;
    }

    private function incomeByMonth($year, $month, $branch_id){
        $sell =  Sell::whereIn('branch_id', $branch_id)->whereYear('sell_date', '=', $year)
            ->whereMonth('sell_date', '=', $month)
            ->sum('paid_amount');

        $payment_from_customer = PaymentFromCustomer::whereIn('branch_id', $branch_id)->whereYear('payment_date', '=', $year)
            ->whereMonth('payment_date', '=', $month)
            ->sum('amount');

        return  $sell + $payment_from_customer;
    }

    private function expenseByMonth($year, $month, $branch_id){
        $expense =  Expense::whereIn('branch_id', $branch_id)->whereYear('expense_date', '=', $year)
            ->whereMonth('expense_date', '=', $month)
            ->sum('amount');

        $supplier_payment = PaymentToSupplier::whereIn('branch_id', $branch_id)->whereYear('payment_date', '=', $year)
            ->whereMonth('payment_date', '=', $month)
            ->sum('amount');

        return  $expense + $supplier_payment;
    }
}
