<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class AdminController extends Controller
{
    //

    function getAllMenu(){
            $allMenu=DB::table('ALL_MENU_HIERARCHY')
            ->select('TITLE', 'ITEM_TYPE', 'CHILD_ID', 'PARENT_ID', 'OBJECT_NAME', 'DESCRIPTION', 'INSERT_DATE', 'UPDATE_BY', 'UPDATE_DATE', 'FILE_NAME', 'SORT_BY', 'IS_ACTIVE', 'INSERT_BY', 'DIR')
            ->get();
            $alluser=DB::table('ALL_USER_INFO')
            ->select('USER_ID', 'EMPLOYEE_ID', 'USER_STATUS', 'USER_GROUP_ID', 'INSERT_DATE', 'UPDATE_BY', 'UPDATE_DATE', 'USER_ROLE', 'INITIAL_PASSWORD', 'INSERT_BY', 'USER_TYPE', 'CREDTI_LIMIT', 'COMPANY_ID', 'USER_MOBILE')
            ->get();

            return view('admin.menulist',['allMenu'=>$allMenu,'alluser'=>$alluser]);

    }
}
