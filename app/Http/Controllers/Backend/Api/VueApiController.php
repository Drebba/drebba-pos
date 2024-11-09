<?php

namespace App\Http\Controllers\Backend\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VueApiController extends Controller
{
    public function products()
    {
        $products = Auth::user()->business->product()->where('type',0)->where('status', 1)->with('productVariantPrices')->get();
        return response($products);
    }

    public function productCategories()
    {
        $product_categories = Auth::user()->business->category()->where('type',0)->where('status', 1)->get();
        return response($product_categories);
    }


}
