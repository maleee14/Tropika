<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardContoller extends Controller
{
    public function index()
    {
        return view('user.home');
    }
}
