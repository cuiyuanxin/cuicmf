<?php

// +----------------------------------------------------------------------
// | cuicmf 后台公用配置类
// +----------------------------------------------------------------------
// | Copyright (c) 2018-2019F All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 崔元欣 <15811506097@163.com>
// +----------------------------------------------------------------------

return [
    // 后台列表条数
    'admin_list_rows' => 20, 
    // 系统管理员
    'user_administrator' => 1,
    // jwt生效使用时间
    'jwt_nbf' => 0,
    // jwt过期时间
    'jwt_exp' => 3600,
    // 重新发token时间
    'jwt_refresh' => 1800
    
];