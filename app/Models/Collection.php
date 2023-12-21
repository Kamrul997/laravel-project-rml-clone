<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Unit;
use App\Models\SubUnit;



class Collection extends Model
{
    use HasFactory;

    protected $fillable = [
        'target_sheet_id',
        'unit',
        'areer_amount',
        'current_amount',
        'vts_charge',
        'name_transfer_fees',
        'collection_amount',
        'advanced_collection',
        'ownership_charge',
        'rotl',
        'brta_charge',
        'others',
        'deposit_type',
        'bank_name',
        'branch_name',
        'ft_code',
        'deposit_date',
        'approved_date',
        'created_by',
        'updated_by',
        'status',
        'approved_by',
        'reject_by',
        'reject_date',
        'disapproved_note_id',
    ];


    public function userName($id)
    {
        $data = User::find($id);
        if (isset($data)) {
            return $data->name;
        } else {
            return '';
        }
    }

    public function createddBy()
    {
        return $this->belongsTo(User::class, 'created_by')->select(['id', 'employee_id', 'name']);
    }

    public function unitData()
    {
        return $this->belongsTo(SubUnit::class, 'unit')->select(['id','name']);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by')->select(['id', 'employee_id', 'name']);
    }

    public function rejectdBy()
    {
        return $this->belongsTo(User::class, 'reject_by')->select(['id', 'employee_id', 'name']);
    }


}
