<?php

use App\Models\Requisition;
use Carbon\Carbon;


function active_if_full_match($path)
{
    return Request::is($path) ? 'active' : '';
}

function active_if_match($path)
{
    return Request::is($path . '*') ? 'active' : '';
}

function show_status($status, $url)
{
    return $status == 1 ?
        '<a href="javascript:void(0)" onclick="$(this).confirm(\'' . $url . '\');" class="label label-success"> Active </a>'
        :
        '<a href="javascript:void(0)" onclick="$(this).confirm(\'' . $url . '\');" class="label label-danger"> Deactive </a>';
}

function toggle_status($status){
    if ($status == 1){
        return 0;
    }else{
        return 1;
    }
}

function monthlySells($branch_id, $key)
{
    $yearMonthArray = explode('-', $key);
    $year = $yearMonthArray[0];
    $month = $yearMonthArray[1];
    return \App\Models\Sell::where('branch_id', $branch_id)->whereYear('sell_date', $year)->whereMonth('sell_date', $month)->sum('grand_total_price');
}

function productAvailableTransactionStockQty($product)
{
    if (is_object($product) && property_exists($product, 'current_stock_quantity')) {
        return $product->current_stock_quantity;
    } else {
        return 0;
    }
}

function productReceivedFromOthersBranch($product_id)
{
    $branch_id = Auth::user()->branch_id;

    $branch_requisitions_from = \App\Models\Requisition::where('requisition_from', $branch_id)
        ->where('status', 2)
        ->select('id')
        ->distinct()
        ->get();

   return $branch_requisitions_from_qty = \App\Models\RequisitionProduct::whereIn('requisition_id', $branch_requisitions_from)
        ->where('product_id', $product_id)
        ->select('id')
        ->sum('quantity');

}

function productSendToOthersBranch($product_id){
    $branch_id = Auth::user()->branch_id;

    $branch_requisitions_to = \App\Models\Requisition::where('requisition_to', $branch_id)
        ->where('status', 2)
        ->select('id')
        ->distinct()
        ->get();

    return $branch_requisitions_to_qty = \App\Models\RequisitionProduct::whereIn('requisition_id', $branch_requisitions_to)
        ->where('product_id', $product_id)
        ->select('id')
        ->sum('quantity');
}

function pendingRequisition()
{
    if (Auth::user()->can('access_to_all_branch')){
        $requisitions = Requisition::orderBy('id', 'DESC')->where('status', 0)->count();
    }else{
        $requisitions = Requisition::where('status', 0)
            ->where(function($query){
                $query->where('requisition_from',  Auth::user()->branch_id);
                $query->orWhere('requisition_to', Auth::user()->branch_id);
            })
            ->orderBy('id', 'DESC')
            ->count();
    }
    return $requisitions;
}

function notifications()
{
    $notifications = \App\Models\Notification::where('notify_date_time', '<',  Carbon::now()->toDateTimeString())->where('status', 0)->select('id', 'status')->get();
    foreach ($notifications as $notification)
    {
        $notification->status = 1;
        $notification->save();
    }


    return $notifications = \App\Models\Notification::where('message_to', Auth::user()->branch_id)
        ->where('is_click', 0)
        ->where('status', 1)
        ->orderBy('id', 'DESC')
        ->get();
}

function get_option($option_key)
{
    $system_settings = config('general_settings');

    if ($option_key && isset($system_settings[$option_key])) {
        return $system_settings[$option_key];
    } else {
        return '';
    }
}


//function get_option($option_key)
//{
//    if (Settings::where('option_key', $option_key)->count() > 0)
//    {
//        $option = \App\Models\Settings::where('option_key', $option_key)->first();
//        return $option->option_value;
//    } else {
//        return '';
//    }
//}

function languages()
{
    return \App\Models\Language::where('status', 1)->get();
}

function toastrMessage($message_type, $message)
{
    Toastr::$message_type($message, '', ['progressBar' => true, 'closeButton' => true, 'positionClass' => 'toast-top-right']);
}


function denied()
{
    return array(
        'message' => 'Access Denied',
        'alert-type' => 'warning'
    );
}


function generateEAN($number)
{
    $code = '800' . str_pad($number, 9, '0');
    $weightflag = true;
    $sum = 0;
    // Weight for a digit in the checksum is 3, 1, 3.. starting from the last digit.
    // loop backwards to make the loop length-agnostic. The same basic functionality
    // will work for codes of different lengths.
    for ($i = strlen($code) - 1; $i >= 0; $i--)
    {
        $sum += (int)$code[$i] * ($weightflag?3:1);
        $weightflag = !$weightflag;
    }
    $code .= (10 - ($sum % 10)) % 10;

    return $code;
}



?>
