<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests\CustomerRequest;
use App\Models\Branch;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Customer;
use App\Models\PaymentToSupplier;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseProduct;
use App\Models\Requisition;
use App\Models\RequisitionProduct;
use App\Models\SellProduct;
use App\Models\Settings;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\OrderType;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class VeuApiController extends Controller
{
    public function getAppConfigs()
    {
        return response(Settings::where('business_id',Auth::user()->business_id)->get());
    }

    public function getAppLang()
    {
        $lang = config('app.locale');
        $files   = glob(resource_path('lang/' . $lang . '/*.php'));
        $strings = [];

        foreach ($files as $file) {
            $name  = basename($file, '.php');
            $strings[$name] = require $file;
        }

       return response($strings['pages']);
    }

    public function tables(){
       return response(auth()->user()->business->table);
    }
    public function products(Request $request)
    {
        $products = Auth::user()->business->product()
        ->when($request->type=='sell',function($query){
            $query->where('type',1);
        })
        ->when($request->type=='purchase',function($query){
            $query->where('type',0);
        })->where('status', 1)
            ->with('tax')
            ->with('unit')
            ->get();
        return response($products);
    }

    public function productsWithPaginate(){
        $products = Auth::user()->business->product()->where('status', 1)
            ->with('tax')
            ->with('unit')
            ->paginate(20);
        return response($products);
    }

    public function productAvailableStockQty($product_id)
    {
       return Auth::user()->business->product()->where('id',$product_id)->current_stock_quantity;
    }



    public function productAvailableStockQtyWithoutSellInvoice($product_id, $sell_id){
        $business_id = Auth::user()->business_id;

        /**
         * Debit Quantity
         **/
        $total_purchase_products_qty = Auth::user()->business->purchaseProduct()
            ->where('product_id', $product_id)
            ->sum('quantity');

        $total_sell_products_qty = Auth::user()->business->sellProduct()
            ->where('sell_id', '!=', $sell_id)
            ->where('product_id', $product_id)
            ->sum('quantity');



        $debit = $total_purchase_products_qty;
        $credit = $total_sell_products_qty ;

        return $debit - $credit;
    }

    public function categories()
    {
        $categories = Auth::user()->business->category()->where('type',0)->where('status', 1)->orderBy('id', 'DESC')->get();
        return response($categories);
    }

    public function transactions()
    {
        $transactions = Auth::user()->business->sell()->whereDate('created_at',today())->limit(12)->orderBy('id', 'DESC')->get();
        $transactions=$transactions->map(function($transaction){
            return [
                'id' => $transaction->id,
                'invoice_id' => $transaction->invoice_id,
                'grand_total_price' => $transaction->grand_total_price,
                'discount' => $transaction->discount,
                'table_name' => $transaction->table?->name??null,
                'order_mode_name'=>$transaction->orderMode?->name??null,
                'order_mode'=>$transaction->order_mode,
                'sell_date'=>Carbon::parse($transaction->sell_date)->format(get_option('app_date_format')),

            ];
        });
        return response($transactions);
    }

    public function orderTypes()
    {
        $types = OrderType::all();
        return response($types);
    }

    public function brands()
    {
        $brands = Brand::where('status', 1)->get();
        return response($brands);
    }

    public function suppliers()
    {
        $suppliers = Auth::user()->business->supplier()->where('status', 1)->get();
        return response($suppliers);
    }

    public function supplierDue($id){

        $dueAmount = Purchase::where('business_id', Auth::user()->business_id)->where('supplier_id', $id)->sum('total_amount');
        $paidAmount =  PaymentToSupplier::where('business_id', Auth::user()->business_id)->where('supplier_id', $id)->sum('amount');

        $due =  $dueAmount - $paidAmount;

        $data['message'] = 'Total Due '. get_option('app_currency').' '. number_format($due,2);
        $data['due_amount'] = number_format($due, 2);

        return response($data);
    }

    public function customers(){
        $customers = Auth::user()->business->customer()->where('status', 1)->get();
        return response($customers);
    }



    public function storeCustomer(Request $request){
        $customer = new Customer();
        $customer->name = $request->new_customer['name'];
        $customer->phone = $request->new_customer['phone'];
        $customer->email = $request->new_customer['email'];
        $customer->address = $request->new_customer['address'];
        $customer->business_id = Auth::user()->business_id;
        $customer->save();
        return response($customer);
    }
}
