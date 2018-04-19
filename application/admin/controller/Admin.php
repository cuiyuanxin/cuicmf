<?php

// +----------------------------------------------------------------------
// | CuiCMF Admin共用方法
// +----------------------------------------------------------------------
// | Copyright (c) 2014-2018 All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: cuiyuanxin <15811506097@163.com>
// +----------------------------------------------------------------------

namespace app\admin\controller;

use think\Controller;
use think\Db;
use auth\Auth;
use think\facade\Session;
use think\facade\Cookie;
use think\facade\Config;
use think\facade\Cache;

class Admin extends Controller {

    //后台构造方法
    public function initialize() {
        $auth = new Auth();
        //未登陆，不允许直接访问
        if (!is_login()) {
            $this->redirect('Login/login');
        } else {
            define('UID', is_login());
        }
        //检测是否未操作超时
        $this->timeout();
        /* 读取数据库中的配置 */
        $config = Cache::get('db_config_data');
        if (!$config) {
            $config = lists();
            Cache::tag('config')->set('db_config_data', $config);
        }
        Config::set($config, 'config');
        if (UID !== Config::get('config.user_administrator') && Config::get('config.admin_allow_ip')) {
            // 检查IP地址访问
            if (!in_array($this->request->ip(), explode(',', Config::get('config.admin_allow_ip')))) {
                $this->assign('meta_title', '403');
                throw new \think\exception\HttpException(403, '禁止访问');
            }
        }
        if (UID !== Config::get('config.user_administrator')) {
            $access = $this->accessControl();
            if (false === $access) {
                $this->assign('meta_title', '403');
                throw new \think\exception\HttpException(403, '未授权访问');
            } elseif (null === $access) {
                //检测访问权限
                $rule = strtolower($this->request->module() . '/' . $this->request->controller() . '/' . $this->request->action());
                if (!$auth->check($rule, UID, ['in', '1,2'])) {
                    $this->assign('meta_title', '403');
                    throw new \think\exception\HttpException(403, '未授权访问');
                }
            }
        }
        $id_curr = $this->get_url_id();
        $menus_curr = $this->get_admin_parents($id_curr);
        $this->assign('id_curr', $id_curr);
        $this->assign('menus_curr', $menus_curr);
        //获取有权限的菜单tree
        $this->assign('__MENU__', $this->getMenus(UID));
    }

    /**
     * 获取指定url的id(可能为显示状态或非显示状态)
     * @param string $url 为空获取当前操作的id
     * @param int $status 1表示取显示状态,为空或为0则不限制
     * @return int -1表示不需要检测 0表示无后台菜单 其他表示当前url对应id
     */
    final protected function get_url_id($url = '', $status = 0) {
        $url = $url ?: $this->request->controller() . '/' . $this->request->action();
//        $route = Request::route();
//        $query = preg_replace('/^.+\?/U', '', $value['url']);
//        if ($query !== $url) {
//            $myArray = [];
//            parse_str($query, $myArray);
//            array_intersect_assoc($route, $myArray);
//        }
//        if (isset($myArray)) {
//            $url .= '?' . http_build_query($myArray);
//        }
//            
//        if ($url == '//') {
//            $routeInfo = request()->routeInfo();
//            //插件管理
//            if ($routeInfo['route'] == '\think\addons\Base@execute') {
//                $menu_id = self::where('name', 'admin/Addons/addons_list')->order('level desc,sort')->value('id');
//                return $menu_id ?: 0;
//            }
//        }
        $where['module'] = $this->request->module();
        $where['url'] = $url;
        if ($status)
            $where['status'] = $status;
        $menu_id = Db::name('auth_rule')->where($where)->order('level desc, sort')->value('id'); //4级或3级(如果4级,status是0,不显示)
        $menu_id = $menu_id ?: 0;
        return $menu_id;
    }

    /**
     * 获取所有父节点id(含自身)
     * @param int $id 节点id
     * @return array
     */
    final protected function get_admin_parents($id = 0) {
        $id = $id ?: $this->get_url_id('', 1);
        if (empty($id))
            return [];
        $lists = Db::name('auth_rule')->order('level desc, sort')->column('pid', 'id');
        $ids = [];
        while (isset($lists[$id]) && $lists[$id] != 0) {
            $ids[] = $id;
            $id = $lists[$id];
        }
        if (isset($lists[$id]) && $lists[$id] == 0)
            $ids[] = $id;

        return array_reverse($ids);
    }

