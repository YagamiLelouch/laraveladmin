<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Admin\Product;
use App\Models\Admin\Category;
use App\Models\Admin\Api;
use Cache, Event;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Storage;
use App\Handlers\ExcelUploadHandler;
use Excel;
use App\Services\TagService;

class ApiController extends Controller
{

    private $tagService;

    public function __construct(TagService $tagService)
    {
        $this->tagService = $tagService;
    }

    public function index(Request $request)
    {

        if ($request->ajax()) {
            $cid = $request->input('cid');
            $data = array();
            $data['draw'] = $request->get('draw');
            $start = $request->get('start');
            $length = $request->get('length');
            $order = $request->get('order');
            $columns = $request->get('columns');
            $search = $request->get('search');

            //对creator和updater字段进行替换
            if ($columns[$order[0]['column']]['data'] == 'creator.name') {
                $columns[$order[0]['column']]['data'] = 'created_by';
            } else if ($columns[$order[0]['column']]['data'] == 'updater.name') {
                $columns[$order[0]['column']]['data'] = 'updated_by';
            }

            $data['recordsTotal'] = count(Category::find($cid)->api()->get());
            //是搜索动作时执行
            if (strlen($search['value']) > 0) {
                $data['recordsFiltered'] = Category::find($cid)->api()->where('cname', 'LIKE', '%' . $search['value'] . '%')->count();
//
                $data['data'] = Category::find($cid)->api()->with(['creator', 'updater'])->where('cname', 'LIKE', '%' . $search['value'] . '%')
                    ->skip($start)->take($length)
                    ->orderBy($columns[$order[0]['column']]['data'], $order[0]['dir'])
                    ->get();
                //非搜索执行
            } else {
                $data['recordsFiltered'] = count(Category::find($cid)->api()->get());
                $data['data'] = Category::find($cid)->api()->with(['creator', 'updater'])->
                skip($start)->take($length)
                    ->orderBy($columns[$order[0]['column']]['data'], $order[0]['dir'])
                    ->get();
            }
            return response()->json($data);
        }
        $data = Category::where('status', 1)->get();
        $trees= $this->typesTree($data, 0);


        return view('admin.api.index', compact('trees'));
    }

    public function typesTree($types, $parent_id)
    {
        $data = [];
        foreach ($types as $type) {
            if ($type['parent_id'] == $parent_id) {
                $type['child'] = self::typesTree($types, $type['id']);
                $data[] = $type;
            }
        }
        return $data;
    }

    public function userApiIndex(Request $request)
    {
        $user = Auth::guard('admin')->user();
        $id = $user['id'];

        if ($request->ajax()) {

            $data = array();
            $data['draw'] = $request->get('draw');
            $start = $request->get('start');
            $length = $request->get('length');
            $order = $request->get('order');
            $columns = $request->get('columns');
            $search = $request->get('search');

            //对creator和updater字段进行替换
            if ($columns[$order[0]['column']]['data'] == 'creator.name') {
                $columns[$order[0]['column']]['data'] = 'created_by';
            } else if ($columns[$order[0]['column']]['data'] == 'updater.name') {
                $columns[$order[0]['column']]['data'] = 'updated_by';
            }

            $data['recordsTotal'] = count(Api::where('created_by', $id)->get());
            if (strlen($search['value']) > 0) {
                $data['recordsFiltered'] = Api::where('created_by', $id)->where('cname', 'LIKE', '%' . $search['value'] . '%')->count();
//
                $data['data'] = Api::with(['creator', 'updater'])->where('created_by', $id)->where('cname', 'LIKE', '%' . $search['value'] . '%')
                    ->skip($start)->take($length)
                    ->orderBy($columns[$order[0]['column']]['data'], $order[0]['dir'])
                    ->get();
                //非搜索执行
            } else {
                $data['recordsFiltered'] = count(Api::where('created_by', $id)->get());
                $data['data'] = Api::with(['creator', 'updater'])->where('created_by', $id)->
                skip($start)->take($length)
                    ->orderBy($columns[$order[0]['column']]['data'], $order[0]['dir'])
                    ->get();
            }
            return response()->json($data);
        }
        return view('admin.api.userlist');
    }

    public function create()
    {
        return view('admin.api.create_and_edit');
    }

    public function show($id)
    {
//        $data = \Storage::disk('public')->get('json/ajax_roots.json');
        $data = \Storage::disk('public')->get('json/ajax_api.json');
        return $data;

// return file_get_contents('http://zcloud.wsw/getCategoryTree/4');

    }
    public function getCategory()
    {


    }

    public function store(Request $request, Api $api)
    {
        $data = $request->except(['_token']);
        $api = $api->create($data);
        $api->category()->attach($data['category_id']);
        //添加tag
        if (isset($data['tag'])) {
            $tags = explode(",", $data['tag']);
            $this->tagService->tagSet($tags, $api['id'], 2);
        }
        event(new \App\Events\userActionEvent('\App\Models\Admin\Api',$api->id,1,"用户".auth('admin')->user()->username."{".auth('admin')->user()->id."}添加api".$api->cname."{".$api->id."}"));
        return 1;
    }

    public function edit(Request $request, $id)
    {
        $product = (Api::with('tag')->where('id', $id)->get()->toArray())[0];
        $product['method'] = 'PUT';
        $product['title'] = '编辑产品';
        if ($product['tag']) {
            $product['tags'] = $this->tagService->getTags($product['tag']);
        } else {
            $product['tags'] = [];
        }
        return view('admin.api.create_and_edit', compact('api'));
    }

    public function update(Request $request, $id)
    {
        dd($request->all);
        $api = Api::find($id);
        $api->cname = $request->input('cname');
        $api->save();
        $api->category()->detach();
        $api->category()->attach($request->input('category_id'));
        event(new \App\Events\userActionEvent('\App\Models\Admin\Product',$api->id,1,"用户".auth('admin')->user()->username."{".auth('admin')->user()->id."}修改api".$api->cname."{".$api->id."}"));
        return 1;

    }

    public function destroy($id)
    {
        $api = Api::find((int)$id);
        $api->category()->delete();
        $api->delete();
        event(new \App\Events\userActionEvent('\App\Models\Admin\Api',$api->id,2,"用户".auth('admin')->user()->username."{".auth('admin')->user()->id."}删除产品".$api->cname."{".$api->id."}"));
        return redirect()->back()
            ->withSuccess("删除成功");
    }

    public function readExcel(Request $request)
    {
        $file = $_FILES['file'];  //得到传输的数据
        $name = $file['name'];  //得到文件名称
        $type = strtolower(substr($name,strrpos($name,'.')+1)); //得到文件类型，并且都转化成小写
        $allow_type = ['xls', 'xlsx'];  //定义允许上传的类型
        //判断文件类型是否被允许上传
        !in_array($type, $allow_type) && $this->error('上传文件类型错误');
        //判断是否是通过HTTP POST上传的
        !is_uploaded_file($file['tmp_name']) && $this->error('文件不是通过HTTP POST上传的');
        $upload_path = $request->type == 1 ? 'uploads/in/' : 'uploads/out/';  //上传文件的存放路径
        $path = $upload_path . str_random() . '.' . $type;
        //开始移动文件到相应的文件夹
        if (move_uploaded_file($file['tmp_name'],$path)) {
            $data = $this->getFileData($path);
            $this->result['data'] = $data;
            $this->success();
        }
        $this->error();

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
