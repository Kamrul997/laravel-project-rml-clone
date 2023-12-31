<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyForecast extends Model
{
    use HasFactory;
    protected $fillable = [
        'date',
        'amount',
        'unit_id',
        'created_by',
    ];
}