    /**
     * action访问控制,在 **登陆成功** 后执行的第一项权限检测任务
     * @return boolean|null  返回值必须使用 `===` 进行判断
     *   返回 **false**, 不允许任何人访问(超管除外)
     *   返回 **true**, 允许任何管理员访问,无需执行节点权限检测
     *   返回 **null**, 需要继续执行节点权限检测决定是否允许访问
     */
    final protected function accessControl() {
        $allow = Config::get('config.allow_visit'); //不受限控制器方法
        $deny = Config::get('config.deny_visit'); //仅超级管理员可访问的控制器方法
        $inspect = Db::name('auth_rule')->where(['status' => 1, 'inspect' => 0])->value('url');
        if ($inspect) {
            $allow = array_merge($allow, $inspect);
        }
        $check = strtolower($this->request->controller() . '/' . $this->request->action());
        //当前请求的QUERY_STRING参数
        $query = $this->request->query();
        if (isset($query) && $query) {
            $check_query = $check . '?' . $query;
        }
        if (!empty($deny) && in_array(strtolower($check), array_map('strtolower', $deny))) {
            return false; //非超管禁止访问deny中的方法
        }
        if (!empty($allow) && in_array(strtolower($check), array_map('strtolower', $allow))) {
            return true;
        } else if (!empty($allow) && isset($check_query) && in_array(strtolower($check_query), array_map('strtolower', $allow))) {
            return true;
        }
        return null; //需要检测节点权限
    }

    /**
     * 获取控制器菜单数组,二级菜单元素位于一级菜单的'_child'元素中
     */
    final public function getMenus($controller = 1) {
        $menus = Cache::get('admin_menu_list' . UID);
        if (empty($menus)) {
            // 获取主菜单
            $auth = new Auth();
            $auth_rule = Db::name('auth_rule')->where(['status' => 1, 'hide' => 1])->order('sort')->select();
            if (UID != Config::get('config.user_administrator')) {
                foreach ($auth_rule as $key => $value) {
                    if (!$auth->check($value['module'] . '/' . $value['url'], UID)) {
                        unset($auth_rule[$key]);
                    }
                }
            }
            $menus = toTree($auth_rule);
            Cache::tag('menu')->set('admin_menu_list' . UID, $menus);
            return $menus;
        }
        return $menus;
    }

    /**
     * 用户无操作超时
     */
    public function timeout() {
        //设置超时为10分
        $nowtime = time();
        $s_time = think_decrypt(Session::get('user_auth.logintime'), Config::get('config.uc_auth_key'));
        if ($s_time) {
            if (($nowtime - $s_time) < 1800) {
                Session::set('user_auth.logintime', think_encrypt($nowtime, Config::get('config.uc_auth_key')));
                Cookie::set('user_auth.logintime', think_encrypt($nowtime, Config::get('config.uc_auth_key')));
            }
        }
    }

    /**
     * 公用禁用方法
     * @param type $model 数据表
     * @param type $data 自定义条件
     * @param type $filed 自定义字段
     * @param type $url 跳转地址
     * @param type $msg 提示语
     * @param type $cache 缓存
     * @param type $type 1:清空缓存 0:删除缓存
     * @param type $action 行为 ['行为标识', 'db']
     */
    protected function forbid($model = '', $data = [], $filed = 'status', $url = '', $msg = '', $cache = false, $type = 1, $action = false) {
        $id = array_unique($this->request->param('id/a', ''));
        $id = is_array($id) ? implode(',', $id) : $id;
        if (count($id) === 0) {
            $this->error('请选择要操作的数据！');
        }
        if (empty($model)) {
            $this->error('缺少关键数据！');
        }
        $db = $model;
        $ret = $db->where($data)->whereIn('id', $id)->update([$filed => 0]);
        if ($ret === false) {
            $this->error($msg . '状态禁用失败！');
        } else {
            if ($cache) {
                if ($type === 1) {
                    Cache::clear($cache);
                } else {
                    Cache::rm($cache);
                }
            }
            if ($action) {
                //记录行为
                action_log($action[0], $action[1], $id, UID);
            }
            $this->success($msg . '状态禁用成功！', $url);
        }
    }

