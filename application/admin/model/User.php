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
use Firebase\JWT\JWT;

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
        $key = Config::get('cuicmf.uc_auth_key');

        $user = self::where(['username' => $data['username']])->field('id, password, status')->find();
        $password = cui_ucenter_md5($data['password'], $key);
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

                $nowtime = time();

				$token = [
                    'iss' => 'http://www.cuicmf.com', //签发者
                    'sub' => think_encrypt($user['id'], Config::get('cuicmf.uc_auth_key')),
                    'aud' => 'http://www.cuicmf.com', //jwt所面向的用户
                    'iat' => $nowtime, //签发时间
                    'nbf' => $nowtime + Config::get('cuicmf.jwt_nbf'), //在什么时间之后该jwt才可用
                    'exp' => $nowtime + Config::get('cuicmf.jwt_exp'), //过期时间-10min
                    'jti' => think_encrypt($user['id'], Config::get('cuicmf.uc_auth_key'))
                ];
				// 生成token
				$jwt = JWT::encode($token, $key);

                return ['data' => $jwt, 'code' => 0, 'msg' => '登录成功！'];
            } else {
                return ['code' => 1, 'msg' => '登录失败！'];
            }
        }
    }

    /**
     * 获取用户数据
     * @param  integer $userId 用户id
     * @param  string  $field  查询字段
     * @return [type]          [description]
     */
    public function get_user_info($userId = 0, $field = '*') {
        if($userId) {
            $result = self::where(['id' => $userId])->field($field)->find();

            if($result) {
                return ['data' => $result, 'code' => 0, 'msg' => '用户数据获取成功！'];
            }

            return ['code' => 1, 'msg' => '用户数据获取失败！'];
        }

        return ['code' => 1, 'msg' => '用户数据获取失败！'];
    }
}