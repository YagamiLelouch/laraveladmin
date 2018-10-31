<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

//注册登录
Route::get('login', 'LoginController@showLoginForm')->name('admin.login');
Route::post('login', 'LoginController@login');
Route::get('logout', 'LoginController@logout');
Route::post('logout', 'LoginController@logout');

//Route::get('/', 'IndexController@index');

Route::get('/', function () {
    return redirect('/admin/index');
});

Route::get('index', ['as' => 'admin.index', 'uses' => function () {
    return redirect('/admin/log-viewer');
}]);



//下面的页面都采用了中间件(auth:admin, menu, authAdmin)
//auth:admin, 代表传值admin给别名为auth的中间件
Route::group(['middleware' => ['auth:admin', 'menu', 'authAdmin']], function () {
    //权限管理路由
    //create页面
    Route::get('permission/{cid}/create', ['as' => 'admin.permission.create', 'uses' => 'PermissionController@create']);
    //以下3个route共用一个方法
    //permission的index页面, 此页面项目除了id为1的user, 其他user都没有涉及
    Route::get('permission/manage', ['as' => 'admin.permission.manage', 'uses' => 'PermissionController@index']);
    //permission里面的几个级别页面
    Route::get('permission/{cid?}', ['as' => 'admin.permission.index', 'uses' => 'PermissionController@index']);
    //permission里面的post数据显示
    Route::post('permission/index', ['as' => 'admin.permission.index', 'uses' => 'PermissionController@index']); //查询

    Route::resource('permission', 'PermissionController', ['names' => ['update' => 'admin.permission.edit', 'store' => 'admin.permission.create']]);


    //角色管理路由
    Route::get('role/index', ['as' => 'admin.role.index', 'uses' => 'RoleController@index']);
    Route::post('role/index', ['as' => 'admin.role.index', 'uses' => 'RoleController@index']);
    Route::resource('role', 'RoleController', ['names' => ['update' => 'admin.role.edit', 'store' => 'admin.role.create']]);


    //用户管理路由
    Route::get('user/index', ['as' => 'admin.user.index', 'uses' => 'UserController@index']);  //用户管理
    Route::post('user/index', ['as' => 'admin.user.index', 'uses' => 'UserController@index']);
    Route::resource('user', 'UserController', ['names' => ['update' => 'admin.role.edit', 'store' => 'admin.role.create']]);


    //产品管理路由
    Route::get('product/index', ['as' => 'admin.product.index', 'uses' => 'ProductController@index']);
    Route::post('product/index', ['as' => 'admin.product.index', 'uses' => 'ProductController@index']);
    Route::resource('product', 'ProductController', ['names' => ['update' => 'admin.product.edit', 'store' => 'admin.product.create']]);
    Route::get('product/getcategory', 'ProductController@getCategory');
    //用户产品管理路由
    Route::get('userproduct/index', ['as' => 'admin.userproduct.index', 'uses' => 'ProductController@userProductIndex']);
    Route::post('userproduct/index', ['as' => 'admin.userproduct.index', 'uses' => 'ProductController@userProductIndex']);

    
    
    //Api管理路由
    Route::get('api/index', ['as' => 'admin.api.index', 'uses' => 'ApiController@index']);
    Route::post('api/index', ['as' => 'admin.api.index', 'uses' => 'ApiController@index']);
    Route::resource('api', 'ApiController', ['names' => ['update' => 'admin.api.edit', 'store' => 'admin.api.create']]);
    Route::get('api/getcategory', 'ApiController@getCategory');
    //导入excel数据
    Route::post('api/readExcel', 'ApiController@readExcel')->name('admin.api.readExcel');
    //用户Api管理路由
    Route::get('userapi/index', ['as' => 'admin.userapi.index', 'uses' => 'ApiController@userApiIndex']);
    Route::post('userapi/index', ['as' => 'admin.userapi.index', 'uses' => 'ApiController@userApiIndex']);

    //图片上传
    Route::post('uploadImag', 'UploadController@uploadImg')->name('uploadImg');





});



