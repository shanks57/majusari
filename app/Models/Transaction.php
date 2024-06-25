<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transactions';

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [ 'id', 'code', 'date', 'customer_id', 'goods_id'];
    protected $dates = ['date'];
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function goods()
    {
        return $this->belongsTo(Goods::class, 'goods_id');
    }
}
