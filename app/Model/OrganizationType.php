<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\softDeletes;

class OrganizationType extends Model
{
    use softDeletes;

    protected $fillable = [
        'organization_type', 'created_by', 'deleted_by', 'note',
    ];
}
