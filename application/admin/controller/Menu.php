<?php

// +----------------------------------------------------------------------
// | CuiCMF 菜单管理
// +----------------------------------------------------------------------
// | Copyright (c) 2014-2018 All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: cuiyuanxin <15811506097@163.com>
// +----------------------------------------------------------------------

namespace app\admin\controller;

use think\Db;
use think\facade\Config;
use think\facade\Cache;
use tree\Tree;
use think\facade\Env;

class Menu extends Admin {

    //菜单列表
    public function index() {
        $admin_rule = Db::name('auth_rule')->order('sort')->select();
        $menu = $this->menu2Html(toTree($admin_rule, 0, true));
        $this->assign('menu2Html', $menu);
        $this->assign('meta_title', '菜单列表');
        return $this->fetch();
    }

    //添加菜单
    public function create_menu() {
        $pid = $this->request->param('pid', '', 'intval');
        if (isset($pid)) {
            $this->assign('pid', $pid);
            $meta_title = '子';
        } else {
            $meta_title = '顶级';
        }
        $this->assign('menu_select', $this->menu2select($pid));
        $this->assign('meta_title', '添加' . $meta_title . '菜单');
        return $this->fetch();
    }

    //编辑菜单
    public function edit_menu() {
        $id = $this->request->param('id', 0, 'intval');
        $rule = Db::name('auth_rule')->where(['id' => $id])->find();
        if ($rule['pid'] > 0) {
            $meta_title = '子';
        } else {
            $meta_title = '顶级';
        }
        $this->assign('id', $id);
        $this->assign('menu_select', $this->menu2select($rule['pid']));
        $this->assign('rule', $rule);
        $this->assign('meta_title', '编辑' . $meta_title . '菜单');
        return $this->fetch('create_menu');
    }

    //复制菜单
    public function copy_menu() {
        $id = $this->request->param('id', 0, 'intval');
        $rule = Db::name('auth_rule')->where(['id' => $id])->find();
        if ($rule['pid'] > 0) {
            $meta_title = '子';
        } else {
            $meta_title = '顶级';
        }
        $this->assign('menu_select', $this->menu2select($rule['pid']));
        $this->assign('rule', $rule);
        $this->assign('meta_title', '复制' . $meta_title . '菜单');
        return $this->fetch('create_menu');
    }

    //菜单数据写入/更新
    public function write_menu() {
        $data = $this->request->post();
        $data['module'] = $this->request->module();
        $level = Db::name('auth_rule')->where(['id' => $data['pid']])->value('level');
        $data['level'] = $level + 1;
        $data['status'] = 1;
        if (empty($data['title'])) {
            $this->error('请填写菜单名称！');
        }
        if (empty($data['url'])) {
            $this->error('请填写菜单链接！');
        }
        if (empty($data['id'])) {
            $ret = Db::name('auth_rule')->insert($data);
            $msg = '添加';
            $id = 0;
        } else {
            $ret = Db::name('auth_rule')->update($data);
            $msg = '编辑';
            $id = $data['id'];
        }
        if ($ret === false) {
            $this->error('菜单' . $msg . '失败！');
        } else {
            Cache::clear('menu');
            //记录行为
            action_log('update_rule_menu', 'auth_rule', $id, UID);
            $this->success('菜单' . $msg . '成功！', url('Menu/index'));
        }
    }

    //菜单排序
    public function sort_menu() {
        $data = $this->request->post();
        if (count($data) > 0) {
            foreach ($data as $id => $sort) {
                Db::name('auth_rule')->where(['id' => $id])->setField('sort', $sort);
            }
            Cache::clear('menu');
            $this->success('排序更新成功', url('Menu/index'));
        } else {
            $this->error('参数错误！', null, -1016);
        }
    }

    /**
     * 状态修改
     */
    public function change_status($method = null, $filed = 'status') {
        switch (strtolower($method)) {
            case 'forbid'://禁用
                $this->forbid(Db::name('auth_rule'), [], $filed, url('Menu/index'), '菜单', 'menu', 1, ['change_status_rule_menu', 'auth_rule']);
                break;
            case 'resume'://启用
                $this->resume(Db::name('auth_rule'), [], $filed, url('Menu/index'), '菜单', 'menu', 1, ['change_status_rule_menu', 'auth_rule']);
                break;
            case 'delete'://删除
                $this->delete(Db::name('auth_rule'), 0, [], url('Menu/index'), '菜单', 'menu', 1, ['change_status_rule_menu', 'auth_rule']);
                break;
            case 'hide'://隐藏
                $this->forbid(Db::name('auth_rule'), [], $filed, url('Menu/index'), '菜单', 'menu', 1, ['change_status_rule_menu', 'auth_rule']);
                break;
            case 'show'://显示
                $this->resume(Db::name('auth_rule'), [], $filed, url('Menu/index'), '菜单', 'menu', 1, ['change_status_rule_menu', 'auth_rule']);
                break;
            case 'unchecked'://不检查
                $this->forbid(Db::name('auth_rule'), [], $filed, url('Menu/index'), '菜单', 'menu', 1, ['change_status_rule_menu', 'auth_rule']);
                break;
            case 'checked'://检查
                $this->resume(Db::name('auth_rule'), [], $filed, url('Menu/index'), '菜单', 'menu', 1, ['change_status_rule_menu', 'auth_rule']);
                break;
            default:
                $this->error($method . '参数非法');
        }
    }

