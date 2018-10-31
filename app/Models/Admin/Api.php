<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class Api extends Model
{
    protected $table='apis';

    protected $fillable = ['ename', 'cname', 'url', 'describe', 'in_parameter', 'out_parameter', 'request_sample', 'reback_sample', 'status'];

    public function category()
    {
        return $this->belongsToMany('App\Models\Admin\Category', 'ac_relation', 'api_id', 'category_id');
    }

    public function tag()
    {
        return $this->belongsToMany('App\Models\Admin\Tag', 'relation', 'key', 'tag_id')->wherePivot('type', '=', 1)->wherePivot('deleted_at', '=', null);
    }

    public function creator()
    {
        return $this->belongsTo('App\Models\Admin\AdminUser', 'created_by');

    }

    public function updater()
    {
        return $this->belongsTo('App\Models\Admin\AdminUser', 'updated_by');
    }


}
