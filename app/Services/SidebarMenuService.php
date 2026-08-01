<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class SidebarMenuService
{
    public function dataForLoggedUser(?string $employeeId, ?string $uri = null): array
    {
        $empty = [
            'data' => null,
            'menu' => collect(),
            'submenu' => collect(),
            'submenu2' => collect(),
            'headeer' => collect(),
        ];

        if (!$employeeId) {
            return $empty;
        }

        $uri = ltrim($uri ?? request()->path(), '/');

        $data = DB::table('ALL_USER_INFO')
            ->select(
                DB::raw('USER_ID as "user_id"'),
                DB::raw('EMPLOYEE_ID as "employee_id"'),
                DB::raw('USER_GROUP_ID as "user_group_id"'),
                DB::raw('INITIAL_PASSWORD as "initial_password"'),
                DB::raw('COMPANY_ID as "company_id"'),
                DB::raw('USER_MOBILE as "user_mobile"'),
                DB::raw('"GET_EMP_NAME"(EMPLOYEE_ID) as "employee_name"')
            )
            ->where('EMPLOYEE_ID', $employeeId)
            ->first();

        if (!$data) {
            return $empty;
        }

        $menu = $this->enabledMenus($data->user_group_id, $data->user_id);
        $allSubmenus = $this->enabledRoutes($data->user_group_id, $data->user_id);
//dd($allSubmenus->filter(fn ($row) => ltrim((string) $row->route, '/') === $uri)->values());
        return [
            'data' => $data,
            'menu' => $menu,
            'submenu' => $allSubmenus->filter(fn ($row) => empty($row->sub_menu_2))->values(),
            'submenu2' => $allSubmenus->filter(fn ($row) => !empty($row->sub_menu_2))->values(),
            'headeer' => $allSubmenus->filter(fn ($row) => ltrim((string) $row->route, '/') === $uri)->values(),
        ];
    }

    private function enabledMenus(string $groupId, string $userId)
    {
        return DB::table('ALL_USER_GROUP_DETAILS_WEB as ugd')
            ->join('ALL_MENU_HIERARCHY as mh', 'mh.CHILD_ID', '=', 'ugd.MENU_ITEM_ID')
            ->select(
                DB::raw('ugd.MENU_ITEM_ID as "menu_item_id"'),
                DB::raw('ugd.USER_GROUP_ID as "user_group_id"'),
                DB::raw('mh.TITLE as "title"'),
                DB::raw('mh.DESCRIPTION as "description"'),
                DB::raw('mh.SORT_BY as "sort_by"')
            )
            ->where('ugd.USER_GROUP_ID', $groupId)
            ->where('ugd.ENABLED', 'Y')
            ->where(function ($query) use ($userId) {
                $query->where('ugd.USER_ID', $userId)
                    ->orWhere(function ($groupQuery) use ($userId) {
                        $groupQuery->whereNull('ugd.USER_ID')
                            ->whereNotExists(function ($exists) use ($userId) {
                                $exists->select(DB::raw(1))
                                    ->from('ALL_USER_GROUP_DETAILS as ugo')
                                    ->whereColumn('ugo.MENU_ITEM_ID', 'ugd.MENU_ITEM_ID')
                                    ->whereColumn('ugo.USER_GROUP_ID', 'ugd.USER_GROUP_ID')
                                    ->where('ugo.USER_ID', $userId);
                            });
                    });
            })
            ->orderBy('mh.SORT_BY')
            ->get();
    }

    private function enabledRoutes(string $groupId, string $userId)
    {
        return DB::table('ALL_USER_SUB_DETAILS as usd')
            ->leftJoin('ALL_ROUTE_DETAILS as rd', 'rd.ROUTE_ID', '=', 'usd.SUB_MENU_ID')
            ->select(
                DB::raw('COALESCE(usd.MENU_ITEM_ID, rd.MENU_CHILD_ID) as "menu_item_id"'),
                DB::raw('usd.USER_GROUP_ID as "user_group_id"'),
                DB::raw('usd.SUB_MENU_ID as "sub_menu_id"'),
                DB::raw('usd.SUB_MENU_1 as "sub_menu_1"'),
                DB::raw('usd.SUB_MENU_2 as "sub_menu_2"'),
                DB::raw('COALESCE(usd.SUB_MENU_NAME, rd.COMPONENT, rd.ROUTE_ID) as "sub_menu_name"'),
                DB::raw('COALESCE(rd.ROUTE_PATH, usd.ROUTE) as "route"'),
                DB::raw('rd.ROUTE_ID as "route_id"')
            )
            ->where('usd.USER_GROUP_ID', $groupId)
            ->where('usd.ENABLED', 'Y')
            ->where(function ($query) use ($userId) {
                $query->where('usd.USER_ID', $userId)
                    ->orWhere(function ($groupQuery) use ($userId) {
                        $groupQuery->whereNull('usd.USER_ID')
                            ->whereNotExists(function ($exists) use ($userId) {
                                $exists->select(DB::raw(1))
                                    ->from('ALL_USER_SUB_DETAILS as uso')
                                    ->whereColumn('uso.SUB_MENU_ID', 'usd.SUB_MENU_ID')
                                    ->whereColumn('uso.USER_GROUP_ID', 'usd.USER_GROUP_ID')
                                    ->where('uso.USER_ID', $userId);
                            });
                    });
            })
            ->orderBy('usd.MENU_ITEM_ID')
            ->orderBy('usd.SUB_MENU_1')
            ->orderBy('usd.SUB_MENU_2')
            ->orderBy('usd.SUB_MENU_ID')
            ->get();
    }
}
