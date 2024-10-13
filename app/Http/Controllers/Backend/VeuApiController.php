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
use Illuminate\Support\Facades\Auth;

class VeuApiController extends Controller
{
    public function getAppConfigs()
    {
        return response(Settings::all());
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

    public function myBranch(){
       return response(auth()->user()->employee);
    }
    public function products()
    {
        $products = Auth::user()->business->product()->where('status', 1)
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
       return Product::findOrFail($product_id)->current_stock_quantity;
    }



    public function productAvailableStockQtyWithoutSellInvoice($product_id, $sell_id){
        $business_id = Auth::user()->business_id;

        /**
         * Debit Quantity
         **/
        $total_purchase_products_qty = PurchaseProduct::where('business_id', $business_id)
            ->where('product_id', $product_id)
            ->sum('quantity');

        $branch_requisitions_from = Requisition::where('requisition_from', $business_id)
            ->where('status', 2)
            ->select('id')
            ->distinct()
            ->get();

        $branch_requisitions_from_qty = RequisitionProduct::whereIn('requisition_id', $branch_requisitions_from)
            ->where('product_id', $product_id)
            ->select('id')
            ->sum('quantity');


        /**
         * Credit Quantity
         **/

        $total_sell_products_qty = SellProduct::where('business_id', $business_id)
            ->where('sell_id', '!=', $sell_id)
            ->where('product_id', $product_id)
            ->sum('quantity');

        $branch_requisitions_to = Requisition::where('requisition_to', $business_id)
            ->where('status', 2)
            ->select('id')
            ->distinct()
            ->get();

        $branch_requisitions_to_qty = RequisitionProduct::whereIn('requisition_id', $branch_requisitions_to)
            ->where('product_id', $product_id)
            ->select('id')
            ->sum('quantity');



        $debit = $total_purchase_products_qty + $branch_requisitions_from_qty;
        $credit = $total_sell_products_qty + $branch_requisitions_to_qty;

        return $debit - $credit;
    }




    public function categories()
    {
        $categories = Auth::user()->business->category()->where('status', 1)->orderBy('id', 'DESC')->get();
        return response($categories);
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
        $customer->save();
        return response($customer);
    }
}
