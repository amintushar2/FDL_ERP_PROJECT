<?php

namespace App\Http\Controllers;

use App\Models\EmpOfficial;
use App\Models\EmpPersonal;
use App\Models\LeaveEntryMaster;
use App\Models\LeaveEntryDetails;
use App\Models\EmpLocation;
use App\Models\Emp_qualificationModel;
use App\Models\Emp_ShortModel;
use App\Models\Emp_familyModel;
use App\Models\Emp_historyModel;
use App\Models\Emp_trainingModel;
use App\Models\Emp_work_expModel;
use App\Models\Emp_leaveModel;
use App\Models\CompanyProfile;
use App\Models\EmpLocationBangla;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Validator;
use DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;


class EmpControllers extends BaseController

{




    const NET_PHOTO   = '\\\\192.168.210.205\\emp_photo\\'; // Y: drive
    const NET_SIGN    = '\\\\192.168.210.205\\emp_sign\\';  // Z: drive
    const HTTP_PHOTO  = 'http://192.168.210.18:81/';
    const HTTP_SIGN   = 'http://192.168.210.18:82/';

 //ll

    // ── Tab 1 data only (2 queries) ───────────────────────
    private function tab1Data(): array {
        return [
            // 'companyList' => DB::table('COMPANY_PROFILE')->get(),
            'companyList' => DB::table('company_profile as cp')
    ->join('company_permission_user as cpu', 'cpu.company_id', '=', 'cp.company_id')
    ->join('F_STORE.ALL_USER_INFO as aui', 'aui.user_id', '=', 'cpu.user_id')
    ->where('aui.user_id', Auth::id()) // or 'AMIN'
    ->where('cp.is_active', 'Y')
                ->select('cp.company_id', 'cp.company_name')
    ->orderBy('cp.company_name')
    ->limit(50)
    ->distinct()
    ->get(),
            'religion'    => DB::table('RELIGION')->get(),
               'lastedu'    => DB::table('PASSED_EXAM')->get(),
        ];
    }

    
    // ─────────────────────────────────────────────────────
    //  LIST
    // ─────────────────────────────────────────────────────
    public function empList() {
        if (!session('LoggedUser')) return redirect('login');
        $companyList = DB::table('company_profile as cp')
            ->join('company_permission as cperm', 'cperm.company_id', '=', 'cp.company_id')
            ->join('user_permission as up', 'up.user_group_id', '=', 'cperm.user_group_id')
            ->join('auth_group as ag', 'ag.user_group_id', '=', 'up.user_group_id')
            ->where('up.user_id', Auth::id())->where('ag.group_tyep', 'U')->where('cp.is_active', 'Y')
            ->select('cp.company_id', 'cp.company_name')->distinct()->get();
        return view('hrm.emplist', compact('companyList'));
    }

    // ─────────────────────────────────────────────────────
    //  EMPLOYEE LIST SEARCH  (AJAX)
    //  GET /hrm/emplist/search
    //  Default status=Active → first load shows active only
    //  status='' → all employees
    // ─────────────────────────────────────────────────────
    public function empListSearch(Request $request) {
        if (!session('LoggedUser')) return response()->json(['message' => 'Unauthorized'], 401);
        $q = DB::table('EMP_PERSONAL AS EP')
            ->join('EMP_OFFICIAL AS EO', 'EO.EMPNO', '=', 'EP.EMPNO')
            ->select(
                'EP.EMPNO', 'EP.NEW_EMPNO',
                DB::raw("TRIM(EP.FIRST_NAME)||' '||TRIM(NVL(EP.MIDDLE_NAME,''))||' '||TRIM(NVL(EP.LAST_NAME,'')) AS EMPNAME"),
                'EP.FATHER_NAME', 'EP.MOTHER_NAME', 'EP.EMP_MOBILE_NO',
                'EP.SEX', 'EP.STATUS', 'EO.COMPANY_ID','EP.INSERT_BY'
            );
//dd($request->all());
        // Status — empty = All, 'Active' = default first load
        $status = $request->input('status', 'Active');
$q->when(!empty($status), function ($query) use ($status) {
    $query->where('EP.STATUS', $status);
});
        // Keyword: name / father / mother
        if ($search = trim($request->input('search', ''))) {
            $like = '%' . strtoupper($search) . '%';
            $q->where(function($s) use ($like) {
                $s->whereRaw("UPPER(TRIM(EP.FIRST_NAME)||' '||TRIM(NVL(EP.MIDDLE_NAME,''))||' '||TRIM(NVL(EP.LAST_NAME,''))) LIKE ?", [$like])
                  ->orWhereRaw('UPPER(EP.FATHER_NAME) LIKE ?', [$like])
                  ->orWhereRaw('UPPER(EP.MOTHER_NAME) LIKE ?', [$like]);
            });
        }

        // Emp No (EMPNO or NEW_EMPNO)
        if ($empno = trim($request->input('empno', ''))) {
            $q->where(function($s) use ($empno) {
                $s->whereRaw('UPPER(EP.EMPNO) LIKE ?',      ['%'.strtoupper($empno).'%'])
                  ->orWhereRaw('UPPER(EP.NEW_EMPNO) LIKE ?',['%'.strtoupper($empno).'%']);
            });
        }

        // Mobile
        if ($mobile = trim($request->input('mobile', '')))
            $q->where('EP.EMP_MOBILE_NO', 'like', '%'.$mobile.'%');

        // Company
        if ($co = $request->input('company_id', ''))
            $q->where('EO.COMPANY_ID', $co);

        $data = $q->orderBy('EP.NEW_EMPNO', 'ASC')->get();
        return response()->json(['data' => $data, 'total' => $data->count()]);
    }

    // ─────────────────────────────────────────────────────
    //  CREATE FORM  (Tab 1 only, fast)
    // ─────────────────────────────────────────────────────
    public function empentry() {
        if(!session('LoggedUser')) return redirect('login');
        return view('hrm.empform', $this->tab1Data());
    }

    // ─────────────────────────────────────────────────────
    //  EDIT FORM  (Tab 1 + emp record only, fast)
    // ─────────────────────────────────────────────────────
    public function empEdit($empno) {
        if(!session('LoggedUser')) return redirect('login');
        $emp = EmpPersonal::where('empno',$empno)->first();
        if(!$emp) return redirect()->route('emplist')->with('fail','Employee not found.');
        return view('hrm.empform', array_merge(['emp'=>$emp], $this->tab1Data()));
    }
public function empEditEntry($empno) {
        if(!session('LoggedUser')) return redirect('login');
        $emp = EmpPersonal::where('new_empno',$empno)->first();
        if(!$emp) return redirect()->route('empnewentry')->with('fail','Employee not found.');
        return view('hrm.empform', array_merge(['emp'=>$emp], $this->tab1Data()));
    }
    // ─────────────────────────────────────────────────────
    //  AJAX TAB: OFFICIAL  (loads dropdown + emp data)
    // ─────────────────────────────────────────────────────
    public function tabOfficial($empno) {
        if(!session('LoggedUser')) abort(401);
        $emp = EmpPersonal::where('empno',$empno)->with('getempofficial')->first();
        return view('hrm.tabs.tab_official', [
            'emp'         => $emp,
            'empno'       => $empno,
            // Only these two are server-rendered in official tab
            'companyList' => DB::table('company as cp')
                ->join('company_permission_user as cpu', 'cpu.company_id', '=', 'cp.company_id')
                ->join('F_STORE.ALL_USER_INFO as aui', 'aui.user_id', '=', 'cpu.user_id')
                ->where('aui.user_id', Auth::id())
                ->where('cp.is_active', 'Y')
                ->select('cp.company_id', 'cp.company_name')
                ->orderBy('cp.company_name')
                ->limit(50)
                ->distinct()
                ->get(),
            'empType'     => DB::table('HRM.EMP_TYPE')->select('EMP_TYPE','TYPE_SET','PRIORITY')->get(),
        ]);
    }

