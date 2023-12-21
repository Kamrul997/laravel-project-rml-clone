<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CollectionAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'collection_id',
        'attachment',
    ];
}
