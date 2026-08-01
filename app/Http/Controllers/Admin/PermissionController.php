<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MenuHierarchy;
use App\Models\RouteDetail;
use App\Models\UserGroupMaster;
use App\Models\UserGroupDetail;
use App\Models\UserSubDetail;
use App\Models\UserInfo;
use Illuminate\Support\Facades\DB;


class PermissionController extends Controller
{
    public function index()
    {
        $permissions = UserSubDetail::whereNull('USER_ID')
            ->orderBy('USER_GROUP_ID')
            ->orderBy('MENU_ITEM_ID')
            ->orderBy('SUB_MENU_1')
            ->orderBy('SUB_MENU_2')
            ->orderBy('SUB_MENU_ID')
            ->get();
        $groups      = UserGroupMaster::orderBy('USER_GROUP_ID')->get();
        $routes      = RouteDetail::orderBy('ROUTE_ID')->get();
        $menus       = MenuHierarchy::orderBy('SORT_BY')->get();

        return view('admin.permissions.index', compact('permissions', 'groups', 'routes', 'menus'));
    }

    public function store(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'USER_GROUP_ID' => 'required',
            'SUB_MENU_ID'   => 'required',
            'MENU_ITEM_ID'  => 'required',
            'SUB_MENU_NAME' => 'required|max:100',
            'ENABLED'       => 'required|in:Y,N',
            'LEVEL'         => 'required|in:2,3',
            'SUB_MENU_2'    => 'required_if:LEVEL,3',
        ]);

        $route = RouteDetail::find($request->SUB_MENU_ID);
        $parentSubMenuId = $request->LEVEL === '3' ? $request->SUB_MENU_2 : null;

        DB::table('F_STORE.ALL_USER_SUB_DETAILS')->updateOrInsert(
            [
                'USER_GROUP_ID' => $request->USER_GROUP_ID,
                'SUB_MENU_ID'   => $request->SUB_MENU_ID,
                'USER_ID'       => null,
            ],
            [
                'MENU_ITEM_ID'  => $request->MENU_ITEM_ID,
                'SUB_MENU_1'    => $request->SUB_MENU_1,
                'SUB_MENU_2'    => $parentSubMenuId,
                'SUB_MENU_NAME' => $request->SUB_MENU_NAME,
                'ROUTE'         => $request->ROUTE ?: ($route->ROUTE_PATH ?? null),
                'ENABLED'       => $request->ENABLED,
            ]
        );

        return redirect()->route('permissions.index')->with('success', 'Permission saved.');
    }

    public function update(\Illuminate\Http\Request $request, $groupId, $subMenuId)
    {
        $request->validate([
            'MENU_ITEM_ID'  => 'required',
            'SUB_MENU_NAME' => 'required|max:100',
            'ENABLED'       => 'required|in:Y,N',
        ]);

        DB::table('F_STORE.ALL_USER_SUB_DETAILS')
            ->where('USER_GROUP_ID', $groupId)
            ->where('SUB_MENU_ID', $subMenuId)
            ->whereNull('USER_ID')
            ->update([
                'MENU_ITEM_ID'  => $request->MENU_ITEM_ID,
                'SUB_MENU_1'    => $request->SUB_MENU_1,
                'SUB_MENU_2'    => $request->SUB_MENU_2,
                'SUB_MENU_NAME' => $request->SUB_MENU_NAME,
                'ROUTE'         => $request->ROUTE,
                'ENABLED'       => $request->ENABLED,
            ]);

        return redirect()->route('permissions.index')->with('success', 'Permission updated.');
    }

    public function destroy($groupId, $subMenuId)
    {
        DB::table('F_STORE.ALL_USER_SUB_DETAILS')
            ->where('USER_GROUP_ID', $groupId)
            ->where('SUB_MENU_ID', $subMenuId)
            ->whereNull('USER_ID')
            ->delete();

        return redirect()->route('permissions.index')->with('success', 'Permission removed.');
    }
}

