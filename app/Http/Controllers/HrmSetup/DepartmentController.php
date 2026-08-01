<?php
namespace App\Http\Controllers\HrmSetup;
use App\Models\Hrm\Dept;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DepartmentController extends SetupBaseController
{
    protected string $model    = Dept::class;
    protected string $view     = 'hrm.setup.department';
    protected string $pk       = 'dept_no';
    protected array  $fillable = ['dept_no','dept_name','in_short','c_name','in_bengali','company_id'];
    protected array  $rules    = [
        'dept_no'    => 'required|string|max:15',
        'dept_name'  => 'nullable|string|max:50',
        'in_short'   => 'nullable|string|max:10',
        'company_id' => 'nullable|string|max:30',
    ];

    public function index(Request $request)
    {
        $q = trim($request->input('q',''));
        $query = DB::table('DEPT as d')
            ->leftJoin('COMPANY_PROFILE as c','d.COMPANY_ID','=','c.COMPANY_ID')
            ->select('d.DEPT_NO as dept_no','d.DEPT_NAME as dept_name','d.IN_SHORT as in_short','d.COMPANY_ID as company_id','c.COMPANY_NAME as company_name')
            ->orderBy('d.DEPT_NO');
        if ($q) {
            $query->where(function($w) use ($q) {
                $w->whereRaw("UPPER(d.DEPT_NO) LIKE UPPER(?)",["%$q%"])
                  ->orWhereRaw("UPPER(d.DEPT_NAME) LIKE UPPER(?)",["%$q%"])
                  ->orWhereRaw("UPPER(c.COMPANY_NAME) LIKE UPPER(?)",["%$q%"]);
            });
        }
        $records   = $query->paginate(15)->withQueryString();
        $companies = DB::table('COMPANY_PROFILE')->select('COMPANY_ID as company_id','COMPANY_NAME as company_name')->orderBy('COMPANY_NAME')->get();
        return view($this->view, compact('records','companies'));
    }

    public function edit($id)
    {
        $record = Dept::where('dept_no',$id)->firstOrFail();
        return response()->json(['success'=>true,'record'=>$record]);
    }
}
