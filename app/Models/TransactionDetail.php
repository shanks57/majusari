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
        'transaction_id',
        'goods_id'
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function goods()
    {
        return $this->belongsTo(Goods::class);
    }
}
