<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CampaignReport extends Model
{

    //
    use SoftDeletes;

    protected $table = 'campaign_reports';

    protected $fillable = ['user_id', 'type', 'status', 'direction', 'date_sent', 'toNumber', 'toContact', 'fromNumber', 'fromContact', 'body', 'created_by', 'updated_by', 'campaign_id', 'mediaurl', 'error_message', 'contact_id', 'subject'];

    public function campaign()
    {
        return $this->belongsTo('App\Campaign', 'campaign_id', 'id');

    }
}
