<?php

namespace App\Http\Controllers\Backend;

use App\Models\Branch;
use App\Models\Customer;
use App\Models\Sell;
use App\Models\Product;
use App\Models\SellProduct;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SellReportController extends Controller
{

    /**
     * Sells Summary Reports
     */
    public function sellsSummary(Request $request)
    {
        if (!Auth::user()->can('view_sells_report')) {
            return redirect('home')->with(denied());
        } // end permission checking

        $start_date = $request->start_date ? $request->start_date : Carbon::now()->subDay(15)->toDateString();
        $end_date = $request->end_date ? $request->end_date : Carbon::now()->toDateString();

        $sells = Auth::user()->business->sell()->orderByDesc('sell_date');

        $sells = $sells->whereBetween('sell_date', [$start_date, $end_date])->get()
            ->groupBy(function($data) use ($request) {
                return Carbon::parse($data->sell_date)->format($request->by_duration ? $request->by_duration : 'Y-m-d');
            });


        if ($request->action_type == 'download'){
            $filter_by = $this->getFilterByDataTitle($request);
            $pdf = PDF::loadView('backend.pdf.reports.sells.sell-summary', [
                'sells' => $sells,
                'filter_by' => $filter_by,
            ]);
            return $pdf->stream();
        }

        return view('backend.report.sell.summary.index',[
            'sells' => $sells
        ]);
    }


    /**
     * Sells Statistics Reports
     */

    public function sellsStatistics(Request $request)
    {
        if (!Auth::user()->can('view_sells_report')) {
            return redirect('home')->with(denied());
        } // end permission checking

        $sell_info = $this->sellsStatisticsFilterQuery($request);

        return view('backend.report.sell.statistics.index',[
            'sell_info' => $sell_info
        ]);
    }

    public function sellsStatisticsFilter(Request $request){
        if (!Auth::user()->can('view_sells_report')) {
            return redirect('home')->with(denied());
        } // end permission checking


        $sell_info = $this->sellsStatisticsFilterQuery($request);
        return view('backend.report.sell.statistics.filter-result',[
            'sell_info' => $sell_info
        ]);
    }

    public function sellsStatisticsFilterPDF(Request $request){
        if (!Auth::user()->can('view_sells_report')) {
            return redirect('home')->with(denied());
        } // end permission checking


        $pdf = PDF::loadView('backend.pdf.reports.sells.statistics', [
            'sell_info' => $this->sellsStatisticsFilterQuery($request)
        ])->setPaper('a4');

        if ($request->action_type == 'download'){
            return $pdf->download('statistics-' . Carbon::now()->format('Y-m-d') . '.pdf');
        }else{
            return $pdf->stream();
        }
    }


    /**
     * Product Wise Sells Reports
     */

    public function productWise(Request $request){
        if (!Auth::user()->can('view_sells_report')) {
            return redirect('home')->with(denied());
        } // end permission checking


        $sell_products = $this->productWiseQuery($request);

        if ($request->action_type != ''){
            $pdf = PDF::loadView('backend.pdf.reports.sells.product-wise-sell-report', [
                'sell_products' => $sell_products,
                'filter_by' => $this->getFilterByDataTitle($request)
            ])->setPaper('a4');

            if ($request->action_type == 'download'){
                return $pdf->download('product-wise-sell-report-' . Carbon::now()->format('Y-m-d') . '.pdf');
            }elseif ($request->action_type == 'print'){
                return $pdf->stream();
            }
        }

        return view('backend.report.sell.product-wise.index',[
            'products' => Product::orderBy('title')->get(),
            'sell_products' => $sell_products,
        ]);
    }



    /**
     * Sells Reports
     */

    public function sells(Request $request){
        if (!Auth::user()->can('view_sells_report')) {
            return redirect('home')->with(denied());
        } // end permission checking

        $start_date = $request->start_date ? $request->start_date : Carbon::now()->subDay(60)->toDateString();
        $end_date = $request->end_date ? $request->end_date : Carbon::now()->toDateString();

         $sells = Auth::user()->business->sell()->orderByDesc('id');

         $sells = $sells->whereBetween('sell_date', [$start_date, $end_date])->paginate(50);


        if ($request->action_type != ''){
            $pdf = PDF::loadView('backend.pdf.reports.sells.sells', [
                'sells' => $sells,
                'filter_by' => $this->getFilterByDataTitle($request),
            ])->setPaper('a4');

            if ($request->action_type == 'download'){
                return $pdf->download('sells-report-' . Carbon::now()->format('Y-m-d') . '.pdf');
            }else{
               return $pdf->stream();
            }
        }


         return view('backend.report.sell.sells.sells',[
            'sells' => $sells,
            'customers' => Customer::orderBy('id', 'DESC')->get(),
         ]);
    }



    /**
     * Private functions for this controller
     */
    private function getFilterByDataTitle($request){
        $start_date = $request->start_date ? $request->start_date : Carbon::now()->subDay(60)->toDateString();
        $end_date = $request->end_date ? $request->end_date : Sell::latest()->pluck('sell_date')->first();

        if ($request->business_id != ''){
            $branch =  Branch::findOrFail($request->business_id);
            $branch_name = $branch->title;
        }else{
            $branch_name = 'All';
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
        $filter_by['start_date'] = Carbon::parse($start_date)->format(get_option('app_date_format'));
        $filter_by['end_date'] = Carbon::parse($end_date)->format(get_option('app_date_format'));
        $filter_by['branch_name'] = $branch_name;
        $filter_by['duration_type'] = $duration_type;
        $filter_by['product_name'] = $product_name;

        return $filter_by;
    }

    private function sellsStatisticsFilterQuery(Request $request)
    {
        if ($request->search_type == 'month'){
            $start_date = Carbon::parse($request->month)->startOfMonth($request->month)->format('Y-m-d');
            $end_date = Carbon::parse($request->month)->endOfMonth($request->month)->format('Y-m-d');

            $sell_info = [];
            foreach (CarbonPeriod::create($start_date, $end_date) as $key => $date) {
                $sells = Auth::user()->business->sell()->where('sell_date', $date->format('Y-m-d'));
                if ($request->business_id){
                    $sells = $sells->where('business_id', $request->business_id);
                }

                $sell_info[$key]['sell_date'] = $date->format('Y-m-d');
                $sell_info[$key]['total_sell_amount'] = $sells->sum('grand_total_price');
            }
        }elseif($request->search_type == 'year'){
            $year = $request->year ? $request->year : Carbon::now()->format('Y');
            $sell_info = [];
            for ($i=1; $i < 13 ; $i++) {
                $dateObj   = Carbon::createFromFormat('!m', $i);
                $month_name = $dateObj->format('F');


                $sells = Auth::user()->business->sell()->whereMonth('sell_date', $i)->whereYear('sell_date', $year);
                if ($request->business_id){
                    $sells = $sells>where('business_id', $request->business_id);
                }

                $sell_info[$i-1]['sell_date'] = $month_name;
                $sell_info[$i-1]['total_sell_amount'] = $sells->sum('grand_total_price');
            }
        }else{
            $sell_info = [];
            $key = 0;
            $days=$request->days ??30;
            for ($i=$days; $i > 0 ; $i--)
            {
                $date_info = Carbon::now()->subDay($i)->format('Y-m-d');
                $sell_info[$key]['sell_date'] = $date_info;
                $sell_info[$key]['total_sell_amount'] = Auth::user()->business->sell()->where('sell_date', $date_info)->sum('grand_total_price');
                $key++;
            }
        }

        return $sell_info;
    }



    private function productWiseQuery($request){
        $start_date = $request->start_date ? $request->start_date : Carbon::now()->subDay(60)->toDateString();
        $end_date = $request->end_date ? $request->end_date :  Carbon::now()->toDateString();

        $sell_products  = Auth::user()->business->sellProduct()->with('product');

        if ($request->product_id){
            $sell_products  = $sell_products->where('product_id', $request->product_id);
        }

        if ($request->business_id){
            $sell_id_from_business_id = Sell::where('business_id', $request->business_id)->pluck('id')->all();
            $sell_products  = $sell_products->whereIn('sell_id', $sell_id_from_business_id);
        }

       return $sell_products = $sell_products->whereBetween('sell_date', [$start_date, $end_date])
            ->get()
            ->groupBy('product_id');
    }
}
