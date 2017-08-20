<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function main()
    {
        return view('dashboard.user.main');
    }
	
	/**
     * Show the configuration page.
     *
     * @return \Illuminate\Http\Response
     */
    public function configuration()
    {
        return view('dashboard.user.configuration.main');
    }
}
