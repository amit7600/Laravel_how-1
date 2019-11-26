<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Organizationcontact extends Model
{
    use Sortable;

    protected $table = 'organization_contact';

    protected $primaryKey = 'id';
    
	public $timestamps = false;

}
