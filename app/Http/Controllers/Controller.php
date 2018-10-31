<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Response;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $result = ['status' => 1, 'info' => '操作成功'];

    protected function success($info = '操作成功', $data = '') {
        $this->result['info'] = $info;
        $data && $this->result['data'] = $data;
        (new Response(json_encode($this->result), 200, ['Content-Type' => 'application/json']))->send() && exit();
    }

    protected function error($info = '操作失败', $data = '') {
        $this->result['status'] = 0;
        $this->result['info'] = $info;
        $data && $this->result['data'] = $data;
        (new Response(json_encode($this->result), 200, ['Content-Type' => 'application/json']))->send() && exit();
    }
}
