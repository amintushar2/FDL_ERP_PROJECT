<?php

namespace App\Models\Inventory\ItemReceived;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ItemReceivedMaster extends Model
{
    protected $table = 'F_STORE.G_ITEM_RECEIVED_MASTER';
    protected $primaryKey = 'RECEIVED_NO_ID';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'RECEIVED_NO','RECEIVED_NO_ID','SUPPLIER_ID','RECEIVED_DATE',
        'SUPPLIER_CHALLAN_NO','REMARKS','ORDER_NO_ID','PO_NUMBER',
        'FOUR_PO_NO','FOUR_PO_ID','INSERT_BY','INSERT_DATE',
        'UPDATE_BY','UPDATE_DATE','SUPP_BILL_NO','CLIENT_PI_NUMBER',
    ];

    protected $casts = [
        'RECEIVED_DATE' => 'date',
        'INSERT_DATE'   => 'datetime',
        'UPDATE_DATE'   => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->RECEIVED_NO_ID)) {
                $model->RECEIVED_NO_ID = (string) Str::uuid();
            }
            $model->INSERT_BY   = auth()->user()->name ?? 'SYSTEM';
            $model->INSERT_DATE = now();
        });
        static::updating(function ($model) {
            $model->UPDATE_BY   = auth()->user()->name ?? 'SYSTEM';
            $model->UPDATE_DATE = now();
        });
    }

    public function styles()
    {
        return $this->hasMany(ItemReceivedStyle::class, 'received_no_id','received_no_id');
    }

    public function details()
    {
        return $this->hasMany(ItemReceivedDetails::class, 'received_no_id','received_no_id');
    }
}