    //格式化下拉列表
    private function menu2select($sid = '') {
        $menu_select = Db::name('auth_rule')->order('sort')->column('id, pid as parent_id, title as name', 'id');
        $tree = new Tree($menu_select);
        $html = $tree->get_tree(0, "<option value=\$id \$selected>\$spacer\$name</option>", $sid);
        return $html;
    }

    //格式化表格菜单
    private function menu2Html($menu, $num = 1) {
        $html = '';
        if ($menu && is_array($menu)) {
            foreach ($menu as $key => $value) {
                $html .= '<tr';
                if ($value['pid'] === 0 && (isset($value['_child']) || !isset($value['_child']))) {
                    $html .= ' data-tt-id="' . $value['id'] . '">';
                } else if ($value['pid'] > 0 && (isset($value['_child']) || !isset($value['_child']))) {
                    $html .= ' data-tt-id="' . $value['id'] . '" data-tt-parent-id="' . $value['pid'] . '">';
                } else {
                    $html .= '>';
                }
                $html .= '<td>' . $value['id'] . '</td>';
                $html .= '<td>';
                if ($value['tip']) {
                    $html .= '<span data-toggle="tooltip" data-placement="right" title="' . $value['tip'] . '">';
                }
                switch ($num) {
                    case 1:
                        $html .= '|-';
                        break;
                    case 2:
                        $html .= '|--';
                        break;
                    case 3:
                        $html .= '|---';
                        break;
                    case 4:
                        $html .= '|----';
                        break;
                    default:
                        $html .= '|-----';
                        break;
                }
                $html .= $value['title'];
                if ($value['tip']) {
                    $html .= '</span>';
                }
                $html .= '</td>';
                $html .= '<td>';
                $html .= $value['url'];
                $html .= '</td>';
                $html .= '<td>' . int_to_string($value['status']) . '</td>';
                $html .= '<td>' . int_to_string($value['hide'], [1 => '显示', 0 => '隐藏']) . '</td>';
                $html .= '<td>' . int_to_string($value['inspect'], [1 => '检查', 0 => '不检查']) . '</td>';
                $html .= '<td>' . $num . '级</td>';
                $html .= '<td><input type="text" name="' . $value['id'] . '" class="form-control" value="' . $value['sort'] . '" style="height:25px;padding: 1px 1px 1px 5px;width: 45px;"></td>';
                $html .= '<td>';
                $html .= '<div class="btn-group">';
                $html .= '<a href="' . url('Menu/create_menu', ['pid' => $value['id']]) . '" class="btn btn-xs btn-success" title="添加子菜单"><i class="fa fa-plus"></i> 添加子菜单 </a>';
                $html .= '<a class="btn btn-xs btn-warning" href="' . url('Menu/edit_menu', ['id' => $value['id']]) . '" title="编辑"><i class="fa fa-pencil-square-o"></i> 编辑 </a>';
                $html .= '<a class="btn btn-xs bg-maroon btn-flat" href="' . url('Menu/copy_menu', ['id' => $value['id']]) . '" title="复制"><i class="fa fa-exchange"></i> 复制 </a>';
                if ($value['status'] == 1) {
                    $html .= '<a href="' . url('Menu/change_status', ['method' => 'forbid', 'id' => $value['id']]) . '" class="btn btn-primary btn-xs ajax-get"><i class="fa fa-ban"></i> 禁用 </a>';
                } else {
                    $html .= '<a href="' . url('Menu/change_status', ['method' => 'resume', 'id' => $value['id']]) . '" class="btn btn-info btn-xs ajax-get"><i class="fa fa-check-circle-o"></i> 启用 </a>';
                }
                if ($value['hide'] == 1) {
                    $html .= '<a href="' . url('Menu/change_status', ['method' => 'hide', 'filed' => 'hide', 'id' => $value['id']]) . '" class="btn btn-primary btn-xs ajax-get"><i class="fa fa-ban"></i> 隐藏 </a>';
                } else {
                    $html .= '<a href="' . url('Menu/change_status', ['method' => 'show', 'filed' => 'hide', 'id' => $value['id']]) . '" class="btn btn-info btn-xs ajax-get"><i class="fa fa-check-circle-o"></i> 显示 </a>';
                }
                if ($value['inspect'] == 1) {
                    $html .= '<a href="' . url('Menu/change_status', ['method' => 'unchecked', 'filed' => 'inspect', 'id' => $value['id']]) . '" class="btn btn-primary btn-xs ajax-get"><i class="fa fa-ban"></i> 不检查 </a>';
                } else {
                    $html .= '<a href="' . url('Menu/change_status', ['method' => 'checked', 'filed' => 'inspect', 'id' => $value['id']]) . '" class="btn btn-info btn-xs ajax-get"><i class="fa fa-check-circle-o"></i> 检查 </a>';
                }
                if ($value['status'] !== -1) {
                    $html .= '<a class="btn btn-xs btn-danger confirm ajax-get" href="' . url('Menu/change_status', ['method' => 'delete', 'all' => 'all', 'id' => $value['id']]) . '" title="删除"><i class="fa fa-trash"></i> 删除 </a>';
                }
                $html .= '</div>';
                $html .= '</td>';
                $html .= '</tr>';
                if (isset($value['_child'])) {
                    $html .= $this->menu2Html($value['_child'], $num + 1);
                }
            }
            return $html;
        } else {
            return $menu;
        }
    }

    //Icons
    public function icons() {
        $json = json_decode(file_get_contents(Env::get('root_path') . 'public/data/Icons/Icons.json'), true);
        $this->assign('icon', $json);
        $this->assign('meta_title', 'Icons');
        return $this->fetch();
    }

}
