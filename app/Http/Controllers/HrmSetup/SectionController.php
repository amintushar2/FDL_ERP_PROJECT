<?php
namespace App\Http\Controllers\HrmSetup;
use App\Models\Hrm\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SectionController extends SetupBaseController
{
    protected string $model    = Section::class;
    protected string $view     = 'hrm.setup.section';
    protected string $pk       = 'section_no';
    protected array  $fillable = ['section_no','section_name','in_bengali','dept_no','dept_name','company_id','is_revenue','revenue_item_id'];
    protected array  $rules    = [
        'section_no'   => 'required|string|max:15',
        'section_name' => 'nullable|string|max:50',
        'dept_no'      => 'nullable|string|max:20',
        'company_id'   => 'nullable|string|max:30',
    ];

    public function index(Request $request)
    {
        $q = trim($request->input('q',''));
        $query = DB::table('SECTION as s')
            ->leftJoin('COMPANY_PROFILE as c','s.COMPANY_ID','=','c.COMPANY_ID')
            ->leftJoin('DEPT as d','s.DEPT_NO','=','d.DEPT_NO')
            ->select('s.SECTION_NO as section_no','s.SECTION_NAME as section_name','s.DEPT_NO as dept_no','d.DEPT_NAME as dept_name','s.COMPANY_ID as company_id','c.COMPANY_NAME as company_name','s.IS_REVENUE as is_revenue')
            ->orderBy('s.SECTION_NO');
        if ($q) {
            $query->where(function($w) use ($q) {
                $w->whereRaw("UPPER(s.SECTION_NO) LIKE UPPER(?)",["%$q%"])
                  ->orWhereRaw("UPPER(s.SECTION_NAME) LIKE UPPER(?)",["%$q%"])
                  ->orWhereRaw("UPPER(c.COMPANY_NAME) LIKE UPPER(?)",["%$q%"]);
            });
        }
        $records   = $query->paginate(15)->withQueryString();
        $depts     = DB::table('DEPT')->select('DEPT_NO as dept_no','DEPT_NAME as dept_name')->orderBy('DEPT_NAME')->get();
        $companies = DB::table('COMPANY_PROFILE')->select('COMPANY_ID as company_id','COMPANY_NAME as company_name')->orderBy('COMPANY_NAME')->get();
        return view($this->view, compact('records','depts','companies'));
    }

    public function edit($id)
    {
        $record = Section::where('section_no',$id)->firstOrFail();
        return response()->json(['success'=>true,'record'=>$record]);
    }
}
