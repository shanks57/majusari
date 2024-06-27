<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Goods extends Model
{
    use HasFactory;
    
    protected $table = 'goods';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'name',
        'category',
        'color',
        'rate',
        'size',
        'merk_id',
        'ask_rate',
        'bid_rate',
        'ask_price',
        'bid_price',
        'entry_date',
        'image',
        'type_id',
        'tray_id',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
