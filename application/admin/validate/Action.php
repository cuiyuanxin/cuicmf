<?php

// +----------------------------------------------------------------------
// | CuiCMF Action验证器
// +----------------------------------------------------------------------
// | Copyright (c) 2014-2018 All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: cuiyuanxin <15811506097@163.com>
// +----------------------------------------------------------------------

namespace app\admin\validate;

use think\Validate;
use think\Db;

class Action extends Validate {

    protected $rule = [
        'name' => ['require', 'regex' => '/^[a-zA-Z]\w{0,39}$/', 'checkOnly'],
        'title' => 'require|max:80',
        'remark' => 'require|max:140'
    ];
    protected $message = [
        'name.require' => '行为标识必须！',
        'name.regex' => '标识不合法！',
        'title.require' => '标题不能为空！',
        'title.max' => '标题长度不能超过80个字符！',
        'remark.require' => '行为描述不能为空！',
        'remark.max' => '行为描述不能超过140个字符！'
    ];

    //验证用户名唯一
    protected function checkOnly($value, $rule, $data) {
        if (!isset($data['id'])) {
            if (!Validate::unique($value, 'action', $data, 'name')) {
                return '行为标识已存在！';
            }
        } else {
            $id = Db::name('action')->where(['name' => $data['name']])->value('id');
            if (isset($id) && $id) {
                if (isset($data['id']) && $data['id'] == $id) {
                    return true;
                } else {
                    return '行为标识已存在！';
                }
            }
        }
        return true;
    }

}
