<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param  string|null $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {

        //Auth:guard($guard)表示调用 config/auth.php 里面的driver和provider, 来控制访问的session和user表. 在这里, 会调用admin里面的这两个配置
        //这里的guest()是来判断用户是否为访客, 如果是管理员, 则会为false, 不会执行if的内容
        //如果guest使用ajax访问或json, 响应401; else, 返回登录页面
        if (Auth::guard($guard)->guest()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response('Unauthorized.', 401);
            } else {
                $login_path = [
                    'admin' => '/admin/login',
                ];
                $url = empty($guard) ? '/login' : (isset($login_path[$guard]) ? $login_path[$guard] : '/login');

                return redirect()->guest($url);
            }
        }

        return $next($request);
    }
}
