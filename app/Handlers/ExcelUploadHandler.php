<?php

namespace App\Handlers;

use Excel;

class ExcelUploadHandler{


    public function save($file, $name, $ext, $type)
    {
        $allowed_ext = ['xls', 'xlsx'];
        //判断文件类型是否被允许上传
        !in_array($ext, $allowed_ext) && $this->error('上传文件类型错误');
        //判断是否是通过HTTP POST上传的
        !is_uploaded_file($file['tmp_name']) && $this->error('文件不是通过HTTP POST上传的');
        $upload_path = $type == 1 ? 'uploads/in/' : 'uploads/out/';  //上传文件的存放路径
        $path = $upload_path . str_random() . '.' . $type;
        //开始移动文件到相应的文件夹
        if (move_uploaded_file($file['tmp_name'],$path)) {
            $data = $this->getFileData($path);
            $this->result['data'] = $data;
            $this->success();
        }
        $this->error();

        // 构建存储的文件夹规则，值如：uploads/images/avatars/201709/21/
        // 文件夹切割能让查找效率更高。
        $folder_name = "uploads/images/$folder/" . date("Ym", time()) . '/'.date("d", time()).'/';

        // 文件具体存储的物理路径，`public_path()` 获取的是 `public` 文件夹的物理路径。
        // 值如：/home/vagrant/Code/larabbs/public/uploads/images/avatars/201709/21/
        $upload_path = public_path() . '/' . $folder_name;

        // 获取文件的后缀名，因图片从剪贴板里黏贴时后缀名为空，所以此处确保后缀一直存在
        $extension = strtolower($file->getClientOriginalExtension()) ?: 'png';

        // 拼接文件名，加前缀是为了增加辨析度，前缀可以是相关数据模型的 ID
        // 值如：1_1493521050_7BVc9v9ujP.png
        $filename = $file_prefix . '_' . time() . '_' . str_random(10) . '.' . $extension;

        // 如果上传的不是图片将终止操作
        if ( ! in_array($extension, $this->allowed_ext)) {
            return false;
        }

        // 将图片移动到我们的目标存储路径中
        $file->move($upload_path, $filename);

        // 如果限制了图片宽度，就进行裁剪
        if ($max_width && $extension != 'gif') {

            // 此类中封装的函数，用于裁剪图片
            $this->reduseSize($upload_path . '/' . $filename, $max_width);
        }

        return [
            'path' => config('app.url') . "/$folder_name/$filename"
        ];

    }

    private function getFileData($path) {
        $excel = Excel::load($path);
        $currentSheet = $excel->getSheet(0);
        $allColumn = $currentSheet->getHighestColumn();
        $allRow = $currentSheet->getHighestRow();
        $data = $result = [];
        for ($currentRow = 2; $currentRow <= $allRow; $currentRow++) {
            //从第A列开始输出
            for ($currentColumn = 'A'; $currentColumn <= $allColumn; $currentColumn++) {
                $val = $currentSheet->getCellByColumnAndRow(ord($currentColumn) - 65, $currentRow)->getValue();  //ord()将字符转为十进制数
                $data[$currentRow - 1][] = $val;
            }
        }
        if ($data) {
            $_path = explode('/', $path);
            foreach ($data as $k => $v) {
                if (null != $v[0]) {
                    if (in_array('in', $_path)) {
                        $result[] = ['ename' => $v[0], 'cname' => $v[1], 'type' => $v[2], 'accuracy' => $v[3], 'dimension' => $v[4], 'is_required' => (int)$v[5], 'describe' => $v[6]];
                    } else {
                        $result[] = ['ename' => $v[0], 'cname' => $v[1], 'type' => $v[2], 'accuracy' => $v[3], 'dimension' => $v[4], 'describe' => $v[5]];
                    }
                }
            }
        }
        return $result;
    }
}

