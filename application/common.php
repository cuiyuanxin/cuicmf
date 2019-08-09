<?php

// +----------------------------------------------------------------------
// | cuicmf 公共方法文件
// +----------------------------------------------------------------------
// | Copyright (c) 2018-2019 All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 崔元欣 <15811506097@163.com>
// +----------------------------------------------------------------------
// 应用公共文件

/**
 * 验证码是否正确
 * @param type $code 验证码
 * @param type $id ID
 * @return type
 */
function check_verify($code, $id = '') {
    $captcha = new \think\captcha\Captcha();
    return $captcha->check($code, $id);
}

/**
 * 系统非常规MD5加密方法
 * @param  string $str 要加密的字符串
 * @return string
 * @author 崔元欣 <15811506097@163.com>
 */
function cui_ucenter_md5($str, $key = '') {
    $key = empty($key) ? 'CuiCMF' : $key;
    return '' === $str ? '' : md5(sha1($str) . $key);
}

/**
 * 把IP转换成整型
 * @param type $ip ip
 * @return type
 */
function ipToint($ip){
    $iparr = explode('.',$ip);
    $num = 0;
    for($i=0;$i<count($iparr);$i++){
        $num += intval($iparr[$i]) * pow(256,count($iparr)-($i+1));
    }
    return $num;
}