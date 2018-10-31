# Laravel 后台管理系统



## 安装

- git clone 到本地
- 执行 `composer install`,创建好数据库
- 配置 **.env** 中数据库连接信息,没有.env请复制.env.example命名为.env
- 执行 `php artisan key:generate`
- 执行 `php artisan migrate`
- 执行 `php artisan db:seed --class=AdminInitSeeder`
- 键入 '域名/admin/login'(后台登录)
- 默认后台账号:root@admin.com 密码:root

