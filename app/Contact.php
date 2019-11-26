<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $table = 'contacts';

    protected $primaryKey = 'contact_recordid';
    
    public $timestamps = false;

    public function organization()
    {
        return $this->belongsTo('App\Organization', 'contact_organizations', 'organization_recordid');
    }

    public function group()
    {
        return $this->belongsTo('App\Group', 'contact_group', 'group_recordid');
    }

    public function address()
    {
        return $this->belongsTo('App\Address', 'contact_mailing_address', 'address_recordid');
    }

    public function service()
    {

        $this->primaryKey='contact_recordid';

        return $this->belongsToMany('App\Service', 'service_contact', 'contact_recordid', 'service_recordid');
    }

    public function cellphone()
    {
        return $this->belongsTo('App\Phone', 'contact_cell_phones', 'phone_recordid');
    }

    public function officephone()
    {
        return $this->belongsTo('App\Phone', 'contact_office_phones', 'phone_recordid');
    }

    public function emergencyphone()
    {
        return $this->belongsTo('App\Phone', 'contact_emergency_phones', 'phone_recordid');
    }
}
