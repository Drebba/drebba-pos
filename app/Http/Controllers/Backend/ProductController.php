<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests\ProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Traits\RedirectControlTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Toastr;
use Milon\Barcode\DNS1D;
use PDF;

class ProductController extends Controller
{
    use RedirectControlTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!Auth::user()->can('manage_product')) {
            return redirect('home')->with(denied());
        } // end permission checking


        $products = Auth::user()->business->product()->orderBy('id', 'DESC');

        if ($request->category_id != ''){
            $products = $products->where('category_id', $request->category_id);
        }

        if ($request->search_key != ''){
            $search_key = $request->search_key;
            $products =  $products->where(function($query) use ($search_key) {
                $query->where('title',  'like', '%'.$search_key.'%');
                $query->orWhere('sku', 'like', '%'.$search_key.'%');
            });
        }

        $products = $products->with('unit')->paginate(24);

        $categories = Category::select('id', 'title')->get();

        return view ('backend.product.index', [
            'products' => $products,
            'categories' => $categories,
        ]);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Auth::user()->can('manage_product')) {
            return redirect('home')->with(denied());
        } // end permission checking

        return view('backend.product.create', [
            'categories' => Auth::user()->business->category()->where('status', '1')->get(),
            'taxes' => Auth::user()->business->tax()->orderBy('title', 'asc')->get(),
            'units' => Auth::user()->business->unit()->orderBy('title', 'asc')->get(),
            'new_sku' => str_pad(Product::withTrashed()->count()+1,get_option('invoice_length'),0,STR_PAD_LEFT),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
        if (!Auth::user()->can('manage_product')) {
            return redirect('home')->with(denied());
        } // end permission checking


        $sku_prifix = get_option('product_sku_prefix');
        $request->merge([
            'sku' => $sku_prifix.$request->get('sku')
        ]);

        $this->validate($request, [
            'sku' => 'required|max:255|unique:products',
        ]);

        $product = new Product();
        $request['business_id']=Auth::user()->business_id;
        $product->fill($request->all());
        if($request->hasFile('thumbnail')){
            $product->thumbnail = $request->thumbnail->move('uploads/product/', Str::random(40) . '.' . $request->thumbnail->extension());
        }
        $product->save();

        return $this->controlRedirection($request, 'product', 'Product');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!Auth::user()->can('manage_product')) {
            return redirect('home')->with(denied());
        } // end permission checking

        $product = Auth::user()->business->product()->with('unit', 'sellProducts')->findOrFail($id);
        $last_30_days_sell = $this->last30DaysSell($product);


        return view('backend.product.show', [
            'product' => $product,
            'last_30_days_sell' => $last_30_days_sell
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!Auth::user()->can('manage_product')) {
            return redirect('home')->with(denied());
        } // end permission checking
        $data['product'] = Auth::user()->business->product()->where('id',$id)->first();
        $data['categories'] = Auth::user()->business->category()->where('status', '1')->orderBy('title', 'asc')->get();
        $data['taxes'] = Auth::user()->business->tax()->orderBy('title', 'asc')->get();
        $data['units'] = Auth::user()->business->unit()->orderBy('title', 'asc')->get();

        return view('backend.product.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequest $request, $id)
    {
        if (!Auth::user()->can('manage_product')) {
            return redirect('home')->with(denied());
        } // end permission checking

        $product = Product::findOrFail($id);
        $product->fill($request->all());
        if($request->hasFile('thumbnail')){
            $product->thumbnail = $request->thumbnail->move('uploads/product/', Str::random(40) . '.' . $request->thumbnail->extension());
        }
        if ($product->save()){
            return $this->controlRedirection($request, 'product', 'Product');
        }
    }

    public function changeStatus($id)
    {
        $product = Auth::user()->business->product()->where('id',$id)->first();
        $product->status = $product->status == 0 ? 1 : 0;
        $product->save();

        Toastr::success('Status has been changed', '', ['progressBar' => true, 'closeButton' => true, 'positionClass' => 'toast-bottom-right']);
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!Auth::user()->can('manage_product')) {
            return redirect('home')->with(denied());
        } // end permission checking

        Auth::user()->business->product()->where('id',$id)->delete();
        Toastr::error('Product has been deleted', '', ['progressBar' => true, 'closeButton' => true, 'positionClass' => 'toast-bottom-right']);
        return redirect()->back();
    }


    private function last30DaysSell($product){
        $last_30_days_sell = [];
        $key = 0;
        for ($i=30; $i >= 0 ; $i--)
        {
            $date_info = Carbon::now()->subDay($i)->format('Y-m-d');
            $last_30_days_sell[$key]['sell_date'] = $date_info;
            $last_30_days_sell[$key]['total_sell_amount'] = $product->sellProducts->where('sell_date', $date_info)->sum('total_price');
            $key++;
        }

        return $last_30_days_sell;
    }

    public function downloadBarcode(Request $request, $id)
    {
        ini_set('max_execution_time', 6000); // 100 Min

        $product = Product::findOrFail($id);
        $numberOfCode = $request->quantity;
        $pdf = PDF::loadView('backend.pdf.barcode-for-product', ['product' => $product, 'numberOfCode' => $numberOfCode])->setPaper('a4');
        return $pdf->download('barcode-' . $product->id . '.pdf');
    }


}
