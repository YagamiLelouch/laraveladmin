<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UploadController extends Controller
{
    public function uploadImg(Request $request)
    {
        $file = $request->file('upload');
        $data['fileName'] = $file->getClientOriginalName();
        $data['uploaded'] = 1;
        //Move Uploaded File
        $destinationPath = "uploads/product";
        $file->move($destinationPath,$file->getClientOriginalName());
        $data['url'] = '/'.$destinationPath.'/'.$data['fileName'];
        return $data;
        return json_encode($data, true);
    }
}
