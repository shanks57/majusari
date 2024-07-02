<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tray extends Model
{
    use HasFactory;

    protected $table = 'trays';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['id', 'code', 'weight', 'capacity', 'showcase_id'];

    public function goods()
    {
        return $this->hasMany(Goods::class, 'tray_id');
    }   

    public function showcase()
    {
        return $this->belongsTo(Showcase::class);
    }
}
