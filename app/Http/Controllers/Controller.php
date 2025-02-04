<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Http\Middleware\SuperAdminMiddleware;
use App\Http\Middleware\AdminMiddleware;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    public function __construct()
    {
        // Aquí se puede usar middleware de forma global para todas las rutas del controlador
        $this->middleware(SuperAdminMiddleware::class)->only('superAdmin');
        $this->middleware(AdminMiddleware::class)->only('admin');
    }
}