    // ─────────────────────────────────────────────────────
    //  AJAX TAB: LOCATION  (no dropdowns — all LOV)
    // ─────────────────────────────────────────────────────
    public function tabLocation($empno) {
        if(!session('LoggedUser')) abort(401);
        $emp = EmpPersonal::where('empno',$empno)->with('getemploc', 'locationBangla')->first();
        //dd($emp);
        return view('hrm.tabs.tab_location', ['emp'=>$emp,'empno'=>$empno]);
    }

    // ─────────────────────────────────────────────────────
    //  AJAX TABS: simple partials (no DB queries needed)
    // ─────────────────────────────────────────────────────
    public function tabEducation($empno)  { if(!session('LoggedUser')) abort(401); return view('hrm.tabs.tab_education',  ['empno'=>$empno]); }
    public function tabShortCourse($empno){ if(!session('LoggedUser')) abort(401); return view('hrm.tabs.tab_shortcourse',['empno'=>$empno]); }
    public function tabTraining($empno)   { if(!session('LoggedUser')) abort(401); return view('hrm.tabs.tab_training',   ['empno'=>$empno]); }
    public function tabExperience($empno) { if(!session('LoggedUser')) abort(401); return view('hrm.tabs.tab_experience', ['empno'=>$empno]); }
    public function tabNominee($empno)    { if(!session('LoggedUser')) abort(401); return view('hrm.tabs.tab_nominee',    ['empno'=>$empno]); }
    public function tabJobHistory($empno) { if(!session('LoggedUser')) abort(401); return view('hrm.tabs.tab_jobhistory', ['empno'=>$empno]); }

    // ─────────────────────────────────────────────────────
    //  DELETE EMPLOYEE
    // ─────────────────────────────────────────────────────
    public function deleteEmployee($empno) {
        if(!session('LoggedUser')) return response()->json(['message'=>'Unauthorized'],401);
        if(!DB::table('EMP_PERSONAL')->where('EMPNO',$empno)->exists())
            return response()->json(['message'=>'Employee not found.'],404);
        try {
            DB::transaction(function() use ($empno) {
                foreach(['EMP_WORK_EXP','EMP_TRAINING','EMP_SHORT_COURSE','EMP_QUALIFICATION',
                         'EMP_FAMILY','EMP_JOB_HISTORY','EMP_LOCATION','EMP_OFFICIAL','EMP_PERSONAL'] as $t)
                    DB::table($t)->where('EMPNO',$empno)->delete();
            });
            return response()->json(['success'=>true,'message'=>"Employee {$empno} deleted."]);
        } catch(\Exception $e) {
            return response()->json(['success'=>false,'message'=>$e->getMessage()],500);
        }
    }

