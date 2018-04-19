<?php

// +----------------------------------------------------------------------
// | CuiCMF 登录模块(不受权限约束)
// +----------------------------------------------------------------------
// | Copyright (c) 2014-2018 All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 崔元欣 <15811506097@163.com>
// +----------------------------------------------------------------------

namespace app\admin\controller;

use think\Controller;
use think\facade\Session;
use think\facade\Cookie;
use think\facade\Config;

class Login extends Controller {

    /**
     * 后台登录
     * @return type
     * @author 崔元欣 <15811506097@163.com>
     */
    public function login() {
        //已登录
        if (is_login()) {
            $this->redirect('Index/index');
        }
        if ($this->request->isAjax()) {
            //获取post数据
            $data = $this->request->post();
            // 验证数据
            $result = $this->validate($data, 'app\admin\validate\User.login');
            if (true !== $result) {
                $this->error($result, '', -10);
            }
            $user = app()->model('User');
            $ret = $user->login($data['username'], $data['password']);
            if (isset($ret['code']) && $ret['code'] === 1) {
                $this->success($ret['msg'], url('Index/index'));
            } else if (isset($ret['code']) && $ret['code'] === 0) {
                $this->error($ret['msg'], url('Login/login'));
            } else {
                $this->error($ret['msg'], '', -9);
            }
        } else {
            $this->assign('meta_title', '登录');
            return $this->fetch();
        }
    }

    /**
     * 退出
     * @author 崔元欣 <15811506097@163.com>
     */
    public function logout() {
        Session::delete('user_auth');
        Cookie::delete('user_auth');
        $this->success('退出成功，前往登录页面', url('Login/login'));
    }

    /**
     * 验证码
     * @return type
     * @author 崔元欣 <15811506097@163.com>
     */
    public function verify() {
        ob_end_clean();
        $verify = new \think\captcha\Captcha(
                array(
            'fontSize' => 28,
            'imageH' => 76,
            'imageW' => 0,
            'length' => 6,
            'useCurve' => true,
                )
        );
        return $verify->entry(1);
    }

    /**
     * 登录超时检测
     */
    public function timeout() {
        //设置超时为10分
        $nowtime = time();
        $s_time = think_decrypt(Session::get('user_auth.logintime'), Config::get('config.uc_auth_key'));
        if ($s_time) {
            if (($nowtime - $s_time) > 1800) {
                Session::delete('user_auth');
                Cookie::delete('user_auth');
                $this->error('登录超时！', url('Login/login'));
            }
        } else {
            Session::delete('user_auth');
            Cookie::delete('user_auth');
            $this->error('登录超时！', url('Login/login'));
        }
    }

}
