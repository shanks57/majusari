<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Goods extends Model
{
    use HasFactory;

    protected $table = 'goods';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'serial_number',
        'code',
        'name',
        'category',
        'color',
        'rate',
        'size',
        'dimensions',
        'merk_id',
        'ask_rate',
        'bid_rate',
        'ask_price',
        'bid_price',
        'image',
        'type_id',
        'tray_id',
        'position',
        'availability',
        'safe_status',
        'date_entry',
        'unit'
    ];

    protected $dates = ['deleted_at'];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function goodsType()
    {
        return $this->belongsTo(GoodsType::class, 'type_id');
    }

    public function merk()
    {
        return $this->belongsTo(Merk::class, 'merk_id');
    }

    public function tray()
    {
        return $this->belongsTo(Tray::class, 'tray_id');
    }

    public function showcase()
    {
        return $this->belongsTo(Showcase::class, 'tray_id', 'tray_id');
    }
}
