<?php

// +----------------------------------------------------------------------
// | CuiCMF Admin共用方法
// +----------------------------------------------------------------------
// | Copyright (c) 2014-2018 All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: cuiyuanxin <15811506097@163.com>
// +----------------------------------------------------------------------

namespace app\common\controller;

use think\Controller;
use think\facade\Env;

class Common extends Controller {

    public function initialize() {
        //检查是否安装了
        if (!is_file(Env::get('root_path') . 'public/data/install.lock')) {
            $this->redirect('Install/Login/login');
            exit;
        }
    }

}
