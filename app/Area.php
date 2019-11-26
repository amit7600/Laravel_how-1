<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    protected $table = 'area';
    
	public $timestamps = false;

	public function services()
    {
        return $this->belongsTo('App\Service', 'area_services', 'service_recordid');
    }
}
