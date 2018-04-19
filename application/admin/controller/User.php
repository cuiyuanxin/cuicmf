<?php

// +----------------------------------------------------------------------
// | CuiCMF 管理员管理
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
use think\facade\Session;
use think\facade\Cookie;

class User extends Admin {

    //管理员列表
    public function index() {
        $user = app()->model('User');
        $keywords = $this->request->param('keywords', '');
        $query = $user->field('id,username,name,nickname,mobile,status');
        if (!empty($keywords)) {
            $map = [
                ['id', 'like', $keywords],
                ['username', 'like', '%' . $keywords . '%'],
                ['mobile', 'like', $keywords],
            ];
            $query->whereOr($map);
        }
        $user_list = $query->order("id desc")->paginate(Config::get('paginate.list_rows'));
        $page = $user_list->render();
        $this->assign('user_list', $user_list);
        $this->assign('page', $page);
        $this->assign('keywords', $keywords);
        $this->assign('empty', '<td colspan="7"><center>没有相关数据</center></td>');
        $this->assign('meta_title', '管理员列表');
        return $this->fetch();
    }

    //添加管理员用户
    public function create_user() {
        $auth_group = Db::name('auth_group')->select();
        $this->assign('auth_group', $auth_group);
        $this->assign('meta_title', '添加管理用户');
        return $this->fetch();
    }

    //编辑权限组
    public function edit_user() {
        $id = $this->request->param('id', 0, 'intval');
        $auth_group = Db::name('auth_group')->select();
        $user = app()->model('User');
        $user_find = $user::where('id', $id)->find();
        $this->assign('id', $id);
        $this->assign('user_find', $user_find);
        $this->assign('auth_group', $auth_group);
        $this->assign('meta_title', '编辑管理用户');
        return $this->fetch('create_user');
    }

    //管理员数据写入/更新
    public function write_user() {
        $data = $this->request->post();
        $user = app()->model('User');
        if (empty($data['id'])) {
            $result = $this->validate($data, 'app\admin\validate\User.create');
        } else {
            $result = $this->validate($data, 'app\admin\validate\User.edit');
        }
        if (true !== $result) {
            $this->error($result);
        }
        if (empty($data['id'])) {
            $ret = $user->create($data);
            $msg = '添加';
            $id = 0;
        } else {
            $ret = $user->update($data);
            $msg = '编辑';
            $id = $data['id'];
        }
        if ($ret === false) {
            $this->error('管理员' . $msg . '失败！');
        } else {
            Cache::clear('menu');
            //记录行为
            action_log('update_user', 'user', $id, UID);
            $this->success('管理员' . $msg . '成功！', url('User/index'));
        }
    }

    /**
     * 状态修改
     */
    public function change_status($method = null, $filed = 'status') {
        $id = $this->request->param('id/a', 0, 'intval');
        if (in_array(Config::get('config.user_administrator'), $id)) {
            $this->error('自己或超级管理员不可操作！');
        }
        switch (strtolower($method)) {
            case 'forbid'://禁用
                $this->forbid(app()->model('User'), [], $filed, url('User/index'), '管理员', 'menu', 1, ['change_status_user', 'user']);
                break;
            case 'resume'://启用
                $this->resume(app()->model('User'), [], $filed, url('User/index'), '管理员', 'menu', 1, ['change_status_user', 'user']);
                break;
            case 'delete'://删除
                $this->delete(app()->model('User'), 0, [], url('User/index'), '管理员', 'menu', 1, ['change_status_user', 'user']);
                break;
            default:
                $this->error($method . '参数非法');
        }
    }

    /**
     * 修改密码
     */
    public function change_password() {
        $data = $this->request->post();
        $result = $this->validate($data, 'app\admin\validate\User.change');
        if (true !== $result) {
            $this->error($result);
        }
        $user = app()->model('User');
        $password = $user->where(['id' => UID])->value('password');
        if (cui_ucenter_md5($data['password'], Config::get('config.uc_auth_key')) !== $password) {
            $this->error('当前密码错误！');
        }
        $map['password'] = cui_ucenter_md5($data['new_password'], Config::get('config.uc_auth_key'));
        $ret = $user->where(['id' => UID])->update($map);
        if ($ret === false) {
            $this->error('密码修改失败！');
        } else {
            //记录行为
            action_log('change_password', 'user', UID, UID);
            Session::delete('user_auth');
            Cookie::delete('user_auth');
            $this->success('密码修改成功', url('Login/login'));
        }
    }

}
