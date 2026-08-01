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

class GroupMenuController extends Controller
{
    public function index()
    {
        $groups  = UserGroupMaster::orderBy('USER_GROUP_ID')->get();
        $menus   = MenuHierarchy::orderBy('SORT_BY')->get();
        $details = UserGroupDetail::get();

        // Build map: {GROUP_ID: {MENU_ID: 'Y'/'N'}}
        $groupAccess = [];
        foreach ($details as $d) {
            $groupAccess[$d->USER_GROUP_ID][$d->MENU_ITEM_ID] = $d->ENABLED;
        }
        return view('admin.groups.menu-access', compact('groups', 'menus', 'groupAccess'));
    }

    public function save(\Illuminate\Http\Request $request, $groupId)
    {
        $access = $request->input('access', []);
        foreach ($access as $menuId => $enabled) {
            DB::table('F_STORE.ALL_USER_GROUP_DETAILS')->updateOrInsert(
                ['MENU_ITEM_ID' => $menuId, 'USER_GROUP_ID' => $groupId, 'USER_ID' => null],
                [
                    'ENABLED'     => $enabled,
                    'UPDATE_BY'   => auth()->id() ?? 'USER',
                    'UPDATE_DATE' => now(),
                    'INSERT_DATE' => now(),
                    'INSERT_BY'   => auth()->id() ?? 'USER',
                ]
            );
        }
        return response()->json(['success' => true]);
    }
}
