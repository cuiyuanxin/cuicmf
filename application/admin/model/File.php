<?php

// +----------------------------------------------------------------------
// | CuiCMF FileModel模型
// +----------------------------------------------------------------------
// | Copyright (c) 2014-2018 All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: cuiyuanxin <15811506097@163.com>
// +----------------------------------------------------------------------

namespace app\admin\model;

use think\Model;
use think\facade\Request;

class File extends Model {

    //开启时间自动写入
    protected $autoWriteTimestamp = true;
    protected $insert = ['status' => 1];
    protected $type = [
        'status' => 'integer',
        'type' => 'integer'
    ];

    

}
