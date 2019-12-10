<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Phone extends Model
{
    protected $table = 'phones';

    protected $primaryKey = 'phone_recordid';

    public $timestamps = false;

    protected $fillable = [
        'phone_recordid', 'phone_number', 'phone_type',
    ];

    public function locations()
    {
        return $this->belongsToMany('App\Location', 'location_phone', 'phone_recordid', 'location_recordid');
    }

    public function services()
    {
        return $this->belongsToMany('App\Service', 'service_phone', 'phone_recordid', 'service_recordid');
    }

    public function organization()
    {
        return $this->belongsTo('App\Organization', 'phone_organizations', 'organization_recordid');
    }

    public function contact()
    {
        return $this->hasMany('App\Contact', 'contact_cell_phones', 'phone_recordid');
    }

    public function schedule()
    {
        return $this->belongsTo('App\Schedule', 'phone_schedule', 'schedule_recordid');
    }

    public function officephones()
    {
        return $this->hasMany('App\Contact', 'contact_office_phones', 'phone_recordid');
    }

    public function officefaxs()
    {
        return $this->hasMany('App\Contact', 'contact_office_fax_phones', 'phone_recordid');
    }

    public function emergencyphones()
    {
        return $this->hasMany('App\Contact', 'contact_emergency_phones', 'phone_recordid');
    }
}
