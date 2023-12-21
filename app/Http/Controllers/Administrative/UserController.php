<?php

namespace App\Http\Controllers\Administrative;

use Carbon\Carbon;
use App\Models\Area;
use App\Models\Unit;
use App\Models\User;
use App\Models\Zone;
use App\Models\SubUnit;
use App\Models\Department;
use App\Models\Designation;
use App\Service\RoleService;
use App\Service\UserService;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserStoreRequest;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    protected $userService, $roleService;

    public function __construct(UserService $userService, RoleService $roleService)
    {
        $this->userService = $userService;
        $this->roleService = $roleService;
    }


    public function index()
    {
        if (!Gate::allows('user_list')) {
            return abort(401);
        }
        return view('administrative.user.index');
    }
    public function data()
    {
        if (!Gate::allows('user_list')) {
            return abort(401);
        }
        return  $this->userService->getAllData();
    }
    public function create()
    {
        if (!Gate::allows('user_create')) {
            return abort(401);
        }
        Session::forget('user_edit');
        $roles = Role::get()->pluck('name', 'id');
        return view('administrative.user.create', compact('roles'));
    }
    public function store(UserStoreRequest $request)
    {

        if (!Gate::allows('user_create')) {
            return abort(401);
        }

        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|unique:users',
            'name' => 'required|string|max:255',
            'role' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'phone' => 'required|unique:users',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withInput()
                ->withErrors($validator);
        }

        $input = $request->except('_token');

        $input['password'] = Hash::make($request->password);
        $input['designation'] = $request->designation;
        $input['place'] = $request->place;
        $input['joining_date'] = $request->joining_date;
        $input['courier_address'] = $request->courier_address;
        $input['sub_unit_id'] = $request->sub_unit_id;
        $input['email_verified_at'] = Carbon::now();

        $request = new Request($input);
        $user = User::create($input);
        $user->assignRole($request->input('role'));
        if ($user) {
            return redirect()->route('administrative.user')->with('success', 'User Created Successfully');
        } else {
            return redirect()->back()->withInput()->with('error', 'Something Wrong,Please Try Again');
        }
    }
    public function edit($id)
    {
        if (!Gate::allows('user_edit')) {
            return abort(401);
        }
        $data =  $this->userService->findbyId($id);
        $roles = Role::get()->pluck('name', 'id');
        if(isset($data->sub_unit_id)){
            $sub_unit = SubUnit::where('id',$data->sub_unit_id)->first();
        }else{
            $sub_unit = 0;
        }
        $unit = Unit::all();

        Session::forget('user_edit');
        Session::put('user_edit', $data);

        return view('administrative.user.edit', compact('data', 'roles', 'sub_unit','unit'));
    }
    public function update($id, Request $request)
    {

        if (!Gate::allows('user_edit')) {
            return abort(401);
        }


        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|unique:users,employee_id,' . $id, // Exclude the current user's ID
            'name' => 'required|string|max:255',
            'role' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $id, // Exclude the current user's ID
            'phone' => 'required|unique:users,phone,' . $id, // Exclude the current user's ID
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withInput()
                ->withErrors($validator);
        }

        $input = $request->except('_token', 'password');
        if ($request->filled('password')) {
            $input['password'] = Hash::make($request->password);
        }

        $input['designation'] = $request->designation;
        $input['place'] = $request->place;
        $input['joining_date'] = $request->joining_date;
        $input['courier_address'] = $request->courier_address;
        $input['sub_unit_id'] = $request->sub_unit_id;

        $user = User::find($id);
        $user->update($input);
        $user->syncRoles($request->input('role'));
        if ($user) {
            return redirect()->route('administrative.user')->with('success', 'User Updated Successfully');
        } else {
            return redirect()->back()->with('error', 'Something Wrong,Please Try Again');
        }
    }
    public function destroy($id)
    {
        if (!Gate::allows('user_delete')) {
            return abort(401);
        }
        $result = $this->userService->destroy($id);
        if ($result) {
            echo 'success';
        } else {
            echo 'error';
        }
    }

    public function getCreateForm(Request $request)
    {
        $role_id = $request->id;
        $zones = Zone::all();
        $areas = Area::all();
        $units = Unit::all();
        $subUnits = SubUnit::all();
        return View('administrative.user.edit-form', compact('role_id', 'zones', 'areas', 'units', 'subUnits'))->render();
    }

    public function getEditForm(Request $request)
    {
        $data = [];
        if (Session::has('user_edit')) {
            $data = Session::get('user_edit');
        }
        $role_id = $request->id;
        $zones = Zone::all();
        $areas = Area::all();
        $units = Unit::all();
        $subUnits = SubUnit::all();
        return View('administrative.user.edit-form', compact('data', 'role_id', 'zones', 'areas', 'units', 'subUnits'))->render();
    }
}
