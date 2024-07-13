<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Showcase extends Model
{
    use HasFactory;

    protected $table = 'showcases';

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['id', 'code', 'name', 'type_id'];

    public function goodsType()
    {
        return $this->belongsTo(GoodsType::class, 'type_id');
    }

    public function goods()
    {
        return $this->hasMany(Goods::class, 'tray_id', 'tray_id'    );
    }

    public function trays()
    {
        return $this->hasMany(Tray::class);
    }
}
