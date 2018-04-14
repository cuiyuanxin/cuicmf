<?php

// +----------------------------------------------------------------------
// | CuiCMF 应用公共文件
// +----------------------------------------------------------------------
// | Copyright (c) 2014-2018 All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 崔元欣 <15811506097@163.com>
// +----------------------------------------------------------------------
use think\Db;
use think\facade\Request;

/**
 * 获取根目录
 * @return type
 */
function root() {
    $root = $phpfile = '';
    $iscgi = (0 === strpos(PHP_SAPI, 'cgi') || false !== strpos(PHP_SAPI, 'fcgi')) ? 1 : 0;
    if ($iscgi) {
        $temp = explode('.php', $_SERVER['PHP_SELF']);
        $phpfile = rtrim(str_replace($_SERVER['HTTP_HOST'], '', $temp[0] . '.php'), '/');
    } else {
        $phpfile = rtrim($_SERVER['SCRIPT_NAME'], '/');
    }
    $root = rtrim(dirname($phpfile), '/');
    return (($root == '/' || $root == '\\') ? '' : $root);
}

/**
 * 自动判断url是http还是https
 * @return type
 */
function is_https() {
    $http_type = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';
    return $http_type;
}

/**
 * 返回站点域名地址
 * @return type
 */
function is_url() {
    return is_https() . Request::host() . Request::rootUrl();
}

/**
 * 验证码是否正确
 * @param type $code
 * @param type $id
 * @return type
 */
function check_verify($code, $id = '') {
    $captcha = new \think\captcha\Captcha();
    return $captcha->check($code, $id);
}

/**
 * 系统加密方法
 * @param string $data 要加密的字符串
 * @param string $key 加密密钥
 * @param int $expire 过期时间 单位 秒
 * @return string
 */
function think_encrypt($data, $key = '', $expire = 0) {
    $key = empty($key) ? 'CuiCMF' : $key;
    $key = md5($key);
    $data = base64_encode($data);
    $x = 0;
    $len = strlen($data);
    $l = strlen($key);
    $char = '';

    for ($i = 0; $i < $len; $i++) {
        if ($x == $l)
            $x = 0;
        $char .= substr($key, $x, 1);
        $x++;
    }

    $str = sprintf('%010d', $expire ? $expire + time() : 0);

    for ($i = 0; $i < $len; $i++) {
        $str .= chr(ord(substr($data, $i, 1)) + (ord(substr($char, $i, 1))) % 256);
    }
    return str_replace(array('+', '/', '='), array('-', '_', ''), base64_encode($str));
}

/**
 * 系统解密方法
 * @param  string $data 要解密的字符串 （必须是think_encrypt方法加密的字符串）
 * @param  string $key 加密密钥
 * @return string
 */
function think_decrypt($data, $key = '') {
    $key = empty($key) ? 'CuiCMF' : $key;
    $key = md5($key);
    $data = str_replace(array('-', '_'), array('+', '/'), $data);
    $mod4 = strlen($data) % 4;
    if ($mod4) {
        $data .= substr('====', $mod4);
    }
    $data = base64_decode($data);
    $expire = substr($data, 0, 10);
    $data = substr($data, 10);

    if ($expire > 0 && $expire < time()) {
        return '';
    }
    $x = 0;
    $len = strlen($data);
    $l = strlen($key);
    $char = $str = '';

    for ($i = 0; $i < $len; $i++) {
        if ($x == $l)
            $x = 0;
        $char .= substr($key, $x, 1);
        $x++;
    }

    for ($i = 0; $i < $len; $i++) {
        if (ord(substr($data, $i, 1)) < ord(substr($char, $i, 1))) {
            $str .= chr((ord(substr($data, $i, 1)) + 256) - ord(substr($char, $i, 1)));
        } else {
            $str .= chr(ord(substr($data, $i, 1)) - ord(substr($char, $i, 1)));
        }
    }
    return base64_decode($str);
}

/**
 * 获取数据库中的配置列表
 * @return array 配置数组
 * @author 崔元欣 <15811506097@163.com>
 */
function lists() {
    $map = ['status' => 1];
    $data = Db::name('config')->where($map)->order('sort')->select();
    $config = array();
    if ($data && is_array($data)) {
        foreach ($data as $value) {
            $parse = parse($value['type'], $value['value']);
            if ($value['type'] != 3) {
                switch ($parse) {
                    case 'true':
                        $parse = true;
                        break;
                    case 'false':
                        $parse = false;
                        break;
                }
            }
            $config[$value['name']] = $parse;
        }
    }
    return $config;
}

/**
 * 根据配置类型解析配置
 * @param  integer $type 配置类型
 * @param  string $value 配置值
 * @author 崔元欣 <15811506097@163.com>
 */
function parse($type, $value) {
    switch ($type) {
        case 3: //解析数组
            $array = preg_split('/[,;\r\n]+/', trim($value, ",;\r\n"));
            if (strpos($value, ':')) {
                $value = array();
                foreach ($array as $val) {
                    list($k, $v) = explode(':', $val);
                    $value[$k] = $v;
                }
            } else {
                $value = $array;
            }
            break;
    }
    return $value;
}

/**
 * 分析枚举类型配置值 格式 a:名称1,b:名称2
 * @param type $string
 * @return type
 * @author 崔元欣 <15811506097@163.com>
 */
function parse_config_attr($string) {
    $array = preg_split('/[,;\r\n]+/', trim($string, ",;\r\n"));
    if (strpos($string, ':')) {
        $value = array();
        foreach ($array as $val) {
            list($k, $v) = explode(':', $val);
            $value[$k] = $v;
        }
    } else {
        $value = $array;
    }
    return $value;
}
