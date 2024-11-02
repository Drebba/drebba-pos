<?php

namespace App\Http\Controllers\Admin;

use App\Models\Branch;
use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\Employee;
use App\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Support\Facades\Hash;
use Toastr;

class BusinessController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return view('admin.business.index',[
            'businesses' => Business::orderBy('id', 'DESC')->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.business.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:businesses,email',
            'phone' => 'required|numeric',
            'address' => 'required',
            'city' => 'required',
            'contact_person' => 'required',
            ]);

        $business = new Business();
        $business->name=$request->name;
        $business->email=$request->email;
        $business->phone=$request->phone;
        $business->city=$request->city;
        $business->address=$request->address;
        $business->contact_person=$request->contact_person;
        $business->save();

        Toastr::success('Branch has been created', '', ['progressBar' => true, 'closeButton' => true, 'positionClass' => 'toast-bottom-right']);
        return redirect()->route('admin.business.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $business=Business::find($id);
        $user=User::find($business->owner_id);
         FacadesAuth::guard('web')->login($user);
         return redirect('/home');
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('admin.business.edit',[
            'business' => Business::findOrFail($id)
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

        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:businesses,email,' . $id . ',id',
            'phone' => 'required|numeric',
            'address' => 'required',
            'city' => 'required',
            'contact_person' => 'required',
            ]);

        $business = Business::find($id);
        $business->name=$request->name;
        $business->email=$request->email;
        $business->phone=$request->phone;
        $business->city=$request->city;
        $business->address=$request->address;
        $business->contact_person=$request->contact_person;
        $business->save();

        Toastr::success('Branch has been updated', '', ['progressBar' => true, 'closeButton' => true, 'positionClass' => 'toast-bottom-right']);
        return redirect()->route('admin.business.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {


        Business::destroy($id);
        Toastr::success('Branch has been deleted', '', ['progressBar' => true, 'closeButton' => true, 'positionClass' => 'toast-bottom-right']);
        return redirect()->back();

    }
}
