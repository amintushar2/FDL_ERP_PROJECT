<?php

namespace App\Http\Controllers;

use App\Models\CityModel;
use App\Models\CompanyProfile;
use App\Models\DepartmentModel;
use App\Models\DesignationModel;
use App\Models\DistrictModel;
use App\Models\LineModel;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class HrmSetupController extends Controller
{
    //desgination
    public function designation()
    {
        $uri = Route::getFacadeRoot()->current()->uri();

        $getdes = DB::table('DESIGNATION_DETAILS')
            ->select('DES_ID', 'DESIGNATION_NAME', 'IN_SHORT', 'IN_BENGALI')
            ->get();

        $getCompany = DB::table('COMPANY_PROFILE')
            ->select('COMPANY_ID', 'COMPANY_NAME')
            ->get();
        $uri = Route::getFacadeRoot()->current()->uri();

        $data = DB::table('ALL_USER_INFO')
            ->select('USER_ID', 'EMPLOYEE_ID', 'USER_GROUP_ID', 'INITIAL_PASSWORD', 'COMPANY_ID', 'USER_MOBILE',
                DB::raw('"GET_EMP_NAME"(EMPLOYEE_ID) as EMPLOYEE_NAME'))
            ->where('EMPLOYEE_ID', '=', session('LoggedUser'))
            ->get();
        $leftmenu = DB::table('ALL_USER_GROUP_DETAILS')
            ->crossJoin('ALL_MENU_HIERARCHY')
            ->select('MENU_ITEM_ID', 'USER_GROUP_ID', 'TITLE', 'DESCRIPTION')
            ->where('ALL_USER_GROUP_DETAILS.ENABLED', '=', 'Y')
            ->where('ALL_MENU_HIERARCHY.CHILD_ID', '=', DB::raw('ALL_USER_GROUP_DETAILS.MENU_ITEM_ID'))
            ->get();
        $submenu = DB::table('ALL_USER_SUB_DETAILS')
            ->select('MENU_ITEM_ID', 'USER_GROUP_ID', 'SUB_MENU_ID', 'SUB_MENU_1', 'SUB_MENU_2', 'SUB_MENU_NAME', 'ROUTE')
            ->whereNull('SUB_MENU_2')

            ->get();
        $headeer = DB::table('ALL_USER_SUB_DETAILS')
            ->select('MENU_ITEM_ID', 'USER_GROUP_ID', 'SUB_MENU_ID', 'SUB_MENU_1', 'SUB_MENU_2', 'SUB_MENU_NAME', 'ROUTE')
            ->where('ROUTE', '=', $uri)
            ->get();
        $empnoList = DB::table('EMP_PERSONAL')
            ->select('EMPNO')
            ->where('status', '=', 'Active')
            ->orderBy('EMPNO', 'asc')

            ->get();
        $submenu2 = DB::table('ALL_USER_SUB_DETAILS')
            ->select('MENU_ITEM_ID', 'USER_GROUP_ID', 'SUB_MENU_ID', 'SUB_MENU_1', 'SUB_MENU_2', 'SUB_MENU_NAME', 'ROUTE')
            ->whereNotNull('SUB_MENU_2')
            ->get();
        //return json_encode($deptList);
        //        dd($ll)

        return view('hrm.hrmsetup.designation', ['desig' => $getdes, 'data' => $data, 'getCompany' => $getCompany, 'menu' => $leftmenu, 'submenu' => $submenu, 'headeer' => $headeer, 'submenu2' => $submenu2, 'empnoList' => $empnoList]);
    }
    public function insertDesignation(Request $request)
    {
        $getdesId = DB::table('DESIGNATION_DETAILS')
            ->select(DB::raw('NVL(MAX(DES_ID),0)+1 DES_ID'))
            ->first();
        $des_id = $getdesId->des_id;
        try {
            $desig = new DesignationModel();
            $desig->des_id = $des_id;
            $desig->designation_name = $request->input('designation_name_new');
            $desig->in_short = $request->input('in_short_new');
            $desig->in_bengali = $request->input('in_bengali_new');
            $desig->save();

            return response()->json([
                'status2' => 200,
                $request->all()]);
        }  catch (\Illuminate\Database\QueryException $e) {
            dd($e->getCode());

        }

    }

    public function destroydesig($des_id)
    {
        $destryfind = DB::table('DESIGNATION_DETAILS')
            ->select('DES_ID', 'DESIGNATION_NAME', 'IN_SHORT', 'IN_BENGALI')
            ->where('DES_ID', '=', $des_id)
            ->first();
           // dd($destryfind);

        if (!optional($destryfind)->des_id == null) {
           // dd($destryfind->des_id );
            try {
                $destroy = DB::table('DESIGNATION_DETAILS')
                    ->where('des_id', '=', $des_id)
                    ->delete();
                    return response()->json([
                        'status2' => 200]);
            } catch (\Illuminate\Database\QueryException $e) {
                return response()->json($e->getCode());

            }
        }
    }
    public function editdes(Request $request)
    {$data = $request->input();
        try {
            if ($request->ajax()) {
                $desig = DesignationModel::where('des_id', '=', $data['des_id_up']);
                $desig->update([
                    'designation_name' => $data['designation_name_up'],
                    'in_short' => $data['in_short_up'],
                    'in_bengali' => $data['in_bengali_up'],
                ]);

                return response()->json([
                    'status2' => 200,
                    $request->input()]);
            }

        } catch (\Illuminate\Database\QueryException $e) {
            dd($e);

        }

    }
    //    Department
    public function department()
    {
        $getDEPT = DB::table('DEPT')
            ->select('DEPT_NO', 'DEPT_NAME', 'IN_BENGALI', 'IN_SHORT', 'C_NAME', 'COMPANY_ID')
            ->get();

        $uri = Route::getFacadeRoot()->current()->uri();

        $getCompany = DB::table('COMPANY_PROFILE')
            ->select('COMPANY_ID', 'COMPANY_NAME')
            ->get();
        $uri = Route::getFacadeRoot()->current()->uri();

        $data = DB::table('ALL_USER_INFO')
            ->select('USER_ID', 'EMPLOYEE_ID', 'USER_GROUP_ID', 'INITIAL_PASSWORD', 'COMPANY_ID', 'USER_MOBILE',
                DB::raw('"GET_EMP_NAME"(EMPLOYEE_ID) as EMPLOYEE_NAME'))
            ->where('EMPLOYEE_ID', '=', session('LoggedUser'))
            ->get();
        $leftmenu = DB::table('ALL_USER_GROUP_DETAILS')
            ->crossJoin('ALL_MENU_HIERARCHY')
            ->select('MENU_ITEM_ID', 'USER_GROUP_ID', 'TITLE', 'DESCRIPTION')
            ->where('ALL_USER_GROUP_DETAILS.ENABLED', '=', 'Y')
            ->where('ALL_MENU_HIERARCHY.CHILD_ID', '=', DB::raw('ALL_USER_GROUP_DETAILS.MENU_ITEM_ID'))
            ->get();
        $submenu = DB::table('ALL_USER_SUB_DETAILS')
            ->select('MENU_ITEM_ID', 'USER_GROUP_ID', 'SUB_MENU_ID', 'SUB_MENU_1', 'SUB_MENU_2', 'SUB_MENU_NAME', 'ROUTE')
            ->whereNull('SUB_MENU_2')

            ->get();
        $headeer = DB::table('ALL_USER_SUB_DETAILS')
            ->select('MENU_ITEM_ID', 'USER_GROUP_ID', 'SUB_MENU_ID', 'SUB_MENU_1', 'SUB_MENU_2', 'SUB_MENU_NAME', 'ROUTE')
            ->where('ROUTE', '=', $uri)
            ->get();
        $empnoList = DB::table('EMP_PERSONAL')
            ->select('EMPNO')
            ->where('status', '=', 'Active')
            ->orderBy('EMPNO', 'asc')

            ->get();
        $submenu2 = DB::table('ALL_USER_SUB_DETAILS')
            ->select('MENU_ITEM_ID', 'USER_GROUP_ID', 'SUB_MENU_ID', 'SUB_MENU_1', 'SUB_MENU_2', 'SUB_MENU_NAME', 'ROUTE')
            ->whereNotNull('SUB_MENU_2')
            ->get();
        //return json_encode($deptList);
        //        dd($ll)

        return view('hrm.hrmsetup.department', ['dept' => $getDEPT, 'data' => $data, 'getCompany' => $getCompany, 'menu' => $leftmenu, 'submenu' => $submenu, 'headeer' => $headeer, 'submenu2' => $submenu2, 'empnoList' => $empnoList]);
    }
    public function savedata(Request $request)
    {

        $getdeptId = DB::table('DEPT')
            ->select(DB::raw('\'D-\'||LPAD(NVL(MAX(SUBSTR(DEPT_NO,3)),0)+1,3,0) DEPT_CODE'))
            ->first();
        $dept_id = $getdeptId->dept_code;
        try {
            $department = new DepartmentModel();
            $department->dept_no = $dept_id;
            $department->dept_name = $request->input('dept_insert_name');

            $department->in_bengali = $request->input('in_bangali_insert');

            $department->in_short = $request->input('in_short_insert');

            $department->company_id = $request->input('company_id_insert');
            $department->save();

            return response()->json([
                'status2' => 200,
                $request->all()]);

        } catch (Exception $e) {
            return redirect('/dept')->with('failed', "operation failed");
        }
    }

    public function destroyDept($department)
    {
        $destryfind = DB::table('DEPT')
            ->select('DEPT_NO', 'DEPT_NAME', 'IN_BENGALI', 'IN_SHORT', 'C_NAME', 'COMPANY_ID')
            ->where('DEPT_NO', '=', $department)
            ->first();
        if (!optional($destryfind)->dept_no == null) {
            try{
            $destroy = DB::table('DEPT')
                ->where('dept_no', '=', $department)
                ->delete();
                return response()->json([
                    'status2' => 200,
                    ]);

                } catch (\Illuminate\Database\QueryException $e) {
                    return response()->json($e->getCode());
    
                }
        }

    }

    public function editdept(Request $request)
    {$data = $request->input();

        try {

            $depte = DepartmentModel::where('dept_no', '=', $data['dept_update_no']);
            $depte->update([
                'dept_name' => $data['dept_change_name'],
                'in_bengali' => $data['in_bangali_up'],
                'in_short' => $data['in_short_up'],
            ]);

            return response()->json([
                'status2' => 200]);

        } catch (\Illuminate\Database\QueryException $e) {
            dd($e);

        }

    }

    // Address
    public function address()
    {
        $getCity = DB::table('CITY')
            ->select('CITY', 'CITY_ID')
            ->get();
        $getdistrict = DB::table('DISTRICT')
            ->select('DISTRICT', 'DISTRICT_ID')
            ->get();

        return view('hrm.hrmsetup.address', ['district' => $getdistrict, 'city' => $getCity]);

    }

    public function insertcity(Request $request)
    {
        try {
            $city = new CityModel();
            $city->city = $request->input('city');
            $city->city_id = $request->input('city_id');
            $city->save();

            return response()->json([
                'status2' => 200]);

        } catch (Exception $e) {
            return redirect('/address')->with('failed', "operation failed");
        }
    }

    public function insertdistrict(Request $request)
    {
        try {
            $district = new DistrictModel();
            $district->district = $request->input('district');
            $district->district_id = $request->input('district_id');

            $district->save();

            return response()->json([
                'status2' => 200]);

        } catch (Exception $e) {
            return redirect('/address')->with('failed', "operation failed");
        }
    }

    public function destroyCity($city)
    {
        $destroyFind = DB::table('CITY')
            ->select('CITY', 'CITY_ID')
            ->where('CITY', '=', $city)
            ->first();

        if (!optional($destroyFind)->city == null) {
            try {
                $destroy = DB::table('CITY')
                    ->where('city', '=', $city)
                    ->delete();
                return redirect('/address')->with('delet', "Deleted Successfully");

            } catch (\Illuminate\Database\QueryException $e) {
                dd($e->getCode());

            }
        }

    }

    public function destroyDistrict($district)
    {
        $destroyFind = DB::table('DISTRICT')
            ->select('DISTRICT')
            ->where('DISTRICT', '=', $district)
            ->first();
        if (!optional($destroyFind)->district == null) {
            try {
                $destroy = DB::table('DISTRICT')
                    ->where('district', '=', $district)
                    ->delete();
                return redirect('/address')->with('delet', "Deleted Successfully");

            } catch (\Illuminate\Database\QueryException $e) {
                dd($e->getCode());

            }
        }

    }
    public function editcity(Request $request)
    {$data = $request->input();

        try {
            if ($request->ajax()) {
                $citych = CityModel::where('city_id', '=', $data['city_update_id']);
                $citych->update([
                    'city' => $data['city_change'],
                ]);

                return response()->json([
                    'status2' => 200]);
            }

        } catch (\Illuminate\Database\QueryException $e) {
            dd($e);

        }

    }

    public function editdistrict(Request $request)
    {$data = $request->input();

        try {
            if ($request->ajax()) {
                $dist = DistrictModel::where('district_id', '=', $data['district_update_id']);
                $dist->update([
                    'district' => $data['district_change'],
                ]);

                return response()->json([
                    'status2' => 200]);
            }

        } catch (\Illuminate\Database\QueryException $e) {
            dd($e);

        }

    }

    // line
    public function Line()
    {

        $getline = DB::table('LINE_INFO')
            ->select('LINE_NO', 'LINE', 'LINE_IN_BANGLA', 'L_GROUP')
            ->get();
        $getCompany = DB::table('COMPANY_PROFILE')
            ->select('COMPANY_ID', 'COMPANY_NAME')
            ->get();

        $uri = Route::getFacadeRoot()->current()->uri();

        $data = DB::table('ALL_USER_INFO')
            ->select('USER_ID', 'EMPLOYEE_ID', 'USER_GROUP_ID', 'INITIAL_PASSWORD', 'COMPANY_ID', 'USER_MOBILE',
                DB::raw('"GET_EMP_NAME"(EMPLOYEE_ID) as EMPLOYEE_NAME'))
            ->where('EMPLOYEE_ID', '=', session('LoggedUser'))
            ->get();
        $leftmenu = DB::table('ALL_USER_GROUP_DETAILS')
            ->crossJoin('ALL_MENU_HIERARCHY')
            ->select('MENU_ITEM_ID', 'USER_GROUP_ID', 'TITLE', 'DESCRIPTION')
            ->where('ALL_USER_GROUP_DETAILS.ENABLED', '=', 'Y')
            ->where('ALL_MENU_HIERARCHY.CHILD_ID', '=', DB::raw('ALL_USER_GROUP_DETAILS.MENU_ITEM_ID'))
            ->get();
        $submenu = DB::table('ALL_USER_SUB_DETAILS')
            ->select('MENU_ITEM_ID', 'USER_GROUP_ID', 'SUB_MENU_ID', 'SUB_MENU_1', 'SUB_MENU_2', 'SUB_MENU_NAME', 'ROUTE')
            ->whereNull('SUB_MENU_2')

            ->get();
        $headeer = DB::table('ALL_USER_SUB_DETAILS')
            ->select('MENU_ITEM_ID', 'USER_GROUP_ID', 'SUB_MENU_ID', 'SUB_MENU_1', 'SUB_MENU_2', 'SUB_MENU_NAME', 'ROUTE')
            ->where('ROUTE', '=', $uri)
            ->get();
        $empnoList = DB::table('EMP_PERSONAL')
            ->select('EMPNO')
            ->where('status', '=', 'Active')
            ->orderBy('EMPNO', 'asc')

            ->get();
        $submenu2 = DB::table('ALL_USER_SUB_DETAILS')
            ->select('MENU_ITEM_ID', 'USER_GROUP_ID', 'SUB_MENU_ID', 'SUB_MENU_1', 'SUB_MENU_2', 'SUB_MENU_NAME', 'ROUTE')
            ->whereNotNull('SUB_MENU_2')
            ->get();
        //return json_encode($deptList);
        //        dd($ll)

        return view('hrm.hrmsetup.lineinfo', ['line' => $getline, 'data' => $data, 'getCompany' => $getCompany, 'menu' => $leftmenu, 'submenu' => $submenu, 'headeer' => $headeer, 'submenu2' => $submenu2, 'empnoList' => $empnoList]);
    }
    public function insertLine(Request $request)
    {
        //return response()->json([$data]);
        $data = $request->input();

        $getlineId = DB::table('LINE_INFO')
            ->select(DB::raw('NVL(MAX(TO_NUMBER(LINE_NO)),0)+1 LINE_NO'))
            ->first();

        $line_no = $getlineId->line_no;
        try {

            $line = new LineModel();

            $line->line_no = $line_no;
            $line->line = $data['line_new'];

            $line->line_in_bangla = $data['line_in_bangla_new'];

            // $line->l_group=$request->input('l_group_new');
            $line->save();

            return response()->json([
                'status2' => 200,
                $request->all()]);
        } catch (\Illuminate\Database\QueryException $e) {
            dd($e);

        }
    }
    public function destroyline($line)
    {

        $destryfind = DB::table('LINE_INFO')
            ->select('LINE_NO', 'LINE', 'LINE_IN_BANGLA', 'L_GROUP')
            ->where('LINE_NO', '=', $line)
            ->first();
        if (!optional($destryfind)->line_no == null) {
            try {
                $destroy = DB::table('LINE_INFO')
                    ->where('line_no', '=', $line)
                    ->delete();
                return redirect('/line')->with('deletef', "Deleted Successfully");

            } catch (\Illuminate\Database\QueryException $e) {
                dd($e->getCode());

            }
        }
    }

    public function editline(Request $request)
    {
        $data = $request->input();

        try {
            $lns = LineModel::where('line_no', '=', $data['line_no_up']);

            // dd($lns);
            $lns->update([
                'line' => $data['line_up'],
                'line_in_bangla' => $data['line_in_bengali_up'],
                'l_group' => $data['l_group_up'],
            ]);

            return response()->json([
                'status2' => 200,
            ]);

        } catch (\Illuminate\Database\QueryException $e) {
            dd($e);

        }

    }

    public function companyDetails(Request $request)
    {
        $data = $request->input();
        $getCompanyDetails = DB::table('COMPANY')
            ->where('company_id', '=', $data['id'])
            ->get();
        return response()->json($getCompanyDetails);
    }

    public function companypf()
    {

        $getCompanyDetails = DB::table('COMPANY')
            ->get();

        // dd($getCompanyDetails);
        $getCompany = DB::table('COMPANY')
            ->select('COMPANY_ID', 'COMPANY_NAME')
            ->get();

        $uri = Route::getFacadeRoot()->current()->uri();

        $data = DB::table('ALL_USER_INFO')
            ->select('USER_ID', 'EMPLOYEE_ID', 'USER_GROUP_ID', 'INITIAL_PASSWORD', 'COMPANY_ID', 'USER_MOBILE',
                DB::raw('"GET_EMP_NAME"(EMPLOYEE_ID) as EMPLOYEE_NAME'))
            ->where('EMPLOYEE_ID', '=', session('LoggedUser'))
            ->get();
        $leftmenu = DB::table('ALL_USER_GROUP_DETAILS')
            ->crossJoin('ALL_MENU_HIERARCHY')
            ->select('MENU_ITEM_ID', 'USER_GROUP_ID', 'TITLE', 'DESCRIPTION')
            ->where('ALL_USER_GROUP_DETAILS.ENABLED', '=', 'Y')
            ->where('ALL_MENU_HIERARCHY.CHILD_ID', '=', DB::raw('ALL_USER_GROUP_DETAILS.MENU_ITEM_ID'))
            ->get();
        $submenu = DB::table('ALL_USER_SUB_DETAILS')
            ->select('MENU_ITEM_ID', 'USER_GROUP_ID', 'SUB_MENU_ID', 'SUB_MENU_1', 'SUB_MENU_2', 'SUB_MENU_NAME', 'ROUTE')
            ->whereNull('SUB_MENU_2')

            ->get();
        $headeer = DB::table('ALL_USER_SUB_DETAILS')
            ->select('MENU_ITEM_ID', 'USER_GROUP_ID', 'SUB_MENU_ID', 'SUB_MENU_1', 'SUB_MENU_2', 'SUB_MENU_NAME', 'ROUTE')
            ->where('ROUTE', '=', $uri)
            ->get();
        $empnoList = DB::table('EMP_PERSONAL')
            ->select('EMPNO')
            ->where('status', '=', 'Active')
            ->orderBy('EMPNO', 'asc')

            ->get();
        $submenu2 = DB::table('ALL_USER_SUB_DETAILS')
            ->select('MENU_ITEM_ID', 'USER_GROUP_ID', 'SUB_MENU_ID', 'SUB_MENU_1', 'SUB_MENU_2', 'SUB_MENU_NAME', 'ROUTE')
            ->whereNotNull('SUB_MENU_2')
            ->get();
        //return json_encode($deptList);
        //        dd($ll)

        return view('hrm.hrmsetup.company', ['getCompanyDetails' => $getCompanyDetails, 'data' => $data, 'getCompany' => $getCompany, 'menu' => $leftmenu, 'submenu' => $submenu, 'headeer' => $headeer, 'submenu2' => $submenu2, 'empnoList' => $empnoList]);

    }
    public function companyInsert(Request $request)
    {
        $data = $request->input();
        // dd($data);

        if (!session('LoggedUser') == null) {
            if($request->file('logo')==null){
                $imagename="";   
            }else{
                $imagename ='com_logo/'. $data['company_id'] . '.' . $request->file('logo')->getClientOriginalExtension();
                $request->file('logo')->storeAs('com_logo', $imagename);
            }
            try {
                $companyInsert = new CompanyProfile;
                $companyInsert->company_id = $data['company_id'];
                $companyInsert->company_name = $data['company_name'];
                $companyInsert->in_bengali = $data['in_bengali'];
                $companyInsert->address = $data['address'];
                $companyInsert->address_bangla = $data['address_in_bengali'];
                $companyInsert->tel = $data['tel'];
                $companyInsert->fax = $data['fax'];
                $companyInsert->email = $data['email'];
                $companyInsert->logo_location = $imagename;
                $companyInsert->save();

            } catch (\Illuminate\Database\QueryException $e) {
                dd($e);

            }
            return response()->json([
                'status2' => 200,
            ]);

        } else {
            return redirect('login');
        }

    }


    public function companyUpdate(Request $request)
{


    $data = $request->input();
    //return response()->json($data);

    $getCompanyDetails = DB::table('COMPANY')
    ->where('company_id','=',$data['company_id'])
    ->first();


    //return response()->json($getCompanyDetails->logo_location);

        if(!optional($getCompanyDetails)->company_id==null){
      
    


            try {
                if($request->file('logo')==null){
                    $imagename="";   
                }else{
                    $imagename ='com_logo/'. $data['company_id'] . '.' . $request->file('logo')->getClientOriginalExtension();
                    $request->file('logo')->storeAs('com_logo', $imagename);
                }
             

               
                    $dist = CompanyProfile::where('company_id', '=', $data['company_id']);
                    $dist->update([
                        'company_name' => $data['company_name'],
                        'logo_location' => 'com_logo/'.$imagename,
                    ]);
    
                    return response()->json([
                        'status2' => 200]);
                
    
            } catch (\Illuminate\Database\QueryException $e) {
                dd($e);
    
            }
            return response()->json('ss');
    
    
       }else{
        // return redirect('upload')->with('deletef',"operation failed");
        return response()->json('e2');

       }}

     //company profile delete
    public function destroyprof($comp_id)
    {

        $destryfind = DB::table('COMPANY')
            ->select('COMPANY_ID', 'COMPANY_NAME', 'IN_BENGALI', 'ADDRESS','ADDRESS_BANGLA')
            ->where('COMPANY_ID', '=', $comp_id)
            ->first();
            // dd($destryfind);
            //$countCom=count($destryfind);
           // dd($countCom);
        if (!optional($destryfind)->company_id == null) {
         //   dd($destryfind->company_id);
            try {
                $destroy = DB::table('COMPANY')
                    ->where('company_id', '=', $comp_id)
                    ->delete();

                    return response()->json([
                        'status2' => 200,
                    ]);
            } catch (\Illuminate\Database\QueryException $e) {
              //  dd($e);
                return response()->json([
                    $e->getCode(),
                ]);

            }
        }
    }
   

    }