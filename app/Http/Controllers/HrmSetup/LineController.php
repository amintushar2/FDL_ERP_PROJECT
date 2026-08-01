<?php
namespace App\Http\Controllers\HrmSetup;
use App\Models\Hrm\LineInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LineController extends SetupBaseController
{
    protected string $model    = LineInfo::class;
    protected string $view     = 'hrm.setup.line';
    protected string $pk       = 'line_no';
    protected array  $fillable = ['line_no','line','line_in_bangla','l_group'];
    protected array  $rules    = [
        'line_no' => 'required|string|max:20',
        'line'    => 'nullable|string|max:20',
    ];

    public function index(Request $request)
    {
        $q = trim($request->input('q',''));
        $query = DB::table('LINE_INFO')->orderBy('LINE_NO')
            ->select('LINE_NO as line_no','LINE as line','LINE_IN_BANGLA as line_in_bangla','L_GROUP as l_group');
        if ($q) {
            $query->where(function($w) use ($q){
                $w->whereRaw("UPPER(LINE_NO) LIKE UPPER(?)",["%$q%"])
                  ->orWhereRaw("UPPER(LINE) LIKE UPPER(?)",["%$q%"])
                  ->orWhereRaw("UPPER(L_GROUP) LIKE UPPER(?)",["%$q%"]);
            });
        }
        $records = $query->paginate(15)->withQueryString();
        return view($this->view, compact('records'));
    }
}
