<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\softDeletes;

class contactType extends Model
{
    use softDeletes;

    protected $fillable = [
        'contact_type', 'created_by', 'deleted_by', 'notes',
    ];
}