    /**
     * 公用启用方法
     * @param type $model 数据表
     * @param type $data 自定义条件
     * @param type $filed 自定义字段
     * @param type $url 跳转地址
     * @param type $msg 提示语
     * @param type $cache 缓存
     * @param type $type 1:清空缓存 0:删除缓存
     * @param type $action 行为 ['行为标识', 'db']
     */
    protected function resume($model = '', $data = [], $filed = 'status', $url = '', $msg = '', $cache = false, $type = 1, $action = false) {
        $id = array_unique($this->request->param('id/a', ''));
        $id = is_array($id) ? implode(',', $id) : $id;
        if (count($id) === 0) {
            $this->error('请选择要操作的数据！');
        }
        if (empty($model)) {
            $this->error('缺少关键数据！');
        }
        $db = $model;
        $ret = $db->where($data)->whereIn('id', $id)->update([$filed => 1]);
        if ($ret === false) {
            $this->error($msg . '状态启用失败！');
        } else {
            if ($cache) {
                if ($type === 1) {
                    Cache::clear($cache);
                } else {
                    Cache::rm($cache);
                }
            }
            if ($action) {
                //记录行为
                action_log($action[0], $action[1], $id, UID);
            }
            $this->success($msg . '状态启用成功！', $url);
        }
    }

    /**
     * 公用删除方法
     * @param type $model 数据表
     * @param type $soft 0:软删除 1:真实删除
     * @param type $data 自定义条件
     * @param type $url 跳转地址
     * @param type $msg 提示语
     * @param type $cache 缓存
     * @param type $type 1:清空缓存 0:删除缓存
     * @param type $action 行为 ['行为标识', 'db']
     */
    protected function delete($model = '', $soft = 0, $data = [], $url = '', $msg = '', $cache = false, $type = 1, $action = false) {
        $all = $this->request->param('all', '');
        if ($all === 'all') {
            $id = $this->request->param('id', 0, 'intval');
            $id = $this->tree2id($id);
        } else {
            $id = array_unique($this->request->param('id/a', ''));
            $id = is_array($id) ? implode(',', $id) : $id;
        }
        if (count($id) === 0) {
            $this->error('请选择要操作的数据！');
        }
        if (empty($model)) {
            $this->error('缺少关键数据！');
        }
        $db = $model;
        if ($soft > 0) {
            if (is_object($db)) {
                $ret = $db->where($data)->whereIn('id', $id)->delete();
            } else {
                $ret = $db::destroy(function($query) {
                            $query->where($data)->whereIn('id', $id);
                        });
            }
        } else {
            if (count($data) > 0) {
                $db->where($data);
            }
            $ret = $db->whereIn('id', $id)->update(['status' => -1]);
        }
        if ($ret === false) {
            $this->error($msg . '删除失败！');
        } else {
            if ($cache) {
                if ($type === 1) {
                    Cache::clear($cache);
                } else {
                    Cache::rm($cache);
                }
            }
            if ($action) {
                //记录行为
                action_log($action[0], $action[1], $id, UID);
            }
            $this->success($msg . '删除成功！', $url);
        }
    }

    /**
     * 获取子菜单ID
     */
    private function tree2id($pid, $num = 1) {
        if ($pid) {
            if ($num === 1) {
                $arr = (int) $pid;
            } else {
                $arr = ',' . (int) $pid;
            }
            $child = Db::name('auth_rule')->where(['pid' => $pid])->select();
            if (is_array($child) && isset($child['0']['id'])) {
                foreach ($child as $key => $value) {
                    $arr .= $this->tree2id($value['id'], $num + 1);
                }
            }
        }
        return $arr;
    }

    /**
     * 空操作
     * @param type $name
     * @return type
     */
    public function _empty($name) {
        $this->assign('meta_title', '404');
        throw new \think\exception\HttpException(404, '文件未找到');
    }

}
