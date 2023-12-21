<?php

namespace App\Models;

use App\Models\Area;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Zone extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
    ];

    public function area()
    {
        return $this->hasMany(Area::class, 'zone_id', 'id');
    }
}
