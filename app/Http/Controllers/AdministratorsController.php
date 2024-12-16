<?php

namespace App\Http\Controllers;

use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\SuperadmiAdminMiddleware;
use Illuminate\Http\Request;
use App\Models\User;

class AdministratorsController extends Controller
{
    function __construct(){
        $this->middleware(AdminMiddleware::class);
        $this->middleware(SuperadmiAdminMiddleware::class)->only(['indexSuper']);
        $this->middleware(SuperadmiAdminMiddleware::class)->except(['indexSuper']);
    }

    function index() {
        //$users = User::where('role', '<>', 'admin')->orderBy('name')->get();
        $users = User::where('id', '<>', '1')->orderBy('name')->get();
        return view('admin.index', ['users' => $users]);
    }

    function indexSuper() {
        $users = User::orderBy('name')->get();
        return view('admin.index', ['users' => $users]);
    }
}
