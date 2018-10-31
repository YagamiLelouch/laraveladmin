<?php
/**
 * Created by PhpStorm.
 * User: shaowen.wang
 * Date: 2018/9/26
 * Time: 9:09
 */

namespace App\Services;
use App\Models\Admin\Api;


class ApiService
{
    private $api;

    public function __construct(Api $api)
    {
        $this->api = $api;
    }

}