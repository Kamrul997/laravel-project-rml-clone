<?php

namespace App\Http\Controllers\Administrative;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{
  public function index()
  {
    return view('administrative.dashboard');
  }
}