    // ─────────────────────────────────────────────────────
    //  SAVE PERSONAL
    // ─────────────────────────────────────────────────────
public function saveEmpPersonal(Request $request)
{
    $empno  = trim($request->input('empno'));
    $record = EmpPersonal::where('empno', $empno)->first();


    $validator = Validator::make($request->all(), [
        'empno'         => 'required|string',
        'first_name'    => 'required|string|max:100',
        'last_name'     => 'nullable|string|max:100',
        'company_id'    => 'required|integer',
        'emp_mobile_no' => 'nullable|string|max:20',
        'photo'         => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        'signature'     => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
    ]);

    if ($validator->fails()) {
        return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
    }

    try {
            $companyName = $request->input('company_id_name')
            ?? optional(\App\Models\CompanyProfile::find($request->input('company_id')))->company_name
            ?? null;

        // ── Resolve religion name: prefer Select2 companion, else look up from DB ──
        $religionName = $request->input('religion_id_name')
            ?? optional(\App\Models\Religion::find($request->input('religion_id')))->religion_name
            ?? null;
        $data = array_merge($request->only([
            'first_name', 'middle_name', 'last_name', 'b_name', 'father_name',
            'mother_name', 'husband_name', 'gurdian_name', 'sex', 'marial_status','religion',
            'religion_id', 'blood_group', 'national_id_no', 'id_mark', 'company_id', 'company_name',
            'passport_no', 'place_of_issue', 'birthday_id',
            'emp_mobile_no', 'sms_mobile_no', 'office_food', 'status', 'hbs_test',
            'nationality_desc', 'last_education','age2'
        ]), [
             'empno'     => $empno,
                'card_no'   => $empno,
                'new_empno' => $empno,
'company_name' => $companyName,
            'religion_name'     => $religionName,


            'dob'           => $this->parseDate($request->input('dob')),
            'as_on'         => $this->parseDate($request->input('as_on')),
            'id_card_issue' => $this->parseDate($request->input('id_card_issue')),
            'valid_till'    => $this->parseDate($request->input('valid_till')),
            'update_by'     => auth()->id() ?? 1,
            'update_date'   => now(),
        ]);

        // ── Images: only update column if a new file was uploaded ──
        // ── otherwise keep whatever is already stored             ──
        $photoFile = $this->saveImage($request, 'photo', $empno);
        $signFile  = $this->saveImage($request, 'signature', $empno);

        if ($photoFile) {
            // New file uploaded → update column
            $data['emp_img'] = $photoFile;
        } elseif ($record) {
            // No new file → preserve existing value from DB
            $data['emp_img'] = $record->emp_img;
        }
        // If it's a brand-new record and no file uploaded → leave emp_img out of $data (stays null)

        if ($signFile) {
            $data['emp_sign'] = $signFile;
        } elseif ($record) {
            $data['emp_sign'] = $record->emp_sign;
        }

        // ── Build preview URLs (use new file first, fall back to DB value) ──
        $photoUrl = $this->imageUrl('photo', $photoFile ?? ($record->emp_img ?? null));
        $signUrl  = $this->imageUrl('sign',  $signFile  ?? ($record->emp_sign ?? null));

        // ── Update or Insert ──
        if ($record) {
            $record->update($data);
            return response()->json([
                'success'   => true,
                'message'   => 'Personal info updated successfully.',
                'photo_url' => $photoUrl,
                'sign_url'  => $signUrl,
            ], 200);
        }

        $data['insert_by']   = auth()->id() ?? 1;
        $data['insert_date'] = now();
        EmpPersonal::create($data);

        return response()->json([
            'success'   => true,
            'message'   => 'Personal info saved successfully.',
            'photo_url' => $photoUrl,
            'sign_url'  => $signUrl,
        ], 201);

    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
}


private function saveImage(Request $request, string $field, string $empno): ?string
{
    if (!$request->hasFile($field)) return null;

    $file = $request->file($field);
    if (!$file->isValid()) return null;

    $ext      = strtolower($file->getClientOriginalExtension()) ?: 'jpg';
    $filename = strtoupper($empno) . '.' . $ext;
    $folder   = ($field === 'photo') ? 'emp_photo' : 'emp_sign';
    $path     = $folder . '/' . $filename;

    $disk = Storage::disk('ftp');

    // ── Delete old file if it exists (handles extension mismatch e.g. old=.png new=.jpg) ──
    foreach (['jpg', 'jpeg', 'png', 'gif'] as $oldExt) {
        $oldPath = $folder . '/' . strtoupper($empno) . '.' . $oldExt;
        if ($oldPath !== $path && $disk->exists($oldPath)) {
            $disk->delete($oldPath);
        }
    }

    // ── Upload new file ──
    $stream = fopen($file->getRealPath(), 'rb');
    try {
        $disk->put($path, $stream);
    } finally {
        if (is_resource($stream)) {
            fclose($stream);
        }
    }

    return $filename;
}


//     public function saveEmpPersonal(Request $request) {
//         $empno  = trim($request->input('empno'));
//         $record = EmpPersonal::where('empno', $empno)->first();
// //dd(Carbon::parse($request->input('dob'))->format('Y-m-d'));
// //dd($request->input('dob'));
//         $validator = Validator::make($request->all(), [
//             'empno'         => 'required|string',
//             'first_name'    => 'required|string|max:100',
//             'last_name'     => 'required|string|max:100',
//             'company_id'    => 'required|integer',
//             'emp_mobile_no' => 'nullable|string|max:20',
//             'photo'         => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
//             'signature'     => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
//         ]);

//         if ($validator->fails()) {
//             return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
//         }

//         try {
//             $data = array_merge($request->only([
//                 'empno', 'first_name', 'middle_name', 'last_name', 'b_name', 'father_name',
//                 'mother_name', 'husband_name', 'gurdian_name', 'sex', 'marial_status',
//                 'religion_id', 'blood_group', 'national_id_no', 'id_mark', 'company_id',
//                 'passport_no', 'place_of_issue', 'birthday_id',
//                 'emp_mobile_no', 'sms_mobile_no', 'office_food', 'status', 'hbs_test',
//                 'nationality_desc', 'last_education',
//             ]), [
//                 'dob'           => Carbon::parse($request->input('dob'))->format('Y-m-d'),
//                 'as_on'         =>Carbon::parse($request->input('as_on'))->format('Y-m-d'),
//                 'id_card_issue' => Carbon::parse($request->input('id_card_issue'))->format('Y-m-d'),
//                 'valid_till'    => Carbon::parse($request->input('valid_till'))->format('Y-m-d'),
//                 'update_by'     => auth()->id() ?? 1,
//                 'update_date'   => now(),
//             ]);

//             // ── Photo → Y column (network drive Y:) ──
//            // PHOTO
// $photoFile = $this->saveImage($request, 'photo', $empno);
// if ($photoFile) {
//     $data['emp_img'] = $photoFile;   // store filename with extension
// }

// // SIGNATURE
// $signFile = $this->saveImage($request, 'signature', $empno);
// if ($signFile) {
//     $data['emp_sign'] = $signFile;   // store filename with extension
// }

//             // ── Build HTTP URLs for front-end preview update ──
//             // $photoUrl = $this->imageUrl('photo', $photoFile ?? ($record->Y ?? null));
//             // $signUrl  = $this->imageUrl('sign',  $signFile  ?? ($record->Z  ?? null));

// $photoUrl = null;
// $signUrl  = null;

// if ($photoFile) {
//     $photoUrl = $this->imageUrl('photo', $photoFile);
// } elseif ($record && !empty($record->emp_img)) {
//     $photoUrl = $this->imageUrl('photo', $record->emp_img);
// }

// if ($signFile) {
//     $signUrl = $this->imageUrl('sign', $signFile);
// } elseif ($record && !empty($record->emp_sign)) {
//     $signUrl = $this->imageUrl('sign', $record->emp_sign);
// }
//             if ($record) {
//                 $record->update($data);
//                 return response()->json([
//                     'success'   => true,
//                     'message'   => 'Personal info updated successfully.',
// 'photo_url' => $photoUrl,
// 'sign_url'  => $signUrl,                ], 200);
//             }

//             $data['insert_by']   = auth()->id() ?? 1;
//             $data['insert_date'] = now();
//             EmpPersonal::create($data);

//             return response()->json([
//                 'success'   => true,
//                 'message'   => 'Personal info saved successfully.',
//                 'photo_url' => $photoFile ? $this->imageUrl('photo', $photoFile) : null,
//                 'sign_url'  => $signFile  ? $this->imageUrl('sign',  $signFile)  : null,
//             ], 201);

//         } catch (\Exception $e) {
//             return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
//         }
//     }
       // ─────────────────────────────────────────────────────
    //  Helper: save uploaded image to network drive
    //  $field : 'photo'  → Y: drive (\\192.168.210.205\emp_photo)
    //           'signature' → Z: drive (\\192.168.210.205\emp_sign)
    //  $empno : used as filename base e.g. EMP001.jpg
    //  Returns: filename only e.g. "EMP001.jpg"  or null on failure
    // ─────────────────────────────────────────────────────
//    private function saveImage(Request $request, string $field, string $empno): ?string
// {
//     if (!$request->hasFile($field)) return null;

//     $file = $request->file($field);
//     if (!$file->isValid()) return null;

//     $ext      = strtolower($file->getClientOriginalExtension() ?: 'jpg');
//     $filename = strtoupper($empno) . '.' . $ext;

//     // Folder name on FTP
//     $folder = ($field === 'photo') ? 'emp_photo' : 'emp_sign';

//     // Full path on FTP
//     $path = $folder . '/' . $filename;

//     // Upload file via FTP
//     Storage::disk('ftp')->put($path, fopen($file->getRealPath(), 'r+'));

//     return $filename;
// }


private function imageUrl(string $type, ?string $filename): ?string
{
    if (empty($filename)) return null;

    $folder  = ($type === 'photo') ? 'emp_photo' : 'emp_sign';
    $baseUrl = env('FTP_URL', 'http://fallback-url.com/files');
//dd($baseUrl);
    return rtrim($baseUrl, '/') . '/' . $folder . '/' . $filename;
}

private function parseDate($val)
{
    return !empty($val) ? Carbon::parse($val)->format('Y-m-d') : null;
}
    // ═════════════════════════════════════════════════════════════════════
    //  SAVE OFFICIAL (Enhanced with LOV Value + Text Support)
    // ═════════════════════════════════════════════════════════════════════
    public function saveEmpOfficial(Request $request) {
        try {
            $empno  = trim($request->input('empno'));
            
            if (!$empno) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Employee number is required'
                ], 400);
            }
            
            $record = EmpOfficial::where('empno', $empno)->first();
            
            // ─────────────────────────────────────────────────────────
            //  Get company name from COMPANY_PROFILE table
            // ─────────────────────────────────────────────────────────
            $companyId = $request->input('company_id');
            $company = DB::table('company')->where('company_id', $companyId)->first();
            $companyName = $company ? $company->company_name : null;
            
