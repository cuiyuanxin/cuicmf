<?php

// +----------------------------------------------------------------------
// | cuicmf 后台Login模块
// +----------------------------------------------------------------------
// | Copyright (c) 2018-2019 All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 崔元欣 <15811506097@163.com>
// +----------------------------------------------------------------------

namespace app\admin\controller;

class Login extends Base {

    /**
     * 后台登录
     * @return type
     * @author 崔元欣 <15811506097@163.com>
     */
    public function login() {
        // 获取post数据
        $data = $this->request->post('', [], 'trim');
        // 验证数据
        $result = $this->validate($data, 'app\admin\validate\User.login');

        if (true !== $result) {
            $this->result('', 1, $result, 'json');
        }
        $userModel = app()->model('User');

        $user = $userModel->login($data);
        if(isset($user) && $user) {
            $resultData = isset($user['data']) ? $user['data'] : '';
            $this->result($resultData, $user['code'], $user['msg'], 'json');
        } else {
            $this->result('', -1, '接口请求出错，请联系管理员！', 'json');
        }
    }


    /**
     * 验证码
     * @return type
     * @author 崔元欣 <15811506097@163.com>
     */
    public function verify() {
        ob_end_clean();
        $verify = new \think\captcha\Captcha([
            'fontSize' => 28,
            'imageH' => 76,
            'imageW' => 0,
            'length' => 6,
            'useCurve' => true
        ]);
        $verifyCode = $verify->entry(1);
        
        return $verifyCode;
    }

}