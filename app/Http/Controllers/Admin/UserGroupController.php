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

class UserGroupController extends Controller
{
    public function index()
    {
        $groups = UserGroupMaster::orderBy('USER_GROUP_ID')->get();
        return view('admin.groups.index', compact('groups'));
    }

    public function store(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'USER_GROUP_ID' => 'required|max:10|unique:ALL_USER_GROUP_MASTER,USER_GROUP_ID',
            'GROUP_TITLE'   => 'required|max:30',
        ]);
        UserGroupMaster::create($request->only(['USER_GROUP_ID','GROUP_TITLE','GROUP_DESCRIPTION'])
            + ['INSERT_BY' => auth()->id() ?? 'USER', 'INSERT_DATE' => now()]);

        return redirect()->route('groups.index')->with('success', 'Group created.');
    }

    public function destroy($id)
    {
        UserGroupMaster::findOrFail($id)->delete();
        return redirect()->route('groups.index')->with('success', 'Group deleted.');
    }
}

