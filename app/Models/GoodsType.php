<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GoodsType extends Model
{
    use HasFactory;

    protected $table = 'goods_types';

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['id', 'name', 'additional_cost', 'status', 'slug'];

    protected $dates = ['deleted_at'];

    public function goods()
    {
        return $this->hasMany(Goods::class, 'goods_type_id');
    }

    public function showcases()
    {
        return $this->hasMany(Showcase::class, 'type_id');
    }
}
