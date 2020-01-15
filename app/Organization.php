<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    protected $table = 'organizations';

    protected $primaryKey = 'organization_recordid';

    public $timestamps = false;

    protected $fillable = [
        'organization_name', 'organization_recordid', 'organization_id', 'organization_religion', 'organization_alt_id', 'organization_faith_tradition', 'organization_denomination', 'organization_judicatory_body', 'organization_type', 'organization_url', 'organization_facebook', 'organization_c_board', 'organization_internet_access', 'organization_description', 'organization_locations', 'flag',
    ];

    public function services()
    {
        return $this->hasMany('App\Service', 'service_organization', 'organization_recordid');

    }

    public function phones()
    {
        return $this->hasmany('App\Phone', 'phone_organizations', 'organization_recordid');
    }

    public function location()
    {
        return $this->hasmany('App\Location', 'location_organization', 'organization_recordid');
    }

    public function contact()
    {
        return $this->belongsToMany('App\Contact', 'organization_contact', 'organization_recordid', 'contact_recordid');
    }

    public function details()
    {
        return $this->belongsToMany('App\Detail', 'organization_detail', 'organization_recordid', 'detail_recordid');
    }
    public function religion()
    {
        return $this->belongsTo('App\Model\Religion', 'organization_religion', 'id');
    }
    public function faith_tradition()
    {
        return $this->belongsTo('App\Model\Religion', 'organization_faith_tradition', 'id');
    }
    public function denomination()
    {
        return $this->belongsTo('App\Model\Religion', 'organization_denomination', 'id');
    }
    public function judicatory_body()
    {
        return $this->belongsTo('App\Model\Religion', 'organization_judicatory_body', 'id');
    }
    public function organigationType()
    {
        return $this->belongsTo('App\Model\OrganizationType', 'organization_type', 'id');
    }

}
