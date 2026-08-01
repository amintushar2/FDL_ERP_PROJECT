<?php
namespace App\Http\Controllers\HrmSetup;
use App\Models\Hrm\ShiftInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShiftController extends SetupBaseController
{
    protected string $model    = ShiftInfo::class;
    protected string $view     = 'hrm.setup.shift';
    protected string $pk       = 'shift_code';
    protected array  $fillable = ['shift_code','shift_name','sin_time','sout_time','grace_period','grace_period_2','meal_time','ot_limit','ot_limit_3','is_active'];
    protected array  $rules    = [
        'shift_code' => 'required|string|max:10',
        'shift_name' => 'nullable|string|max:100',
        'is_active'  => 'nullable|in:Y,N',
        'ot_limit'   => 'nullable|numeric|min:0',
    ];

    public function index(Request $request)
    {
        $q = trim($request->input('q',''));
        $query = DB::table('SHIFT_INFO')->orderBy('SHIFT_CODE')
            ->select('SHIFT_CODE as shift_code','SHIFT_NAME as shift_name','SIN_TIME as sin_time','SOUT_TIME as sout_time','GRACE_PERIOD as grace_period','OT_LIMIT as ot_limit','IS_ACTIVE as is_active');
        if ($q) {
            $query->where(function($w) use ($q){
                $w->whereRaw("UPPER(SHIFT_CODE) LIKE UPPER(?)",["%$q%"])
                  ->orWhereRaw("UPPER(SHIFT_NAME) LIKE UPPER(?)",["%$q%"]);
            });
        }
        $records = $query->paginate(15)->withQueryString();
        return view($this->view, compact('records'));
    }
}
