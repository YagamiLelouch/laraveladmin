<?php

namespace App\Http\Middleware;

use Closure;
use Auth, Cache;

class GetMenu
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //view()->share('comData',$this->getMenu());
        //给request设置一个属性
        $request->attributes->set('comData_menu', $this->getMenu());
        return $next($request);
    }

    /**
     * 获取左边菜单栏
     * @return array
     */
    function getMenu()
    {
        $openArr = [];
        $data = [];
        $data['top'] = [];
        //通过"/"拆分url成数组
        $path_arr = explode('/', \URL::getRequest()->path());
        //判断url里面有多少级别, 有2级则联立在1级2级+index拼接, 只有1级则直接1级+index
        if (isset($path_arr[1])) {
            $urlPath = $path_arr[0] . '.' . $path_arr[1] . '.index';
        } else {
            $urlPath = $path_arr[0] . '.index';
        }
//        获取所有分类
        $table = Cache::store('file')->rememberForever('menus', function () {
            return \App\Models\Admin\Permission::where('name', 'LIKE', '%index')
                ->orWhere('cid', 0)
                ->get();
        });

        //获取所有根节点和当前user拥有权限的url存入数组
        //$openArr[]数组为当前选择的节点标记
        foreach ($table as $v) {
            //forUser返回一个当前user的Gate实例
            if ($v->cid == 0 || \Gate::forUser(auth('admin')->user())->check($v->name)) {
                //permission的name是否和当前url一致, 一致的话放入新数组, 用来以后的默认展开
                if ($v->name == $urlPath) {
                    $openArr[] = $v->id;
                    $openArr[] = $v->cid;
                }
                $data[$v->cid][] = $v->toarray();
            }
        }

        //遍历所有根节点, 判断根节点下是否拥有user所拥有的权限, 最后移除原本的根节点数组, 形成一个只有根节点下面有子节点才存在的数组
        foreach ($data[0] as $v) {
            if (isset($data[$v['id']]) && is_array($data[$v['id']]) && count($data[$v['id']]) > 0) {
                $data['top'][] = $v;
            }
        }
        unset($data[0]);

        //当前选择的节点
        $data['openarr'] = array_unique($openArr);
//        dd(\Gate::forUser(auth('admin')->user())->check('admin.permission.destroy'));
        return $data;

    }
}
