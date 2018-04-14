<?php

// +------------------------------------------------------------------------
// | CuiCMS
// +------------------------------------------------------------------------
// | Copyright (c) 2014-2017 http://www.cuiyuanxin.com, All rights reserved.
// +------------------------------------------------------------------------
// | Author: cuiyuanxin <15811506097@163.com>
// +------------------------------------------------------------------------

namespace app\admin\controller;

use think\Db;
use think\Loader;
use think\Request;

class Action extends Admin {

    /**
     * 用户行为列表
     * @author cuiyuanxin <15811506097@163.com>
     */
    public function action() {
        $action_list = Db::name('action')->field('id,name,title,rule,type,status')->order("id desc")->paginate(config('paginate.list_rows'));
        $page = $action_list->render();
        $this->assign('page', $page);
        $this->assign('action_list', $action_list);
        $this->assign('empty', '<td colspan="7"><center>没有相关数据</center></td>');
        $this->assign('meta_title', '用户行为');
        return $this->fetch();
    }

    /**
     * 新增行为
     * @author cuiyuanxin <15811506097@163.com>
     */
    public function action_add() {
        if (Request::isAjax()) {
            $data['name'] = input('name', '');
            $data['title'] = input('title', '');
            $data['type'] = input('type', '', 'intval');
            $data['remark'] = input('remark', '');
            $data['rule'] = input('rule', '');
            $data['log'] = input('log', '');
            $data['status'] = input('status', 0, 'intval');
            if (empty($data['name'])) {
                $this->error('行为标识不存在！', null, -1027);
            }
            if (empty($data['title'])) {
                $this->error('行为名称不存在！', null, -1028);
            }
            $result = Db::name('action')->insert($data);
            if ($result) {
                $this->success('行为创建成功！', url('action'));
            } else {
                $this->error('行为创建失败！');
            }
        } else {
            $this->assign('meta_title', '新增用户行为');
            return $this->fetch();
        }
    }

    /**
     * 编辑行为
     * @author cuiyuanxin <15811506097@163.com>
     */
    public function action_edit() {
        $id = input('id', 0, 'intval');
        if (Request::isAjax()) {
            $data['id'] = $id;
            $data['name'] = input('name', '');
            $data['title'] = input('title', '');
            $data['type'] = input('type', '', 'intval');
            $data['remark'] = input('remark', '');
            $data['rule'] = input('rule', '');
            $data['log'] = input('log', '');
            $data['status'] = input('status', 0, 'intval');
            $data['update_time'] = time();
            if (empty($data['name'])) {
                $this->error('行为标识不存在！', null, -1027);
            }
            if (empty($data['title'])) {
                $this->error('行为名称不存在！', null, -1028);
            }
            $result = Db::name('action')->update($data);
            if ($result) {
                $this->success('行为修改成功！', url('action'));
            } else {
                $this->error('行为修改失败！');
            }
        } else {
            $action = Db::name('action')->where('id', $id)->find();
            $this->assign('action', $action);
            $this->assign('meta_title', '编辑用户行为');
            return $this->fetch();
        }
    }

    //状态修改
    public function action_status() {
        return $this->status('action');
    }

    //删除
    public function action_del() {
        return $this->delete('action', 'action');
    }

    /**
     * 行为日志列表
     * @author cuiyuanxin <15811506097@163.com>
     */
    public function actionlog() {
        //获取列表数据
        $join = [
            ['action ac', 'a.action_id = ac.id'],
        ];
        $list = Db::name('action_log')->alias('a')->join($join)->field('a.id, ac.title, a.user_id, a.create_time')->order("a.id desc")->paginate(config('paginate.list_rows'));
        $page = $list->render();
        $this->assign('page', $page);
        $this->assign('_list', $list);
        $this->assign('empty', '<td colspan="5"><center>没有相关数据</center></td>');
        $this->assign('meta_title', '行为日志');
        return $this->fetch();
    }

    /**
     * 查看行为日志
     * @author cuiyuanxin <15811506097@163.com>
     */
    public function action_details($id = 0) {
        empty($id) && $this->error('参数错误！');
        $join = [
            ['action ac', 'a.action_id = ac.id'],
        ];
        $info = Db::name('action_log')->alias('a')->join($join)->where('a.id', $id)->field('ac.title, a.user_id, a.action_ip, a.create_time, a.remark')->find();
        $this->assign('info', $info);
        $this->assign('meta_title', '查看行为日志');
        return $this->fetch();
    }

    /**
     * 删除日志
     * @param mixed $ids
     * @author cuiyuanxin <15811506097@163.com>
     */
    public function action_remove() {
        return $this->delete('action_log', 'actionlog');
    }

    /**
     * 清空日志
     */
    public function action_clear() {
        $res = Db::name('action_log')->where('1=1')->delete();
        if ($res !== false) {
            $this->success('日志清空成功！', url('actionlog'));
        } else {
            $this->error('日志清空失败！');
        }
    }

}
