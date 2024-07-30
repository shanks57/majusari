<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tray extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'trays';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['id', 'code', 'weight', 'capacity', 'showcase_id'];
    protected $dates = ['deleted_at'];

    public function goods()
    {
        return $this->hasMany(Goods::class, 'tray_id');
    }   

    public function showcase()
    {
        return $this->belongsTo(Showcase::class);
    }

    // Method untuk mendapatkan sisa kapasitas
    public function getRemainingCapacityAttribute()
    {
        // Hitung jumlah goods saat ini di tray ini
        $currentGoodsCount = $this->goods()->count();

        // Hitung sisa kapasitas
        return $this->capacity - $currentGoodsCount;
    }
}
