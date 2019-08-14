<?php

// +----------------------------------------------------------------------
// | cuicmf 后台User模块
// +----------------------------------------------------------------------
// | Copyright (c) 2018-2019 All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 崔元欣 <15811506097@163.com>
// +----------------------------------------------------------------------

namespace app\admin\controller;

use think\facade\Config;

class User extends Base {

    /**
     * 获取登录用户信息
     * @return type
     * @author 崔元欣 <15811506097@163.com>
     */
    public function get_user_info() {
        $userId = think_decrypt($this->jwt['userId'], Config::get('cuicmf.uc_auth_key'));
        $userInfo = app()->model('User')->get_user_info($userId, 'id, username, nickname, realname, last_login_ip, last_login_time');

        if(!isset($userInfo['data'])) {
            $userInfo['data'] = [];
        }

        $this->result($userInfo['data'], $userInfo['code'], $userInfo['msg'], 'json');
    }

}