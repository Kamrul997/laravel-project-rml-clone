<?php

namespace App\Http\Controllers\Administrative;

use App\Service\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function index()
    {
        return view('administrative.login');
    }
    protected function authenticated(Request $request, $user)
    {
        return redirect('/dashboard');
    }

    protected function validateLogin(Request $request)
    {

        $request->validate([
            $this->username() => 'required',
            'password' => 'required',
        ]);
    }

    public function authenticate(Request $request)
    {

        $requestEmployeeId = $request->get('employee_id');
        $this->validate($request, [
            'employee_id' => 'required', 'password' => 'required',
        ]);

        $credentials = [
            'employee_id' => $requestEmployeeId,
            'password' => $request->get('password')
        ];
        $user = User::where('employee_id', $requestEmployeeId)->first();

        if ($user && $user->status == 'inactive') {
            $errors = new MessageBag(['password' => ['Id Deactived.']]);
            return redirect()->back()->withErrors($errors);
        }

        // $result = Auth::attempt($credentials);

        if ($user->employee_id == $request->get('employee_id') && password_verify( $request->get('password'), $user->password)) {
            Auth::login($user);
            // dd('ojk');
            return redirect()->route('administrative.dashboard');
        }else {
            // dd('not ojk');
            $errors = new MessageBag(['password' => ['Employee ID and/or Password invalid.']]);
            return redirect()->back()->withErrors($errors);
          }
    }
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
