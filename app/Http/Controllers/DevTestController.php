<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Product;
use App\Models\ProductStockHistory;
use App\Models\Purchase;
use App\Models\PurchaseProduct;
use App\Models\SellProduct;
use App\Traits\ProductStcokDataTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DevTestController extends Controller
{

    use ProductStcokDataTrait;


    public function printExamples(){
        return view('dev-test.print');
    }

    public function printExamplesOne(){
        return view('dev-test.print-ex-1');
    }


    public function printExamplesTwo(){

    }


    /**
     *
     * @return array
     */
    public function mergeProductWithBranch(){
        $branches = Branch::all();

        foreach ($branches as $branch){
            $products = Product::where('status', 1)
                ->get()
                ->makeHidden(['current_stock_quantity', 'total_sell_qty']);

            foreach ($products as $product){
                $check_product_is_exists = ProductStockHistory::where('branch_id', $branch->id)
                    ->where('product_id', $product->id)
                    ->count();

                if ($check_product_is_exists == 0){
                    $stock_history = new ProductStockHistory();
                    $stock_history->branch_id = $branch->id;
                    $stock_history->product_id = $product->id;
                    $stock_history->save();
                }
            }
        }


        $data['test'] = Str::random(5);
        $data['branch'] = Branch::count();
        $data['products'] = Product::count();
        $data['branch_products'] = $data['branch'] * $data['products'];
        $data['stock_history'] = ProductStockHistory::count();

        return $data;
    }


    public function mergerSellQuantity(Request $request){
        ini_set('max_execution_time', 6000); // 100 Min

        $limit = $request->limit;
        $dev_process = $request->dev_process;

        $products = Product::where('status', 1)
            ->where('is_dev_process', $dev_process)
            ->limit($limit)
            ->get()
            ->makeHidden(['current_stock_quantity', 'total_sell_qty']);

        foreach ($products as $product){
            foreach (Branch::all() as $branch){
                $product_stock_history = ProductStockHistory::where('branch_id', $branch->id)
                    ->where('product_id', $product->id)
                    ->first();

                if (!$product_stock_history){
                    $product_stock_history = new ProductStockHistory();
                    $product_stock_history->branch_id = $branch->id;
                    $product_stock_history->product_id = $product->id;
                    $product_stock_history->save();
                }


                $sell_products = SellProduct::where('product_id', $product->id)
                    ->where('branch_id', $branch->id)
                    ->sum('quantity');

                $product_stock_history->sell_qty = $sell_products;
                $product_stock_history->save();


                /**
                 * PurchaseProduct
                 */
                $purchase_products = PurchaseProduct::where('product_id', $product->id)
                    ->where('branch_id', $branch->id)
                    ->sum('quantity');

                $product_stock_history->purchase_qty = $purchase_products;
                $product_stock_history->save();




                /**
                 * Requisition
                 */

                $branch_requisitions_to = \App\Models\Requisition::where('requisition_to', $branch->id)
                    ->where('status', 2)
                    ->select('id')
                    ->distinct()
                    ->get();

                $branch_requisitions_to_qty = \App\Models\RequisitionProduct::whereIn('requisition_id', $branch_requisitions_to)
                    ->where('product_id', $product->id)
                    ->select('id')
                    ->sum('quantity');



                $branch_requisitions_from = \App\Models\Requisition::where('requisition_from', $branch->id)
                    ->where('status', 2)
                    ->select('id')
                    ->distinct()
                    ->get();

                $branch_requisitions_from_qty = \App\Models\RequisitionProduct::whereIn('requisition_id', $branch_requisitions_from)
                    ->where('product_id', $product->id)
                    ->select('id')
                    ->sum('quantity');


                $product_stock_history->req_send = $branch_requisitions_to_qty; // Request request to brach
                $product_stock_history->req_received = $branch_requisitions_from_qty; // Request request form brach
                $product_stock_history->save();
            }

            $product->is_dev_process = $dev_process == 1 ? 0 : 1;
            $product->save();
        }


        $products = DB::table('products')->get();

        $data['status'] =  Carbon::now()->toDateTimeString(). ' Success';
        $data['branch'] = Branch::count();
        $data['products'] = Product::count();
        $data['branch_products'] = $data['branch'] * $data['products'];
        $data['stock_history'] = ProductStockHistory::count();
        $data['product_0'] = $products->where('is_dev_process', 0)->count();
        $data['product_1'] = $products->where('is_dev_process', 1)->count();

        return $data;
    }

    public function mergerPurchaseQuantity(Request $request){
        ini_set('max_execution_time', 6000); // 100 Min

        $limit = $request->limit;
        $dev_process = $request->dev_process;


        $products = Product::where('status', 1)
            ->where('is_dev_process', $dev_process)
            ->limit($limit)
            ->get()
            ->makeHidden(['current_stock_quantity', 'total_sell_qty']);

        foreach ($products as $product){
            foreach (Branch::all() as $branch){
                $product_stock_history = ProductStockHistory::where('branch_id', $branch->id)
                    ->where('product_id', $product->id)
                    ->first();

                if (!$product_stock_history){
                    $product_stock_history = new ProductStockHistory();
                    $product_stock_history->branch_id = $branch->id;
                    $product_stock_history->product_id = $product->id;
                    $product_stock_history->save();
                }


                $purchase_products = PurchaseProduct::where('product_id', $product->id)
                    ->where('branch_id', $branch->id)
                    ->sum('quantity');

                $product_stock_history->purchase_qty = $purchase_products;
                $product_stock_history->save();
            }

            $product->is_dev_process = $dev_process == 1 ? 0 : 1;
            $product->save();
        }




        $products = DB::table('products')->get();

        $data['status'] =  Carbon::now()->toDateTimeString(). ' Success';
        $data['branch'] = Branch::count();
        $data['products'] = Product::count();
        $data['branch_products'] = $data['branch'] * $data['products'];
        $data['stock_history'] = ProductStockHistory::count();
        $data['product_0'] = $products->where('is_dev_process', 0)->count();
        $data['product_1'] = $products->where('is_dev_process', 1)->count();

        return $data;
    }

    public function mergerRequisitionQuantity(Request $request){
        ini_set('max_execution_time', 6000); // 100 Min
        $limit = $request->limit;
        $dev_process = $request->dev_process;

        $products = Product::where('status', 1)
            ->where('is_dev_process', $dev_process)
            ->limit($limit)
            ->get()
            ->makeHidden(['current_stock_quantity', 'total_sell_qty']);

        foreach ($products as $product){
            foreach (Branch::all() as $branch){

                $product_stock_history = ProductStockHistory::where('branch_id', $branch->id)
                    ->where('product_id', $product->id)
                    ->first();

                if (!$product_stock_history){
                    $product_stock_history = new ProductStockHistory();
                    $product_stock_history->branch_id = $branch->id;
                    $product_stock_history->product_id = $product->id;
                    $product_stock_history->save();
                }


                $branch_requisitions_to = \App\Models\Requisition::where('requisition_to', $branch->id)
                    ->where('status', 2)
                    ->select('id')
                    ->distinct()
                    ->get();

                $branch_requisitions_to_qty = \App\Models\RequisitionProduct::whereIn('requisition_id', $branch_requisitions_to)
                    ->where('product_id', $product->id)
                    ->select('id')
                    ->sum('quantity');



                $branch_requisitions_from = \App\Models\Requisition::where('requisition_from', $branch->id)
                    ->where('status', 2)
                    ->select('id')
                    ->distinct()
                    ->get();

                $branch_requisitions_from_qty = \App\Models\RequisitionProduct::whereIn('requisition_id', $branch_requisitions_from)
                    ->where('product_id', $product->id)
                    ->select('id')
                    ->sum('quantity');


                $product_stock_history->req_send = $branch_requisitions_to_qty; // Request request to brach
                $product_stock_history->req_received = $branch_requisitions_from_qty; // Request request form brach
                $product_stock_history->save();
            }



            $product->is_dev_process = $dev_process == 1 ? 0 : 1;
            $product->save();
        }




        $products = DB::table('products')->get();

        $data['status'] =  Carbon::now()->toDateTimeString(). ' Success';
        $data['branch'] = Branch::count();
        $data['products'] = Product::count();
        $data['branch_products'] = $data['branch'] * $data['products'];
        $data['stock_history'] = ProductStockHistory::count();
        $data['product_0'] = $products->where('is_dev_process', 0)->count();
        $data['product_1'] = $products->where('is_dev_process', 1)->count();

        return $data;
    }

}
