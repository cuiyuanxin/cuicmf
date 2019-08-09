<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

Route::group('admin', function () {

    // 登录验证码
    Route::get('login/verify', 'admin/login/verify')->ext('');
    // 登录
    Route::post('login/login', 'admin/login/login')->ext('');
    // 表单验证
    Route::post('login/validation', 'admin/login/validation')->ext('');

})->header('Access-Control-Allow-Headers', 'Authorization, Content-Type, If-Match, If-Modified-Since, If-None-Match, If-Unmodified-Since, X-Requested-With, X-URL-PATH, X-Token')->allowCrossDomain();

//->pattern([])
//->header('Access-Control-Allow-Origin','localhost:80808')
//->header('Access-Control-Allow-Credentials', 'true')