<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlyForecast extends Model
{
    use HasFactory;
    protected $fillable = [
        'month',
        'amount',
        'unit_id',
        'created_by',
    ];
}
