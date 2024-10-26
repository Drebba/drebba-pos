<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Table;
use Illuminate\Support\Facades\Auth;

class TableController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Auth::user()->can('manage_table')) {
            return redirect('home')->with(denied());
        } // end permission checking

        return view('backend.table.index',[
            'tables' =>Auth::user()->business->table()
            ->orderBy('id', 'DESC')->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Auth::user()->can('manage_table')) {
            return redirect('home')->with(denied());
        } // end permission checking

        return view('backend.table.create',[
            'tables' =>Auth::user()->business->table()->orderBy('id', 'DESC')->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Auth::user()->can('manage_table')) {
            return redirect('home')->with(denied());
        } // end permission checking

        $table = new Table();
        $table->name = $request->table['name'];
        $table->business_id = Auth::user()->business_id;
        $table->save();
        return response($table);
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
        if (!Auth::user()->can('manage_table')) {
            return redirect('home')->with(denied());
        } // end permission checking

        return view('backend.table.edit',[
            'table' => Auth::user()->business->table()->where('id',$id)->first()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!Auth::user()->can('manage_table')) {
            return redirect('home')->with(denied());
        } // end permission checking

        $table = Auth::user()->business->table()->where('id',$id)->firstOrFail();
        $table->name = $request->table['name'];
        $table->save();
        return response()->json(['success', 'Table Successfully Updated']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!Auth::user()->can('manage_table')) {
            return redirect('home')->with(denied());
        } // end permission checking


        Auth::user()->business->table()->where('id',$id)->delete();
        return response()->json(['success', 'Table has been deleted successfully']);
    }
}
