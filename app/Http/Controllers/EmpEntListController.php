<?php

namespace App\Http\Controllers;
use DB;
use Illuminate\Http\Request;
use App\Models\DepartmentModel;



class EmpEntListController extends Controller
{
    public function deptList($id){
        $deptList=DB::table('DEPT')
        ->where('company_id','=',$id)
        ->orderBy('DEPT_NO','asc')

      //  ->pluck('dept_no','dept_name');
        ->get();

        return response()->json($deptList);

    

    }
public function floorList($id){

    $floorList=DB::table('FLOOR')
    ->orderBy('Floor_id','asc')
    ->where('company_id','=',$id)

  //  ->pluck('dept_no','dept_name');
    ->get();
    return response()->json($floorList);

}
    public function lineInfo(){
   

        $LineInfo=DB::table('LINE_INFO')
        ->orderBy('LINE','asc')
      //  ->pluck('dept_no','dept_name');
        ->get();

    }



    public function getEmp(){
    $emp=DB::table('EMP_PERSONAL')
    ->select(DB::raw("TO_CHAR(DOB, 'DD/MM/RRRR') as DOB"))
    ->get();
    return response()->json($emp);

    }

    public function demo(){
      return view('demo');
    }

    public function getEMPDT(){
      $df=DB::table(DB::raw('EMP_PERSONAL EP'))
      ->select('EP.EMPNO',DB::raw('EP.FIRST_NAME||\' \'||EP.MIDDLE_NAME||\' \'||EP.LAST_NAME  EMPNAME'),'EP.FATHER_NAME','EP.MOTHER_NAME','EP.HUSBAND_NAME','EP.STATUS','EP.EMP_PIC','EO.COMPANY_ID','EO.SECTION_NO','EO.DESIGNATION_NAME','EO.JOINING_DATE','EO.GROSS')
      ->crossJoin(DB::raw('EMP_OFFICIAL EO'))
      ->whereRaw('EP.empno = EO.empno')
      ->where([['STATUS','Active']])

      ->get();

      dd($df);
    }

    public function getEdu($id){





     $eduList= DB::table('HRM.EMP_QUALIFICATION')
     ->select('EMPNO','NAME_OF_INS','PASSED_EXAM','DIVISION','YEAR','BOARD','MARKS','SUBJECT')
     ->where('EMPNO','=',$id)
     ->get();

     
//      DB::table('HRM.EMP_LOCATION')
// ->select('EMPNO','P_ADDRESS','P_CITY','P_DISTRICT','P_PIN_CODE','P_PHONE','P_FAX','P_CPERSON','R_ADDRESS','R_CITY','R_DISTRICT','R_PIN_CODE','R_PHONE','R_FAX','R_MOBILE','R_EMAIL','R_CPERSON','P_VILLAGE','P_POST_OFF','P_POLICE_STATION')
// ->where('EMPNO','=',$id)
// ->get();

return view('hrm.empentry.empedulist',['eduList'=>$eduList]);

    }

    public function getCourse($id){
      $courseList=DB::table('HRM.EMP_SHORT_COURSE')
      ->select('C_FROM','C_TO','CERTIFICATE','CONDUCTED_BY','COURSE_NAME','EMPNO','TOTAL_DAY')
      ->where('EMPNO','=',$id)
      ->get();
      return view('hrm.empentry.empcourselist',['courseList'=>$courseList]);

    }
    public function getNome($id){
      $famList=DB::table('HRM.EMP_FAMILY')
      ->select('ADDRESS','ADDRESS_BN','D_AGE','D_AS_ON','D_DOB','D_SEX','DEPD_NAME','DEPD_NO','DEPENT_NAME_BANGLA','EMPNO','PERCENTAGE','RELATION_BN','RELATIONSHIP')
      ->where('EMPNO','=',$id)
      ->get();
     // dd($famList);
      return view('hrm.empentry.empnominee',['famList'=>$famList]);

    }
    public function getJob($id){
      $jobList=DB::table('HRM.EMP_HISTRORY')
      ->select('DESIGNATION','EMPNO','JOIN_AS','JOIN_DATE','WORK_LOCATION')
      ->where('EMPNO','=',$id)
      ->get();
     // dd($famList);
      return view('hrm.empentry.empjoblist',['jobList'=>$jobList]);

    }
    

    public function getTrain($id){
      $trainList=DB::table('HRM.EMP_TRAINING')
      ->select('EMPNO','SKILL_TYPE','T_CERTIFICATE','T_CONDUCTED_BY','T_FROM','T_TITLE','T_TO','TO_DAYS')
      ->where('EMPNO','=',$id)
      ->get();
     // dd($famList);
      return view('hrm.empentry.emptrainlist',['trainList'=>$trainList]);

    }


    public function empExper($id){
      $empExpe=DB::table('HRM.EMP_WORK_EXP')
      ->select('D_FROM','D_TO','DESIGNATION','EMPNO','LAST_SAL_DRAWN','LEAVE_REASON','ORG_ADDRESS','ORG_TEL','ORGANIZATION','PRV_EMP_NO','TOTAL_DAYS')
      ->where('Empno','=',$id)
      ->get();
     // dd($famList);
      return view('hrm.empentry.empExplist',['empExpe'=>$empExpe]);

    }
    public function companypf(){


  
    }


}
