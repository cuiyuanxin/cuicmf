<?php

// +----------------------------------------------------------------------
// | CuiCMF User验证器
// +----------------------------------------------------------------------
// | Copyright (c) 2014-2018 All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: cuiyuanxin <15811506097@163.com>
// +----------------------------------------------------------------------

namespace app\admin\validate;

use think\Validate;
use think\facade\Config;

class User extends Validate {

    protected $rule = [
        'group_id' => 'require|number',
        'username' => 'require|min:5|max:60',
        'password' => 'require|min:6|max:64',
        'new_password' => 'require|min:6|max:64',
        'confirm_password' => 'require|min:6|max:64|confirm:password',
        'mobile' => 'require|number|length:11|mobile',
        'email' => 'require|email',
        'verify' => 'require|checkVerify'
    ];
    protected $message = [
        'group_id.require' => '请选择所属用户组！',
        'group_id.number' => '用户组ID只能是数字！',
        'username.require' => '请输入用户名！',
        'username.min' => '用户名最少5个字符！',
        'username.max' => '用户名最多不能超过60个字符！',
        'password.require' => '请输入密码！',
        'password.min' => '密码最少6个字符！',
        'password.max' => '密码最多不能超过64个字符！',
        'new_password.require' => '请输入新密码！',
        'new_password.min' => '密码最少6个字符！',
        'new_password.max' => '密码最多不能超过64个字符！',
        'confirm_password.require' => '请输入确认密码！',
        'confirm_password.min' => '密码最少6个字符！',
        'confirm_password.max' => '密码最多不能超过64个字符！',
        'confirm_password.confirm' => '两次输入的密码不一致！',
        'mobile.require' => '请输入手机号！',
        'mobile.number' => '手机号只能是数字！',
        'mobile.length' => '请输入11位正确手机号！',
        'mobile.mobile' => '手机号格式不正确！',
        'email.require' => '请输入邮箱！',
        'email.email' => '邮箱格式错误！',
        'verify.require' => '请输入验证码！'
    ];

    /**
     * 验证用户名唯一
     * @param type $value
     * @param type $rule
     * @param type $data
     * @return boolean|string
     */
    protected function checkOnly($value, $rule, $data) {
        if (!isset($data['id'])) {
            if (!Validate::unique($value, 'user', $data, 'username')) {
                return '用户名已存在！';
            }
        }
        return true;
    }

    /**
     * exit 验证场景定义
     * @return type
     */
    public function sceneCreate() {
        return $this->only(['group_id', 'username', 'password', 'confirm_password', 'mobile', 'email'])
                        ->append('username', 'checkOnly');
    }

    /**
     * exit 验证场景定义
     * @return type
     */
    public function sceneEdit() {
        return $this->only(['group_id', 'mobile', 'email']);
    }

    /**
     * 验证用户名是否存在
     * @param type $value
     * @param type $rule
     * @param type $data
     * @return boolean|string
     * @author 崔元欣 <15811506097@163.com>
     */
    protected function checkOnlyLogin($value, $rule, $data) {
        if (!Validate::unique($value, 'user', $data, 'username')) {
            return true;
        }
        return '用户名不存在，请联系管理员！';
    }

    /**
     * 验证验证码是否正确
     * @param type $value
     * @param type $rule
     * @param type $data
     * @return boolean|string
     * @author 崔元欣 <15811506097@163.com>
     */
    protected function checkVerify($value, $rule, $data) {
        if (check_verify($value, 1) === true) {
            return true;
        }
        return '验证码错误！';
    }

    /**
     * login 验证场景定义
     * @return type
     */
    public function sceneLogin() {
        return $this->only(['username', 'password', 'verify'])
                        ->append('username', 'checkOnlyLogin');
    }
    
    /**
     * 修改密码是对比两次密码是否相同
     * @param type $value
     * @param type $rule
     * @param type $data
     */
    protected function checkConfirm($value, $rule, $data){
        if(isset($data['new_password']) && isset($data['confirm_password']) && $data['new_password'] !== $data['confirm_password']){
            return '两次输入的密码不一致！';
        }
        return true;
    }
    
    /**
     * change 验证场景定义
     * @return type
     */
    public function sceneChange() {
        return $this->only(['password', 'new_password', 'confirm_password'])
                        ->remove('confirm_password', 'confirm')
                        ->append('confirm_password', 'checkConfirm');
    }

}
