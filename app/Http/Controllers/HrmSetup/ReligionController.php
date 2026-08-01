<?php
namespace App\Http\Controllers\HrmSetup;
use App\Models\Hrm\Religion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReligionController extends SetupBaseController
{
    protected string $model    = Religion::class;
    protected string $view     = 'hrm.setup.religion';
    protected string $pk       = 'religion_id';
    protected array  $fillable = ['religion_id','religion_name','is_active'];
    protected array  $rules    = [
        'religion_id'   => 'required|integer',
        'religion_name' => 'required|string|max:50',
        'is_active'     => 'nullable|in:Y,N',
    ];

    public function index(Request $request)
    {
        $q = trim($request->input('q',''));
        $query = DB::table('RELIGION')->orderBy('RELIGION_ID')
            ->select('RELIGION_ID as religion_id','RELIGION_NAME as religion_name');
        if ($q) {
            $query->whereRaw("UPPER(RELIGION_NAME) LIKE UPPER(?)",["%$q%"]);
        }
        $records = $query->paginate(15)->withQueryString();
        return view($this->view, compact('records'));
    }
}
