<?php

use App\Models\Requisition;
use App\Models\Settings;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

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

function monthlySells($business_id, $key)
{
    $yearMonthArray = explode('-', $key);
    $year = $yearMonthArray[0];
    $month = $yearMonthArray[1];
    return \App\Models\Sell::where('business_id', $business_id)->whereYear('sell_date', $year)->whereMonth('sell_date', $month)->sum('grand_total_price');
}

function currentStock($product){
    return $product->purchaseProducts->where('business_id', Auth::user()->business_id)->sum('quantity')-
    $product->sellProducts->where('business_id', Auth::user()->business_id)->sum('quantity');
}

function notifications()
{
    $notifications = \App\Models\Notification::where('notify_date_time', '<',  Carbon::now()->toDateTimeString())->where('status', 0)->select('id', 'status')->get();
    foreach ($notifications as $notification)
    {
        $notification->status = 1;
        $notification->save();
    }


    return $notifications = \App\Models\Notification::where('message_to', Auth::user()->business_id)
        ->where('is_click', 0)
        ->where('status', 1)
        ->orderBy('id', 'DESC')
        ->get();
}

function get_option($option_key)
{
    $system_settings = config('general_settings');
   $system_settings= Settings::where('business_id',Auth::user()->business_id)->get()->pluck('option_value', 'option_key')->toArray();

        return $system_settings[$option_key]??null;
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
