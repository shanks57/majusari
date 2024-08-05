<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cart extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'carts';

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['id', 'user_id', 'goods_id', 'new_selling_price', 'status_price', 'complaint', 'tray_id'];

    protected $dates = ['deleted_at'];

    public function goods()
    {
        return $this->belongsTo(Goods::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tray()
    {
        return $this->belongsTo(Tray::class, 'tray_id');
    }
}
