<?php

namespace App\Http\Controllers\Backend;

use App\Models\Branch;
use App\Models\Customer;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductStockHistory;
use App\Models\Sell;
use App\Models\SellProduct;
use App\Traits\ProductStcokDataTrait;
use Mpdf\Mpdf;
use PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Toastr;

class SellController extends Controller
{

    use ProductStcokDataTrait;


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!Auth::user()->can('manage_sell_invoice')) {
            return redirect('home')->with(denied());
        } // end permission checking


        $sells = Sell::orderBy('id', 'DESC');

        if ($request->branch_id){
            $sells = $sells->where('branch_id', $request->branch_id);
        }

        if ($request->customer_id){
            $sells = $sells->where('customer_id', $request->customer_id);
        }

        if ($request->start_date != '' || $request->end_date != ''){
            $start_date = $request->start_date ? $request->start_date : Sell::oldest()->pluck('sell_date')->first();
            $end_date = $request->end_date ? $request->end_date : Sell::latest()->pluck('sell_date')->first();

            $sells = $sells->whereBetween('sell_date', [$start_date, $end_date]);
        }

        if ($request->invoice_id){
            $sells = $sells->where('invoice_id', 'like', '%'.$request->invoice_id.'%');
        }

        $sells = $sells->with(['customer', 'branch'])->paginate(50);

        return view('backend.sell.index', [
            'sells' => $sells,
            'customers' => Customer::all(),
            'branches' => Branch::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Auth::user()->can('create_sell_invoice')) {
            return redirect('home')->with(denied());
        } // end permission checking


        return view('backend.sell.create',[
            'categories' => Category::orderBY('id', 'DESC')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Auth::user()->can('create_sell_invoice')) {
            return redirect('home')->with(denied());
        } // end permission checking


        if ($request->summary['change_amount'] > 0){
            $paid_amount = $request->summary['paid_amount'] - $request->summary['change_amount'];
        }else{
            $paid_amount = $request->summary['paid_amount'];
        }

        $sell = new Sell();
        $sell->customer_id = $request->customer['id'];
        $sell->fill($request->summary);
        $sell->paid_amount = $paid_amount;
        $sell->save();

        $this->storeSellProducts($request, $sell);

        $data['sell'] = Sell::where('id', $sell->id)->with('sellProducts')->with('customer')->first();
        $data['sell_date'] = Carbon::parse($sell->created_at)->format(get_option('app_date_format'). ', h:ia');
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
        if (!Auth::user()->can('manage_sell_invoice')) {
            return redirect('home')->with(denied());
        } // end permission checking

        $sell = Sell::with('sellProducts')->findOrFail($id);

        if (!Auth::user()->can('access_to_all_branch')) {
            if ($sell->branch_id != Auth::user()->branch_id){
                return redirect()->back()->with(denied());
            }
        }

        return view('backend.sell.show', [
            'sell' => $sell
        ]);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!Auth::user()->can('manage_sell_invoice')) {
            return redirect('home')->with(denied());
        } // end permission checking

        $sell = Sell::findOrFail($id);
        if (!Auth::user()->can('access_to_all_branch')) {
            if ($sell->branch_id != Auth::user()->branch_id){
                return redirect()->back()->with(denied());
            }
        }

        return view('backend.sell.edit', [
            'sell' => $sell,
            'categories' => Category::orderBY('id', 'DESC')->get(),
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
        if (!Auth::user()->can('manage_sell_invoice')) {
            return redirect('home')->with(denied());
        } // end permission checking

        if ($request->summary['change_amount'] > 0){
            $paid_amount = $request->summary['paid_amount'] - $request->summary['change_amount'];
        }else{
            $paid_amount = $request->summary['paid_amount'];
        }

        $sell = Sell::findOrFail($id);
        $sell->customer_id = $request->customer['id'];
        $sell->sub_total = $request->summary['sub_total'];
        $sell->discount = $request->summary['discount'];
        $sell->grand_total_price = $request->summary['grand_total'];
        $sell->due_amount = $request->summary['due_amount'];
        $sell->paid_amount = $paid_amount;
        $sell->save();

        SellProduct::where('sell_id', $id)->delete();
        $this->updateSellProducts($request, $sell);

        $data['sell'] = Sell::where('id', $sell->id)->with('sellProducts')->with('customer')->first();
        $data['sell_date'] = Carbon::parse($sell->created_at)->format(get_option('app_date_format'). ', h:ia');
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
        SellProduct::where('sell_id', $id)->delete();
        Sell::destroy($id);
        Toastr::success('Sell has been successfully Deleted', '', ['progressBar' => true, 'closeButton' => true, 'positionClass' => 'toast-bottom-right']);
        return redirect()->back();
    }

    public function getSellDetails($sell_id)
    {
        if (!Auth::user()->can('manage_sell_invoice')) {
            return redirect('home')->with(denied());
        } // end permission checking

       return $sell = Sell::where('id', $sell_id)->with('sellProducts')->with('customer')->first();

    }

//    public function pdf($sell_id, $action_type){
//        if (!Auth::user()->can('manage_sell_invoice')) {
//            return redirect('home')->with(denied());
//        } // end permission checking
//
//        $sell = Sell::findOrFail(decrypt($sell_id));
//        if (!Auth::user()->can('access_to_all_branch')) {
//            if ($sell->branch_id != Auth::user()->branch_id){
//                return redirect()->back()->with(denied());
//            }
//        }
//
//
//        $pdf = PDF::loadView('backend.pdf.sell.invoice', compact('sell'))->setPaper('a4');
//
//        if ($action_type == 1){
//            return $pdf->download($sell->invoice_id . '.pdf');
//        }else{
//            $pdf->save('pdf/sell/' . $sell->invoice_id . '.pdf');
//            return redirect('pdf/sell/' . $sell->invoice_id .'.pdf');
//        }
//    }

    public function pdf($sell_id, $action_type)
    {
        if (!Auth::user()->can('manage_sell_invoice')) {
            return redirect('home')->with('denied');
        }

        $sell = Sell::findOrFail(decrypt($sell_id));

        if (!Auth::user()->can('access_to_all_branch')) {
            if ($sell->branch_id != Auth::user()->branch_id){
                return redirect()->back()->with('denied');
            }
        }

        // Initialize mPDF with UTF-8 encoding and A4 page size
        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'orientation' => 'P',
            'autoScriptToLang' => true,
            'autoLangToFont' => true,
        ]);

        // Check if RTL support is needed
        if (session()->get('is_rtl_support') == 'yes') {
            $mpdf->SetDirectionality('rtl');
        }

        // Load the Blade view content
        $view = session()->get('is_rtl_support') == 'yes' ? 'backend.pdf.sell.rtl-invoice' : 'backend.pdf.sell.invoice';

        $html = view($view, compact('sell'))->render();

        // Write HTML to PDF
        $mpdf->WriteHTML($html);

        // Output the PDF
        $filename = $sell->invoice_id . '.pdf';
        if ($action_type == 1) {
            // Download PDF
            $mpdf->Output($filename, 'D');
        } else {
            // Display in browser
            $mpdf->Output($filename, 'I');
        }
    }

    public function printInvoice($sell_id){
        $sell = Sell::findOrFail($sell_id);
        $pdf = PDF::loadView('backend.pdf.sell.invoice', compact('sell'))->setPaper('a4');
        $pdf->save('pdf/sell/' . $sell->invoice_id . '.pdf');
        return redirect('pdf/sell/' . $sell->invoice_id .'.pdf');
    }

    private function storeSellProducts($request, $sell)
    {
        foreach ($request->carts as $cart_product) {
            $product = Product::findOrFail($cart_product['id']);


            $sell_product = new SellProduct();
            $sell_product->sell_id = $sell->id;
            $sell_product->product_id = $cart_product['id'];
            $sell_product->sell_date = $sell->sell_date;
            $sell_product->branch_id = $sell->branch_id;
            $sell_product->fill($cart_product);
            $sell_product->total_price = number_format($cart_product['total_price'], 2);
            $sell_product->save();

            $product->sell_price = $cart_product['sell_price'];
            $product->save();

            // Update Product Stock History
            $product_stock_history = ProductStockHistory::where('branch_id', $sell->branch_id)
                ->where('product_id', $product->id)
                ->first();

            if (!$product_stock_history){
                $product_stock_history = new ProductStockHistory();
                $product_stock_history->branch_id = $sell->branch_id;
                $product_stock_history->product_id = $product->id;
                $product_stock_history->save();
            }

            $product_stock_history->sell_qty += $sell_product->quantity;
            $product_stock_history->save();
        }
    }


    private function updateSellProducts($request, $sell){
        foreach ($request->carts as $cart_product) {
            $product = Product::findOrFail($cart_product['id']);


            $sell_product = new SellProduct();
            $sell_product->sell_id = $sell->id;
            $sell_product->product_id = $cart_product['id'];
            $sell_product->sell_date = $sell->sell_date;
            $sell_product->branch_id = $sell->branch_id;
            $sell_product->fill($cart_product);
            $sell_product->total_price = number_format($cart_product['total_price'], 2);
            $sell_product->save();

            $product->sell_price = $cart_product['sell_price'];
            $product->save();

            $this->meargeSellQty($product);
        }
    }
}
