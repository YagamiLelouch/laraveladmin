<?php

namespace App\Models\Admin;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class AdminUser extends Authenticatable
{
    use Notifiable;
    protected $table='admin_users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
//    protected $hidden = ['password', 'remember_token'];

    //用户角色
    public function roles()
    {
        return $this->belongsToMany(Role::class,'admin_role_user','user_id','role_id');
    }

    // 判断用户是否具有某个角色
    public function hasRole($role)
    {
        //???什么时候才是string?
        //如果为string, 则会返回true or false, 而不是拥有权限数量, 什么时候会用到string?
        if (is_string($role)) {
            return $this->roles->contains('name', $role);
        }

        //获取user拥有这些role的role数量
        return !!$role->intersect($this->roles)->count();
    }

    // 判断用户是否具有某权限
    public function hasPermission($permission)
    {
        //$permisssion为数据表里面的所有权限之一

        if (is_string($permission)) {
            $permission = Permission::where('name',$permission)->first();
            if (!$permission) return false;
        }
        //$permission->roles判断有多少role中含有改permission
        return $this->hasRole($permission->roles);
    }

    // 给用户分配角色
    public function assignRole($role)
    {
        return $this->roles()->save($role);
    }


    //角色整体添加与修改
    public function giveRoleTo(array $RoleId){
        //移除中间表的关联关系
        $this->roles()->detach();
        //获取所有选择的role
        $roles=Role::whereIn('id',$RoleId)->get();
        //遍历来分配role给user
        foreach ($roles as $v){
            $this->assignRole($v);
        }
        return true;
    }
}
