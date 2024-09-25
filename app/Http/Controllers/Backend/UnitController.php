<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\UnitRequest;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Auth::user()->can('manage_unit')) {
            return redirect('home')->with(denied());
        } // end permission checking

        return view('backend.unit.index',[
            'units' => Unit::orderBy('id', 'DESC')->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Auth::user()->can('manage_unit')) {
            return redirect('home')->with(denied());
        } // end permission checking

        return view('backend.unit.create',[
            'units' => Unit::orderBy('id', 'DESC')->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(unitRequest $request)
    {
        if (!Auth::user()->can('manage_unit')) {
            return redirect('home')->with(denied());
        } // end permission checking

        $unit = new Unit();
        $unit->title = $request->unit['title'];
        $unit->save();
        return response($unit);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!Auth::user()->can('manage_unit')) {
            return redirect('home')->with(denied());
        } // end permission checking

        return view('backend.unit.edit',[
            'unit' => Unit::findOrFail($id)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UnitRequest $request, $id)
    {
        if (!Auth::user()->can('manage_unit')) {
            return redirect('home')->with(denied());
        } // end permission checking

        $unit = Unit::findOrFail($id);
        $unit->title = $request->unit['title'];
        $unit->save();
        return response()->json(['success', 'Unit Successfully Updated']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!Auth::user()->can('manage_unit')) {
            return redirect('home')->with(denied());
        } // end permission checking


        Unit::destroy($id);
        return response()->json(['success', 'Unit has been deleted successfully']);
    }
}
