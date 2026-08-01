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

class MenuController extends Controller
{
    public function index()
    {
        $menus = MenuHierarchy::orderBy('SORT_BY')->get();
        return view('admin.menu.index', compact('menus'));
    }

    public function store(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'TITLE'    => 'required|max:100',
            'CHILD_ID' => 'required|max:15|unique:ALL_MENU_HIERARCHY,CHILD_ID',
        ]);
        MenuHierarchy::create($request->only([
            'TITLE','ITEM_TYPE','CHILD_ID','PARENT_ID','OBJECT_NAME',
            'DESCRIPTION','FILE_NAME','DIR','SORT_BY','IS_ACTIVE','INSERT_BY',
        ]) + ['INSERT_BY' => auth()->id() ?? 'USER', 'INSERT_DATE' => now()]);

        return redirect()->route('menus.index')->with('success', 'Menu item saved successfully.');
    }

    public function edit($id)
    {
        $menu  = MenuHierarchy::findOrFail($id);
        $menus = MenuHierarchy::orderBy('SORT_BY')->get();
        return view('admin.menu.edit', compact('menu', 'menus'));
    }

    public function update(\Illuminate\Http\Request $request, $id)
    {
        $menu = MenuHierarchy::findOrFail($id);
        $request->validate(['TITLE' => 'required|max:100']);
        $menu->update($request->only([
            'TITLE','ITEM_TYPE','PARENT_ID','OBJECT_NAME',
            'DESCRIPTION','FILE_NAME','DIR','SORT_BY','IS_ACTIVE',
        ]) + ['UPDATE_BY' => auth()->id() ?? 'USER', 'UPDATE_DATE' => now()]);

        return redirect()->route('menus.index')->with('success', 'Menu item updated.');
    }

    public function destroy($id)
    {
        MenuHierarchy::findOrFail($id)->delete();
        return redirect()->route('menus.index')->with('success', 'Menu item deleted.');
    }

    public function tree()
    {
        $menus = MenuHierarchy::orderBy('SORT_BY')->get();
        return view('admin.menu.tree', compact('menus'));
    }
}
