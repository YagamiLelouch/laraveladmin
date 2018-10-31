<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Admin\Product;
use App\Models\Admin\Category;
use App\Models\Admin\AdminUser;
use Cache, Event;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\ProductCreateRequest;
use App\Services\CategoryService;
use App\Services\TagService;
use Validator;
use Illuminate\Support\Facades\Redirect;
use App\Notifications\NewProduct;
use App\Mail\OrderShipped;
use App\Jobs\OrderTest as OrderJob;
use App\Jobs\SendEmailTest;


class ProductController extends Controller
{

    private $categoryService;
    private $tagService;

    public function __construct(CategoryService $categoryService, TagService $tagService)
    {
        $this->categoryService = $categoryService;
        $this->tagService = $tagService;
    }


    public function index(Request $request)
    {
        if ($request->ajax()) {
            $cid = $request->input('cid');

            $catData = Category::where('parent_id', $cid)->get();
            $pid = [];
            foreach ($catData as $value) {
                $pid[] = $value['id'];
            }
            $pdata = Product::whereIn('category_id', $pid)->get();

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

            $data['recordsTotal'] = count(Product::whereIn('category_id', $pid)->get());
            //是搜索动作时执行
            if (strlen($search['value']) > 0) {
                $data['recordsFiltered'] = Product::where(function ($query) use ($search, $pid) {
                    $query->where('name', 'LIKE', '%' . $search['value'] . '%')->whereIn('category_id', $pid);
                })->count();
                $data['data'] = Product::with(['creator', 'updater'])->where(function ($query) use ($search, $pid) {
                    $query->where('name', 'LIKE', '%' . $search['value'] . '%')->whereIn('category_id', $pid);
                })
                    ->skip($start)->take($length)
                    ->orderBy($columns[$order[0]['column']]['data'], $order[0]['dir'])
                    ->get();
                //非搜索执行
            } else {
                $data['recordsFiltered'] = count(Product::whereIn('category_id', $pid)->get());
                $data['data'] = Product::with(['creator', 'updater'])->whereIn('category_id', $pid)->
                skip($start)->take($length)
                    ->orderBy($columns[$order[0]['column']]['data'], $order[0]['dir'])
                    ->get();
            }
            return response()->json($data);
        }
        $data = Category::where('status', 1)->get();
        $trees = $this->typesTree($data, 0);


        return view('admin.product.index', compact('trees'));
    }

    public function typesTree2($types, $parent_id)
    {

    }

    public function userProductIndex(Request $request)
    {
        $user = Auth::guard('admin')->user();
        $id = $user['id'];

        if ($request->ajax()) {

            $data = array();
            //获取请求的各种参数
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

            $data['recordsTotal'] = count(Product::where('created_by', $id)->get());
//
            //是搜索动作时执行
            if (strlen($search['value']) > 0) {
                $data['recordsFiltered'] = Product::where(function ($query) use ($search, $id) {
                    $query->where('name', 'LIKE', '%' . $search['value'] . '%')->where('created_by', $id);
                })->count();
                $data['data'] = Product::with(['creator', 'updater'])->where(function ($query) use ($search, $id) {
                    $query->where('name', 'LIKE', '%' . $search['value'] . '%')->where('created_by', $id);
                })
                    ->skip($start)->take($length)
                    ->orderBy($columns[$order[0]['column']]['data'], $order[0]['dir'])
                    ->get();
                //非搜索执行
            } else {
                $data['recordsFiltered'] = count(Product::where('created_by', $id)->get());
                $data['data'] = Product::with(['creator', 'updater'])->where('created_by', $id)
                    ->skip($start)->take($length)
                    ->orderBy($columns[$order[0]['column']]['data'], $order[0]['dir'])
                    ->get();
            }
//            dd($data['data']->toArray());
            return response()->json($data);
        }
        return view('admin.product.userlist');
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


    public function create()
    {

        $catAll = Product::all();
        $trees = $this->typesTree($catAll, 0);
        return view('admin.product.create_and_edit');
    }


    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = \Storage::disk('public')->get('json/test.json');
//        $data = \Storage::disk('public')->get('json/ajax_product.json');
        return $data;

// return file_get_contents('http://zcloud.wsw/getCategoryTree/4');

    }


    public function getCategory()
    {


    }

    public function store(ProductCreateRequest $request, Category $category, Product $product)
    {
        $data = array_filter($request->all());
        //添加分类
        $category->create(['name' => $data['name'], 'parent_id' => $data['category_id'], 'level' => 4]);
        //添加产品
        $product = $product->create($data);
        //添加tag
        if (isset($data['tag'])) {
            $tags = explode(",", $data['tag']);
            $this->tagService->tagSet($tags, $product['id'], 1);
        }

        //添加成功的日志
        event(new \App\Events\userActionEvent('\App\Models\Admin\Product', $product->id, 1, "用户" . auth('admin')->user()->username . "{" . auth('admin')->user()->id . "}添加产品" . $product->name . "{" . $product->id . "}"));
        return redirect('/admin/product/')->withSuccess('添加成功！');
    }

    public function edit(Request $request, $id)
    {
        $product = (Product::with('tag')->where('id', $id)->get()->toArray())[0];
        $product['method'] = 'PUT';
        $product['title'] = '编辑产品';
        if ($product['tag']) {
            $product['tags'] = $this->tagService->getTags($product['tag']);
        } else {
            $product['tags'] = [];
        }

        return view('admin.product.create_and_edit', compact('product'));
    }

    public function update(ProductCreateRequest $request, $id)
    {
        $data = $request->all();
        $product = Product::find($id);
        //修改Category里面name为Product的字段所在的分类
        Category::where('name', $product->name)->update(['name' => $data['name'], 'parent_id' => $data['category_id']]);
        //修改product的信息
        $product->update($data);
        //修改product 的tag信息(可能会添加新的tag)
        $product->tag()->detach();
        if (isset($data['tag'])) {
            $tags = explode(",", $data['tag']);
            $this->tagService->tagSet($tags, $product['id'], 1);
        }
        event(new \App\Events\userActionEvent('\App\Models\Admin\Product', $product->id, 1, "用户" . auth('admin')->user()->username . "{" . auth('admin')->user()->id . "}修改产品" . $product->name . "{" . $product->id . "}"));
        return redirect('/admin/product/')->withSuccess('添加成功！');

    }

    public function destroy($id)
    {
        $product = Product::find((int)$id);
        $product->categories()->delete();
        $product->delete();
        event(new \App\Events\userActionEvent('\App\Models\Admin\Product', $product->id, 2, "用户" . auth('admin')->user()->username . "{" . auth('admin')->user()->id . "}删除产品" . $product->name . "{" . $product->id . "}"));
        return redirect()->back()
            ->withSuccess("删除成功");
    }

}
