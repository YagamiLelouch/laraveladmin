<?php

namespace App\Providers;

use App\Http\Requests\Request;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use League\Flysystem\Exception;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];


    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    //boot服务提供者,默认执行,本项目通过gate授权
    //Gates 是用来决定用户是否授权执行给定的动作的闭包函数
    public function boot(GateContract $gate)
    {
        if (!empty($_SERVER['SCRIPT_NAME']) && strtolower($_SERVER['SCRIPT_NAME']) === 'artisan') {
            return false;
        }

        //user的id为1的不用判断, 这是一个测试账号
        $gate->before(function ($user, $ability) {
            if ($user->id === 1) {
                return true;
            }
        });

        $this->registerPolicies($gate);

        //获取所有权限
        $permissions = \App\Models\Admin\Permission::with('roles')->get();

        foreach ($permissions as $permission) {
            //define一个gate,2个参数,第一个是权限,第二个Eloquent model
            //第一个参数是数据表的permission名字, 第二个参数是user权限的判断
            $gate->define($permission->name, function ($user) use ($permission) {
                //判断是否有某权限
                return $user->hasPermission($permission);
            });
        }
    }


}
