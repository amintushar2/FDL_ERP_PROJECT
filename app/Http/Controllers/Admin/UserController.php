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

class UserController extends Controller
{
    public function index()
    {
        $users  = UserInfo::orderBy('INSERT_DATE', 'desc')->get();
        $groups = UserGroupMaster::orderBy('USER_GROUP_ID')->get();

        return view('admin.users.index', compact('users', 'groups'));
    }

    public function store(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'USER_ID'          => 'required|max:30|unique:ALL_USER_INFO,USER_ID',
            'EMPLOYEE_ID'      => 'required|max:15',
            'USER_GROUP_ID'    => 'required',
            'USER_ROLE'        => 'required|max:100',
            'INITIAL_PASSWORD' => 'required|max:30',
            'USER_STATUS'      => 'required',
        ]);

        UserInfo::create($request->only([
            'USER_ID','EMPLOYEE_ID','USER_STATUS','USER_GROUP_ID','USER_ROLE',
            'INITIAL_PASSWORD','USER_TYPE','CREDTI_LIMIT','COMPANY_ID','USER_MOBILE',
        ]) + ['INSERT_BY' => auth()->id() ?? 'USER', 'INSERT_DATE' => now()]);

        return redirect()->route('users.index')->with('success', 'Oracle user created successfully.');
    }

    public function edit($id)
    {
        $user   = UserInfo::findOrFail($id);
        $groups = UserGroupMaster::orderBy('USER_GROUP_ID')->get();
        return view('users.edit', compact('user', 'groups'));
    }

    public function update(\Illuminate\Http\Request $request, $id)
    {
        $user = UserInfo::findOrFail($id);
        $request->validate(['EMPLOYEE_ID' => 'required', 'USER_GROUP_ID' => 'required', 'USER_ROLE' => 'required']);
        $user->update($request->only([
            'EMPLOYEE_ID','USER_STATUS','USER_GROUP_ID','USER_ROLE',
            'USER_TYPE','CREDTI_LIMIT','COMPANY_ID','USER_MOBILE',
        ]) + ['UPDATE_BY' => auth()->id() ?? 'USER', 'UPDATE_DATE' => now()]);

        return redirect()->route('users.index')->with('success', 'User updated.');
    }

    public function destroy($id)
    {
        UserInfo::findOrFail($id)->delete();
        return redirect()->route('users.index')->with('success', 'User deleted.');
    }
}