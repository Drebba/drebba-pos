<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests\PaymentToSupplierRequest;
use App\Models\Branch;
use App\Models\PaymentToSupplier;
use App\Models\Supplier;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Toastr;

class PaymentToSupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if (!Auth::user()->can('manage_supplier_payment')) {
            return redirect('home')->with(denied());
        } // end permission checking

        $payments = Auth::user()->business->paymenttosupplier()->orderBY('id', 'DESC');


        if ($request->business_id){
            $payments = $payments->where('business_id', $request->business_id);
        }

        if ($request->supplier_id){
            $payments = $payments->where('supplier_id', $request->supplier_id);
        }

        if ($request->start_date || $request->end_date ){
            $start_date = $request->start_date ? $request->start_date : PaymentToSupplier::oldest()->pluck('payment_date')->first();
            $end_date = $request->end_date ? $request->end_date : PaymentToSupplier::latest()->pluck('payment_date')->first();

            $payments = $payments->whereBetween('payment_date', [$start_date, $end_date]);
        }

        $payments = $payments->paginate(50);

        return view('backend.payment.supplier.index',[
            'payments' => $payments,
            'suppliers' =>  Auth::user()->business->supplier,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Auth::user()->can('manage_supplier_payment')) {
            return redirect('home')->with(denied());
        } // end permission checking

        return view('backend.payment.supplier.create',[
            'suppliers' => Auth::user()->business->supplier()->where('status', 1)->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PaymentToSupplierRequest $request)
    {
        if (!Auth::user()->can('manage_supplier_payment')) {
            return redirect('home')->with(denied());
        } // end permission checking

        $payment = new PaymentToSupplier();
        $payment->fill($request->all());
        $payment->business_id=Auth::user()->business_id;
        $payment->save();

        Toastr::success('Payment successfully saved', '', ['progressBar' => true, 'closeButton' => true, 'positionClass' => 'toast-bottom-right']);
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!Auth::user()->can('manage_supplier_payment')) {
            return redirect('home')->with(denied());
        } // end permission checking

       $payment = Auth::user()->business->paymenttosupplier()->findOrFail($id);
        if (!Auth::user()->can('access_to_all_branch')) {
            if ($payment->business_id != Auth::user()->business_id){
                return redirect('home')->with(denied());
            }
        }

        return view('backend.payment.supplier.edit',[
            'payment' => $payment,
            'suppliers' => Auth::user()->business->supplier,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $payment = Auth::user()->business->paymenttosupplier()->findOrFail($id);
        if (!Auth::user()->can('access_to_all_branch')) {
            if ($payment->business_id != Auth::user()->business_id){
                return redirect('home')->with(denied());
            }
        }

        $payment->fill($request->all());
        $payment->save();

        Toastr::success('Payment successfully updated', '', ['progressBar' => true, 'closeButton' => true, 'positionClass' => 'toast-bottom-right']);
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $payment = Auth::user()->business->paymenttosupplier()->findOrFail($id);
        if (!Auth::user()->can('access_to_all_branch')) {
            if ($payment->business_id != Auth::user()->business_id){
                return redirect('home')->with(denied());
            }
        }

        $payment->delete();

        Toastr::error('Payment successfully deleted', '', ['progressBar' => true, 'closeButton' => true, 'positionClass' => 'toast-bottom-right']);
        return redirect()->back();
    }

    public function pdf(Request $request)
    {
        $business_id = $request->business_id ? [$request->business_id] : PaymentToSupplier::select('business_id')->distinct()->get();
        $supplier_id = $request->supplier_id ? [$request->supplier_id] : PaymentToSupplier::select('supplier_id')->distinct()->get();
        $start_date = $request->start_date ? $request->start_date : PaymentToSupplier::oldest()->pluck('payment_date')->first();
        $end_date = $request->end_date ? $request->end_date : PaymentToSupplier::latest()->pluck('payment_date')->first();

        $payments = PaymentToSupplier::whereIn('business_id', $business_id)
            ->whereIn('supplier_id', $supplier_id)
            ->whereBetween('payment_date', [$start_date, $end_date])
            ->orderBY('id', 'DESC')
            ->get();


        if ($request->business_id != ''){
            $branch =  Branch::findOrFail($request->business_id);
            $branch_name = $branch->title;
        }else{
            $branch_name = 'All';
        }

        if ($request->supplier_id != ''){
            $supplier =  Supplier::findOrFail($request->supplier_id);
            $supplier_name = $supplier->company_name;
        }else{
            $supplier_name = 'All';
        }


        $filter_by = [];
        $filter_by['branch_name'] = $branch_name;
        $filter_by['supplier_name'] = $supplier_name;
        $filter_by['start_date'] = Carbon::parse($start_date)->format(get_option('app_date_format'));
        $filter_by['end_date'] = Carbon::parse($end_date)->format(get_option('app_date_format'));;

        $random_string = Str::random(10);
        $pdf = PDF::loadView('backend.pdf.payment.supplier', compact('payments', 'filter_by'))->setPaper('a4');

        $pdf->save('pdf/payment/supplier/' . 'payment-to-supplier-' . Carbon::now()->format('Y-m-d') . '-'. $random_string . '.pdf');
        return redirect('pdf/payment/supplier/' . 'payment-to-supplier-' . Carbon::now()->format('Y-m-d') . '-'. $random_string .'.pdf');
    }
}
