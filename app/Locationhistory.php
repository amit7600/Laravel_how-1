<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Locationhistory extends Model
{
    use Sortable;
    protected $table = 'locations_change_log';
    protected $primaryKey = 'id';
    
}
