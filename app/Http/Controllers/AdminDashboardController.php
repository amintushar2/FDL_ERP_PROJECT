<?php
// ─────────────────────────────────────────────────────────────
// DashboardController.php
// ─────────────────────────────────────────────────────────────
namespace App\Http\Controllers;

use App\Models\MenuHierarchy;
use App\Models\RouteDetail;
use App\Models\UserGroupMaster;
use App\Models\UserGroupDetail;
use App\Models\UserSubDetail;
use App\Models\UserInfo;

class AdminDashboardController extends BaseController
{
    public function index()
    {
        return view('admin.dashboard', [
            'menuCount'       => MenuHierarchy::count(),
            'routeCount'      => RouteDetail::count(),
            'groupCount'      => UserGroupMaster::count(),
            'userCount'       => UserInfo::count(),
            'permissionCount' => UserSubDetail::count(),
            'groupMenuCount'  => UserGroupDetail::where('ENABLED', 'Y')->count(),
            'recentUsers'     => UserInfo::latest('INSERT_DATE')->take(5)->get(),
            'recentMenus'     => MenuHierarchy::orderBy('SORT_BY')->take(6)->get(),
        ]);
    }
}
