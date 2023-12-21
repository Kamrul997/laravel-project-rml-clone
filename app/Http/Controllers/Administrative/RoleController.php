<?php
namespace App\Http\Controllers\Administrative;

use App\Service\RoleService;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\RoleStoreRequest;
use Spatie\Permission\Models\Permission;


class RoleController extends Controller
{

    protected $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    public function index()
    {
        if(! Gate::allows('role_list')){
            return abort(401);
        }
        return view('administrative.role.index');
    }
    public function data()
    {
        if(! Gate::allows('role_list')){
            return abort(401);
        }
        return  $this->roleService->getAllData();
    }
    public function create()
    {
        if(! Gate::allows('role_create')){
            return abort(401);
        }
        $permissions = Permission::get()->pluck('name', 'name');
        return view('administrative.role.create',compact('permissions'));
    }
    public function store(RoleStoreRequest $request)
    {
        if(! Gate::allows('role_create')){
            return abort(401);
        }
        $input = $request->except('_token','permission');
        $permissionRequest = $request->except('_token','name');
        $request = new Request($input);
        $result = $this->roleService->store($request);
        $permissions = $permissionRequest ? $permissionRequest : [];
        $result->givePermissionTo($permissions);
        if($result){
            return redirect()->route('administrative.role')->with('success', 'Role Created Successfully');
        }else{
            return redirect()->back()->withInput()->with('error', 'Something Wrong,Please Try Again');
        }
    }
    public function edit($id)
    {
        if(! Gate::allows('role_edit')){
            return abort(401);
        }
        $permissions = Permission::get()->pluck('name', 'name');
        $data =  $this->roleService->findbyId($id);
        return view('administrative.role.edit',compact('data','permissions'));
    }
    public function update($id,Request $request)
    {
        if(! Gate::allows('role_edit')){
            return abort(401);
        }
        $input = $request->except('_token','_method','permission');
        $permissionRequest = $request->except('_token','_method','name');
        $request = new Request($input);
        $role = Role::find($id);
        $role->update($input);
        $permissions = $permissionRequest ? $permissionRequest : [];
        $role->syncPermissions($permissions);
        if($role){
            return redirect()->route('administrative.role')->with('success', 'Role Updated Successfully');
        }else{
            return redirect()->back()->with('error', 'Something Wrong,Please Try Again');
        }
    }

    public function destroy($id)
    {
        if(! Gate::allows('role_delete')){
            return abort(401);
        }
        $result = $this->roleService->destroy($id);
        if($result){
            echo 'success';
        }else{
            echo 'error';
        }
    }


}
