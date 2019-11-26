<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Campaign extends Model
{
    //
    use SoftDeletes;

    protected $table = 'campaigns';

    protected $fillable = ['user_id', 'name', 'campaign_type', 'subject', 'campaign_file', 'group_id', 'body', 'schedule_date', 'created_by', 'updated_by', 'status', 'recipient', 'sending_type', 'sending_status'];

    public function report()
    {
        return $this->hasMany('App\CampaignReport', 'campaign_id', 'id');
    }
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
}
