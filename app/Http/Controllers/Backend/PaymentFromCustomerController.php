<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests\PaymentFromCustomerRequest;
use App\Models\PaymentFromCustomer;
use App\Models\Sell;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Toastr;

class PaymentFromCustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!Auth::user()->can('manage_customer_payment')) {
            return redirect('home')->with(denied());
        } // end permission checking
        $start_date = $request->start_date ? Carbon::parse($request->start_date) : today()->subWeek(1);
        $end_date = $request->end_date ? Carbon::parse($request->end_date) : today();

        if ($start_date->diffInMonths($end_date) > 3) {
            Toastr::error('Select date range should be less than 3 month', '', ['progressBar' => true, 'closeButton' => true, 'positionClass' => 'toast-bottom-right']);
            return redirect()->back();
        }
       $sells = Auth::user()->business->sell()
           ->where('due_amount', '>', 'paid_amount');

       if ($request->customer_id){
           $sells = $sells->where('customer_id', $request->customer_id);
       }

        $sells = $sells->whereBetween('sell_date', [$start_date, $end_date]);
        $sells = $sells->with('customer')->paginate(50);

        return view('backend.payment.customer.index',[
            'due_sells' => $sells,
            'customers' => Auth::user()->business->customer,
        ]);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Auth::user()->can('manage_customer_payment')) {
            return redirect('home')->with(denied());
        } // end permission checking

        return view('backend.payment.customer.create',[
            'customers' => Auth::user()->business->customer()->where('status', 1)->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PaymentFromCustomerRequest $request)
    {
        if (!Auth::user()->can('manage_supplier_payment')) {
            return redirect('home')->with(denied());
        } // end permission checking

        $sell = Auth::user()->business->sell()->findOrFail($request->sell_id);
        if ($sell->due_amount >= $request->amount ){
            $sell->paid_amount = $sell->paid_amount + $request->amount;
            $sell->due_amount = $sell->due_amount - $request->amount;
            $sell->save();

            $payment = new PaymentFromCustomer();
            $payment->fill($request->all());
            $payment->business_id =  Auth::user()->business_id;
            $payment->customer_id = $sell->customer_id;
            $payment->sell_id = $sell->id;
            $payment->save();

            Toastr::success('Payment successfully saved', '', ['progressBar' => true, 'closeButton' => true, 'positionClass' => 'toast-bottom-right']);
            return redirect()->back();
        }else{
            Toastr::error('Paid amount should be smaller than or equal of due amount', '', ['progressBar' => true, 'closeButton' => true, 'positionClass' => 'toast-bottom-right']);
            return redirect()->back();
        }
    }


}
