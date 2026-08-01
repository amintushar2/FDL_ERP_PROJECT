<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserCompanyPermissionController extends Controller
{
    // ═══════════════════════════════════════════════════════════════════════════
    //  INDEX — render the permission page
    // ═══════════════════════════════════════════════════════════════════════════
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

        return view('admin.users.company-permission', compact('users', 'groups'));
    }

    // ═══════════════════════════════════════════════════════════════════════════
    //  GET COMPANIES — AJAX: all companies from COMPANY_PROFILE,
    //  toggle ON  = row exists in COMPANY_PERMISSION for this user
    //  toggle OFF = no row
    //  Falls back to group-level rows when the user has no personal rows yet.
    // ═══════════════════════════════════════════════════════════════════════════
    public function getCompanies(Request $request, string $userId)
    {
        $userId = trim($userId);

        // ── 1. All companies from master ─────────────────────────────────────
        $companies = DB::table('F_STORE.COMPANY_PROFILE')
            ->select(
                DB::raw('TRIM(COMPANY_ID)   AS company_id'),
                DB::raw('TRIM(COMPANY_NAME) AS company_name')
            )
            ->orderBy('COMPANY_NAME')
            ->get();

        // ── 2. User's group ──────────────────────────────────────────────────
        $userInfo = DB::table('F_STORE.ALL_USER_INFO')
            ->whereRaw('TRIM(USER_ID) = ?', [$userId])
            ->select(DB::raw('TRIM(USER_GROUP_ID) AS user_group_id'))
            ->first();

        $groupId = $userInfo ? trim((string) $userInfo->user_group_id) : '';

        // ── 3. User-level permission rows → plain PHP array (company_id keys) ─
        //    We use array_flip on a plain array — no Collection magic needed.
        $userPermRows = DB::table('F_STORE.COMPANY_PERMISSION_USER')
            ->whereRaw('TRIM(USER_ID) = ?', [$userId])
            ->select(DB::raw('TRIM(COMPANY_ID) AS company_id'))
            ->get();

        // Build a plain associative array: [ 'C001' => true, 'C002' => true, … ]
        $userPermSet = [];
        foreach ($userPermRows as $row) {
            $userPermSet[trim((string) $row->company_id)] = true;
        }

        $hasUserPerms = count($userPermSet) > 0;

        // ── 4. Group-level permission rows (no user_id) ──────────────────────
        $groupPermRows = DB::table('F_STORE.COMPANY_PERMISSION_USER')
            ->whereRaw('TRIM(USER_GROUP_ID) = ?', [$groupId])
            ->whereRaw("(USER_ID IS NULL OR TRIM(USER_ID) = '')")
            ->select(DB::raw('TRIM(COMPANY_ID) AS company_id'))
            ->get();

        $groupPermSet = [];
        foreach ($groupPermRows as $row) {
            $groupPermSet[trim((string) $row->company_id)] = true;
        }

        // ── 5. Build result ──────────────────────────────────────────────────
        $result = $companies->map(function ($company) use (
            $userPermSet, $groupPermSet, $hasUserPerms
        ) {
            $cid = trim((string) $company->company_id);

            if ($hasUserPerms) {
                // User has their own rows → user row = enabled, absence = disabled
                $enabled = isset($userPermSet[$cid]);
                $source  = $enabled ? 'user' : 'group';
            } else {
                // No user rows yet → show group defaults as read reference
                $enabled = isset($groupPermSet[$cid]);
                $source  = 'group';
            }

            return [
                'company_id'   => $cid,
                'company_name' => $company->company_name,
                'enabled'      => $enabled,
                'source'       => $source,
            ];
        });

        return response()->json([
            'companies' => $result->values(),
            'group_id'  => $groupId,
        ]);
    }

    // ═══════════════════════════════════════════════════════════════════════════
    //  SAVE — enabled → INSERT (if not exists) or touch UPDATE_DATE
    //         disabled → DELETE row (absence = no permission)
    // ═══════════════════════════════════════════════════════════════════════════
    public function save(Request $request, string $userId)
    {
        $userId  = trim($userId);
        $groupId = trim((string) $request->input('user_group_id', ''));
        $raw     = $request->input('permissions', '{}');

        // permissions = { "C001": true, "C002": false, … }
        $permissions = is_array($raw) ? $raw : (json_decode($raw, true) ?? []);

        $actor = auth()->id() ?? 'USER';
        $now   = now();

        foreach ($permissions as $companyId => $enabled) {
            $companyId = trim((string) $companyId);

            $exists = DB::table('F_STORE.COMPANY_PERMISSION_USER')
                ->whereRaw('TRIM(USER_ID)    = ?', [$userId])
                ->whereRaw('TRIM(COMPANY_ID) = ?', [$companyId])
                ->exists();

            if ($enabled) {
                if ($exists) {
                    // Row already there — just refresh the update timestamp
                    DB::table('F_STORE.COMPANY_PERMISSION_USER')
                        ->whereRaw('TRIM(USER_ID)    = ?', [$userId])
                        ->whereRaw('TRIM(COMPANY_ID) = ?', [$companyId])
                        ->update([
                            'UPDATE_DATE' => $now,
                            'UPDATE_BY'   => $actor,
                        ]);
                } else {
                    // New row — fetch company name for the denormalised column
                    $company = DB::table('F_STORE.COMPANY_PROFILE')
                        ->whereRaw('TRIM(COMPANY_ID) = ?', [$companyId])
                        ->select(DB::raw('TRIM(COMPANY_NAME) AS company_name'))
                        ->first();

                    DB::table('F_STORE.COMPANY_PERMISSION_USER')->insert([
                        'USER_ID'       => $userId,
                        'USER_GROUP_ID' => $groupId,
                        'COMPANY_ID'    => $companyId,
                        'COMPANY_NAME'  => $company?->company_name ?? $companyId,
                        'INSERT_DATE'   => $now,
                        'INSERT_BY'     => $actor,
                    ]);
                }
            } else {
                // Toggle OFF → absence means no permission, so delete if present
                if ($exists) {
                    DB::table('F_STORE.COMPANY_PERMISSION_USER')
                        ->whereRaw('TRIM(USER_ID)    = ?', [$userId])
                        ->whereRaw('TRIM(COMPANY_ID) = ?', [$companyId])
                        ->delete();
                }
            }
        }

        return response()->json(['success' => true]);
    }

    // ═══════════════════════════════════════════════════════════════════════════
    //  RESET — wipe all user-level rows → UI reverts to group defaults
    // ═══════════════════════════════════════════════════════════════════════════
    public function reset(Request $request, string $userId)
    {
        $userId  = trim($userId);
        $groupId = trim((string) $request->input('user_group_id', ''));

        DB::table('F_STORE.COMPANY_PERMISSION_USER')
            ->whereRaw('TRIM(USER_ID)       = ?', [$userId])
            ->whereRaw('TRIM(USER_GROUP_ID) = ?', [$groupId])
            ->delete();

        return response()->json(['success' => true]);
    }
}
