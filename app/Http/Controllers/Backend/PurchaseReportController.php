<?php

namespace App\Http\Controllers\Backend;

use App\Models\Branch;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseProduct;
use App\Models\Sell;
use App\Models\Supplier;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Str;
use Schema;
use Illuminate\Database\Eloquent\Collection;
use League\Csv\Writer;
use SplTempFileObject;
use Illuminate\Support\Facades\Auth;

class PurchaseReportController extends Controller
{
    /**
     * Purchase Summary Reports
     */
    public function summary(Request $request)
    {
        if (!Auth::user()->can('view_purchase_report')) {
            return redirect('home')->with(denied());
        } // end permission checking

        $purchases =  $this->summaryFilterQuery($request);

        if ($request->action_type){
            $filter_by = $this->getFilterByDataTitle($request);
            $pdf = PDF::loadView('backend.pdf.reports.purchase.purchase-summary', [
                'purchases' => $purchases,
                'filter_by' => $filter_by,
            ])->setPaper('a4');

            if ($request->action_type == 'download'){
                return $pdf->download('purchase-summary-' . Carbon::now()->format('Y-m-d') . '.pdf');
            }else{
                return $pdf->stream();
            }
        }

        return view('backend.report.purchase.summary.index',[
            'suppliers' => Auth::user()->business->supplier()->orderBy('id', 'DESC')->get(),
            'purchases' => $purchases
        ]);
    }



    /**
     * Purchase Statistics Reports
     */

    public function statistics(Request $request)
    {
        if (!Auth::user()->can('view_purchase_report')) {
            return redirect('home')->with(denied());
        } // end permission checking

        $purchase_info = $this->statisticsFilterQuery($request);

        return view('backend.report.purchase.statistics.index',[
            'purchase_info' => $purchase_info
        ]);
    }

    public function statisticsFilter(Request $request){
        if (!Auth::user()->can('view_purchase_report')) {
            return redirect('home')->with(denied());
        } // end permission checking


        $purchase_info = $this->statisticsFilterQuery($request);
        return view('backend.report.purchase.statistics.filter-result',[
            'purchase_info' => $purchase_info
        ]);
    }

    public function statisticsFilterPDF(Request $request)
    {
        if (!Auth::user()->can('view_purchase_report')) {
            return redirect('home')->with(denied());
        } // end permission checking

        $pdf = PDF::loadView('backend.pdf.reports.purchase.statistics', [
            'purchase_info' => $this->statisticsFilterQuery($request)
        ])->setPaper('a4');

        if ($request->action_type == 'download'){
            return $pdf->download('purchase-statistics-' . Carbon::now()->format('Y-m-d') . '.pdf');
        }else{
            return $pdf->stream();
        }
    }

    public function lastDynamicDaysStatistics($days)
    {
        if (!Auth::user()->can('view_purchase_report')) {
            return redirect('home')->with(denied());
        } // end permission checking

        $purchase_info = $this->lastDynamicDaysStatisticsQuery($days);
        return view('backend.report.purchase.statistics.dynamic-days',[
            'days' => $days,
            'purchase_info' => $purchase_info
        ]);
    }

    public function lastDynamicDaysStatisticsPDF($days, $action_type)
    {
        if (!Auth::user()->can('view_purchase_report')) {
            return redirect('home')->with(denied());
        } // end permission checking

        $pdf = PDF::loadView('backend.pdf.reports.purchase.statistics',[
            'purchase_info' =>  $this->lastDynamicDaysStatisticsQuery($days)
        ])->setPaper('a4');

        if ($action_type == 'download'){
            return $pdf->download('statistics-' . Carbon::now()->format('Y-m-d') . '.pdf');
        }else{
            return $pdf->stream();
        }

    }

    /**
     * Purchase Product Wise Reports
     */

    public function productWise(Request $request)
    {
        if (!Auth::user()->can('view_purchase_report')) {
            return redirect('home')->with(denied());
        } // end permission checking

        $purchase_products =  $this->productWiseFilterQuery($request);

        if ($request->action_type){
            $pdf = PDF::loadView('backend.pdf.reports.purchase.product-wise-purchase', [
                'purchase_products' => $purchase_products,
                'filter_by' => $this->getFilterByDataTitle($request),
            ])->setPaper('a4');

            if ($request->action_type == 'download'){
                return $pdf->download('product-wise-purchase-' . Carbon::now()->format('Y-m-d') . '.pdf');
            }else{
                $pdf->stream();
            }
        }

        return view('backend.report.purchase.product-wise.index',[
            'products' => Product::orderBy('title')->get(),
            'purchase_products' => $purchase_products,
        ]);
    }



    /**
     * Purchase  Reports
     */

    public function purchases(Request $request)
    {
        if (!Auth::user()->can('view_purchase_report')) {
            return redirect('home')->with(denied());
        } // end permission checking


        $purchases = $this->purchasesFilterQuery($request);

        if ($request->action_type){
            $random_string = Str::random(10);
            $pdf = PDF::loadView('backend.pdf.reports.purchase.purchases', [
                'purchases' => $purchases,
                'filter_by' => $this->getFilterByDataTitle($request),
            ])->setPaper('a4');

            if ($request->action_type == 'download'){
                return $pdf->download('purchases-report-' . Carbon::now()->format('Y-m-d') . '.pdf');
            }else{
                return $pdf->stream();
            }
        }

        return view('backend.report.purchase.purchases.index',[
            'purchases' => $purchases,
            'suppliers' => Supplier::orderBy('id', 'DESC')->get(),
        ]);
    }


    /**
     * Private functions for this controller
     */

