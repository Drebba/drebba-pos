<?php

namespace App\Http\Controllers\Backend;

use App\Models\Purchase;
use App\Models\Settings;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Models\SellProduct;
use App\Models\Sell;
use Auth;

class ChartApiController extends Controller
{
    public function dashboard(){

        $business_id = Auth::user()->business_id;
        $last_30_days_sell = [];
        $key = 0;
        for ($i=30; $i >= 0 ; $i--)
        {
            $date_info = Carbon::now()->subDay($i)->format('Y-m-d');
            $last_30_days_sell[$key]['currency'] = get_option('app_currency') . ' ';
            $last_30_days_sell[$key]['sell_date'] = $date_info;
            if (Auth::user()->can('access_to_all_branch')) {
                $amount = Sell::where('sell_date', $date_info)->sum('grand_total_price');
                $last_30_days_sell[$key]['total_sell_amount'] = number_format($amount, 2, '.', '');
            }else{
                $amount = Sell::where('business_id', $business_id)->where('sell_date', $date_info)->sum('grand_total_price');
                $last_30_days_sell[$key]['total_sell_amount'] = number_format($amount, 2, '.', '');
            }
            $key++;
        }

        return response($last_30_days_sell);
    }

    public function product($id)
    {
    	$last_30_days_sell = [];
        $key = 0;
        for ($i=30; $i >= 0 ; $i--)
        {
            $date_info = Carbon::now()->subDay($i)->format('Y-m-d');
            $last_30_days_sell[$key]['currency'] = get_option('app_currency') . ' ';
            $last_30_days_sell[$key]['sell_date'] = $date_info;

                $last_30_days_sell[$key]['total_sell_amount'] = SellProduct::where('product_id', $id)->where('business_id', auth()->user()->business_id)->where('sell_date', $date_info)->sum('total_price');
            $key++;
        }

        return response($last_30_days_sell);
    }


    public function saleReportStatisticData()
    {
        $sell_info = [];
        $key = 0;
        for ($i=30; $i >= 0 ; $i--)
        {
            $date_info = Carbon::now()->subDay($i)->format('Y-m-d');
            $sell_info[$key]['currency'] = get_option('app_currency') . ' ';
            $sell_info[$key]['sell_date'] = $date_info;
            if (Auth::user()->can('access_to_all_branch')){
                $sell_info[$key]['total_sell_amount'] = Sell::where('sell_date', $date_info)->sum('grand_total_price');
            }else{
                $sell_info[$key]['total_sell_amount'] = Sell::where('business_id', Auth::user()->business_id)->where('sell_date', $date_info)->sum('grand_total_price');
            }
            $key++;
        }

        return response($sell_info);
    }

    public function saleReportStatisticByDay($days)
    {
        $sell_info = [];
        $key = 0;
        for ($i=$days; $i >= 0 ; $i--)
        {
            $number_of_day = $i + 1;
            $date_info = Carbon::now()->subDay($number_of_day)->format('Y-m-d');
            $sell_info[$key]['currency'] = get_option('app_currency') . ' ';
            $sell_info[$key]['sell_date'] = $date_info;
            if (Auth::user()->can('access_to_all_branch')){
                $sell_info[$key]['total_sell_amount'] = Sell::where('sell_date', $date_info)->sum('grand_total_price');
            }else{
                $sell_info[$key]['total_sell_amount'] = Sell::where('business_id', Auth::user()->business_id)->where('sell_date', $date_info)->sum('grand_total_price');
            }
            $key++;
        }

        return response($sell_info);
    }

