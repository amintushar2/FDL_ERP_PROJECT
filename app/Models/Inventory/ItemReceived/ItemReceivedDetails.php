<?php

namespace App\Models\Inventory\ItemReceived;

use Illuminate\Database\Eloquent\Model;

class ItemReceivedDetails extends Model
{
    protected $table = 'F_STORE.G_ITEM_RECEIVED_DETAILS';
    public $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'RECEIVED_NO_ID','ITEM_NO','ITEM_QTY','ITEM_UNIT','ITEM_REMARKS',
        'PO_NUMBER','INSERT_BY','INSERT_DATE','UPDATE_BY','UPDATE_DATE',
        'PUR_QTY','PERCENTAGE','PREVIOUS_RECEIVED','PO_NUMBER_ID',
    ];

    protected $casts = [
        'ITEM_QTY'          => 'decimal:2',
        'PUR_QTY'           => 'decimal:2',
        'PERCENTAGE'        => 'decimal:2',
        'PREVIOUS_RECEIVED' => 'decimal:2',
        'INSERT_DATE'       => 'datetime',
        'UPDATE_DATE'       => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->INSERT_BY   = auth()->user()->name ?? 'SYSTEM';
            $model->INSERT_DATE = now();
        });
        static::updating(function ($model) {
            $model->UPDATE_BY   = auth()->user()->name ?? 'SYSTEM';
            $model->UPDATE_DATE = now();
        });
    }

    public function master()
    {
        return $this->belongsTo(ItemReceivedMaster::class, 'RECEIVED_NO_ID');
    }
}
