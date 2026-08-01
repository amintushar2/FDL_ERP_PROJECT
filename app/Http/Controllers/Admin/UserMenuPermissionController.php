<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserMenuPermissionController extends Controller
{
    // ══════════════════════════════════════════════════════════════════════════════
    //  INDEX — render the permission manager page
    // ══════════════════════════════════════════════════════════════════════════════
    public function index(Request $request)
    {
        $users = DB::table('F_STORE.ALL_USER_INFO')
            ->select(
                DB::raw('TRIM(USER_ID)       AS user_id'),
                DB::raw('TRIM(EMPLOYEE_ID)   AS employee_id'),
                DB::raw('TRIM(USER_GROUP_ID) AS user_group_id'),
                DB::raw('TRIM(USER_ROLE)     AS user_role')
            )
            ->orderBy('USER_ID')
            ->get();

        $groups = DB::table('F_STORE.ALL_USER_GROUP_MASTER')
            ->select(DB::raw('TRIM(USER_GROUP_ID) AS user_group_id'))
            ->orderBy('USER_GROUP_ID')
            ->get();

        return view('admin.users.menu-permission', compact('users', 'groups'));
    }

    // ══════════════════════════════════════════════════════════════════════════════
    //  GET MENUS — returns all menus with enabled/source state for a user
    // ══════════════════════════════════════════════════════════════════════════════
    public function getMenus(Request $request, $userId)
    {
        $userId = trim((string) $userId);

        // ── All menus in display order ────────────────────────────────────────────
        $menus = DB::table('F_STORE.ALL_MENU_HIERARCHY')
            ->select(
                DB::raw('TRIM(CHILD_ID)  AS child_id'),
                DB::raw('TRIM(TITLE)     AS title'),
                DB::raw('ITEM_TYPE       AS item_type'),
                DB::raw('TRIM(DIR)       AS dir'),
                DB::raw('SORT_BY         AS sort_by'),
                DB::raw('TRIM(PARENT_ID) AS parent_id'),
                DB::raw('TRIM(IS_ACTIVE) AS is_active')
            )
            ->orderBy('SORT_BY')
            ->get();

        // ── Total route count per menu ────────────────────────────────────────────
        $routeCounts = DB::table('F_STORE.ALL_ROUTE_DETAILS')
            ->select(
                DB::raw('TRIM(MENU_CHILD_ID) AS menu_child_id'),
                DB::raw('COUNT(*) AS total')
            )
            ->groupBy('MENU_CHILD_ID')
            ->get()
            ->keyBy('menu_child_id')
            ->map(fn($r) => (int) $r->total);

        // ── Resolve the user's group ──────────────────────────────────────────────
        $userInfo = DB::table('F_STORE.ALL_USER_INFO')
            ->whereRaw('TRIM(USER_ID) = ?', [$userId])
            ->select(DB::raw('TRIM(USER_GROUP_ID) AS user_group_id'))
            ->first();

        $groupId = $userInfo ? trim($userInfo->user_group_id) : '';

        // ── User-level overrides (keyed by menu_item_id) ──────────────────────────
        $userMenuPerms = DB::table('F_STORE.ALL_USER_GROUP_DETAILS_WEB')
            ->select(
                DB::raw('TRIM(MENU_ITEM_ID) AS menu_item_id'),
                DB::raw('TRIM(ENABLED)      AS enabled')
            )
            ->whereRaw('TRIM(USER_ID) = ?', [$userId])
            ->get()
            ->keyBy('menu_item_id')
            ->map(fn($r) => $r->enabled === 'Y');

        $hasUserOverrides = $userMenuPerms->isNotEmpty();

        // ── Group-level defaults (keyed by menu_item_id) ──────────────────────────
        $groupMenuPerms = DB::table('F_STORE.ALL_USER_GROUP_DETAILS_WEB')
            ->select(
                DB::raw('TRIM(MENU_ITEM_ID) AS menu_item_id'),
                DB::raw('TRIM(ENABLED)      AS enabled')
            )
            ->whereRaw('TRIM(USER_GROUP_ID) = ?', [$groupId])
            ->whereRaw("(USER_ID IS NULL OR TRIM(USER_ID) = '')")
            ->get()
            ->keyBy('menu_item_id')
            ->map(fn($r) => $r->enabled === 'Y');

        // ── Enabled route count per menu for this user ────────────────────────────
        $enabledRouteCounts = DB::table('F_STORE.ALL_USER_SUB_DETAILS')
            ->select(
                DB::raw('TRIM(MENU_ITEM_ID) AS menu_item_id'),
                DB::raw('COUNT(*) AS enabled_total')
            )
            ->whereRaw('TRIM(USER_ID) = ?', [$userId])
            ->whereRaw("TRIM(ENABLED) = 'Y'")
            ->groupBy('MENU_ITEM_ID')
            ->get()
            ->keyBy('menu_item_id')
            ->map(fn($r) => (int) $r->enabled_total);

        // ── Build result ──────────────────────────────────────────────────────────
        $result = $menus->map(function ($menu) use (
            $userMenuPerms, $groupMenuPerms, $hasUserOverrides,
            $routeCounts, $enabledRouteCounts
        ) {
            $mid = $menu->child_id;

            if ($hasUserOverrides) {
                $enabled = $userMenuPerms[$mid] ?? ($groupMenuPerms[$mid] ?? false);
                $source  = isset($userMenuPerms[$mid]) ? 'user' : 'group';
            } else {
                $enabled = $groupMenuPerms[$mid] ?? false;
                $source  = 'group';
            }

            return [
                'child_id'       => $mid,
                'title'          => $menu->title,
                'item_type'      => (int) $menu->item_type,
                'dir'            => $menu->dir,
                'sort_by'        => $menu->sort_by,
                'parent_id'      => $menu->parent_id,
                'is_active'      => $menu->is_active,
                'enabled'        => $enabled,
                'source'         => $source,
                'total_routes'   => $routeCounts[$mid]        ?? 0,
                'enabled_routes' => $enabledRouteCounts[$mid] ?? 0,
            ];
        });

        return response()->json([
            'menus'    => $result,
            'group_id' => $groupId,
        ]);
    }

    // ══════════════════════════════════════════════════════════════════════════════
    //  GET ROUTES — returns all routes for a menu with enabled/source state
    // ══════════════════════════════════════════════════════════════════════════════
    public function getRoutes(Request $request, $userId, $menuId)
    {
        $userId = trim((string) $userId);
        $menuId = trim((string) $menuId);

        // ── All routes for this menu ──────────────────────────────────────────────
        $routes = DB::table('F_STORE.ALL_ROUTE_DETAILS')
            ->select(
                DB::raw('TRIM(ROUTE_ID)      AS route_id'),
                DB::raw('TRIM(ROUTE_PATH)    AS route_path'),
                DB::raw('TRIM(MENU_CHILD_ID) AS menu_child_id'),
                DB::raw('TRIM(COMPONENT)     AS component')
            )
            ->whereRaw('TRIM(MENU_CHILD_ID) = ?', [$menuId])
            ->orderBy('ROUTE_ID')
            ->get();

        // ── Resolve user's group ──────────────────────────────────────────────────
        $userInfo = DB::table('F_STORE.ALL_USER_INFO')
            ->whereRaw('TRIM(USER_ID) = ?', [$userId])
            ->select(DB::raw('TRIM(USER_GROUP_ID) AS user_group_id'))
            ->first();

        $groupId = $userInfo ? trim($userInfo->user_group_id) : '';

        // ── User-level route overrides ────────────────────────────────────────────
        $userRoutePerms = DB::table('F_STORE.ALL_USER_SUB_DETAILS')
            ->select(
                DB::raw('TRIM(SUB_MENU_ID) AS sub_menu_id'),
                DB::raw('TRIM(ENABLED)     AS enabled')
            )
            ->whereRaw('TRIM(USER_ID)      = ?', [$userId])
            ->whereRaw('TRIM(MENU_ITEM_ID) = ?', [$menuId])
            ->get()
            ->keyBy('sub_menu_id')
            ->map(fn($r) => $r->enabled === 'Y');

        $hasUserRouteOverrides = $userRoutePerms->isNotEmpty();

        // ── Group-level route defaults ────────────────────────────────────────────
        $groupRoutePerms = DB::table('F_STORE.ALL_USER_SUB_DETAILS')
            ->select(
                DB::raw('TRIM(SUB_MENU_ID) AS sub_menu_id'),
                DB::raw('TRIM(ENABLED)     AS enabled')
            )
            ->whereRaw('TRIM(USER_GROUP_ID) = ?', [$groupId])
            ->whereRaw('TRIM(MENU_ITEM_ID)  = ?', [$menuId])
            ->whereRaw("(USER_ID IS NULL OR TRIM(USER_ID) = '')")
            ->get()
            ->keyBy('sub_menu_id')
            ->map(fn($r) => $r->enabled === 'Y');

        // ── Build result ──────────────────────────────────────────────────────────
        $result = $routes->map(function ($route) use (
            $userRoutePerms, $groupRoutePerms, $hasUserRouteOverrides
        ) {
            $rid = $route->route_id;

            if ($hasUserRouteOverrides) {
                $enabled = $userRoutePerms[$rid] ?? ($groupRoutePerms[$rid] ?? false);
                $source  = isset($userRoutePerms[$rid]) ? 'user' : 'group';
            } else {
                $enabled = $groupRoutePerms[$rid] ?? false;
                $source  = 'group';
            }

            return [
                'route_id'      => $rid,
                'route_path'    => $route->route_path,
                'menu_child_id' => $route->menu_child_id,
                'component'     => $route->component,
                'enabled'       => $enabled,
                'source'        => $source,
            ];
        });

        return response()->json([
            'routes'  => $result,
            'menu_id' => $menuId,
        ]);
    }

    // ══════════════════════════════════════════════════════════════════════════════
    //  SAVE — upsert menu-level and route-level permissions for a user
    //
    //  Rules:
    //   • Menu row EXISTS            → UPDATE always
    //   • Menu row MISSING + no rows → INSERT (fresh user)
    //   • Menu row MISSING + has rows→ SKIP  (don't create partial overrides)
    //
    //   • Route toggled OFF          → DELETE the row if it exists
    //   • Route toggled ON + EXISTS  → UPDATE ENABLED = 'Y'
    //   • Route toggled ON + MISSING → INSERT new row
    // ══════════════════════════════════════════════════════════════════════════════
    public function save(Request $request, $userId)
    {
        $userId  = trim((string) $userId);
        $groupId = trim((string) $request->input('user_group_id'));

        $raw         = $request->input('permissions', '[]');
        $permissions = is_array($raw) ? $raw : (json_decode($raw, true) ?? []);

        // Does this user already have ANY menu-level rows?
        $userHasGroupDetails = DB::table('F_STORE.ALL_USER_GROUP_DETAILS_WEB')
            ->whereRaw('TRIM(USER_GROUP_ID) = ?', [$groupId])
            ->whereRaw('TRIM(USER_ID)       = ?', [$userId])
            ->exists();

        $authId = auth()->id() ?? 'USER';

        foreach ($permissions as $menuId => $data) {
            $menuId  = trim((string) $menuId);
            $enabled = ($data['enabled'] ?? false) ? 'Y' : 'N';

            // ── Menu-level (ALL_USER_GROUP_DETAILS_WEB) ───────────────────────────
            $menuRowExists = DB::table('F_STORE.ALL_USER_GROUP_DETAILS_WEB')
                ->whereRaw('TRIM(MENU_ITEM_ID)  = ?', [$menuId])
                ->whereRaw('TRIM(USER_GROUP_ID) = ?', [$groupId])
                ->whereRaw('TRIM(USER_ID)       = ?', [$userId])
                ->exists();

            if ($menuRowExists) {
                // Row already exists → UPDATE
                DB::table('F_STORE.ALL_USER_GROUP_DETAILS_WEB')
                    ->whereRaw('TRIM(MENU_ITEM_ID)  = ?', [$menuId])
                    ->whereRaw('TRIM(USER_GROUP_ID) = ?', [$groupId])
                    ->whereRaw('TRIM(USER_ID)       = ?', [$userId])
                    ->update([
                        'ENABLED'     => $enabled,
                        'UPDATE_BY'   => $authId,
                        'UPDATE_DATE' => now(),
                    ]);
            } elseif (!$userHasGroupDetails) {
                // No existing rows at all for this user → fresh INSERT
                DB::table('F_STORE.ALL_USER_GROUP_DETAILS_WEB')->insert([
                    'USER_GROUP_ID' => $groupId,
                    'MENU_ITEM_ID'  => $menuId,
                    'USER_ID'       => $userId,
                    'ENABLED'       => $enabled,
                    'INSERT_DATE'   => now(),
                    'INSERT_BY'     => $authId,
                ]);
            }
            // else: user has other menu rows but NOT this one → skip

            // ── Route-level (ALL_USER_SUB_DETAILS) ───────────────────────────────
            foreach ($data['routes'] ?? [] as $routeId => $routeEnabled) {
                $routeId = trim((string) $routeId);

                $routeRowExists = DB::table('F_STORE.ALL_USER_SUB_DETAILS')
                    ->whereRaw('TRIM(USER_ID)       = ?', [$userId])
                    ->whereRaw('TRIM(USER_GROUP_ID) = ?', [$groupId])
                    ->whereRaw('TRIM(SUB_MENU_ID)   = ?', [$routeId])
                    ->whereRaw('TRIM(MENU_ITEM_ID)  = ?', [$menuId])
                    ->exists();

                if (!$routeEnabled) {
                    // Toggle OFF → delete the row if it exists
                    if ($routeRowExists) {
                        DB::table('F_STORE.ALL_USER_SUB_DETAILS')
                            ->whereRaw('TRIM(USER_ID)       = ?', [$userId])
                            ->whereRaw('TRIM(USER_GROUP_ID) = ?', [$groupId])
                            ->whereRaw('TRIM(SUB_MENU_ID)   = ?', [$routeId])
                            ->whereRaw('TRIM(MENU_ITEM_ID)  = ?', [$menuId])
                            ->delete();
                    }
                    continue; // nothing more to do for this route
                }

                // Toggle ON
                if ($routeRowExists) {
                    // Row exists → UPDATE
                    DB::table('F_STORE.ALL_USER_SUB_DETAILS')
                        ->whereRaw('TRIM(USER_ID)       = ?', [$userId])
                        ->whereRaw('TRIM(USER_GROUP_ID) = ?', [$groupId])
                        ->whereRaw('TRIM(SUB_MENU_ID)   = ?', [$routeId])
                        ->whereRaw('TRIM(MENU_ITEM_ID)  = ?', [$menuId])
                        ->update([
                            'ENABLED'     => 'Y',
                            'UPDATE_BY'   => $authId,
                            'UPDATE_DATE' => now(),
                        ]);
                } else {
                    // Row missing → INSERT
                    // Fetch route metadata to populate SUB_MENU_NAME / ROUTE / SUB_MENU_1/2
                    $route = DB::table('F_STORE.ALL_ROUTE_DETAILS')
                        ->whereRaw('TRIM(ROUTE_ID) = ?', [$routeId])
                        ->select(
                            DB::raw('TRIM(ROUTE_PATH)  AS route_path'),
                            DB::raw('TRIM(COMPONENT)   AS component'),
                            DB::raw('TRIM(SUB_MENU_1)  AS sub_menu_1'),
                            DB::raw('TRIM(SUB_MENU_2)  AS sub_menu_2')
                        )
                        ->first();

                    DB::table('F_STORE.ALL_USER_SUB_DETAILS')->insert([
                        'USER_GROUP_ID' => $groupId,
                        'SUB_MENU_ID'   => $routeId,
                        'USER_ID'       => $userId,
                        'MENU_ITEM_ID'  => $menuId,
                        'SUB_MENU_1'    => $route?->sub_menu_1 ?? '',
                        'SUB_MENU_2'    => $route?->sub_menu_2 ?? '',
                        'SUB_MENU_NAME' => $route?->component  ?? $routeId,
                        'ROUTE'         => $route?->route_path  ?? '',
                        'ENABLED'       => 'Y',
                        'INSERT_DATE'   => now(),
                        'INSERT_BY'     => $authId,
                    ]);
                }
            }
        }

        return response()->json(['success' => true]);
    }

    // ══════════════════════════════════════════════════════════════════════════════
    //  RESET — delete all user-level overrides so group defaults take effect again
    // ══════════════════════════════════════════════════════════════════════════════
    public function reset(Request $request, $userId)
    {
        $userId  = trim((string) $userId);
        $groupId = trim((string) $request->input('user_group_id'));

        DB::table('F_STORE.ALL_USER_GROUP_DETAILS_WEB')
            ->whereRaw('TRIM(USER_GROUP_ID) = ?', [$groupId])
            ->whereRaw('TRIM(USER_ID)       = ?', [$userId])
            ->delete();

        DB::table('F_STORE.ALL_USER_SUB_DETAILS')
            ->whereRaw('TRIM(USER_GROUP_ID) = ?', [$groupId])
            ->whereRaw('TRIM(USER_ID)       = ?', [$userId])
            ->delete();

        return response()->json(['success' => true]);
    }
}