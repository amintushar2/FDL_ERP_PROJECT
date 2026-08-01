<?php
namespace App\Http\Controllers\HrmSetup;
use App\Models\Hrm\EntrySystem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EntrySystemController extends SetupBaseController
{
    protected string $model    = EntrySystem::class;
    protected string $view     = 'hrm.setup.entry-system';
    protected string $pk       = 'id';
    protected array  $fillable = ['entry_name','entry_code','is_active'];
    protected array  $rules    = [
        'entry_name' => 'required|string|max:100',
        'entry_code' => 'required|string|max:20',
        'is_active'  => 'nullable|in:Y,N',
    ];

    public function index(Request $request)
    {
        $q = trim($request->input('q',''));
        $query = DB::table('ENTRY_SYSTEM')->orderBy('ID')
            ->select('ID as id','ENTRY_NAME as entry_name','ENTRY_CODE as entry_code','IS_ACTIVE as is_active');
        if ($q) {
            $query->where(function($w) use ($q){
                $w->whereRaw("UPPER(ENTRY_NAME) LIKE UPPER(?)",["%$q%"])
                  ->orWhereRaw("UPPER(ENTRY_CODE) LIKE UPPER(?)",["%$q%"]);
            });
        }
        $records = $query->paginate(15)->withQueryString();
        return view($this->view, compact('records'));
    }
}
