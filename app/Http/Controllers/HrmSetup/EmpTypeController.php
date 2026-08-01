<?php
namespace App\Http\Controllers\HrmSetup;
use App\Models\Hrm\EmpType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmpTypeController extends SetupBaseController
{
    protected string $model    = EmpType::class;
    protected string $view     = 'hrm.setup.emp-type';
    protected string $pk       = 'emp_type';
    protected array  $fillable = ['emp_type','type_set','priority'];
    protected array  $rules    = [
        'emp_type' => 'required|string|max:30',
        'type_set' => 'nullable|string|max:30',
        'priority' => 'nullable|integer',
    ];

    public function index(Request $request)
    {
        $q = trim($request->input('q',''));
        $query = DB::table('EMP_TYPE')->orderBy('PRIORITY')
            ->select('EMP_TYPE as emp_type','TYPE_SET as type_set','PRIORITY as priority');
        if ($q) {
            $query->where(function($w) use ($q){
                $w->whereRaw("UPPER(EMP_TYPE) LIKE UPPER(?)",["%$q%"])
                  ->orWhereRaw("UPPER(TYPE_SET) LIKE UPPER(?)",["%$q%"]);
            });
        }
        $records = $query->paginate(15)->withQueryString();
        return view($this->view, compact('records'));
    }
}
