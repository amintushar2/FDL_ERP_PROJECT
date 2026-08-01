<?php
namespace App\Http\Controllers\HrmSetup;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

abstract class SetupBaseController extends Controller
{
    protected string $model;
    protected string $view;
    protected string $pk       = 'id';
    protected array  $fillable = [];
    protected array  $rules    = [];

    public function edit($id)
    {
        $record = $this->model::where($this->pk, $id)->firstOrFail();
        return response()->json(['success' => true, 'record' => $record]);
    }

    public function store(Request $request)
    {
        $v = Validator::make($request->all(), $this->rules);
        if ($v->fails()) return response()->json(['success'=>false,'message'=>$v->errors()->first(),'errors'=>$v->errors()], 422);
        try {
            $record = $this->model::create($request->only($this->fillable));
            return response()->json(['success'=>true,'message'=>'Record saved successfully.','record'=>$record], 201);
        } catch (\Exception $e) {
            return response()->json(['success'=>false,'message'=>$e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $rules = collect($this->rules)->mapWithKeys(function($rule,$field) use ($id){
            if(is_string($rule) && str_contains($rule,'unique:')) $rule.=','.$id.','.$this->pk;
            return [$field=>$rule];
        })->toArray();
        $v = Validator::make($request->all(), $rules);
        if ($v->fails()) return response()->json(['success'=>false,'message'=>$v->errors()->first(),'errors'=>$v->errors()], 422);
        try {
            $record = $this->model::where($this->pk, $id)->firstOrFail();
            $record->update($request->only($this->fillable));
            return response()->json(['success'=>true,'message'=>'Record updated successfully.','record'=>$record]);
        } catch (\Exception $e) {
            return response()->json(['success'=>false,'message'=>$e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $this->model::where($this->pk, $id)->firstOrFail()->delete();
            return response()->json(['success'=>true,'message'=>'Record deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success'=>false,'message'=>$e->getMessage()], 500);
        }
    }

    public function create() { abort(404); }
    public function show($id) { abort(404); }
}
