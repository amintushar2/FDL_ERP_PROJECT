<?php

namespace App\Http\Controllers;

use App\Http\Requests\TempEmpRequest;
use App\Models\EmpPersonal;
use App\Models\EmpOfficial;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * TempEmpController
 * ──────────────────────────────────────────────────────────────
 * Migrated from Oracle Forms: temp_emp.fmb
 * Tables : emp_personal  (EMP_PERSONAL block)
 *          emp_official  (EMP_OFFICIAL block)
 *
 * EMPNO  : Auto → MAX(TO_NUMBER(EMPNO))+1 WHERE WORKER_TYPE='T'
 *
 * LOV fields save BOTH id AND name:
 *   company_id   + company_name
 *   dept_no      + dept_name
 *   section_no   + section_name
 *   floor_id     + floor_desc
 *   des_id       + des_name
 *   shift_code   + shift_name
 *   line_no      + line
 * ──────────────────────────────────────────────────────────────
 */
class TempEmpController extends Controller
{
    private function auth(): void
    {
        if (!session('LoggedUser')) abort(401);
    }

    /* ═══════════════════════════════════════════
       VIEW  —  GET /temp-emp
    ═══════════════════════════════════════════ */
    public function index()
    {
        $this->auth();
        
        // Fetch company list for page load
        $companies = DB::table('COMPANY_PROFILE as cp')
            ->join('COMPANY_PERMISSION as perm', 'cp.COMPANY_ID', '=', 'perm.COMPANY_ID')
            ->select('cp.COMPANY_ID as id', 'cp.COMPANY_NAME as text')
            ->orderBy('cp.COMPANY_NAME')
            ->distinct()
            ->get();
        
        return view('hrm.tempemp.index', [
            'companies' => $companies,
        ]);
    }

    /* ═══════════════════════════════════════════
       NEXT-ID  —  GET /temp-emp/next-id
       SQL: MAX(TO_NUMBER(EMPNO))+1 WHERE WORKER_TYPE='T'
    ═══════════════════════════════════════════ */
    public function nextId(): JsonResponse
    {
        $this->auth();

        $row = DB::table('emp_personal')
            ->where('worker_type', 'T')
            ->orderByRaw('TO_NUMBER(empno) DESC')
            ->first();

        $next = $row ? ((int) $row->empno + 1) : 1;

        return response()->json([
            'success' => true,
            'empno'   => (string) $next,
        ]);
    }

    /* ═══════════════════════════════════════════
       SHOW  —  GET /temp-emp/{empno}
    ═══════════════════════════════════════════ */
    public function show(string $empno): JsonResponse
    {
        $this->auth();

        $emp = EmpPersonal::with('getempofficial')
            ->where('worker_type', 'T')
            ->findOrFail($empno);

        return response()->json([
            'success' => true,
            'data'    => $emp,
        ]);
    }

    /* ═══════════════════════════════════════════
       SEARCH  —  GET /temp-emp/search?q=
    ═══════════════════════════════════════════ */
    public function search(Request $request): JsonResponse
    {
        $this->auth();
        $q = trim($request->get('q', ''));

        if ($q === '') {
            return response()->json(['success' => false, 'message' => 'Query required.'], 422);
        }

        $emp = EmpPersonal::with('getempofficial')
            ->where('worker_type', 'T')
            ->where(function ($query) use ($q) {
                $query->where('empno',      $q)
                      ->orWhere('first_name', 'LIKE', "%{$q}%")
                      ->orWhere('last_name',  'LIKE', "%{$q}%")
                      ->orWhere('card_no',    'LIKE', "%{$q}%");
            })
            ->first();

        if (!$emp) {
            return response()->json(['success' => false, 'message' => "No record found for: {$q}"], 404);
        }

        return response()->json(['success' => true, 'data' => $emp]);
    }

    /* ═══════════════════════════════════════════
       LOV  —  GET /temp-emp/lov?q=
    ═══════════════════════════════════════════ */
    public function lov(Request $request): JsonResponse
    {
        $this->auth();
        $q = trim($request->get('q', ''));

        $query = EmpPersonal::select('empno', 'first_name', 'last_name', 'permanent_empno', 'status')
            ->with(['getempofficial:empno,dept_no,dept_name'])
            ->where('worker_type', 'T')
            ->orderByRaw('TO_NUMBER(empno) ASC');

        if ($q !== '') {
            $query->where(function ($sub) use ($q) {
                $sub->where('empno',       'LIKE', "%{$q}%")
                    ->orWhere('first_name', 'LIKE', "%{$q}%")
                    ->orWhere('last_name',  'LIKE', "%{$q}%")
                    ->orWhere('permanent_empno', 'LIKE', "%{$q}%");
            });
        }

        $records = $query->get();

        $items = $records->map(fn($e) => [
          'id'     => $e->empno,
            'first'  => $e->first_name,
            'last'   => $e->last_name ?? '',
            'perm'   => '('.$e->permanent_empno.')' ?? '',
            'dept'   => optional($e->getempofficial)->dept_name ?? '—',
            'status' => $e->status,
        ]);
        return response()->json([
            'success' => true,
            'data'    => $items,
            'total'   => $records->count(),
        ]);
    }

