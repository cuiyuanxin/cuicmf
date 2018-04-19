<?php

// +----------------------------------------------------------------------
// | CuiCMF 系统设置
// +----------------------------------------------------------------------
// | Copyright (c) 2014-2018 All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 崔元欣 <15811506097@163.com>
// +----------------------------------------------------------------------

namespace app\admin\controller;

use think\facade\Config;
use think\facade\Cache;
use think\facade\Env;

class System extends Admin {

    /**
     * 系统配置
     * @return type
     * @author 崔元欣 <15811506097@163.com>
     */
    public function index() {
        $group = Config::get('config.config_group_list');
        $group_id = $this->request->param('group_id', 1);
        $model = app()->model('Config');
        $config = $model::where(['group' => $group_id])->select();
        $this->assign('empty', '<center>暂无相关配置U_U</center>');
        $this->assign('config', $config);
        $this->assign('group', $group);
        $this->assign('active', $group_id);
        $this->assign('meta_title', '系统配置');
        return $this->fetch();
    }

    public function write_save() {
        $id = $this->request->param('group_id', 1);
        $data = $this->request->post();
        if ($data && is_array($data)) {
            $model = app()->model('Config');
            $list = [];
            foreach ($data as $key => $value) {
                if (is_array($value)) {
                    $value = implode(',', $value);
                }
                if (strtotime($value)) {
                    $value = strtotime($value);
                }
                $model::where(['group' => $id, 'name' => $key])->setField('value', $value);
            }
        }
        Cache::clear('config');
        //记录行为
        action_log('update_config', 'config', $id, UID);
        $this->success('保存成功！', url('System/index', ['group_id' => $id]));
    }

    //配置管理
    public function configuration() {
        $model = app()->model('Config');
        $group_id = $this->request->param('group_id', '');
        $group = Config::get('config.config_group_list');
        $keywords = $this->request->param('keywords', '');
        $query = $model->field('id,name,title,group,type,status');
        if ($group_id) {
            $query->where(['group' => $group_id]);
        }
        if (!empty($keywords)) {
            $map = [
                ['id', 'like', $keywords],
                ['name', 'like', '%' . $keywords . '%'],
                ['title', 'like', '%' . $keywords . '%'],
            ];
            $query->whereOr($map);
        }
        $config = $query->order('sort', 'id desc')->paginate(Config::get('paginate.list_rows'));
        $page = $config->render();
        $this->assign('group', $group);
        $this->assign('config', $config);
        $this->assign('page', $page);
        $this->assign('keywords', $keywords);
        $this->assign('empty', '<td colspan="6"><center>没有相关数据</center></td>');
        $this->assign('meta_title', '配置列表');
        return $this->fetch();
    }

    //添加配置
    public function create_configuration() {
        $this->assign('meta_title', '添加配置');
        return $this->fetch();
    }

    /**
     * 编辑配置
     * @return type
     */
    public function edit_configuration() {
        $id = $this->request->param('id', 0);
        $model = app()->model('Config');
        $config = $model::where(['id' => $id])->find();
        $this->assign('id', $id);
        $this->assign('config', $config);
        $this->assign('meta_title', '编辑配置');
        return $this->fetch('create_configuration');
    }

    //添加更新配置
    public function write_configuration() {
        $data = $this->request->post();
        $result = $this->validate($data, 'app\admin\validate\Config');
        if (true !== $result) {
            $this->error($result);
        }
        $config = app()->model('Config');
        if (empty($data['id'])) {
            $ret = $config->create($data);
            $msg = '添加';
            $id = 0;
        } else {
            $ret = $config->update($data);
            $msg = '编辑';
            $id = $data['id'];
        }
        if ($ret === false) {
            $this->error('配置' . $msg . '失败！');
        } else {
            Cache::clear('config');
            //记录行为
            action_log('config', 'config', $id, UID);
            $this->success('配置' . $msg . '成功！', url('System/configuration'));
        }
    }

    /**
     * 状态修改
     */
    public function change_status($method = null, $filed = 'status') {
        switch (strtolower($method)) {
            case 'forbid'://禁用
                $this->forbid(app()->model('Config'), [], $filed, url('System/configuration'), '配置', 'config', 1, ['change_status_config', 'config']);
                break;
            case 'resume'://启用
                $this->resume(app()->model('Config'), [], $filed, url('System/configuration'), '配置', 'config', 1, ['change_status_config', 'config']);
                break;
            case 'delete'://删除
                $this->delete(app()->model('Config'), 0, [], url('System/configuration'), '配置', 'config', 1, ['change_status_config', 'config']);
                break;
            default:
                $this->error($method . '参数非法');
        }
    }

    /**
     * 清空缓存
     */
    public function clear() {
        //临时文件
        $runtime = Env::get('runtime_path');
        $path = ['cache', 'log', 'temp'];
        if (file_exists($runtime)) {
            foreach ($path as $key => $value) {
                deldir($runtime . $value);
            }
            $this->success('缓存清楚成功！');
        } else {
            $this->error('无法找到缓存目录！');
        }
    }

}
