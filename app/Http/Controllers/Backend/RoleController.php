<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests\RoleRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Toastr;
class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.role.index', [
            'roles' => Auth::user()->business->role()->orderBy('id', 'DESC')->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RoleRequest $request)
    {
        $role = new Role();
        $role->name = $request->role['name'];
        $role->guard_name = 'web';
        $role->business_id=Auth::user()->business_id;
        $role->save();
        return response($role);

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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(RoleRequest $request, $id)
    {
        $role = Role::findOrFail($id);
        $role->name = $request->role['name'];
        $role->save();
        return response($role);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {


      try {
        $role= Auth::user()->business->role()->findorFail($id);
        $role->delete();
        return response()->json(['success', 'Role Deleted'], 200);
      } catch (\Throwable $th) {
        return response()->json(['error', 'Role not Deleted'], 412);
      }
    }




    public function permission()
    {
        if (!Auth::user()->can('manage_user')) {
            return redirect('home')->with(denied());
        } // end permission checking

        $data['title'] = 'Set Permission';
        $data['roles'] = DB::table('roles')->where('business_id',Auth::user()->business_id)->get();
        $data['permissions'] = DB::table('permissions')->get();
        $data['permissionRole'] = DB::table('role_has_permissions')
              ->where('business_id',Auth::user()->business_id)
            ->select(DB::raw('CONCAT(role_id,"-",permission_id) AS detail'))
            ->pluck('detail')->toArray();

        return view('backend.settings.user-permission', $data);
    }

    public function savePermission(Request $request)
    {
        if (!Auth::user()->can('manage_user')) {
            return redirect('home')->with(denied());
        } // end permission checking

        DB::table('role_has_permissions')->where('business_id',Auth::user()->id)->truncate();
        $permissions = $request->permission;

        if ($permissions)
            foreach ($permissions as $r_key => $permission) {
                foreach ($permission as $p_key => $per) {
                    $values[] = $p_key;
                }

                if (count($values))
                    for ($i = 0; $i < count($values); $i++)
                    {
                        DB::table('role_has_permissions')->insert([
                            'permission_id' => $values[$i],
                            'role_id' => $r_key,
                            'business_id'=>Auth::user()->business_id
                        ]);
                    }
                unset($values);
            }

        Artisan::call('cache:clear');

        Toastr::success('Permission successfully Saved', '', ['progressBar' => true, 'closeButton' => true, 'positionClass' => 'toast-bottom-right']);
        return redirect()->back();
    }
}