    public function store(TempEmpRequest $request): JsonResponse
    {
        $this->auth();
        DB::beginTransaction();

        try {
            /* 1. Auto-generate EMPNO (locked row) */
            $row = DB::table('emp_personal')
                ->where('worker_type', 'T')
                ->orderByRaw('TO_NUMBER(empno) DESC')
                ->lockForUpdate()
                ->first();

            $empno = (string)(($row ? (int)$row->empno : 0) + 1);

            /* 2. Full name */
            $empName = trim(implode(' ', array_filter([
                $request->first_name,
                $request->middle_name,
                $request->last_name,
            ])));

            /* 3. Insert EMP_PERSONAL */
            EmpPersonal::create([
                'empno'           => $empno,
                'new_empno'       => $empno,
                'worker_type'     => 'T',
                /* company: save both id and name */
                'company_id'      => $request->company_id,
                'company_name'    => $request->company_name,
                'card_no'         => $empno,                  // card_no = empno on new (fmb logic)
                'first_name'      => $request->first_name,
                'middle_name'     => $request->middle_name,
                'last_name'       => $request->last_name,
                'b_name'          => $request->b_name,
                'emp_name'        => $empName,
                'status'          => $request->status,
                'dob'             => $request->dob,
                'sex'             => $request->sex,
                'permanent_empno' => $request->permanent_empno,
                'insert_by'       => auth()->id(),
                'insert_date'     => now(),
            ]);

            /* 4. Insert EMP_OFFICIAL — all LOV fields: id + name */
            $off = $request->input('official', []);

            EmpOfficial::create(array_merge([
                'empno'        => $empno,
                /* company */
                'company_id'   => $off['company_id']   ?? null,
                'company_name' => $off['company_name'] ?? null,
                /* dept: id=dept_no, name=dept_name */
                'dept_no'      => $off['dept_no']      ?? null,
                'dept_name'    => $off['dept_name']    ?? null,
                /* section: id=section_no, name=section_name */
                'section_no'   => $off['section_no']   ?? null,
                'section_name' => $off['section_name'] ?? null,
                /* emp_type (plain select — no separate id) */
                'emp_type'     => $off['emp_type']     ?? null,
                /* floor: id=floor_id, name=floor_desc */
                'floor_id'     => $off['floor_id']     ?? null,
                'floor_desc'   => $off['floor_desc']   ?? null,
                /* ot */
                'ot_ent'       => $off['ot_ent']       ?? null,
                'gross'        => $off['gross']        ?? null,
                'joining_date' => $off['joining_date'] ?? null,
                /* designation: id=des_id, name=des_name */
                'des_id'       => $off['des_id']       ?? null,
                'des_name'     => $off['des_name']     ?? null,
                /* shift: id=shift_code, name=shift_name */
                'shift_code'   => $off['shift_code']   ?? null,
                'shift_name'   => $off['shift_name']   ?? null,
                /* weekly off (value=text for this field) */
                'weekly_off'   => $off['weekly_off']   ?? null,
                'work_ent'     => $off['work_ent']     ?? null,
                /* line: id=line_no, name=line */
                  'line'      => $off['line_no']      ?? null,
                'line_info'         => $off['line']            ?? null,
                'insert_by'    => auth()->id(),
                'insert_date'  => now(),
            ]));

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Employee created successfully.',
                'empno'   => $empno,
            ], 201);

        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create: ' . $e->getMessage(),
            ], 500);
        }
    }

    /* ═══════════════════════════════════════════
       UPDATE  —  PUT /temp-emp/{empno}
    ═══════════════════════════════════════════ */
    public function update(TempEmpRequest $request, string $empno): JsonResponse
    {
        $this->auth();
        $personal = EmpPersonal::where('worker_type', 'T')->findOrFail($empno);
        DB::beginTransaction();

        try {
            $empName = trim(implode(' ', array_filter([
                $request->first_name,
                $request->middle_name,
                $request->last_name,
            ])));

            $personal->update([
                'company_id'      => $request->company_id,
                'company_name'    => $request->company_name,
                'card_no'         => $request->card_no,
                'first_name'      => $request->first_name,
                'middle_name'     => $request->middle_name,
                'last_name'       => $request->last_name,
                'b_name'          => $request->b_name,
                'emp_name'        => $empName,
                'status'          => $request->status,
                'dob'             => $request->dob,
                'sex'             => $request->sex,
                'permanent_empno' => $request->permanent_empno,
                'update_by'       => auth()->id(),
                'update_date'     => now(),
            ]);

            $off = $request->input('official', []);

            EmpOfficial::updateOrCreate(
                ['empno' => $empno],
                [
                    'company_id'   => $off['company_id']   ?? null,
                    'company_name' => $off['company_name'] ?? null,
                    'dept_no'      => $off['dept_no']      ?? null,
                    'dept_name'    => $off['dept_name']    ?? null,
                    'section_no'   => $off['section_no']   ?? null,
                    'section_name' => $off['section_name'] ?? null,
                    'emp_type'     => $off['emp_type']     ?? null,
                    'floor_id'     => $off['floor_id']     ?? null,
                    'floor_desc'   => $off['floor_desc']   ?? null,
                    'ot_ent'       => $off['ot_ent']       ?? null,
                    'gross'        => $off['gross']        ?? null,
                    'joining_date' => $off['joining_date'] ?? null,
                    'des_id'       => $off['des_id']       ?? null,
                    'des_name'     => $off['des_name']     ?? null,
                    'shift_code'   => $off['shift_code']   ?? null,
                    'shift_name'   => $off['shift_name']   ?? null,
                    'weekly_off'   => $off['weekly_off']   ?? null,
                    'work_ent'     => $off['work_ent']     ?? null,
                   'line'      => $off['line_no']      ?? null,
                    'line_info'         => $off['line']          ?? null,
                    'update_by'    => auth()->id(),
                    'update_date'  => now(),
                ]
            );

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Employee updated successfully.',
                'empno'   => $empno,
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update: ' . $e->getMessage(),
            ], 500);
        }
    }

    /* ═══════════════════════════════════════════
       MIGRATE  —  POST /temp-emp/{empno}/migrate
       PB_TRANSFER from team_emp.fmb:
       1. INSERT EMP_PERSONAL (WORKER_TYPE='P', EMPNO=PERMANENT_EMPNO)
       2. INSERT EMP_OFFICIAL (copy from temp)
       3. UPDATE ATTENDANCE_DETAILS SET EMPNO=PERMANENT_EMPNO
    ═══════════════════════════════════════════ */
    public function migrate(Request $request, string $empno): JsonResponse
    {
        $this->auth();
        $request->validate(['permanent_empno' => 'required|string|max:7']);

        $permEmpNo = trim($request->permanent_empno);
        $temp      = EmpPersonal::with('getempofficial')->where('worker_type','T')->findOrFail($empno);

        if (EmpPersonal::where('empno', $permEmpNo)->exists()) {
            return response()->json([
                'success' => false,
                'message' => "Permanent Emp No '{$permEmpNo}' already exists.",
            ], 422);
        }

        DB::beginTransaction();
        try {
            $companyName = DB::table('COMPANY_PROFILE')
                ->where('COMPANY_ID', $temp->getempofficial->company_id ?? $temp->company_id)
                ->value('COMPANY_NAME') ?? $temp->company_name;

            EmpPersonal::create([
                'empno'        => $permEmpNo, 'new_empno' => $permEmpNo,
                'worker_type'  => 'P',
                'company_id'   => $temp->company_id,
                'company_name' => $companyName,
                'first_name'   => $temp->first_name,
                'middle_name'  => $temp->middle_name,
                'last_name'    => $temp->last_name,
                'emp_name'     => $temp->emp_name,
                'card_no'      => $empno, // card_no = empno for permanent as well
                'status'       => $temp->status ?? 'Active',
                'dob'          => $temp->dob,
                'sex'          => $temp->sex,
                'permanent_empno' => $permEmpNo,
                'insert_by'    => auth()->id(),
                'insert_date'  => now(),
            ]);

            if ($temp->getempofficial) {
                $off = $temp->getempofficial->toArray();
                unset($off['id'], $off['created_at'], $off['updated_at']);
                EmpOfficial::create(array_merge($off, [
                    'empno'       => $permEmpNo,
                    'insert_by'   => auth()->id(),
                    'insert_date' => now(),
                ]));
            }

            DB::table('ATTENDANCE_DETAILS')
                ->where('EMPNO', $empno)
                ->update(['EMPNO' => $permEmpNo, 'EMPNO_NEW' => $permEmpNo]);

            // After migration: Set temporary employee to Inactive and clear card_no
            EmpPersonal::where('empno', $empno)->update([
                'status'      => 'Inactive',
                'card_no'     => null,
                'update_by'   => session('LoggedUser'),
                'update_date' => now(),
            ]);
 EmpOfficial::where('empno', $empno)->update([
                'joining_date'     => null,
                           ]);
            DB::commit();

            return response()->json([
                'success'         => true,
                'message'         => "Migration complete. Permanent ID: {$permEmpNo}",
                'temp_empno'      => $empno,
                'permanent_empno' => $permEmpNo,
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['success'=>false,'message'=>'Migration failed: '.$e->getMessage()], 500);
        }
    }

    /* ═══════════════════════════════════════════
       DESTROY  —  DELETE /temp-emp/{empno}
    ═══════════════════════════════════════════ */
    public function destroy(string $empno): JsonResponse
    {
        $this->auth();
        $personal = EmpPersonal::where('worker_type','T')->findOrFail($empno);
        DB::beginTransaction();
        try {
            $personal->delete();
            DB::commit();
            return response()->json(['success'=>true,'message'=>"Employee ID {$empno} deleted."]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['success'=>false,'message'=>'Delete failed: '.$e->getMessage()], 500);
        }
    }
}
