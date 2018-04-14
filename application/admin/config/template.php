<?php

// +----------------------------------------------------------------------
// | CuiCMF 后台模板设置
// +----------------------------------------------------------------------
// | Copyright (c) 2014-2018 All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: cuiyuanxin <15811506097@163.com>
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | 模板设置
// +----------------------------------------------------------------------
use think\facade\Request;

return [
    // 视图输出字符串内容替换
    'tpl_replace_string' => [
        '__PUBLIC__' => root() . '/static',
        '__STATIC__' => root() . '/static/static',
//        '__ADDONS__' => '/static/' . request()->module() . '/addons',
        '__IMG__' => root() . '/static/' . Request::module() . '/images',
        '__CSS__' => root() . '/static/' . Request::module() . '/css',
        '__JS__' => root() . '/static/' . Request::module() . '/js',
        '__STATICS__' => root() . '/static/' . Request::module() . '/static',
    ],
    //模板标签预加载
    'taglib_pre_load' => 'app\admin\taglib\Form',
];