            // ─────────────────────────────────────────────────────────
            //  Build data array with both IDs and Names from LOV
            // ─────────────────────────────────────────────────────────
            $data = [
                // ─── Base Information ─────────────────────────────────
                'empno'           => $empno,
                'company_id'      => $companyId,
                'company_name'    => $companyName,
                'emp_type'        => $request->input('emp_type'),
                
                // ─── Department (LOV) ─────────────────────────────────
                'dept_no'         => $request->input('dept_no'),
                'dept_name'       => $request->input('dept_no_name'), // From lovFormObjectWithNames
                
                // ─── Section (LOV) ────────────────────────────────────
                'section_no'      => $request->input('section_no'),
                'section_name'    => $request->input('section_no_name'),
                
                // ─── Floor (LOV) ──────────────────────────────────────
                'floor_id'        => $request->input('floor_id'),
                'floor_desc'      => $request->input('floor_id_name'),
                
                // ─── Line (LOV) ───────────────────────────────────────
                'line'            => $request->input('line'),
                'line_info'       => $request->input('line_name'),
                
                // ─── Designation (LOV) ────────────────────────────────
                'des_id'          => $request->input('des_id'),
                'des_name'        => $request->input('des_id_name'),
                
                // ─── Grade (LOV) ──────────────────────────────────────
                'grade_id'        => $request->input('grade_id'),
                'grade_name'      => $request->input('grade_id_name'),
                
                // ─── Shift (LOV) ──────────────────────────────────────
                'shift_code'      => $request->input('shift_code'),
                'shift_name'      => $request->input('shift_code_name'),
                
                // ─── Calendar (LOV) ───────────────────────────────────
                'cal_code'        => $request->input('cal_code'),
                
                // ─── Weekly Off (LOV) ─────────────────────────────────
                'weekly_off'      => $request->input('weekly_off'),
                
                // ─── Shift Group ──────────────────────────────────────
                's_group_name'    => $request->input('s_group_name'),
                
                // ─── Joining & Date Information ───────────────────────
                'joining_date'       => $this->parseDate($request->input('join_date')),
                'join_time'       => $request->input('join_time'),
                'conform_date' => $this->parseDate($request->input('join_date')),
                'confirmation_duration' => $request->input('confirmation_duration'),
                'last_promo_date' => $this->parseDate($request->input('last_promo_date')),
                'last_increment_date' => $this->parseDate($request->input('last_increment_date')),
                
                // ─── Attendance & Card Information ────────────────────
                'punch_card_no'   => $request->input('punch_card_no'),
                'proximity_card_no' => $request->input('proximity_card_no'),
                'ot_cat'          => $request->input('ot_cat'),
                'attn_eff_date'   => $this->parseDate($request->input('attn_eff_date')),

                // ─── Entitlement Information ──────────────────────────
                'work_ent'        => $request->input('work_ent'),
                'ot_ent'          => $request->input('ot_ent'),
                'res_ent'         => $request->input('res_ent'),
                'tran_ent'        => $request->input('tran_ent'),
                'pf_ent'          => $request->input('pf_ent'),
                'tax_ent'         => $request->input('tax_ent'),
                
                // ─── Leave Information (LOV) ──────────────────────────
                'lv_cat_id'       => $request->input('lv_cat_id'),
                'lv_cat_name'     => $request->input('lv_cat_id_name'),
                'entry_date'      => $this->parseDate($request->input('entry_date')),
                
                // ─── Salary & Bank Information ────────────────────────
                'gross'           => $request->input('gross'),
                'other_allowance' => $request->input('other_allowance'),
                'bank_name'       => $request->input('bank_name'), // LOV value = text in this case
                'bank_ac_no'      => $request->input('bank_ac_no'),
                'tin_no'          => $request->input('tin_no'),
                'tax_deduction'   => $request->input('tax_deduction'),
                'service_book_number' => $request->input('service_book_number'),
                'ac_no'           => $request->input('ac_no'),
                
                // ─── Release Information ──────────────────────────────
                'termination_date' => $this->parseDate($request->input('termination_date')),
                'resigned_date'   => $this->parseDate($request->input('resigned_date')),
                'reason'          => $request->input('reason'),
                'is_lefty'        => $request->input('is_lefty'),
                
                // ─── Audit Fields ─────────────────────────────────────
                'update_by'       => auth()->id() ?? 1,
                'update_date'     => now(),
            ];
            
            // Remove null values to avoid overwriting existing data with nulls
            $data = array_filter($data, function($value) {
                return $value !== null && $value !== '';
            });
            
            // ─────────────────────────────────────────────────────────
            //  Update or Create Record
            // ─────────────────────────────────────────────────────────
            if ($record) {
                $record->update($data);
                
                // Log for debugging (remove in production)
                \Log::info('Official Info Updated', [
                    'empno' => $empno,
                    'dept' => $data['dept_no'] ?? null,
                    'dept_name' => $data['dept_name'] ?? null,
                ]);
                
                return response()->json([
                    'success' => true, 
                    'message' => 'Official information updated successfully',
                   
                ], 200);
            }
            
            // For new records, add insert audit fields
            $data['insert_by'] = auth()->id() ?? 1;
            $data['insert_date'] = now();
            
            $newRecord = EmpOfficial::create($data);
            
            // Log for debugging (remove in production)
            \Log::info('Official Info Created', [
                'empno' => $empno,
                'dept' => $data['dept_no'] ?? null,
                'dept_name' => $data['dept_name'] ?? null,
            ]);
            
