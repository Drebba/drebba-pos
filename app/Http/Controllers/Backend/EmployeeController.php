<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests\EmployeeRequest;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Employee;
use App\Traits\RedirectControlTrait;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use DB;
use Toastr;

class EmployeeController extends Controller
{
    use RedirectControlTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Auth::user()->can('manage_employee')) {
            return redirect('home')->with(denied());
        } // end permission checking

        $employees =  Auth::user()->business->employee()->orderBy('id', 'DESC')->get();

        return view('backend.employee.index', [
            'employees' => $employees
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Auth::user()->can('manage_employee')) {
            return redirect('home')->with(denied());
        } // end permission checking

        return view('backend.employee.create', [
            'departments' => Auth::user()->business->department()->orderBy('title', 'asc')->get(),
            'designations' => Auth::user()->business->designation()->orderBy('title', 'asc')->get(),
            'roles' => Auth::user()->business->role,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EmployeeRequest $request)
    {
        if (!Auth::user()->can('manage_employee')) {
            return redirect('home')->with(denied());
        } // end permission checking

        $user = new User();
        $user->fill($request->all());
        $user->password = Hash::make($request->get('password'));
        $user->assignRole($request->role);
        $user->business_id = Auth::user()->business_id;
        $user->save();

        $employee = new Employee();
        $employee->fill($request->all());
        $employee->business_id = Auth::user()->business_id;
        $employee->user_id = $user->id;
        if($request->hasFile('profile_picture')){
            $employee->profile_picture = $request->profile_picture->move('uploads/user/', Str::random(20) . '.' . $request->profile_picture->extension());;
        }
        $employee->save();

        return $this->controlRedirection($request, 'employee', 'Employee');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $employee = Auth::user()->business->employee()->findOrFail($id);
        if (!Auth::user()->can('access_to_all_branch')){
            if ($employee->business_id  = Auth::user()->business_id){
                return redirect()->back()->with(denied());
            }
        }

        if (DB::table('model_has_roles')->where('model_id', $employee->user_id)->count() > 0) {
            $selected_role = DB::table('model_has_roles')->where('model_id', $employee->user_id)->first();
            $role = Auth::user()->business->role()->findOrFail($selected_role->role_id)->name;
        } else {
            $role = '--';
        }

        return view('backend.employee.show', [
            'role' => $role,
            'employee' => Auth::user()->business->employee()->findOrFail($id),
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
        if (!Auth::user()->can('manage_employee')) {
            return redirect('home')->with(denied());
        } // end permission checking

       $employee = Auth::user()->business->employee()->findOrFail($id);

        if (!Auth::user()->can('access_to_all_branch')){
            if ($employee->business_id  = Auth::user()->business_id){
                return redirect()->back()->with(denied());
            }
        }


        if (DB::table('model_has_roles')->where('model_id', $employee->user_id)->count() > 0) {
            $selected_role = DB::table('model_has_roles')->where('model_id', $employee->user_id)->first();
            $selected_role_id = $selected_role->role_id;
        } else {
            $selected_role_id = 0;
        }


        return view('backend.employee.edit', [
            'departments' => Auth::user()->business->department()->orderBy('title', 'asc')->get(),
            'designations' => Auth::user()->business->designation()->orderBy('title', 'asc')->get(),
            'roles' => Auth::user()->business->role,
            'selected_role_id' => $selected_role_id,
            'employee' => Auth::user()->business->employee()->findOrFail($id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EmployeeRequest $request, $id)
    {

       if (!Auth::user()->can('manage_employee')) {
            return redirect('home')->with(denied());
        } // end permission checking

        $employee = Auth::user()->business->employee()->findOrFail($id);

        if (!Auth::user()->can('access_to_all_branch')){
            if ($employee->business_id  = Auth::user()->business_id){
                return redirect()->back()->with(denied());
            }
        }


        $employee->fill($request->all());
        if($request->hasFile('profile_picture')){
            $employee->profile_picture = $request->profile_picture->move('uploads/employee/', Str::random(40) . '.' . $request->profile_picture->extension());;
        }
        $employee->save();

        $user = User::findOrFail($employee->user_id);
        $user->fill($request->all());
        if ($request->password != ''){
            $user->password = bcrypt($request->password);
        }
        $user->save();

        DB::table('model_has_roles')->where('model_id', $user->id)->where('business_id',Auth::user()->id)->delete();
        $user->assignRole($request->role);

        return $this->controlRedirection($request, 'employee', 'Employee');
    }

    public function changeStatus($user_id)
    {
        if (!Auth::user()->can('manage_employee')) {
            return redirect('home')->with(denied());
        } // end permission checking

        $user = User::where('business_id',Auth::user()->business_id)->findOrFail($user_id);
        $user->active_status = $user->active_status == 1 ? 0 : 1;
        $user->save();

        $employee  = $user->employee;
        $employee->status = $user->active_status;
        Toastr::success('Status successfully changed', '', ['progressBar' => true, 'closeButton' => true, 'positionClass' => 'toast-bottom-right']);
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
        if (!Auth::user()->can('manage_employee')) {
            return redirect('home')->with(denied());
        } // end permission checking

        $employee = Auth::user()->business->employee()->findOrFail($id);
        User::destroy($employee->id);
        Employee::destroy($id);
        Toastr::success('Employee Successfully deleted', '', ['progressBar' => true, 'closeButton' => true, 'positionClass' => 'toast-bottom-right']);
        return redirect()->back();
    }
}
