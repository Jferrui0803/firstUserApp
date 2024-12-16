<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth') -> except(['guest', 'index']);
        $this->middleware('verified') -> only(['verified']);
        $this->middleware('guest') -> only(['guest']);
        // $this->middleware('auth')->only();
    }

    function guest() {
        return view('user.guest');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function home()
    {
        return view('home');
    }

    function index() {
        return view('index');
    }

    function verificado() {
        return view('verificado');
    }
}
