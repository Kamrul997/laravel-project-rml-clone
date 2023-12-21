<?php

/**
 * Created by PhpStorm.
 * User: Hashibul Hasan
 * Date: 01-Jul-19
 * Time: 1:46 PM
 */

namespace App\Service;


use App\Repositories\UserRepository;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    protected $userRepository;

    // Constructor to bind model to repo
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function authenticate($request)
    {
        $credentials = $request->only('mail', 'password');
        return Auth::attempt($credentials);
    }

    public function authenticateByUsername($request)
    {
        // dd($request->all());
        // $credentials = $request->only('username','password');
        return Auth::attempt(['username' => $request->username, 'password' => $request->password]);
        // dd('test');
    }
}
