<?php

// +----------------------------------------------------------------------
// | CuiCMF 权限管理
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

class Auth extends Admin {

    //权限组列表
    public function index() {
        $keywords = $this->request->param('keywords', '');
        $query = Db::name('auth_group')->field("id, title, description, status")->where(['module' => 'admin']);
        if (!empty($keywords)) {
            $map = [
                ['id', 'like', $keywords],
                ['title', 'like', '%' . $keywords . '%'],
            ];
            $query->whereOr($map);
        }
        $auth_group = $query->order("id asc")->paginate(Config::get('paginate.list_rows'));
        $page = $auth_group->render();
        $this->assign('auth_group', $auth_group);
        $this->assign('page', $page);
        $this->assign('keywords', $keywords);
        $this->assign('empty', '<td colspan="6"><center>没有相关数据</center></td>');
        $this->assign('meta_title', '权限组列表');
        return $this->fetch();
    }

    //添加权限组
    public function create_group() {
        $this->assign('meta_title', '添加权限组');
        return $this->fetch();
    }

    //编辑权限组
    public function edit_group() {
        $id = $this->request->param('id', 0, 'intval');
        $auth_group = Db::name('auth_group')->where(['id' => $id])->find();
        $this->assign('id', $id);
        $this->assign('auth_group', $auth_group);
        $this->assign('meta_title', '编辑权限组');
        return $this->fetch('create_group');
    }

    /**
     * 权限组数据写入/更新
     */
    public function write_group() {
        $data = $this->request->post();
        $data['module'] = $this->request->module();
        $data['status'] = $this->request->post('status', 1, 'intval');
        if (empty($data['title'])) {
            $this->error('请输入用户组名！');
        }
        if (empty($data['id'])) {
            $ret = Db::name('auth_group')->insert($data);
            $msg = '添加';
        } else {
            $ret = Db::name('auth_group')->update($data);
            $msg = '编辑';
        }
        if ($ret === false) {
            $this->error('权限组' . $msg . '失败！');
        } else {
            $this->success('用户组' . $msg . '成功！', url('Auth/index'));
        }
    }

    /**
     * 状态修改
     */
    public function change_status($method = null, $filed = 'status') {
        switch (strtolower($method)) {
            case 'forbid'://禁用
                $this->forbid(Db::name('auth_group'), [], $filed, url('Auth/index'), '权限组');
                break;
            case 'resume'://启用
                $this->resume(Db::name('auth_group'), [], $filed, url('Auth/index'), '权限组');
                break;
            case 'delete'://删除
                $this->delete(Db::name('auth_group'), 0, [], url('Auth/index'), '权限组');
                break;
            default:
                $this->error($method . '参数非法');
        }
    }

    //分配菜单权限
    public function rule_group() {
        if ($this->request->isAjax()) {
            $data = $this->request->post();
            $rules = implode(',', $data['rule']);
            $map = array(
                'id' => $data['id'],
                'rules' => $rules,
            );
            $ret = Db::name('auth_group')->update($map);
            if ($ret === false) {
                $this->error('保存失败！');
            } else {
                Cache::clear('menu');
                $this->success('保存成功！', url('Auth/index'));
            }
        } else {
            $id = $this->request->param('id', 0, 'intval');
            if (empty($id)) {
                $this->error('参数错误！');
            }
            $rules = Db::name('auth_group')->where(['id' => $id])->value('rules');
            $data = Db::name('auth_rule')->field('id,pid,title as name,icon,tip')->where(['status' => 1])->order('sort')->select();
            foreach ($data as $key => $value) {
                $data[$key]['name'] = '<i class="fa ';
                if ($value['icon']) {
                    $data[$key]['name'] .= $value['icon'];
                } else {
                    $data[$key]['name'] .= 'fa-circle-o';
                }
                $data[$key]['name'] .= '"></i> ' . $value['name'];
            }
            $ztree = str_replace('_child', 'children', json_encode(toTree($data), JSON_UNESCAPED_UNICODE));
            $this->assign('rules', json_encode(explode(',', $rules)));
            $this->assign('id', $id);
            $this->assign('ztree', $ztree);
            $this->assign('meta_title', '权限菜单');
            return $this->fetch();
        }
    }

}
