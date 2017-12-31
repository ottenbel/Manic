<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminController extends Controller
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
        return view('dashboard.admin.main');
    }
	
	/**
     * Show the configuration page.
     *
     * @return \Illuminate\Http\Response
     */
    public function configuration()
    {
        return view('dashboard.admin.configuration.main');
    }
}
