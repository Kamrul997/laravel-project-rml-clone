<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Target extends Model
{
    use HasFactory;
    protected $table = 'target_sheets';
    protected $fillable = [
        'tally_ref_id',
        'unit',
        'unit_name',
        'employee_id',
        'employee_name',
        'mobile_no',
        'ord_no',
        'cutomer_name',
        'cutomer_id',
        'terms',
        'inst_id',
        'delivery_date',
        'inst_due_date',
        'part_no',
        'registration_no',
        'market_receivabl',
        'penalty_target',
        'target_current',
        'advance_collection',
        'target_arrear',
        'term',
        'customer',
        'resold',
        'seize',
        'vtd_status',
        'target_total',
        'profit_customer',
        'created_at',
    ];
}
