<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Address extends Model
{
    use Sortable;

    protected $table = 'address';

    protected $primaryKey = 'address_recordid';

    public $timestamps = false;

    protected $fillable = [
        'address_recordid', 'address', 'address_1', 'address_city', 'address_state', 'address_zip_code', 'address_type',
    ];

    public function locations()
    {
        return $this->belongsToMany('App\Location', 'location_address', 'address_recordid', 'location_recordid');
    }

    public function services()
    {
        return $this->belongsToMany('App\Service', 'service_address', 'address_recordid', 'service_recordid');
    }

    public function contacts()
    {
        return $this->hasMany('App\Contact', 'contact_mailing_address', 'address_recordid');
    }
}
