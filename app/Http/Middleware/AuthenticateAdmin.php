<?php

namespace App\Http\Middleware;

use Closure;
use Route, URL, Auth;

class AuthenticateAdmin
{

    //为什么设置这个并没有作用?????
    protected $except = [
        'admin/index',
    ];

    /**
     * Handle an incoming request.
     * @param $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //user的id为1的不用判断, 这是一个测试账号
        if (Auth::guard('admin')->user()->id === 1) {
            return $next($request);
        }

        //之前链接
        $previousUrl = URL::previous();


        //判断string是否以后面的string开头
        //Route::currentRouteName()获取route的name
        $routeName = starts_with(Route::currentRouteName(), 'admin.') ? Route::currentRouteName() : 'admin.' . Route::currentRouteName();
        //权限判断, 如果没有权限, 而且是ajax或不是get方法, 响应403, else响应403错误页面
        if (!\Gate::forUser(auth('admin')->user())->check($routeName)) {
            if ($request->ajax() && ($request->getMethod() != 'GET')) {
                return response()->json([
                    'status' => -1,
                    'code'   => 403,
                    'msg'    => '您没有权限执行此操作',
                ]);
            } else {
                return response()->view('admin.errors.403', compact('previousUrl'));
            }
        }

        return $next($request);
    }
}
