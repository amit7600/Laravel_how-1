<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $table = 'groups';
    protected $primaryKey = 'group_recordid';
    public $timestamps = false;

    protected $fillable = ['group_members', 'group_tag'];

    public function contact()
    {
        return $this->hasMany('App\Contact', 'contact_group', 'group_recordid');
    }
}
