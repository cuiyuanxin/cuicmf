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
function int_to_string($status, $map = [1 => '正常', -1 => '删除', 0 => '禁用', 2 => '未审核', 3 => '草稿']) {
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
                if ($item['pid'] == 0) {
                    $item['class'] = $item['id'];
                } else {
                    $item['class'] = $item['pid'];
                }
                $item['_child'] = toTree($node[$item['id']], $item['id']);
            } else {
                $item['class'] = $item['id'];
            }
            $tree[] = $item;
        }
    }
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

/**
 * 清空缓存
 * @param type $dir
 */
function deldir($dir) {
    $dh = opendir($dir);
    while ($file = readdir($dh)) {
        if ($file != "." && $file != "..") {
            $fullpath = $dir . "/" . $file;
            if (!is_dir($fullpath)) {
                unlink($fullpath);
            } else {
                deldir($fullpath);
            }
        }
    }
}

/**
 * 记录行为日志，并执行该行为的规则
 * @param string $action 行为标识
 * @param string $model 触发行为的模型名
 * @param int $record_id 触发行为的记录id
 * @param int $user_id 执行行为的用户id
 * @return boolean
 * @author 崔元欣 <15811506097@163.com>
 */
function action_log($action = null, $model = null, $record_id = null, $user_id = null) {
    //参数检查
    if (empty($action) || empty($model) || empty($record_id)) {
        return '参数不能为空';
    }
    if (empty($user_id)) {
        $user_id = is_login();
    }
    //查询行为,判断是否执行
    $action_model = app()->model('Action');
    $action_info = $action_model::where('name', $action)->find();
    if ($action_info['status'] != 1) {
        return '该行为被禁用或删除';
    }
    //插入行为日志
    $data['action_id'] = $action_info['id'];
    $data['user_id'] = $user_id;
    $data['action_ip'] = ip2long(Request::ip());
    $data['model'] = $model;
    $data['record_id'] = $record_id;
    $data['method'] = Request::method();
    $data['url'] = Request::url();
    $data['create_time'] = time();
    //解析日志规则,生成日志备注
    if (!empty($action_info['log'])) {
        if (preg_match_all('/\[(\S+?)\]/', $action_info['log'], $match)) {
            $log['user'] = $user_id;
            $log['record'] = $record_id;
            $log['model'] = $model;
            $log['time'] = time();
            $log['data'] = ['user' => $user_id, 'model' => $model, 'record' => $record_id, 'time' => time()];
            foreach ($match[1] as $value) {
                $param = explode('|', $value);
                if (isset($param[1])) {
                    $parameter = isset($log[$param[0]]) ? $log[$param[0]] : $param[0];
                    $replace[] = call_user_func($param[1], $parameter);
                } else {
                    if ($param[0] == 'date') {
                        $replace[] = date('Y-m-d H:i:s');
                    } else if (isset($log[$param[0]])) {
                        $replace[] = $log[$param[0]];
                    } else {
                        $replace[] = $param[0];
                    }
                }
            }
            $data['remark'] = str_replace($match[0], $replace, $action_info['log']);
        } else {
            $data['remark'] = $action_info['log'];
        }
    } else {
        //未定义日志规则，记录操作url
        $data['remark'] = '操作url：' . Request::url();
    }
    Db::name('action_log')->insert($data);
    if (!empty($action_info['rule'])) {
        // 解析行为
        $rules = parse_action($action, $user_id);
        //执行行为
        $res = execute_action($rules, $action_info['id'], $user_id);
    }
}

/**
     * 解析行为规则
     * 规则定义  table:$table|field:$field|condition:$condition|rule:$rule[|cycle:$cycle|max:$max][;......]
     * 规则字段解释：table->要操作的数据表，不需要加表前缀；
     *              field->要操作的字段；
     *              condition->操作的条件，目前支持字符串，默认变量{$self}为执行行为的用户
     *              rule->对字段进行的具体操作，目前支持四则混合运算，如：1+score*2/2-3
     *              cycle->执行周期，单位（小时），表示$cycle小时内最多执行$max次
     *              max->单个周期内的最大执行次数（$cycle和$max必须同时定义，否则无效）
     * 单个行为后可加 ； 连接其他规则
     * @param string $action 行为id或者name
     * @param int $self 替换规则里的变量为执行用户的id
     * @return boolean|array: false解析出错 ， 成功返回规则数组
     * @author huajie <banhuajie@163.com>
     */
//    function parse_action($action = null, $self) {
//        if (empty($action)) {
//            return false;
//        }
//
//        //参数支持id或者name
//        if (is_numeric($action)) {
//            $map = array('id' => $action);
//        } else {
//            $map = array('name' => $action);
//        }
//
//        //查询行为信息
//        $info = M('Action')->where($map)->find();
//        if (!$info || $info['status'] != 1) {
//            return false;
//        }
//
//        //解析规则:table:$table|field:$field|condition:$condition|rule:$rule[|cycle:$cycle|max:$max][;......]
//        $rules = $info['rule'];
//        $rules = str_replace('{$self}', $self, $rules);
//        $rules = explode(';', $rules);
//        $return = array();
//        foreach ($rules as $key => &$rule) {
//            $rule = explode('|', $rule);
//            foreach ($rule as $k => $fields) {
//                $field = empty($fields) ? array() : explode(':', $fields);
//                if (!empty($field)) {
//                    $return[$key][$field[0]] = $field[1];
//                }
//            }
//            //cycle(检查周期)和max(周期内最大执行次数)必须同时存在，否则去掉这两个条件
//            if (!array_key_exists('cycle', $return[$key]) || !array_key_exists('max', $return[$key])) {
//                unset($return[$key]['cycle'], $return[$key]['max']);
//            }
//        }
//
//        return $return;
//    }

    /**
     * 执行行为
     * @param array $rules 解析后的规则数组
     * @param int $action_id 行为id
     * @param array $user_id 执行的用户id
     * @return boolean false 失败 ， true 成功
     * @author huajie <banhuajie@163.com>
     */
//    function execute_action($rules = false, $action_id = null, $user_id = null) {
//        if (!$rules || empty($action_id) || empty($user_id)) {
//            return false;
//        }
//
//        $return = true;
//        foreach ($rules as $rule) {
//
//            //检查执行周期
//            $map = array('action_id' => $action_id, 'user_id' => $user_id);
//            $map['create_time'] = array('gt', NOW_TIME - intval($rule['cycle']) * 3600);
//            $exec_count = M('ActionLog')->where($map)->count();
//            if ($exec_count > $rule['max']) {
//                continue;
//            }
//
//            //执行数据库操作
//            $Model = M(ucfirst($rule['table']));
//            $field = $rule['field'];
//            $res = $Model->where($rule['condition'])->setField($field, array('exp', $rule['rule']));
//
//            if (!$res) {
//                $return = false;
//            }
//        }
//        return $return;
//    }
