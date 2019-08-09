<?php

// +----------------------------------------------------------------------
// | cuicmf User模型
// +----------------------------------------------------------------------
// | Copyright (c) 2018-2019 All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 崔元欣 <15811506097@163.com>
// +----------------------------------------------------------------------

namespace app\admin\model;

use think\Model;
use think\facade\Request;
use think\facade\Config;

class User extends Model {

	// 自动写入数间戳
    protected $autoWriteTimestamp = true;

    // 数据类型字段转换
    protected $type = [
        'id' => 'intval',
        'status' => 'integer'
    ];
    // 自动完成包含新增和更新操作
    protected $auto = [];
    // 自动完成新增操作
    protected $insert = ['password', 'reg_ip'];  
    // 自动完成更新操作
    protected $update = [];  

	/**
     * 登录操作
     * @param  [type] $data 
     * @return [type]       [description]
     * @author 崔元欣 <15811506097@163.com>
     */
    public function login($data) {
        $user = self::where(['username' => $data['username']])->field('id, username, nickname, realname, password, status')->find();
        $password = cui_ucenter_md5($data['password'], Config::get('cuicmf.uc_auth_key'));
        if (empty($user)) {
            return ['code' => 1, 'msg' => '用户名不存在！'];
        } elseif ($user['password'] != $password) {
            return ['code' => 1, 'msg' => '密码错误！'];
        } elseif (0 == $user['status']) {
            return ['code' => 1, 'msg' => '帐号被禁用！'];
        } elseif (2 == $user['status']) {
            return ['code' => 1, 'msg' => '帐号锁定中！'];
        } elseif (3 == $user['status']) {
            return ['code' => 1, 'msg' => '帐号审核中！'];
        } else {
            $data = [
                'last_login_time' => time(),
                'last_login_ip' => ipToint(Request::ip())
            ];
            if (self::where(['id' => $user['id']])->update($data)) {
                if(isset($user['realname']) && $user['realname']) {
                    $nickname =  $user['realname'];
                } elseif(isset($user['nickname']) && $user['nickname']) {
                    $nickname =  $user['nickname'];
                } else {
                    $nickname =  $user['username'];
                }
                $auth = array(
                    'uid' => $user['id'],
                    'nickname' => $nickname,
                    'last_login_time' => $data['last_login_time']
                );
                //记录行为
//                action_log('user_login', 'user', $user['id'], $user['id']);

                return ['data' => $auth, 'code' => 0, 'msg' => '登录成功！'];
            } else {
                return ['code' => 1, 'msg' => '登录失败！'];
            }
        }
    }
}