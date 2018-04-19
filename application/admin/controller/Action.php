<?php

// +----------------------------------------------------------------------
// | CuiCMF 用户行为
// +----------------------------------------------------------------------
// | Copyright (c) 2014-2018 All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: cuiyuanxin <15811506097@163.com>
// +----------------------------------------------------------------------

namespace app\admin\controller;

use think\Db;
use think\Loader;
use think\facade\Request;

class Action extends Admin {

    /**
     * 用户行为列表
     * @author cuiyuanxin <15811506097@163.com>
     */
    public function index() {
        $action = app()->model('Action');
        $action_list = $action->field('id,name,title,remark,type,status')->order("id desc")->paginate(config('paginate.list_rows'));
        $page = $action_list->render();
        $this->assign('page', $page);
        $this->assign('action_list', $action_list);
        $this->assign('empty', '<td colspan="7"><center>没有相关数据</center></td>');
        $this->assign('meta_title', '行为列表');
        return $this->fetch();
    }

    /**
     * 新增用户行为
     * @author cuiyuanxin <15811506097@163.com>
     */
    public function create_action() {
        $this->assign('meta_title', '添加行为');
        return $this->fetch();
    }

    /**
     * 编辑用户行为
     * @author cuiyuanxin <15811506097@163.com>
     */
    public function edit_action() {
        $id = $this->request->param('id', 0, 'intval');
        $action = app()->model('Action');
        $action_find = $action::where('id', $id)->find();
        $this->assign('id', $id);
        $this->assign('action_find', $action_find);
        $this->assign('meta_title', '编辑行为');
        return $this->fetch('create_action');
    }

    /**
     * 行为日志写入/更新
     */
    public function write_action() {
        $data = $this->request->post();
        $action = app()->model('Action');
        $result = $this->validate($data, 'app\admin\validate\Action');
        if (true !== $result) {
            $this->error($result);
        }
        if (empty($data['id'])) {
            $ret = $action->create($data);
            $msg = '添加';
        } else {
            $ret = $action->update($data);
            $msg = '编辑';
        }
        if ($ret === false) {
            $this->error('行为' . $msg . '失败！');
        } else {
            $this->success('行为' . $msg . '成功！', url('Action/index'));
        }
    }

    /**
     * 状态修改
     */
    public function change_status($method = null, $filed = 'status') {
        switch (strtolower($method)) {
            case 'forbid'://禁用
                $this->forbid(app()->model('Action'), [], $filed, url('Action/index'), '行为');
                break;
            case 'resume'://启用
                $this->resume(app()->model('Action'), [], $filed, url('Action/index'), '行为');
                break;
            case 'delete'://删除
                $this->delete(app()->model('Action'), 0, [], url('Action/index'), '行为');
                break;
            default:
                $this->error($method . '参数非法');
        }
    }

    /**
     * 行为日志列表
     * @author cuiyuanxin <15811506097@163.com>
     */
    public function actionlog() {
        //获取列表数据
        $list = Db::name('action_log')->alias('a')->join('action ac', 'a.action_id = ac.id')->join('user u', 'a.user_id = u.id')->field('a.id, ac.title, a.user_id, a.create_time, u.username, a.method, a.url')->order("a.id desc")->paginate(config('paginate.list_rows'));
        $page = $list->render();
        $this->assign('page', $page);
        $this->assign('_list', $list);
        $this->assign('empty', '<td colspan="6"><center>没有相关数据</center></td>');
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
            ['user u', 'a.user_id = u.id'],
        ];
        $info = Db::name('action_log')->alias('a')->join($join)->where('a.id', $id)->field('ac.name, ac.title, a.user_id, a.action_ip, a.create_time, a.remark, u.username, a.method, a.url')->find();
        $html = '';
        $html .= '行为标识：' . $info['name'] . '<br />';
        $html .= '行为名称：' . $info['title'] . '<br />';
        $html .= '执行者：' . $info['username'] . '<br />';
        $html .= '请求类型：' . $info['method'] . '<br />';
        $html .= '请求地址：' . $info['url'] . '<br />';
        $html .= '请求IP：' . long2ip($info['action_ip']) . '<br />';
        $html .= '行为描述：' . $info['remark'] . '<br />';
        $html .= '行为执行时间：' . date('Y-m-d H:i:s', $info['create_time']) . '<br />';
        $this->success('查询成功！', '', $html);
    }

    /**
     * 删除日志
     * @author cuiyuanxin <15811506097@163.com>
     */
    public function actionlog_del() {
        $this->delete(Db::name('action_log'), 1, [], url('Action/actionlog'), '行为日志');
    }

    /**
     * 清空日志
     */
    public function action_clear() {
        $res = Db::name('action_log')->where('1=1')->delete();
        if ($res !== false) {
            $this->success('日志清空成功！', url('Action/actionlog'));
        } else {
            $this->error('日志清空失败！');
        }
    }

}
