<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    use HasFactory;
    protected $table = 'transaction_details';

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    
    protected $fillable = [
        'id',
        'nota',
        'transaction_id',
        'goods_id',
        'tray_id',
        'harga_jual'
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function goods()
    {
        return $this->belongsTo(Goods::class);
    }

    public function tray()
    {
        return $this->belongsTo(Tray::class, 'tray_id');
    }
}
