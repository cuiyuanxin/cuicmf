<?php

// +----------------------------------------------------------------------
// | CuiCMF 后台公用方法
// +----------------------------------------------------------------------
// | Copyright (c) 2014-2018 All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 崔元欣 <15811506097@163.com>
// +----------------------------------------------------------------------
use think\Db;
use think\facade\Config;
use think\facade\Session;
use think\facade\Request;

/**
 * 检测用户是否登录
 * @return integer 0-未登录，大于0-当前登录用户ID
 */
function is_login() {
    $user = Session::get('user_auth.uid');
    if (empty($user)) {
        return 0;
    } else {
        return (int) think_decrypt($user, Config::get('config.uc_auth_key'));
    }
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
 * 获取缓存信息
 * @param type $str
 */
function user_auth($str = '') {
    $user_auth = Session::get('user_auth');
    if ($str) {
        return think_decrypt($user_auth[$str], Config::get('config.uc_auth_key'));
    }
    return $str;
    ;
}

/**
 * 获取当前用户组
 * @param type $id
 */
function group_id($id = 0, $find = 'title') {
    if ($id > 0) {
        return Db::name('auth_group_access')->alias('aga')->join('auth_group ag ', 'ag.id= aga.group_id')->where(['uid' => $id])->value($find);
    }
    return $id;
}

/**
 * 获取配置的分组
 * @param string $group 配置分组
 * @return string
 */
function get_config_group($group = 0) {
    $list = Config::get('config.config_group_list');
    return $group ? $list[$group] : '';
}

/**
 * 获取配置的类型
 * @param string $type 配置类型
 * @return string
 */
function get_config_type($type = 0) {
    $list = Config::get('config.config_type_list');
    return $list[$type];
}

/**
 * 返回status状态文本
 */
function int_to_string($status, $map = array(1 => '正常', -1 => '删除', 0 => '禁用', 2 => '未审核', 3 => '草稿')) {
    if ($status === false || $status === null) {
        return $status;
    }
    $status = (int) $status;
    return $map[$status];
}

/**
 * 权限树格式化
 * @staticvar array $node
 * @param type $data 数据组
 * @param type $pid 父级
 * @param type $lveal 级别
 * @param type $reset 
 * @return type
 */
function toTree($data = null, $pid = 0, $reset = false) {
    static $node = [];
    if (empty($data) || !is_array($data)) {
        return [];
    }
    if ($reset) {
        $node = [];
    }
    //父节点
    if ($node === []) {
        foreach ($data as $item) {
            if ($item['pid'] == $item['id']) {
                //避免死循环
                continue;
            }
            if (isset($node[$item['pid']])) {
                $node[$item['pid']][] = $item;
            } else {
                $node[$item['pid']] = [$item];
            }
        }
    }
    $tree = [];
    foreach ($data as $item) {
        if ($pid == $item['pid']) {
            if (isset($node[$item['id']])) {
                if($item['pid'] == 0){
                    $item['class'] = $item['id'];
                } else {
                    $item['class'] = $item['pid'];
                }
//                dump($item['id']);
//                dump($node[$item['id']]);
                $item['_child'] = toTree($node[$item['id']], $item['id']);
            } else {
                $item['class'] = $item['id'];
            }
            $tree[] = $item;
        }
    }
//    dump($tree);
    return $tree;
}

/**
 * 多枚举选中判断
 * @param type $key
 * @param type $arr
 * @author 崔元欣 <15811506097@163.com>
 */
function extra_selected($key = '', $arr = []) {
    $arr = explode(',', $arr);
    if (is_numeric($key) && count($arr) > 0) {
        return in_array($key, $arr);
    }
    return false;
}

/**
 * 格式化字节大小
 * @param  number $size 字节数
 * @param  string $delimiter 数字和单位分隔符
 * @return string            格式化后的带单位的大小
 */
function format_bytes($size, $delimiter = '') {
    $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
    for ($i = 0; $size >= 1024 && $i < 5; $i++)
        $size /= 1024;
    return round($size, 2) . $delimiter . $units[$i];
}
