<?php


namespace App\Traits;


use App\Models\ProductStockHistory;
use App\Models\PurchaseProduct;
use App\Models\SellProduct;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use File;

trait ProductStcokDataTrait
{
    private function meargeStockQty($product)
    {
        $business = Auth::user()->business_id;


        $product_stock_history = ProductStockHistory::where('business_id', $business)
            ->where('product_id', $product->id)
            ->first();

        if (!$product_stock_history){
            $product_stock_history = new ProductStockHistory();
            $product_stock_history->business_id = $business;
            $product_stock_history->product_id = $product->id;
            $product_stock_history->save();
        }


        $sell_products = SellProduct::where('product_id', $product->id)
            ->where('business_id', $business)
            ->sum('quantity');

        $product_stock_history->sell_qty = $sell_products;
        $product_stock_history->save();


        /**
         * PurchaseProduct
         */
        $purchase_products = PurchaseProduct::where('product_id', $product->id)
            ->where('business_id', $business)
            ->sum('quantity');


        $product_stock_history->purchase_qty = $purchase_products;
        $product_stock_history->save();




        /**
         * Requisition
         */

        $branch_requisitions_to = \App\Models\Requisition::where('requisition_to', $business)
            ->where('status', 2)
            ->pluck('id')
            ->all();


        $branch_requisitions_to_qty = \App\Models\RequisitionProduct::whereIn('requisition_id', $branch_requisitions_to)
            ->where('product_id', $product->id)
            ->select('id')
            ->sum('quantity');



        $branch_requisitions_from = \App\Models\Requisition::where('requisition_from', $business)
            ->where('status', 2)
            ->pluck('id')
            ->all();

        $branch_requisitions_from_qty = \App\Models\RequisitionProduct::whereIn('requisition_id', $branch_requisitions_from)
            ->where('product_id', $product->id)
            ->select('id')
            ->sum('quantity');


        $product_stock_history->req_send = $branch_requisitions_to_qty; // Request request to brach
        $product_stock_history->req_received = $branch_requisitions_from_qty; // Request request form brach
        $product_stock_history->save();
    }


    private function meargeSellQty($product)
    {
        $branch = Auth::user()->employee->branch;

        $product_stock_history = ProductStockHistory::where('business_id', $branch->id)
            ->where('product_id', $product->id)
            ->first();

        if (!$product_stock_history){
            $product_stock_history = new ProductStockHistory();
            $product_stock_history->business_id = $branch->id;
            $product_stock_history->product_id = $product->id;
            $product_stock_history->save();
        }


        $sell_products = SellProduct::where('product_id', $product->id)
            ->where('business_id', $branch->id)
            ->sum('quantity');

        $product_stock_history->sell_qty = $sell_products;
        $product_stock_history->save();
        $product_stock_history->save();
    }

    private function meargePurchaseQty($product)
    {
        $branch = Auth::user()->employee->branch;

        $product_stock_history = ProductStockHistory::where('business_id', $branch->id)
            ->where('product_id', $product->id)
            ->first();

        if (!$product_stock_history){
            $product_stock_history = new ProductStockHistory();
            $product_stock_history->business_id = $branch->id;
            $product_stock_history->product_id = $product->id;
            $product_stock_history->save();
        }

        $purchase_products = PurchaseProduct::where('product_id', $product->id)
            ->where('business_id', $branch->id)
            ->sum('quantity');

        $product_stock_history->purchase_qty = $purchase_products;
        $product_stock_history->save();

        $product_stock_history->save();
    }

    private function meargeRequisitionQty($product)
    {
        $branch = Auth::user()->employee->branch;

        $product_stock_history = ProductStockHistory::where('business_id', $branch->id)
            ->where('product_id', $product->id)
            ->first();

        if (!$product_stock_history){
            $product_stock_history = new ProductStockHistory();
            $product_stock_history->business_id = $branch->id;
            $product_stock_history->product_id = $product->id;
            $product_stock_history->save();
        }



        /**
         * Requisition
         */

        $branch_requisitions_to = \App\Models\Requisition::where('requisition_to', $branch->id)
            ->where('status', 2)
            ->pluck('id')
            ->all();


        $branch_requisitions_to_qty = \App\Models\RequisitionProduct::whereIn('requisition_id', $branch_requisitions_to)
            ->where('product_id', $product->id)
            ->select('id')
            ->sum('quantity');



        $branch_requisitions_from = \App\Models\Requisition::where('requisition_from', $branch->id)
            ->where('status', 2)
            ->pluck('id')
            ->all();

        $branch_requisitions_from_qty = \App\Models\RequisitionProduct::whereIn('requisition_id', $branch_requisitions_from)
            ->where('product_id', $product->id)
            ->select('id')
            ->sum('quantity');


        $product_stock_history->req_send = $branch_requisitions_to_qty; // Request request to brach
        $product_stock_history->req_received = $branch_requisitions_from_qty; // Request request form brach
        $product_stock_history->save();
    }
}
