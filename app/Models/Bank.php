<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    use HasFactory;

    protected $fillable=[
        'bank_name','short_name','account_no','internet_bank','status'
    ];

    public function branch(){
        return $this->hasMany(Branch::class);
    }
}
