<?php
namespace App\Http\Controllers\HrmSetup;
use App\Models\Hrm\Floor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FloorController extends SetupBaseController
{
    protected string $model    = Floor::class;
    protected string $view     = 'hrm.setup.floor';
    protected string $pk       = 'floor_id';
    protected array  $fillable = ['floor_id','floor_desc','floor_loc','company_id'];
    protected array  $rules    = [
        'floor_id'   => 'required|string|max:10',
        'floor_desc' => 'nullable|string|max:50',
        'floor_loc'  => 'nullable|string|max:100',
        'company_id' => 'nullable|string|max:10',
    ];

    public function index(Request $request)
    {
        $q = trim($request->input('q',''));
        $query = DB::table('FLOOR as f')
            ->leftJoin('COMPANY_PROFILE as c','f.COMPANY_ID','=','c.COMPANY_ID')
            ->select('f.FLOOR_ID as floor_id','f.FLOOR_DESC as floor_desc','f.FLOOR_LOC as floor_loc','f.COMPANY_ID as company_id','c.COMPANY_NAME as company_name')
            ->orderBy('f.FLOOR_ID');
        if ($q) {
            $query->where(function($w) use ($q){
                $w->whereRaw("UPPER(f.FLOOR_ID) LIKE UPPER(?)",["%$q%"])
                  ->orWhereRaw("UPPER(f.FLOOR_DESC) LIKE UPPER(?)",["%$q%"])
                  ->orWhereRaw("UPPER(c.COMPANY_NAME) LIKE UPPER(?)",["%$q%"]);
            });
        }
        $records   = $query->paginate(15)->withQueryString();
        $companies = DB::table('COMPANY_PROFILE')->select('COMPANY_ID as company_id','COMPANY_NAME as company_name')->orderBy('COMPANY_NAME')->get();
        return view($this->view, compact('records','companies'));
    }

    public function edit($id)
    {
        $record = Floor::where('floor_id',$id)->firstOrFail();
        return response()->json(['success'=>true,'record'=>$record]);
    }
}
