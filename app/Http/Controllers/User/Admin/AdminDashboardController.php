<?php

namespace App\Http\Controllers\User\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminDashboardController extends Controller
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
        return view('user.admin.dashboard.main');
    }
	
	/**
     * Show the configuration page.
     *
     * @return \Illuminate\Http\Response
     */
    public function configuration()
    {
        return view('user.admin.dashboard.configuration.main');
    }
}
