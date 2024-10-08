<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests\SupplierRequest;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Toastr;
use File;
use Illuminate\Support\Facades\Auth;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Auth::user()->can('manage_supplier')) {
            return redirect('home')->with(denied());
        } // end permission checking

        return view('backend.supplier.index',[
            'suppliers' => Auth::user()->business->supplier()->orderBy('id', 'DESC')->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Auth::user()->can('manage_supplier')) {
            return redirect('home')->with(denied());
        } // end permission checking


        return view('backend.supplier.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SupplierRequest $request)
    {
        if (!Auth::user()->can('manage_supplier')) {
            return redirect('home')->with(denied());
        } // end permission checking

        $request['business_id']=Auth::user()->business_id;
        $supplier = new Supplier();
        $supplier->fill($request->all());
        if($request->hasFile('logo')){
            $supplier->logo = $request->logo->move('uploads/supplier/', Str::random(20) . '.' . $request->logo->extension());;
        }
        $supplier->save();
        Toastr::success('Supplier has been created', '', ['progressBar' => true, 'closeButton' => true, 'positionClass' => 'toast-bottom-right']);
        return redirect()->route('supplier.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!Auth::user()->can('manage_supplier')) {
            return redirect('home')->with(denied());
        } // end permission checking

        $purchases = Auth::user()->business->purchase()->where('supplier_id', $id)->paginate(50);
        $supplier = Auth::user()->business->supplier()->where('id',$id)->firstOrFail();
        return view('backend.supplier.show',[
            'supplier' => $supplier,
            'purchases' => $purchases,
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
        if (!Auth::user()->can('manage_supplier')) {
            return redirect('home')->with(denied());
        } // end permission checking


        return view('backend.supplier.edit',[
            'supplier' => Auth::user()->business->supplier()->where('id',$id)->firstOrFail()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SupplierRequest $request, $id)
    {
        if (!Auth::user()->can('manage_supplier')) {
            return redirect('home')->with(denied());
        } // end permission checking

        $supplier = Supplier::findOrFail($id);
        $supplier->fill($request->all());
        if($request->hasFile('logo')){
            File::delete($supplier->logo);
            $supplier->logo = $request->logo->move('uploads/supplier/', Str::random(20) . '.' . $request->logo->extension());;
        }
        $supplier->save();
        Toastr::success('Supplier has been updated', '', ['progressBar' => true, 'closeButton' => true, 'positionClass' => 'toast-bottom-right']);
        return redirect()->back();
    }

    public function changeStatus($id)
    {
        $supplier = Auth::user()->business->supplier()->where('id',$id)->firstOrFail();
        $supplier->status = $supplier->status == 0 ? 1 : 0;
        $supplier->save();

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
        if (!Auth::user()->can('manage_supplier')) {
            return redirect('home')->with(denied());
        } // end permission checking


        Auth::user()->business->supplier()->where('id',$id)->delete();
        Toastr::error('Supplier has been deleted', '', ['progressBar' => true, 'closeButton' => true, 'positionClass' => 'toast-bottom-right']);
        return redirect()->back();

    }
}
