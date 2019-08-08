<?php

// +----------------------------------------------------------------------
// | cuicmf User验证类
// +----------------------------------------------------------------------
// | Copyright (c) 2018-2019 All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 崔元欣 <15811506097@163.com>
// +----------------------------------------------------------------------

namespace app\admin\validate;

use think\Validate;

class User extends Validate {
    
    protected $rule =   [
        'group_id' => 'require|checkLength',
        'username' => 'require|min:6|max:20|alphaDash|unique:user',
        'password' => 'require|min:6|max:25|regex:[A-Za-z0-9_@!~-]+',
        'confirm_password' => 'require|max:25|alphaNum|confirm:password',    
        'realname' => 'max:10',
        'nickname' => 'max:10',
        'mobile' => 'require|mobile|unique:user',
        'email' => 'require|email|unique:user',
        'status' => 'require',
        'verify' => 'require|length:6|alphaNum|checkVerify'
    ];
    
    protected $message = [
        'group_id.require' => '用户组必须填写!',
        'username.require' => '请输入用户名！',
        'username.min' => '用户名最短6个字符！',
        'username.max' => '用户名最长20个字符！',
        'username.alphaDash' => '用户名只允许字母和数字，下划线_及破折号-!',
        'username.unique' => '用户名已存在!',
        'password.require' => '请输入密码！',
        'password.min' => '密码最短6个字符！',
        'password.max' => '密码最长25个字符！',
        'password.regex' => '密码只支持大小写英文字母,数字和特殊符号(_@!~-)！',
        'confirm_password.require' => '用户密码必须填写!',
        'confirm_password.max' => '用户密码最长25个字符!',
        'confirm_password.alphaNum' => '用户密码只允许字母和数字!',
        'confirm_password.confirm' => '用户密码两次输入不一致!',
        'realname.max' => '真实姓名不能大于10个字符!',
        'nickname.max' => '昵称不能大于10个字符!',
        'mobile.require' => '手机号必须填写!',
        'mobile.mobile' => '手机号格式不正确!',
        'mobile.unique' => '手机号已存在!',
        'email.require' => '邮箱必须填写!',
        'email.email' => '邮箱格式不正确!',
        'email.unique' => '邮箱已存在!',
        'status.require' => '用户组状态必须选择!',
        'verify.require' => '请输入验证码！',
        'verify.length' => '请输入6个字符的验证码！',
        'verify.alphaNum' => '验证码只支持英文字母,数字！'
    ];
    
    /**
     * create 验证场景定义
     * @return type
     */
    public function sceneCreate() {
        return $this->only(['group_id', 'username', 'password', 'confirm_password', 'realname', 'nickname', 'mobile', 'email', 'status']);
    } 
    
    /**
     * update 验证场景定义
     * @return type
     */
    public function sceneUpdate() {
        return $this->only(['group_id', 'realname', 'nickname', 'mobile', 'email', 'status']);
    } 
    
    /**
     * login 验证场景定义
     * @return type
     */
    public function sceneLogin() {
        return $this->only(['username', 'password', 'verify'])->remove('username', 'unique')->append('username', 'checkOnlyLogin');
    } 
    
    /**
     * 验证用户组个数
     * @param type $value
     * @param type $rule
     * @param type $data
     * @return boolean|string
     */
    public function checkLength($value, $rule, $data) {
        $length = explode(',', $value);
        if(isset($length) && count($length) > 3) {
            return '用户组最多选择3个!！';
        }
        return true;
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
     * 验证用户名是否存在
     * @param type $value
     * @param type $rule
     * @param type $data
     * @return boolean|string
     * @author 崔元欣 <15811506097@163.com>
     */
    protected function checkOnlyLogin($value, $rule, $data) {
        if (!$this->unique($value, 'user', $data, 'username')) {
            return true;
        }
        return '用户名不存在，请联系管理员！';
    }
}
