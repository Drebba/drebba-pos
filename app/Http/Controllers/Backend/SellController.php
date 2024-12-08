<?php

namespace App\Http\Controllers\Backend;

use App\Exports\SellExport;
use App\Models\Customer;
use App\Models\Category;
use App\Models\Product;
use App\Models\Sell;
use App\Models\SellProduct;
use Mpdf\Mpdf;
use PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Table;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Toastr;

class SellController extends Controller
{



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!Auth::user()->can('manage_sell_invoice')) {
            return redirect('home')->with('error', 'You do not have permission to manage sales invoices.');
        }

        $start_date = $request->start_date ? Carbon::parse($request->start_date) : today()->subWeek(1);
        $end_date = $request->end_date ? Carbon::parse($request->end_date) : today();

        if ($start_date->diffInMonths($end_date) > 3) {
            Toastr::error('Select date range should be less than 3 month', '', ['progressBar' => true, 'closeButton' => true, 'positionClass' => 'toast-bottom-right']);
            return redirect()->back();
        }

        $sells = Auth::user()->business->sell()
            ->when($request->customer_id, fn($query) => $query->where('customer_id', $request->customer_id))
            ->when($request->user_id, fn($query) => $query->where('created_by', $request->user_id))
            ->when($request->start_date && $request->end_date, fn($query) =>
                $query->whereBetween('sell_date', [$start_date, $end_date])
            )
            ->when($request->invoice_id, fn($query) =>
                $query->where('invoice_id', 'like', '%'.$request->invoice_id.'%')
            )
            ->when($request->payment_mode, fn($query) =>
            $query->where('payment_type', $request->payment_mode)
        )
            ->when($request->table_id, fn($query) => $query->where('table_id', $request->table_id))
            ->when($request->order_mode, fn($query) => $query->where('order_mode', $request->order_mode))
            ->with('customer')
            ->orderBy('id', 'DESC');

        if ($request->export) {
            return Excel::download(new SellExport($sells->get()), now() . ".xlsx");
        }

        return view('backend.sell.index', [
            'sells' => $sells->paginate(50),
            'customers' => Auth::user()->business->customer()->get(),
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
            'categories' => Auth::user()->business->category()->orderBY('id', 'DESC')->where('type',1)->get(),
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
        $sell->business_id = Auth::user()->business_id;
        $sell->fill($request->summary);
        $sell->paid_amount = $paid_amount;
        $sell->status=0;
        $sell->table_id=$request->table;
        $sell->order_mode=$request->order_mode;
        $sell->save();

        if(!$request->isKot){
            $sell->status=1;
            $sell->save();
        }

        $table=Table::find($request->table);
        if ($table&&$request->order_mode==2&&$request->isKot) {
        $table->status=1;
        $table->current_sell_id=$sell->id;
        $table->total_amount=$sell->grand_total_price;
        $table->save();
        }

        $this->storeSellProducts($request, $sell);

        $data['sell'] = Sell::where('id', $sell->id)->with('sellProducts')->with('customer')->first();
        $data['sell_date'] = Carbon::parse($sell->sell_date)->format(get_option('app_date_format'). ', h:ia');
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

        $sell = Auth::user()->business->sell()->with('sellProducts')->findOrFail($id);

        if (!Auth::user()->can('access_to_all_branch')) {
            if ($sell->business_id != Auth::user()->business_id){
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

        $sell =  Auth::user()->business->sell()->findOrFail($id);
        if (!Auth::user()->can('access_to_all_branch')) {
            if ($sell->business_id != Auth::user()->business_id){
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

        $sell =  Auth::user()->business->sell()->findOrFail($id);
        $sell->customer_id = $request->customer['id'];
        $sell->sub_total = $request->summary['sub_total'];
        $sell->discount = $request->summary['discount'];
        $sell->grand_total_price = $request->summary['grand_total_price'];
        $sell->due_amount = $request->summary['due_amount'];
        $sell->paid_amount = $paid_amount;
        $sell->status=$request->kot?0:1;
        $sell->save();

        Auth::user()->business->sellProduct()->where('sell_id', $id)->delete();
        $this->updateSellProducts($request, $sell);

        // if kot then just print
        $table=Table::find($sell->table_id);
        if (!$request->order_mode==2&&$table&&!$request->kot) {
        $table->status=0;
        $table->current_sell_id=null;
        $table->total_amount=null;
        $table->save();
        }else if($request->kot&&$table){
            $table->total_amount=$sell->grand_total_price;
            $table->save();
        }

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
        Auth::user()->business->sellProduct()->where('sell_id', $id)->delete();
        Auth::user()->business->sell()->where('id', $id)->delete();

        Toastr::success('Sell has been successfully Deleted', '', ['progressBar' => true, 'closeButton' => true, 'positionClass' => 'toast-bottom-right']);
        return redirect()->back();
    }

    public function getSellDetails($sell_id)
    {
        if (!Auth::user()->can('manage_sell_invoice')) {
            return redirect('home')->with(denied());
        } // end permission checking

       return $sell =  Auth::user()->business->sell()->where('id', $sell_id)->with('sellProducts')->with('customer')->first();

    }

    public function pdf($sell_id, $action_type)
    {
        if (!Auth::user()->can('manage_sell_invoice')) {
            return redirect('home')->with('denied');
        }

        $sell =  Auth::user()->business->sell()->findOrFail(decrypt($sell_id));

        if (!Auth::user()->can('access_to_all_branch')) {
            if ($sell->business_id != Auth::user()->business_id){
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
            $kot=0;
            // Display in browser
            return view('backend.pdf.sell.thermal-invoice',compact('sell','kot'));
        }
    }

    public function printInvoice(Request $request,$sell_id){
        $kot=(int)$request->kot??0;
        $sell = Auth::user()->business->sell()->where('id',$sell_id)->firstOrFail();
        return view('backend.pdf.sell.thermal-invoice',compact('sell','kot'))->render();
        // $pdf = PDF::loadView('backend.pdf.sell.rtl-invoice', compact('sell'))->setPaper('a4');
        // return $pdf->download();
    }

    private function storeSellProducts($request, $sell)
    {
        foreach ($request->carts as $cart_product) {
            $product = Auth::user()->business->product()->where('id',$cart_product['id'])->first();


            $sell_product = new SellProduct();
            $sell_product->sell_id = $sell->id;
            $sell_product->product_id = $cart_product['id'];
            $sell_product->sell_date = $sell->sell_date;
            $sell_product->business_id = $sell->business_id;
            $sell_product->fill($cart_product);
            $sell_product->total_price = number_format($cart_product['total_price'], 2);
            $sell_product->save();

            $product->sell_price = $cart_product['sell_price'];
            $product->save();

        }
    }


    private function updateSellProducts($request, $sell){
        foreach ($request->carts as $cart_product) {
            $product = Auth::user()->business->product()->where('id',$cart_product['id'])->first();
            $sell_product = new SellProduct();
            $sell_product->sell_id = $sell->id;
            $sell_product->product_id = $cart_product['id'];
            $sell_product->sell_date = $sell->sell_date;
            $sell_product->business_id = $sell->business_id;
            $sell_product->fill($cart_product);
            $sell_product->total_price = number_format($cart_product['total_price'], 2);
            $sell_product->save();

            $product->sell_price = $cart_product['sell_price'];
            $product->save();

        }
    }
}
