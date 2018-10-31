<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use App\Notifications\NewProduct;

class Product extends Model
{
    use Notifiable;

    public function routeNotificationForMail()
    {
        return 'laduanxun98@gmail.com';
    }

    protected $fillable = ['type', 'category_id', 'name', 'url', 'service_mode', 'update_time', 'service_provider', 'package_price', 'hotline', 'image', 'intro', 'status'];

    public function categories()
    {
        return $this->belongsTo('App\Models\Admin\Category', 'category_id');
    }

    public function creator()
    {
        return $this->belongsTo('App\Models\Admin\AdminUser', 'created_by');

    }

    public function updater()
    {
        return $this->belongsTo('App\Models\Admin\AdminUser', 'updated_by');
    }

    public function tag()
    {
        return $this->belongsToMany('App\Models\Admin\Tag', 'relation', 'key', 'tag_id');

    }

}
