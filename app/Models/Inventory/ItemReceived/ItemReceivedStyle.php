<?php

namespace App\Models\Inventory\ItemReceived;

use Illuminate\Database\Eloquent\Model;

class ItemReceivedStyle extends Model
{
    protected $table = 'F_STORE.G_ITEM_RECEIVED_STYLE';
    public $primaryKey = 'RECEIVED_NO_ID';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'RECEIVED_NO_ID','PO_NUMBER','STYLE_NO','ORDER_NO',
        'ORDER_NO_ID','INSERT_BY','INSERT_DATE','UPDATE_BY','UPDATE_DATE',
    ];

    protected $casts = [
        'INSERT_DATE' => 'datetime',
        'UPDATE_DATE' => 'datetime',
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
        return $this->belongsTo(ItemReceivedMaster::class,  'RECEIVED_NO_ID');
    }
}
