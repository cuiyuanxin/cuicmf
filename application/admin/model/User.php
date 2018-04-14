<?php

// +----------------------------------------------------------------------
// | CuiCMF UserModel模型
// +----------------------------------------------------------------------
// | Copyright (c) 2014-2018 All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 崔元欣 <15811506097@163.com>
// +----------------------------------------------------------------------

namespace app\admin\model;

use think\Db;
use think\Model;
use think\facade\Request;
use think\facade\Config;
use think\facade\Session;
use think\facade\Cookie;

class User extends Model {

    //开启时间自动写入
    protected $autoWriteTimestamp = true;
    // 定义时间戳字段名
    protected $createTime = 'reg_time';
    protected $auto = ['name', 'nickname'];
    protected $insert = ['reg_ip', 'status' => 1, 'password'];
    protected $update = ['update_time'];
    protected $type = [
        'status' => 'integer',
        'id' => 'integer',
        'group_id' => 'integer'
    ];

    protected static function init() {
        //新增后
        self::afterInsert(function ($user) {
            $accdata = [
                'uid' => $user['id'],
                'group_id' => $user['group_id']
            ];
            //添加管理组
            $ret = Db::name('auth_group_access')->insert($accdata);
            if ($ret === false) {
                self::destroy($user['id']);
                return false;
            }
            return true;
        });
        //更新后
        self::afterUpdate(function ($user) {
            $accdata = [
                'group_id' => $user['group_id']
            ];
            //添加管理组
            $ret = Db::name('auth_group_access')->where(['uid' => $user['id']])->update($accdata);
            if ($ret === false) {
                return false;
            }
            return true;
        });
    }

    /**
     * 自动完成name
     * @param type $value
     * @return type
     * @author 崔元欣 <15811506097@163.com>
     */
    protected function setNameAttr($value) {
        $data = self::getData();
        if (isset($data['id'])) {
            if (!$value) {
                return self::where(['id' => $data['id']])->value('username');
            }
        } else {
            if (isset($data['username']) && !$value) {
                return $data['username'];
            }
        }
        return $value;
    }

    /**
     * 自动完成nickname
     * @param type $value
     * @return type
     * @author 崔元欣 <15811506097@163.com>
     */
    protected function setNicknameAttr($value) {
        $data = self::getData();
        if (isset($data['id'])) {
            if (!$value) {
                return self::where(['id' => $data['id']])->value('username');
            }
        } else {
            if (isset($data['username']) && !$value) {
                return $data['username'];
            }
        }
        return $value;
    }

    /**
     * 自动完成注册ip
     * @param type $value
     * @return type
     * @author 崔元欣 <15811506097@163.com>
     */
    protected function setRegIpAttr($value) {
        return ip2long(Request::ip());
    }

    /**
     * 自动完成密码加密
     * @param type $value
     * @return type
     * @author 崔元欣 <15811506097@163.com>
     */
    protected function setPasswordAttr($value) {
        return cui_ucenter_md5($value, Config::get('config.uc_auth_key'));
    }

    /**
     * 自动完成更新时间写入
     * @param type $value
     * @return type
     * @author 崔元欣 <15811506097@163.com>
     */
    protected function setUpdateTimeAttr($value) {
        return time();
    }

    /**
     * 登录方法
     * @param type $username 用户名
     * @param type $password 用户密码
     * @param type $aggre 用户聚合扩展
     * @author 崔元欣 <15811506097@163.com>
     */
    public function login($username = '', $password = '', $aggre = '') {
        $user = self::where(['username' => $username, 'status' => 1])->field('id, username, nickname, password, status')->find();
        $key = Config::get('config.uc_auth_key');
        $password = cui_ucenter_md5($password, $key);
        if (empty($user)) {
            return ['msg' => '用户名错误！'];
        } elseif ($user['password'] != $password) {
            return ['msg' => '密码错误！'];
        } elseif (0 == $user['status']) {
            return ['msg' => '帐号被禁用！'];
        } elseif (2 == $user['status']) {
            return ['msg' => '帐号审核中！'];
        } else {
            $data = [
                'last_login_time' => time(),
                'last_login_ip' => ip2long(Request::ip())
            ];
            $auth = array(
                'uid' => think_encrypt($user['id'], $key),
                'username' => think_encrypt($user['username'], $key),
                'nickname' => think_encrypt($user['nickname'], $key),
                'last_login_time' => think_encrypt($data['last_login_time'], $key),
                'logintime' => think_encrypt(time(), $key)
            );
            if (self::where(['id' => $user['id']])->update($data)) {
                Session::set('user_auth', $auth);
                Cookie::set('user_auth', $auth);
                //记录行为
//                action_log('user_login', 'user', $admin['id'], $admin['id']);
                return ['code' => 1, 'msg' => '登录成功'];
            } else {
                return ['code' => 0, 'msg' => '登录失败'];
            }
        }
    }

}