    public function saleReportStatisticsFilter($selected_month, $selected_year, $selected_branch, $search_type)
    {
       if ($selected_month == 'a')
       {
           $selected_month = null;
       }

        if ($selected_year == 'a')
        {
            $selected_year = null;
        }

        if ($selected_branch == 'a')
        {
            $selected_branch = null;
        }


        $business_id = $selected_branch ? [$selected_branch] : Sell::select('business_id')->distinct()->get();

        if ($search_type == 'month'){
            $start_date = Carbon::parse($selected_month)->startOfMonth($selected_month)->format('Y-m-d');
            $end_date = Carbon::parse($selected_month)->endOfMonth($selected_month)->format('Y-m-d');

            $sell_info = [];
            foreach (CarbonPeriod::create($start_date, $end_date) as $key => $date) {
                $sells = Sell::where('sell_date', $date->format('Y-m-d'));
                if ($selected_branch != null){
                    $sells = $sells->where('business_id', $selected_branch);
                }
                $sell_info[$key]['currency'] = get_option('app_currency') . ' ';
                $sell_info[$key]['sell_date'] = $date->format('Y-m-d');
                $sell_info[$key]['total_sell_amount'] = $sells->sum('grand_total_price');
            }


        }else{
            $year = $selected_year ? $selected_year : Carbon::now()->format('Y');
            $sell_info = [];

            for ($i=1; $i < 13 ; $i++) {
                $dateObj   = Carbon::createFromFormat('!m', $i);
                $month_name = $dateObj->format('F');


                $sells = Sell::whereMonth('sell_date', $i)->whereYear('sell_date', $year);
                if ($selected_branch != null){
                    $sells = $sells->where('business_id', $selected_branch);
                }

                $sell_info[$i-1]['currency'] = get_option('app_currency') . ' ';
                $sell_info[$i-1]['sell_date'] = $month_name;
                $sell_info[$i-1]['total_sell_amount'] = $sells->sum('grand_total_price');
            }
        }

        return response($sell_info);
    }

    public function purchaseReportStatisticData()
    {
        $purchase_info = [];
        $key = 0;
        for ($i=30; $i >= 0 ; $i--)
        {
            $date_info = Carbon::now()->subDay($i)->format('Y-m-d');
            $purchase_info[$key]['currency'] = get_option('app_currency') . ' ';
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

        return response($purchase_info);
    }

    public function purchaseReportStatisticByDay($days)
    {
        $purchase_info = [];

        $key = 0;
        for ($i=$days; $i >= 0 ; $i--)
        {
            $date_info = Carbon::now()->subDay($i)->format('Y-m-d');
            $purchase_info[$key]['currency'] = get_option('app_currency') . ' ';
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


        return response($purchase_info);
    }

    public function purchaseReportStatisticsFilter($selected_month, $selected_year, $selected_branch, $search_type)
    {
        if ($selected_month == 'a')
        {
            $selected_month = null;
        }

        if ($selected_year == 'a')
        {
            $selected_year = null;
        }

        if ($selected_branch == 'a')
        {
            $selected_branch = null;
        }


        if ($search_type == 'month'){
            $start_date = Carbon::parse($selected_month)->startOfMonth($selected_month)->format('Y-m-d');
            $end_date = Carbon::parse($selected_month)->endOfMonth($selected_month)->format('Y-m-d');

            $purchase_info = [];
            foreach (CarbonPeriod::create($start_date, $end_date) as $key => $date) {
                $purchase = Purchase::where('purchase_date', $date->format('Y-m-d'));
                if ($selected_branch != null){
                    $purchase = $purchase->where('business_id', $selected_branch);
                }
                $purchase_info[$key]['purchase_date'] = $date->format('Y-m-d');
                $purchase_info[$key]['total_purchase_amount'] = $purchase->sum('total_amount');
                $purchase_info[$key]['currency'] = get_option('app_currency') . ' ';
            }

        }else{
            $year = $selected_year ? $selected_year : Carbon::now()->format('Y');
            $purchase_info = [];
            for ($i=0; $i < 12 ; $i++)
            {
                $dateObj   = Carbon::createFromFormat('!m', $i+1);
                $month_name = $dateObj->format('F');

                $purchase = Purchase::whereYear('purchase_date', $year)->whereMonth('purchase_date', $i);

                if ($selected_branch){
                    $purchase = $purchase->where('business_id', $selected_branch);
                }

                $purchase_info[$i]['currency'] = get_option('app_currency') . ' ';
                $purchase_info[$i]['purchase_date'] = $month_name;
                $purchase_info[$i]['total_purchase_amount'] = $purchase->sum('total_amount');;
            }
        }

        return response($purchase_info);

    }


}
