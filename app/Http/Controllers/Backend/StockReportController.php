<?php

namespace App\Http\Controllers\Backend;

use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class StockReportController extends Controller
{
    public function index()
    {
        if (!Auth::user()->can('view_stock')) {
            return redirect('home')->with(denied());
        } // end permission checking


        $products = Auth::user()->business->product;
        return view('backend.report.stock.index',[
            'products' => $products
        ]);
    }

    public function stockReportPdf(Request $request){
        if (!Auth::user()->can('view_stock')) {
            return redirect('home')->with(denied());
        } // end permission checking

        $products =Auth::user()->business->product()->get();

        $random_string = Str::random(10);
        $pdf = PDF::loadView('backend.pdf.reports.stock.all-branch', compact('products', 'request'))->setPaper('a4');

        if ($request->action_type == 'download'){
            return $pdf->download('stock-report-' . Carbon::now()->format(get_option('app_date_format')) . '-'. $random_string . '.pdf');
        }else{

            $headers = [
                'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0'
                , 'Content-type' => 'text/csv'
                , 'Content-Disposition' => 'attachment; filename=galleries.csv'
                , 'Expires' => '0'
                , 'Pragma' => 'public'
            ];

            $filename = 'download.csv';
            $handle = fopen($filename, 'w');
            fputcsv($handle, [
                __('pages.sl'),
                __('pages.product'),
                __('pages.purchase') . ' '. __('pages.quantity'),
                __('pages.sells') . ' '. __('pages.quantity'),
                __('pages.current_stock_quantity'),
                __('pages.current_stock_value'),
            ]);

            $products = Auth::user()->business->product()->get();
            foreach ($products as $key => $product) {

                   $purchaseQuantity = $product->purchaseProducts->sum('quantity');
                    $sellQuantity = $product->sellProducts->sum('quantity');
                    $current_stock_qty = $product->purchaseProducts->sum('quantity') - $product->sellProducts->sum('quantity');


                $product_tax = $product->sell_price * $product->tax->value / 100;
                $current_stock_amount = $current_stock_qty * $product->sell_price;


                fputcsv($handle, [
                    $key + 1,
                    $product->title . '|'. $product->sku,
                    $purchaseQuantity,
                    $sellQuantity,
                    $current_stock_qty,
                    number_format($current_stock_amount,2),
                ]);
            }

            fclose($handle);
            return response()->download($filename, 'stock-report-' . Carbon::now()->toDateString() . '.csv', $headers);

        }
    }

}
