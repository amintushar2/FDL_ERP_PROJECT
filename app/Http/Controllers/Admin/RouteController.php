<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MenuHierarchy;
use App\Models\RouteDetail;

class RouteController extends Controller
{
    /**
     * Display Route Page
     */
    public function index()
    {
        $routes = RouteDetail::orderBy('ROUTE_ID')->get();

        $menus = MenuHierarchy::orderBy('SORT_BY')->get();

        return view('admin.routes.index', compact(
            'routes',
            'menus'
        ));
    }

    /**
     * Store Route
     */
    public function store(Request $request)
    {
        $request->validate([

            'ROUTE_ID'      => 'required|max:30|unique:ALL_ROUTE_DETAILS,ROUTE_ID',

            'ROUTE_PATH'    => 'required|max:256',

            'MENU_ID'       => 'required|max:30',

            'SUB_MENU_ID'   => 'nullable|max:30',

            'SUB_MENU_1'    => 'nullable|max:30',

            'SUB_MENU_2'    => 'nullable|max:30',

            'COMPONENT'     => 'nullable|max:100',

            'SUB_MENU_NAME' => 'nullable|max:200',

            'IS_ACTIVE'     => 'required|in:Y,N',
        ]);

        RouteDetail::create([

            'ROUTE_ID'      => strtoupper(trim($request->ROUTE_ID)),

            'ROUTE_PATH'    => trim($request->ROUTE_PATH),

            // MAIN MENU
            'MENU_CHILD_ID' => trim($request->MENU_ID),

            // SUB MENU
            'SUB_MENU_ID'   => trim($request->SUB_MENU_ID),

            // PARENT ROUTE
            'SUB_MENU_1'    => trim($request->SUB_MENU_1),

            // LEVEL 3
            'SUB_MENU_2'    => trim($request->SUB_MENU_2),

            // COMPONENT
            'COMPONENT'     => trim($request->COMPONENT),

            // SCREEN NAME
            'SUB_MENU_NAME' => trim($request->SUB_MENU_NAME),

            'DESCRIPTION'   => trim($request->DESCRIPTION),

            'IS_ACTIVE'     => $request->IS_ACTIVE,

            'INSERT_BY'     => auth()->id() ?? 'USER',

            'INSERT_DATE'   => now(),
        ]);

        return redirect()
            ->route('routes.index')
            ->with('success', 'Route saved successfully.');
    }

    /**
     * Delete Route
     */
    public function destroy($id)
    {
        $route = RouteDetail::where('ROUTE_ID', $id)->firstOrFail();

        $route->delete();

        return redirect()
            ->route('routes.index')
            ->with('success', 'Route deleted successfully.');
    }
}
