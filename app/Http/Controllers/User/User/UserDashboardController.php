<?php

namespace App\Http\Controllers\User\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserDashboardController extends Controller
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
        return view('user.user.dashboard.main');
    }
	
	/**
     * Show the configuration page.
     *
     * @return \Illuminate\Http\Response
     */
    public function configuration()
    {
        return view('user.user.dashboard.configuration.main');
    }
}
