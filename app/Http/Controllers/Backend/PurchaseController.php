<?php

namespace App\Http\Controllers\Backend;

use App\Models\PaymentToSupplier;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseProduct;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Sell;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade as PDF;

class PurchaseController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!Auth::user()->can('manage_purchase_invoice')) {
            return redirect('home')->with(denied());
        } // end permission checking

        $purchases = Auth::user()->business->purchase()->orderBy('id', 'DESC');
        if ($request->supplier_id){
            $purchases = $purchases->where('supplier_id', $request->supplier_id);
        }

        if ($request->start_date != '' || $request->end_date != ''){
            $start_date = $request->start_date ;
            $end_date = $request->end_date;

            $purchases = $purchases->whereBetween('purchase_date', [$start_date, $end_date]);
        }

        if ($request->invoice_id){
            $purchases = $purchases->where('invoice_id', 'like', '%'.$request->invoice_id.'%');
        }

        $purchases = $purchases->paginate(50);


        return view('backend.purchase.index',[
            'purchases' => $purchases,
            'suppliers' => Auth::user()->business->supplier,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Auth::user()->can('create_purchase_invoice')) {
            return redirect('home')->with(denied());
        } // end permission checking


        return view('backend.purchase.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Auth::user()->can('create_purchase_invoice')) {
            return redirect('home')->with(denied());
        } // end permission checking

        $purchase = new Purchase();
        $purchase->supplier_id = $request->supplier['id'];
        $purchase->total_amount = $request->summary['total_amount'];
        $purchase->paid_amount = $request->summary['paid_amount'];
        $purchase->due_amount = $request->summary['due_amount'];
        $purchase->save();

        $this->savePurchaseProducts($request, $purchase);

        if ($purchase->paid_amount > 0){
            $payment = new PaymentToSupplier();
            $payment->supplier_id = $purchase->supplier_id;
            $payment->purchase_id = $purchase->id;
            $payment->payment_date = $purchase->purchase_date;
            $payment->amount = $purchase->paid_amount;
            $payment->save();
        }

        $data['purchase'] = Purchase::where('id', $purchase->id)->with('purchaseProducts')->with('supplier')->first();
        $data['custom_purchase_date'] = Carbon::parse($purchase->created_at)->format(get_option('app_date_format'). ', h:ia');
        return response($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!Auth::user()->can('manage_purchase_invoice')) {
            return redirect('home')->with(denied());
        } // end permission checking


        $purchase = Auth::user()->business->purchase()->where('id',$id)->firstOrFail();
        if (!Auth::user()->can('access_to_all_branch')) {
            if ($purchase->business_id != Auth::user()->business_id){
                return redirect()->back()->with(denied());
            }
        }

        return view('backend.purchase.show', [
            'purchase' => $purchase
        ]);
    }

    public function pdf($purchase_id, $action_type){
        if (!Auth::user()->can('manage_purchase_invoice')) {
            return redirect('home')->with(denied());
        } // end permission checking

        $purchase =Auth::user()->business->purchase()->where('id',$id)->firstOrFail();
        if (!Auth::user()->can('access_to_all_branch')) {
            if ($purchase->business_id != Auth::user()->business_id){
                return redirect()->back()->with(denied());
            }
        }

        $pdf = PDF::loadView('backend.pdf.purchase.invoice', compact('purchase'))->setPaper('a4');

        if ($action_type == 'pdf'){
            return $pdf->download($purchase->invoice_id . '.pdf');
        }else{
            $pdf->save('pdf/purchase/' . $purchase->invoice_id . '.pdf');
            return redirect('/pdf/purchase/' . $purchase->invoice_id .'.pdf');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!Auth::user()->can('manage_purchase_invoice')) {
            return redirect('home')->with(denied());
        } // end permission checking

        $purchase =  Auth::user()->business->purchase()->findOrFail($id);
        if (!Auth::user()->can('access_to_all_branch')) {
            if ($purchase->business_id != Auth::user()->business_id){
                return redirect()->back()->with(denied());
            }
        }

        return view('backend.purchase.edit', [
            'purchase' => $purchase
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
        if (!Auth::user()->can('manage_purchase_invoice')) {
            return redirect('home')->with(denied());
        } // end permission checking

        $purchase = Auth::user()->business->purchase()->where('id',$id)->firstOrFail();
        $purchase->supplier_id = $request->supplier['id'];
        $purchase->total_amount = $request->summary['total_amount'];
        $purchase->paid_amount = $request->summary['paid_amount'];
        $purchase->due_amount = $request->summary['due_amount'];
        $purchase->save();

        Auth::user()->business->purchaseProduct()->where('purchase_id',$id)->delete();
        $this->updatePurchaseProducts($request, $purchase);

        $check_payment = PaymentToSupplier::where('purchase_id', $purchase->id)->count();
        if ($check_payment > 0){
            $payment = PaymentToSupplier::where('purchase_id', $purchase->id)->first();
            $payment->amount = $purchase->paid_amount;
            $payment->save();
        }else{
            if ($purchase->paid_amount > 0){
                $payment = new PaymentToSupplier();
                $payment->supplier_id = $purchase->supplier_id;
                $payment->purchase_id = $purchase->id;
                $payment->payment_date = $purchase->purchase_date;
                $payment->amount = $purchase->paid_amount;
                $payment->save();
            }
        }

        $data =Auth::user()->business->purchase()->where('id',$id)
            ->with('purchaseProducts')
            ->with('supplier')
            ->first();

        $data['custom_purchase_date'] = Carbon::parse($purchase->created_at)->format(get_option('app_date_format'). ', h:ia');
        return response($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!Auth::user()->can('manage_purchase_invoice')) {
            return redirect('home')->with(denied());
        } // end permission checking


        Auth::user()->business->purchaseProduct()->where('purchase_id',$id)->firstOrFail();
        Auth::user()->business->purchase()->where('id',$id)->delete();
        return redirect()->back()->with('success','Purchase has been deleted successfully');
    }

    public function getPurchaseDetails($id)
    {
        return  Purchase::where('id', $id)->with('purchaseProducts')->with('supplier')->first();
    }



    private function savePurchaseProducts($request, $purchase)
    {
        foreach ($request->carts as $cart_product) {
            $purchase_product = new PurchaseProduct();
            $purchase_product->purchase_id = $purchase->id;
            $purchase_product->product_id = $cart_product['id'];
            $purchase_product->purchase_date = $purchase->purchase_date;
            $purchase_product->business_id = $purchase->business_id;
            $purchase_product->fill($cart_product);
            $purchase_product->total_price = $cart_product['purchase_price'] * $cart_product['quantity'];
            $purchase_product->save();
        }
    }

    private function updatePurchaseProducts($request, $purchase)
    {
        foreach ($request->carts as $cart_product) {
            $purchase_product = new PurchaseProduct();
            $purchase_product->purchase_id = $purchase->id;
            $purchase_product->product_id = $cart_product['id'];
            $purchase_product->purchase_date = $purchase->purchase_date;
            $purchase_product->business_id = $purchase->business_id;
            $purchase_product->fill($cart_product);
            $purchase_product->total_price = $cart_product['purchase_price'] * $cart_product['quantity'];
            $purchase_product->save();

        }
    }
}
