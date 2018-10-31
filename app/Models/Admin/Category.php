<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';

    protected $fillable =  ['name', 'parent_id', 'level', 'top_id', 'status'];

    public function api()
    {
        return $this->belongsToMany('App\Models\Admin\Api', 'ac_relation', 'category_id', 'api_id');
    }
}
