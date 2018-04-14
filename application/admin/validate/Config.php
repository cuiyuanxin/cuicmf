<?php

// +----------------------------------------------------------------------
// | CuiCMF User验证器
// +----------------------------------------------------------------------
// | Copyright (c) 2014-2018 All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: cuiyuanxin <15811506097@163.com>
// +----------------------------------------------------------------------

namespace app\admin\validate;

use think\Validate;

class Config extends Validate {

    protected $rule = [
        'name' => 'require|checkOnly',
        'title' => 'require'
    ];
    protected $message = [
        'name.require' => '请输入配置标识名称！',
        'title.require' => '请输入配置标题！'
    ];

    //验证用户名唯一
    protected function checkOnly($value, $rule, $data) {
        if (!isset($data['id'])) {
            if (!Validate::unique($value, 'config', $data, 'name')) {
                return '配置标识已存在！';
            }
        }
        return true;
    }

}
