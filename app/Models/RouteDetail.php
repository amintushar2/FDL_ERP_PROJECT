<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class RouteDetail extends Model
{
    protected $table      = 'F_STORE.ALL_ROUTE_DETAILS';
    protected $primaryKey = 'ROUTE_ID';
    public    $keyType    = 'string';
    public    $incrementing = false;
    public    $timestamps   = false;

   protected $fillable = [ 'ROUTE_ID', 'ROUTE_PATH', 
   // MAIN MENU 
   'MENU_CHILD_ID', 
   // SUB MENU 
   'SUB_MENU_ID', 
   // CHILD MENU 
   'SUB_MENU_1', 
   // LEVEL 3 
   'SUB_MENU_2', 
   'COMPONENT', 
   'SUB_MENU_NAME', 
   'DESCRIPTION', 
   'IS_ACTIVE', 
   'INSERT_DATE', 
   'INSERT_BY', 
   'UPDATE_DATE', 
   'UPDATE_BY', 
 ];

    public function menu()
    {
        return $this->belongsTo(MenuHierarchy::class, 'MENU_CHILD_ID', 'CHILD_ID');
    }
}