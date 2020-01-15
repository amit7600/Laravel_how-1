<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\softDeletes;

class facilityType extends Model
{
    use softDeletes;

    protected $fillable = [
        'facility_type', 'created_by', 'deleted_by', 'notes',
    ];
}