            return response()->json([
                'success' => true, 
                'message' => 'Official information saved successfully',
                
            ], 201);
            
        } catch(\Illuminate\Database\QueryException $e) {
            // Database specific errors
            \Log::error('Database Error in saveEmpOfficial', [
                'empno' => $request->input('empno'),
                'error' => $e->getMessage(),
                'sql' => $e->getSql() ?? 'N/A'
            ]);
            
            return response()->json([
                'success' => false, 
                'message' => 'Database error: ' . $e->getMessage()
            ], 500);
            
        } catch(\Exception $e) {
            // General errors
            \Log::error('Error in saveEmpOfficial', [
                'empno' => $request->input('empno'),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false, 
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }








public function saveEmpLocation(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'empno' => 'required|string',
                'p_address' => 'nullable|string|max:500',
                'p_city' => 'nullable|string|max:100',
                'p_district' => 'nullable|string|max:100',
                'p_pin_code' => 'nullable|string|max:20',
                'p_phone' => 'nullable|string|max:20',
                'p_fax' => 'nullable|string|max:20',
                'p_cperson' => 'nullable|string|max:100',
                'p_village' => 'nullable|string|max:100',
                'p_post_off' => 'nullable|string|max:100',
                'p_police_station' => 'nullable|string|max:100',
                'r_address' => 'nullable|string|max:500',
                'r_city' => 'nullable|string|max:100',
                'r_district' => 'nullable|string|max:100',
                'P_PIN_CODE' => 'nullable|numeric|max:20',
                'r_phone' => 'nullable|string|max:20',
                'r_fax' => 'nullable|string|max:20',
                'r_mobile' => 'nullable|string|max:20',
                'r_email' => 'nullable|email|max:100',
                'r_cperson' => 'nullable|string|max:100',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $empno = $request->input('empno');
            $empLocation = EmpLocation::where('empno', $empno)->first();
//dd($request->all());
            $locationData = [
                'empno' => $empno,
                'p_address' => $request->input('p_address'),
                'p_city' => $request->input('p_city'),
                'p_district' => $request->input('p_district'),
                'p_pin_code' => $request->input('p_pin_code'),
                'p_phone' => $request->input('p_phone'),
                'p_fax' => $request->input('p_fax'),
                'p_cperson' => $request->input('p_cperson'),
                'p_village' => $request->input('p_village'),
                'p_post_off' => $request->input('p_post_off'),
                'p_police_station' => $request->input('p_police_station'),
                'r_address' => $request->input('r_address'),
                'r_city' => $request->input('r_city'),
                'r_district' => $request->input('r_district'),
                'r_pin_code' => $request->input('r_pin_cod'),
                'r_phone' => $request->input('r_phone'),
                'r_fax' => $request->input('r_fax'),
                'r_mobile' => $request->input('r_mobile'),
                'r_email' => $request->input('r_email'),
                'r_cperson' => $request->input('r_cperson'),
            ];

            if ($empLocation) {
                //dd($locationData);
                $empLocation->update($locationData);
                $message = 'Employee location information updated successfully';
                $statusCode = 200;
            } else {
                $empLocation = EmpLocation::create($locationData);
                $message = 'Employee location information saved successfully';
                $statusCode = 201;
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => $empLocation
            ], $statusCode);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error processing employee location information',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Save Qualification (CREATE)
     * POST: /api/saveEmpQualification
     * The route saveEmpQualification could not be found.
     */
    public function saveEmpQualification(Request $request)
    {


        try {
            $validator = Validator::make($request->all(), [
                'empno' => 'required|string',
                'name_of_ins' => 'nullable|string|max:255',
                'passed_exam' => 'nullable|string|max:100',
                'division' => 'nullable|string|max:50',
                'year' => 'nullable|integer',
                'board' => 'nullable|string|max:100',
                'marks' => 'nullable|string|max:50',
                'subject' => 'nullable|string|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $qualification = Emp_qualificationModel::create([
                'empno' => $request->input('empno'),
                'name_of_ins' => $request->input('name_of_ins'),
                'passed_exam' => $request->input('passed_exam'),
                'division' => $request->input('division'),
                'year' => $request->input('year'),
                'board' => $request->input('board'),
                'marks' => $request->input('marks'),
                'subject' => $request->input('subject'),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Qualification saved successfully',
                'data' => $qualification
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error saving qualification',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function getEmpQualifications($empno)
    {
        try {
            $qualifications = Emp_qualificationModel::where('empno', $empno)->get();
            return response()->json([
                'success' => true,
                'data' => $qualifications
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching qualifications',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get Passed Exams
     */
    public function getPassedExams()
    {
        try {
            $exams = DB::table('HRM.PASSED_EXAM')->select('PASSED_EXAM', 'BANGLA_NAME')->get();
            return response()->json($exams);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching passed exams',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update Qualification (UPDATE)
     * PUT: /api/updateEmpQualification/{id}
     */
    public function updateEmpQualification($id, Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name_of_ins' => 'nullable|string|max:255',
                'passed_exam' => 'nullable|string|max:100',
                'division' => 'nullable|string|max:50',
                'year' => 'nullable|integer',
                'board' => 'nullable|string|max:100',
                'marks' => 'nullable|string|max:50',
                'subject' => 'nullable|string|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $qualification = Emp_qualificationModel::findOrFail($id);
            $qualification->update($request->only([
                'name_of_ins', 'passed_exam', 'division', 'year', 'board', 'marks', 'subject'
            ]));

            return response()->json([
                'success' => true,
                'message' => 'Qualification updated successfully',
                'data' => $qualification
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating qualification',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete Qualification (DELETE)
     * DELETE: /api/deleteEmpQualification/{id}
     */
    public function deleteEmpQualification($id)
    {
        try {
            $qualification = Emp_qualificationModel::findOrFail($id);
            $qualification->delete();

            return response()->json([
                'success' => true,
                'message' => 'Qualification deleted successfully'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting qualification',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Save Short Course (CREATE)
     * POST: /api/saveEmpShortCourse
     */
    public function saveEmpShortCourse(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'empno' => 'required|string',
                'course_name' => 'nullable|string|max:255',
                'conducted_by' => 'nullable|string|max:255',
                'c_from' => 'nullable|date',
                'c_to' => 'nullable|date',
                'certificate' => 'nullable|string|max:255',
                'total_day' => 'nullable|integer',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $course = Emp_ShortModel::create([
                'empno' => $request->input('empno'),
                'course_name' => $request->input('course_name'),
                'conducted_by' => $request->input('conducted_by'),
                'c_from' => $this->parseDate($request->input('c_from')),
                'c_to' => $this->parseDate($request->input('c_to')),
                'certificate' => $request->input('certificate'),
                'total_day' => $request->input('total_day'),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Short course saved successfully',
                'data' => $course
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error saving short course',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update Short Course (UPDATE)
     * PUT: /api/updateEmpShortCourse/{id}
     */
    public function updateEmpShortCourse($id, Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'course_name' => 'nullable|string|max:255',
                'conducted_by' => 'nullable|string|max:255',
                'c_from' => 'nullable|date',
                'c_to' => 'nullable|date',
                'certificate' => 'nullable|string|max:255',
                'total_day' => 'nullable|integer',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $course = Emp_ShortModel::findOrFail($id);
            $course->update([
                'course_name' => $request->input('course_name'),
                'conducted_by' => $request->input('conducted_by'),
                'c_from' => $this->parseDate($request->input('c_from')),
                'c_to' => $this->parseDate($request->input('c_to')),
                'certificate' => $request->input('certificate'),
                'total_day' => $request->input('total_day'),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Short course updated successfully',
                'data' => $course
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating short course',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete Short Course (DELETE)
     * DELETE: /api/deleteEmpShortCourse/{id}
     */
    public function deleteEmpShortCourse($id)
    {
        try {
            $course = Emp_ShortModel::findOrFail($id);
            $course->delete();

            return response()->json([
                'success' => true,
                'message' => 'Short course deleted successfully'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting short course',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Save Family Member (CREATE)
     * POST: /api/saveEmpFamily
     */
 public function saveEmpFamily(Request $request)
{
    try {

        $validator = Validator::make($request->all(), [

            'empno' => 'required|string|max:20',
            'depd_name' => 'required|string|max:50',

        ]);

        if ($validator->fails()) {

            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | AUTO GENERATE DEPD_NO
        |--------------------------------------------------------------------------
        */

        $maxDepdNo = Emp_familyModel::where('empno', $request->empno)
            ->max('depd_no');

        $newDepdNo = $maxDepdNo ? $maxDepdNo + 1 : 1;

        /*
        |--------------------------------------------------------------------------
        | SAVE
        |--------------------------------------------------------------------------
        */

        $family = Emp_familyModel::create([

            'empno' => $request->empno,
            'depd_no' => $newDepdNo,

            'depd_name' => $request->depd_name,
            'relationship' => $request->relationship,

            'd_dob' => $this->parseDate($request->d_dob),

            'd_age' => $request->d_age,
            'd_sex' => $request->d_sex,

            'd_as_on' => $this->parseDate($request->d_as_on),

            'percentage' => $request->percentage,

            'address' => $request->address,

            'depent_name_bangla' => $request->depent_name_bangla,

            'relation_bn' => $request->relation_bn,

            'address_bn' => $request->address_bn,

            'village_bn' => $request->village_bn,

            'po_bn' => $request->po_bn,

            'ps_bn' => $request->ps_bn,

            'district_bn' => $request->district_bn,

        ]);

        return response()->json([

            'success' => true,
            'message' => 'Family member saved successfully',
            'data' => $family

        ], 201);

    } catch (\Exception $e) {

        return response()->json([

            'success' => false,
            'message' => $e->getMessage()

        ], 500);
    }
}

    public function getEmpFamily($empno)
    {
        try {
            $family = Emp_familyModel::where('empno', $empno)->get();
            return response()->json([
                'success' => true,
                'data' => $family
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching family records',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update Family Member (UPDATE)
     * PUT: /api/updateEmpFamily/{id}
     */
    public function updateEmpFamily(Request $request)
{
    try {

        $family = Emp_familyModel::where('empno', $request->empno)
            ->where('depd_no', $request->depd_no)
            ->first();

        if (!$family) {

            return response()->json([
                'success' => false,
                'message' => 'Family record not found'
            ], 404);
        }

        $family->update([

            'depd_name' => $request->depd_name,
            'relationship' => $request->relationship,

            'd_dob' => $this->parseDate($request->d_dob),

            'd_age' => $request->d_age,

            'd_sex' => $request->d_sex,

            'd_as_on' => $this->parseDate($request->d_as_on),

            'percentage' => $request->percentage,

            'address' => $request->address,

            'depent_name_bangla' => $request->depent_name_bangla,

            'relation_bn' => $request->relation_bn,

            'address_bn' => $request->address_bn,

            'village_bn' => $request->village_bn,

            'po_bn' => $request->po_bn,

            'ps_bn' => $request->ps_bn,

            'district_bn' => $request->district_bn,

        ]);

        return response()->json([
            'success' => true,
            'message' => 'Updated Successfully',
            'data' => $family
        ]);

    } catch (\Exception $e) {

        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);

    }
}
    /**
     * Delete Family Member (DELETE)
     * DELETE: /api/deleteEmpFamily/{id}
     */
  public function deleteEmpFamily(Request $request)
{
    try {

        $deleted = Emp_familyModel::where('empno', $request->empno)
            ->where('depd_no', $request->depd_no)
            ->delete();

        if ($deleted == 0) {

            return response()->json([

                'success' => false,
                'message' => 'Family member not found'

            ], 404);
        }

        return response()->json([

            'success' => true,
            'message' => 'Family member deleted successfully'

        ], 200);

    } catch (\Exception $e) {

        return response()->json([

            'success' => false,
            'message' => 'Error deleting family member',
            'error' => $e->getMessage()

        ], 500);
    }
}
    /**
     * Save Job History (CREATE)
     * POST: /api/saveEmpHistory
     */
    public function saveEmpHistory(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'empno' => 'required|string',
                'join_as' => 'nullable|string|max:100',
                'work_location' => 'nullable|string|max:100',
                'join_date' => 'nullable|date',
                'designation' => 'nullable|string|max:100',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $history = Emp_historyModel::create([
                'empno' => $request->input('empno'),
                'join_as' => $request->input('join_as'),
                'work_location' => $request->input('work_location'),
                'join_date' => $this->parseDate($request->input('join_date')),
                'designation' => $request->input('designation'),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Job history saved successfully',
                'data' => $history
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error saving job history',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update Job History (UPDATE)
     * PUT: /api/updateEmpHistory/{id}
     */
    public function updateEmpHistory($id, Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'join_as' => 'nullable|string|max:100',
                'work_location' => 'nullable|string|max:100',
                'join_date' => 'nullable|date',
                'designation' => 'nullable|string|max:100',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $history = Emp_historyModel::findOrFail($id);
            $history->update([
                'join_as' => $request->input('join_as'),
                'work_location' => $request->input('work_location'),
                'join_date' => $this->parseDate($request->input('join_date')),
                'designation' => $request->input('designation'),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Job history updated successfully',
                'data' => $history
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating job history',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete Job History (DELETE)
     * DELETE: /api/deleteEmpHistory/{id}
     */
    public function deleteEmpHistory($id)
    {
        try {
            $history = Emp_historyModel::findOrFail($id);
            $history->delete();

            return response()->json([
                'success' => true,
                'message' => 'Job history deleted successfully'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting job history',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Save Training (CREATE)
     * POST: /api/saveEmpTraining
     */
    public function saveEmpTraining(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'empno' => 'required|string',
                't_title' => 'nullable|string|max:255',
                't_conducted_by' => 'nullable|string|max:255',
                't_from' => 'nullable|date',
                't_to' => 'nullable|date',
                't_certificate' => 'nullable|string|max:255',
                'skill_type' => 'nullable|string|max:100',
                'to_days' => 'nullable|integer',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $training = Emp_trainingModel::create([
                'empno' => $request->input('empno'),
                't_title' => $request->input('t_title'),
                't_conducted_by' => $request->input('t_conducted_by'),
                't_from' => $this->parseDate($request->input('t_from')),
                't_to' => $this->parseDate($request->input('t_to')),
                't_certificate' => $request->input('t_certificate'),
                'skill_type' => $request->input('skill_type'),
                'to_days' => $request->input('to_days'),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Training saved successfully',
                'data' => $training
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error saving training',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update Training (UPDATE)
     * PUT: /api/updateEmpTraining/{id}
     */
    public function updateEmpTraining($id, Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                't_title' => 'nullable|string|max:255',
                't_conducted_by' => 'nullable|string|max:255',
                't_from' => 'nullable|date',
                't_to' => 'nullable|date',
                't_certificate' => 'nullable|string|max:255',
                'skill_type' => 'nullable|string|max:100',
                'to_days' => 'nullable|integer',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $training = Emp_trainingModel::findOrFail($id);
            $training->update([
                't_title' => $request->input('t_title'),
                't_conducted_by' => $request->input('t_conducted_by'),
                't_from' => $this->parseDate($request->input('t_from')),
                't_to' => $this->parseDate($request->input('t_to')),
                't_certificate' => $request->input('t_certificate'),
                'skill_type' => $request->input('skill_type'),
                'to_days' => $request->input('to_days'),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Training updated successfully',
                'data' => $training
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating training',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete Training (DELETE)
     * DELETE: /api/deleteEmpTraining/{id}
     */
    public function deleteEmpTraining($id)
    {
        try {
            $training = Emp_trainingModel::findOrFail($id);
            $training->delete();

            return response()->json([
                'success' => true,
                'message' => 'Training deleted successfully'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting training',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Save Work Experience (CREATE)
     * POST: /api/saveEmpWorkExp
     */
    public function saveEmpWorkExp(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'empno' => 'required|string',
                'organization' => 'required|string|max:255',
                'd_from' => 'required|date',
                'd_to' => 'nullable|date',
                'leave_reason' => 'nullable|string|max:500',
                'prv_emp_no' => 'nullable|string|max:50',
                'org_address' => 'nullable|string|max:500',
                'org_tel' => 'nullable|string|max:20',
                'last_sal_drawn' => 'nullable|numeric',
                'total_years' => 'nullable|numeric',
                'designation' => 'nullable|string|max:100',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $workExp = Emp_work_expModel::create([
                'empno' => $request->input('empno'),
                'organization' => $request->input('organization'),
                'd_from' => $this->parseDate($request->input('d_from')),
                'd_to' => $this->parseDate($request->input('d_to')),
                'leave_reason' => $request->input('leave_reason'),
                'prv_emp_no' => $request->input('prv_emp_no'),
                'org_address' => $request->input('org_address'),
                'org_tel' => $request->input('org_tel'),
                'last_sal_drawn' => $request->input('last_sal_drawn'),
                'total_days' => $request->input('total_years'),
                'designation' => $request->input('designation'),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Work experience saved successfully',
                'data' => $workExp
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error saving work experience',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get Work Experience (READ)
     * GET: /api/getEmpWorkExperience/{empno}
     */
    public function getEmpWorkExperience($empno)
    {
        try {
            $workExperiences = Emp_work_expModel::where('empno', $empno)->get();

            return response()->json([
                'success' => true,
                'data' => $workExperiences
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching work experience records',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update Work Experience (UPDATE)
     * PUT: /api/updateEmpWorkExp/{id}
     */
    public function updateEmpWorkExp($id, Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'organization' => 'required|string|max:255',
                'd_from' => 'required|date',
                'd_to' => 'nullable|date',
                'leave_reason' => 'nullable|string|max:500',
                'prv_emp_no' => 'nullable|string|max:50',
                'org_address' => 'nullable|string|max:500',
                'org_tel' => 'nullable|string|max:20',
                'last_sal_drawn' => 'nullable|numeric',
                'total_years' => 'nullable|numeric',
                'designation' => 'nullable|string|max:100',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $workExp = Emp_work_expModel::findOrFail($id);
            $workExp->update([
                'organization' => $request->input('organization'),
                'd_from' => $this->parseDate($request->input('d_from')),
                'd_to' => $this->parseDate($request->input('d_to')),
                'leave_reason' => $request->input('leave_reason'),
                'prv_emp_no' => $request->input('prv_emp_no'),
                'org_address' => $request->input('org_address'),
                'org_tel' => $request->input('org_tel'),
                'last_sal_drawn' => $request->input('last_sal_drawn'),
                'total_days' => $request->input('total_years'),
                'designation' => $request->input('designation'),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Work experience updated successfully',
                'data' => $workExp
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating work experience',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete Work Experience (DELETE)
     * DELETE: /api/deleteEmpWorkExp/{id}
     */
    public function deleteEmpWorkExp($id)
    {
        try {
            $workExp = Emp_work_expModel::findOrFail($id);
            $workExp->delete();

            return response()->json([
                'success' => true,
                'message' => 'Work experience deleted successfully'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting work experience',
                'error' => $e->getMessage()
            ], 500);
        }
    }




















    // ═════════════════════════════════════════════════════════════════════
    //  GET OFFICIAL DATA (Helper method to retrieve official info with names)
    // ═════════════════════════════════════════════════════════════════════
    public function getEmpOfficial($empno) {
        if(!session('LoggedUser')) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        
        try {
            $official = EmpOfficial::where('empno', $empno)->first();
            
            if (!$official) {
                return response()->json([
                    'success' => false,
                    'message' => 'Official record not found'
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'data' => $official
            ]);
            
        } catch(\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // ─────────────────────────────────────────────────────
    //  AUTOCOMPLETE / SEARCH
    // ─────────────────────────────────────────────────────
    public function empsearch(Request $request) {
        $rows = DB::table('EMP_PERSONAL')
            ->select('NEW_EMPNO')
            ->where('NEW_EMPNO','like','%'.$request->search_key.'%')
            ->take(20)->get();
        return response()->json(['data'=>$rows]);
    }

    public function getEmpDetSearch(Request $request) {
        return EmpPersonal::where('new_empno','=',$request->empno)
            ->with('getempofficial','getemploc','empQualification',
                   'getEmpShortModel','getEmpFamily','getEmpHistory',
                   'getEmpTraining','getEmpWorkExp')
            ->get();

    }

    public function empSearchExist(Request $request) {
        return DB::table('EMP_PERSONAL')
            ->select(DB::raw('COUNT(EMPNO) as EMPCOUNT'))
            ->where('EMPNO','=',$request->input('empno'))->get();
    }

    // ─────────────────────────────────────────────────────
    //  LEAVE (unchanged)
    // ─────────────────────────────────────────────────────
    public function getLeaveDetails($empno,$year) {
        $rows = DB::table('LEAVE_ENTRY_DETAILS')->where('YEAR','=',$year)->where('EMPNO','=',$empno)->get();
        return view('hrm.leave_table',['getLeaveDetails'=>$rows]);
    }
    
    public function getLeavePrebal($empno,$year,$lv) {
        return DB::table(DB::raw('LEAVE_ENTRY_DETAILS LV'))
            ->select(DB::raw('NVL(SUM(APPROVE_DAYS),0) APPROVE_DAYS'))
            ->where('LV.leave_id','=',$lv)->where('empno','=',$empno)
            ->whereRaw('\''.$year.'\' = to_char(lv_to,\'YYYY\')')
            ->whereRaw('\''.$year.'\' = to_char(lv_from,\'YYYY\')')->get();
    }
    
    public function getLeavBal($lv) {
        return DB::table('leave_info')->select(DB::raw('nvl(max_days,60)max_days'))->where('leave_id','=',$lv)->get();
    }
    
    public function leaveEntryIns(Request $request) {
        $d=$request->input();
        $c=DB::table('LEAVE_ENTRY_MASTER')->selectRaw('COUNT(*) AS COUNT_LEAVE')
            ->where('YEAR','=',$d['year'])->where('EMPNO','=',$d['empno'])
            ->where('COMPANY_ID','=',$d['company_id'])->first();
        if(optional($c)->count_leave>0) return response()->json(['status'=>200]);
        try {
            $l=new LeaveEntryMaster;
            $l->company_id=$d['company_id']; $l->empno=$d['empno'];
            $l->lv_cat_id=$d['lv_cat_id'];   $l->new_empno=$d['empno'];
            $l->year=$d['year']; $l->save();
            return response()->json(['status'=>200]);
        } catch(\Exception $e){ return $e; }
    }
    
    public function leaveEntryDet(Request $request) {
        $d=$request->input();
        $max=DB::table('LEAVE_ENTRY_details')->select(DB::raw('(nVL(MAX(LV_SL),0)+1) lv_sl'))
            ->where('YEAR','=',$d['year_det'])->where('EMPNO','=',$d['emp_no_det'])
            ->where('lv_cat_id','=',$d['lv_cat_id_det'])->first();
        try {
            $l=new LeaveEntryDetails;
            $l->lv_cat_id=$d['lv_cat_id_det']; $l->year=$d['year_det']; $l->empno=$d['emp_no_det'];
            $l->balance=$d['new_balance'];       $l->application_date=$d['submitted'];
            $l->approve_date=$d['approve_date']; $l->approve_days=$d['approve_days'];
            $l->approve_by=$d['approve_by'];     $l->lv_from=$d['lv_from'];
            $l->lv_to=$d['lv_to'];               $l->leave_id=$d['leave_name'];
            $l->max_days=$d['max_days'];          $l->pre_balance=$d['pre_balance'];
            $l->remax=$d['remarks'];              $l->information=$d['information'];
            $l->lv_sl=$max->lv_sl; $l->save();
            return response()->json(['status'=>200]);
        } catch(\Exception $e){ return $e; }
    }
    
    public function deleteLeave($empno,$year,$sl) {
        $r=DB::table('LEAVE_ENTRY_details')->select('lv_sl')
            ->where('YEAR','=',$year)->where('EMPNO','=',$empno)->where('lv_sl','=',$sl)->first();
        if(!optional($r)->lv_sl==null) {
            try {
                DB::table('LEAVE_ENTRY_details')
                    ->where('YEAR','=',$year)->where('EMPNO','=',$empno)->where('lv_sl','=',$sl)->delete();
                return response()->json(['status2'=>200]);
            } catch(\Exception $e){ return response()->json([$e->getCode()]); }
        }
    }
    
    public function logout() { 
        session()->pull('LoggedUser'); 
        return redirect('login'); 
    }

   public function saveEmpLocationBangla(Request $request)
{
    try {
        // ── 1. Validate ───────────────────────────────────────────────────
        $validator = Validator::make($request->all(), [
            'empno'            => 'required|string|max:30',
            'father_name'      => 'nullable|string|max:100',
            'mother_name'      => 'nullable|string|max:100',
            'present_village'  => 'nullable|string|max:100',
            'present_psot'     => 'nullable|string|max:100',
            'present_thana'    => 'nullable|string|max:100',
            'present_dist'     => 'nullable|string|max:100',
            'permanent_village'=> 'nullable|string|max:100',
            'parmaent_post'    => 'nullable|string|max:100',
            'permanent_thana'  => 'nullable|string|max:100',
            'permanent_dist'   => 'nullable|string|max:100',
            'sopuse_name'      => 'nullable|string|max:100',
            'worker_class'     => 'nullable|string|max:100',
            'working_type'     => 'nullable|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $validator->errors(),
            ], 422);
        }

        // ── 2. Check employee exists in master table ──────────────────────
        $empno = $request->input('empno');

        $employeeExists = EmpPersonal::where('empno', $empno)->exists();

        if (!$employeeExists) {
            return response()->json([
                'success' => false,
                'message' => "Employee '{$empno}' not found in the system.",
            ], 404);
        }

        // ── 3. Prepare data ───────────────────────────────────────────────
        $banglaData = [
            'father_name'       => $request->input('father_name'),
            'mother_name'       => $request->input('mother_name'),
            'present_village'   => $request->input('present_village'),
            'present_psot'      => $request->input('present_psot'),
            'present_thana'     => $request->input('present_thana'),
            'present_dist'      => $request->input('present_dist'),
            'permanent_village' => $request->input('permanent_village'),
            'parmaent_post'     => $request->input('parmaent_post'),
            'permanent_thana'   => $request->input('permanent_thana'),
            'permanent_dist'    => $request->input('permanent_dist'),
            'sopuse_name'       => $request->input('sopuse_name'),
            'worker_class'      => $request->input('worker_class'),
            'working_type'      => $request->input('working_type'),
            'new_empno'         => $empno, // mirrors empno as per original logic
        ];

        // ── 4. Check if record already exists (for response message) ──────
        $exists = EmpLocationBangla::where('empno', $empno)->exists();

        // ── 5. Upsert ─────────────────────────────────────────────────────
        $locationBangla = EmpLocationBangla::updateOrCreate(
            ['empno' => $empno],   // search key
            $banglaData            // data to insert or update
        );

        // ── 6. Respond ────────────────────────────────────────────────────
        return response()->json([
            'success' => true,
            'message' => $exists
                ? 'Bangla location information updated successfully.'
                : 'Bangla location information saved successfully.',
            'data'    => $locationBangla,
        ], $exists ? 200 : 201);

    } catch (\Illuminate\Database\QueryException $e) {
        // Database-level errors (constraint violations, connection issues, etc.)
        return response()->json([
            'success' => false,
            'message' => 'Database error while saving Bangla location information.',
            'error'   => $e->getMessage(),
        ], 500);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Unexpected error while processing Bangla location information.',
            'error'   => $e->getMessage(),
        ], 500);
    }
}

    /**
     * Get Bangla Location by empno
     * GET: /api/getEmpLocationBangla/{empno}
     */
    public function getEmpLocationBangla($empno)
    {
        try {
            $locationBangla = EmpLocationBangla::where('empno', $empno)->first();

            if (!$locationBangla) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bangla location record not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $locationBangla
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching Bangla location information',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update Bangla Location
     * PUT: /api/updateEmpLocationBangla/{empno}
     */
    public function updateEmpLocationBangla($empno, Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'father_name' => 'nullable|string|max:100',
                'mother_name' => 'nullable|string|max:100',
                'present_village' => 'nullable|string|max:100',
                'present_psot' => 'nullable|string|max:100',
                'present_thana' => 'nullable|string|max:100',
                'present_dist' => 'nullable|string|max:100',
                'permanent_village' => 'nullable|string|max:100',
                'parmaent_post' => 'nullable|string|max:100',
                'permanent_thana' => 'nullable|string|max:100',
                'permanent_dist' => 'nullable|string|max:100',
                'sopuse_name' => 'nullable|string|max:100',
                'worker_class' => 'nullable|string|max:100',
                'working_type' => 'nullable|string|max:100',
                'new_empno' => 'nullable|string|max:30',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $locationBangla = EmpLocationBangla::where('empno', $empno)->firstOrFail();
            $locationBangla->update($request->only([
                'father_name', 'mother_name', 'present_village', 'present_psot',
                'present_thana', 'present_dist', 'permanent_village', 'parmaent_post',
                'permanent_thana', 'permanent_dist', 'sopuse_name', 'worker_class',
                'working_type', 'new_empno'
            ]));

            return response()->json([
                'success' => true,
                'message' => 'Bangla location updated successfully',
                'data' => $locationBangla
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating Bangla location',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete Bangla Location
     * DELETE: /api/deleteEmpLocationBangla/{empno}
     */
    public function deleteEmpLocationBangla($empno)
    {
        try {
            $locationBangla = EmpLocationBangla::where('empno', $empno)->firstOrFail();
            $locationBangla->delete();

            return response()->json([
                'success' => true,
                'message' => 'Bangla location deleted successfully'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting Bangla location',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * ════════════════════════════════════════════════════════════════════
     * COMBINED - GET BOTH ENGLISH AND BANGLA LOCATION
     * ════════════════════════════════════════════════════════════════════
     */

    /**
     * Get Both English and Bangla Location
     * GET: /api/getEmpLocationCombined/{empno}
     */
    public function getEmpLocationCombined($empno)
    {
        try {
            $locationEnglish = EmpLocation::where('empno', $empno)->first();
            $locationBangla = EmpLocationBangla::where('empno', $empno)->first();

            return response()->json([
                'success' => true,
                'data' => [
                    'english' => $locationEnglish,
                    'bangla' => $locationBangla
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching location information',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