    private function summaryFilterQuery($request)
    {
        if (!Auth::user()->can('view_purchase_report')) {
            return redirect('home')->with(denied());
        } // end permission checking


        $start_date = $request->start_date ? $request->start_date : Carbon::now()->subDay(30)->toDateString();
        $end_date = $request->end_date ? $request->end_date : Carbon::now()->toDateString();


        $purchases = Auth::user()->business->purchase()->whereBetween('purchase_date', [$start_date, $end_date]);

        if ($request->supplier_id){
            $purchases = $purchases->where('supplier_id', $request->supplier_id);
        }

        $purchases = $purchases->get()
            ->groupBy(function($data) use ($request) {
                return Carbon::parse($data->purchase_date)->format($request->by_duration ? $request->by_duration : 'Y-m-d');
            });

        return $purchases;
    }

    private function statisticsFilterQuery(Request $request){
        if ($request->search_type == 'month'){
            $start_date = Carbon::parse($request->month)->startOfMonth($request->month)->format('Y-m-d');
            $end_date = Carbon::parse($request->month)->endOfMonth($request->month)->format('Y-m-d');

            $purchase_info = [];
            foreach (CarbonPeriod::create($start_date, $end_date) as $key => $date) {
                $purchase = Auth::user()->business->purchase()->where('purchase_date', $date->format('Y-m-d'));
                $purchase_info[$key]['purchase_date'] = $date->format('Y-m-d');
                $purchase_info[$key]['total_purchase_amount'] = $purchase->sum('total_amount');
            }
        }elseif($request->search_type == 'year'){
            $year = $request->year ? $request->year : Carbon::now()->format('Y');
            $purchase_info = [];
            for ($i=0; $i < 12 ; $i++)
            {
                $dateObj   = Carbon::createFromFormat('!m', $i+1);
                $month_name = $dateObj->format('F');

                $purchase = Purchase::whereYear('purchase_date', $year)->whereMonth('purchase_date', $i);

                if ($request->business_id){
                    $purchase = $purchase->where('business_id', $request->business_id);
                }

                $purchase_info[$i]['purchase_date'] = $month_name;
                $purchase_info[$i]['total_purchase_amount'] = $purchase->sum('total_amount');;
            }
        }else{
            $purchase_info = [];
            $key = 0;
            for ($i=30; $i >= 0 ; $i--)
            {
                $date_info = Carbon::now()->subDay($i)->format('Y-m-d');
                $purchase_info[$key]['purchase_date'] = $date_info;
                if (Auth::user()->can('access_to_all_branch')) {
                    $purchase_info[$key]['total_purchase_amount'] = Purchase::where('purchase_date', $date_info)->sum('total_amount');
                }else{
                    $purchase_info[$key]['total_purchase_amount'] = Purchase::where('business_id', Auth::user()->business_id)
                        ->where('purchase_date', $date_info)
                        ->sum('total_amount');
                }
                $key++;
            }
        }

        return $purchase_info;
    }


    private function productWiseFilterQuery(Request $request)
    {
        $product_id = $request->product_id ? [$request->product_id] : PurchaseProduct::select('product_id')->distinct()->get();

        $start_date = $request->start_date ? $request->start_date : Carbon::now()->subDay(60)->toDateString();
        $end_date = $request->end_date ? $request->end_date :  Carbon::now()->toDateString();


         $purchase_products = Auth::user()->business->purchaseproduct()->whereBetween('purchase_date', [$start_date, $end_date]);

         if ($request->product_id){
             $purchase_products = $purchase_products->where('product_id', $product_id);
         }


        return $purchase_products = $purchase_products->get()->groupBy('product_id');
    }

    private function purchasesFilterQuery($request)
    {
        $start_date = $request->start_date ? $request->start_date : Carbon::now()->subDay(30)->toDateString();
        $end_date = $request->end_date ? $request->end_date : Carbon::now()->toDateString();;


        $purchases = Auth::user()->business->purchase()->whereBetween('purchase_date', [$start_date, $end_date]);

        if ($request->supplier_id){
            $purchases = $purchases->where('supplier_id', $request->supplier_id);
        }

        if ($request->business_id){
            $purchases = $purchases->where('business_id', $request->business_id);
        }


        if ($request->action_type){
            return $purchases = $purchases->get();
        }else{
            return $purchases = $purchases->paginate(50);
        }

    }

    private function getFilterByDataTitle($request){
        $start_date = $request->start_date ? $request->start_date : Carbon::now()->subDay(60)->toDateString();
        $end_date = $request->end_date ? $request->end_date : Carbon::now()->toDateString();



        if ($request->supplier_id != ''){
            $supplier = Supplier::findOrFail($request->supplier_id);
            $supplier_name = $supplier->company_name;
        }else{
            $supplier_name = 'All';
        }

        if ($request->product_id != ''){
            $product =  Product::findOrFail($request->product_id);
            $product_name = $product->title;
        }else{
            $product_name = 'All Products';
        }

        if ($request->by_duration == 'Y-m-d'){
            $duration_type = 'Daily';
        }elseif ($request->by_duration == 'Y-W'){
            $duration_type = 'Weekly';
        }elseif ($request->by_duration == 'Y-M'){
            $duration_type = 'Monthly';
        }else{
            $duration_type = 'Daily';
        }

        $filter_by = [];
        $filter_by['start_date'] = Carbon::parse($start_date)->format(get_option('app_date_format'));;
        $filter_by['end_date'] = Carbon::parse($end_date)->format(get_option('app_date_format'));;
        $filter_by['supplier_name'] = $supplier_name;
        $filter_by['duration_type'] = $duration_type;
        $filter_by['product_name'] = $product_name;

        return $filter_by;
    }

}
