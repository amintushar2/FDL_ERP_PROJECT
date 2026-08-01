<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SidebarMenuService;
use Illuminate\Support\Facades\Log;

class BaseController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {

            // Only share if a user session exists
            if (session('LoggedUser')) {
                try {
                    view()->share(app(SidebarMenuService::class)->dataForLoggedUser(
                        session('LoggedUser'),
                        $request->path()
                    ));
                } catch (\Exception $e) {
                    Log::error('Sidebar menu load failed: ' . $e->getMessage());
                }
            }

            return $next($request);
        });
    }
}
