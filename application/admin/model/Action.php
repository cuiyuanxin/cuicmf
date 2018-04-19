<?php

// +----------------------------------------------------------------------
// | CuiCMF ActionModel模型
// +----------------------------------------------------------------------
// | Copyright (c) 2014-2018 All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: cuiyuanxin <15811506097@163.com>
// +----------------------------------------------------------------------

namespace app\admin\model;

use think\Model;

class Action extends Model {

    //开启时间自动写入
    protected $autoWriteTimestamp = true;
    // 定义时间戳字段名
    protected $auto = [];
    protected $insert = ['status' => 1];
    protected $update = ['update_time'];
    protected $type = [
        'status' => 'integer'
    ];

    //自动完成更新时间写入
    protected function setUpdateTimeAttr($value) {
        return time();
    }

}
