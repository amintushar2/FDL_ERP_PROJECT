<?php
namespace App\Http\Controllers\HrmSetup;
use App\Models\Hrm\DesignationDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DesignationController extends SetupBaseController
{
    protected string $model    = DesignationDetail::class;
    protected string $view     = 'hrm.setup.designation';
    protected string $pk       = 'des_id';
    protected array  $fillable = ['designation_name','in_short','grade_id','in_bengali','bns_amnt','night_bill'];
    protected array  $rules    = [
        'designation_name' => 'required|string|max:50',
        'in_short'         => 'nullable|string|max:15',
        'grade_id'         => 'nullable|string|max:15',
        'bns_amnt'         => 'nullable|numeric',
        'night_bill'       => 'nullable|numeric',
    ];

    public function index(Request $request)
    {
        $q = trim($request->input('q',''));
        $query = DB::table('DESIGNATION_DETAILS')->orderBy('DES_ID')
            ->select('DES_ID as des_id','DESIGNATION_NAME as designation_name','IN_SHORT as in_short','GRADE_ID as grade_id','BNS_AMNT as bns_amnt','NIGHT_BILL as night_bill','IN_BENGALI as in_bengali');
        if ($q) {
            $query->where(function($w) use ($q){
                $w->whereRaw("UPPER(DESIGNATION_NAME) LIKE UPPER(?)",["%$q%"])
                  ->orWhereRaw("UPPER(IN_SHORT) LIKE UPPER(?)",["%$q%"]);
            });
        }
        $records = $query->paginate(15)->withQueryString();
        return view($this->view, compact('records'));
    }
}
