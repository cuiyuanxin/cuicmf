<?php

// +----------------------------------------------------------------------
// | CuiCMF 数据库管理
// +----------------------------------------------------------------------
// | Copyright (c) 2014-2018 All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 崔元欣 <15811506097@163.com>
// +----------------------------------------------------------------------

namespace app\admin\controller;

use think\Db;
use think\facade\Config;
use think\facade\Session;

class Database extends Admin {

    //数据库列表
    public function index() {
        if (empty($type)) {
            $type = 'export';
        }
        $title = '';
        $list = [];
        $list = Db::query('SHOW TABLE STATUS FROM ' . Config::get('database.database'));
        $list = array_map('array_change_key_case', $list);
        //过滤非本项目前缀的表
        foreach ($list as $k => $v) {
            if (stripos($v['name'], strtolower(Config::get('database.prefix'))) !== 0) {
                unset($list[$k]);
            }
        }
        //渲染模板
        $this->assign('meta_title', '数据备份');
        $this->assign('data_list', $list);
        return $this->fetch($type);
    }

    //数据备份
    public function export($tables = null, $id = null, $start = null) {
        set_time_limit(0);
        if ($this->request->isAjax() && !empty($tables) && is_array($tables)) { //初始化
            $path = Config::get('config.data_backup_path');
            if (!is_dir($path)) {
                mkdir($path, 0755, true);
            }
            //读取备份配置
            $config = [
                'path' => realpath($path) . DIRECTORY_SEPARATOR,
                'part' => Config::get('config.data_backup_part_size'),
                'compress' => Config::get('config.data_backup_compress'),
                'level' => Config::get('config.data_backup_compress_level'),
            ];
            //检查是否有正在执行的任务
            $lock = "{$config['path']}backup.lock";
            if (is_file($lock)) {
                $this->error('检测到有一个备份任务正在执行，请稍后再试！');
            } else {
                //创建锁文件
                file_put_contents($lock, time());
            }
            //检查备份目录是否可写
            is_writeable($config['path']) || $this->error('备份目录不存在或不可写，请检查后重试！');
            Session::set('backup_config', $config);
            //生成备份文件信息
            $file = [
                'name' => date('Ymd-His', time()),
                'part' => 1,
            ];
            Session::set('backup_file', $file);
            //缓存要备份的表
            Session::set('backup_tables', $tables);
            //创建备份文件
            $Database = new \database\Database($file, $config);
            if (false !== $Database->create()) {
                $tab = ['id' => 0, 'start' => 0];
                return json(['code' => 1, 'tab' => $tab, 'tables' => $tables, 'msg' => '初始化成功！']);
            } else {
                $this->error('初始化失败，备份文件创建失败！');
            }
        } elseif ($this->request->isGet() && is_numeric($id) && is_numeric($start)) { //备份数据
            $tables = Session::get('backup_tables');
            //备份指定表
            $Database = new \database\Database(Session::get('backup_file'), Session::get('backup_config'));
            $start = $Database->backup($tables[$id], $start);
            if (false === $start) { //出错
                $this->error('备份出错！');
            } elseif (0 === $start) { //下一表
                if (isset($tables[++$id])) {
                    $tab = ['id' => $id, 'start' => 0];
                    return json(['code' => 1, 'tab' => $tab, 'msg' => '备份完成！']);
                } else { //备份完成，清空缓存
                    unlink(Session::get('backup_config.path') . 'backup.lock');
                    Session::set('backup_tables', null);
                    Session::set('backup_file', null);
                    Session::set('backup_config', null);
                    return json(['code' => 1, 'msg' => '备份完成！']);
                }
            } else {
                $tab = ['id' => $id, 'start' => $start[0]];
                $rate = floor(100 * ($start[0] / $start[1]));
                return json(['code' => 1, 'tab' => $tab, 'msg' => "正在备份...({$rate}%)"]);
            }
        } else { //出错
            $this->error('参数错误！');
        }
    }

    //数据库还原
    public function import() {
        $title = '';
        $list = [];
        //列出备份文件列表
        $path = Config::get('config.data_backup_path');
        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }
        $path = realpath($path);
        $flag = \FilesystemIterator::KEY_AS_FILENAME;
        $glob = new \FilesystemIterator($path, $flag);
        foreach ($glob as $name => $file) {
            if (preg_match('/^\d{8,8}-\d{6,6}-\d+\.sql(?:\.gz)?$/', $name)) {
                $name = sscanf($name, '%4s%2s%2s-%2s%2s%2s-%d');
                $date = "{$name[0]}-{$name[1]}-{$name[2]}";
                $time = "{$name[3]}:{$name[4]}:{$name[5]}";
                $part = $name[6];
                if (isset($list["{$date} {$time}"])) {
                    $info = $list["{$date} {$time}"];
                    $info['part'] = max($info['part'], $part);
                    $info['size'] = $info['size'] + $file->getSize();
                } else {
                    $info['part'] = $part;
                    $info['size'] = $file->getSize();
                }
                $extension = strtoupper(pathinfo($file->getFilename(), PATHINFO_EXTENSION));
                $info['compress'] = ($extension === 'SQL') ? '-' : $extension;
                $info['time'] = strtotime("{$date} {$time}");
                $list["{$date} {$time}"] = $info;
            }
        }
        //渲染模板
        $this->assign('meta_title', '数据还原');
        $this->assign('data_list', $list);
        $this->assign('empty', '<td colspan="7"><center>暂无备份数据</center></td>');
        return $this->fetch();
    }

    /**
     * 优化表
     * @param  String $tables 表名
     */
    public function optimize($tables = null) {
        if ($tables) {
            if (is_array($tables)) {
                $tables = implode('`,`', $tables);
                $list = Db::query("OPTIMIZE TABLE `{$tables}`");
                if ($list) {
                    $this->success("数据表优化完成！");
                } else {
                    $this->error("数据表优化出错请重试！");
                }
            } else {
                $list = Db::query("OPTIMIZE TABLE `{$tables}`");
                if ($list) {
                    $this->success("数据表'{$tables}'优化完成！");
                } else {
                    $this->error("数据表'{$tables}'优化出错请重试！");
                }
            }
        } else {
            $this->error("请指定要优化的表！");
        }
    }

    /**
     * 修复表
     * @param  String $tables 表名
     */
    public function repair($tables = null) {
        if ($tables) {
            if (is_array($tables)) {
                $tables = implode('`,`', $tables);
                $list = Db::query("REPAIR TABLE `{$tables}`");
                if ($list) {
                    $this->success("数据表修复完成！");
                } else {
                    $this->error("数据表修复出错请重试！");
                }
            } else {
                $list = Db::query("REPAIR TABLE `{$tables}`");
                if ($list) {
                    $this->success("数据表'{$tables}'修复完成！");
                } else {
                    $this->error("数据表'{$tables}'修复出错请重试！");
                }
            }
        } else {
            $this->error("请指定要修复的表！");
        }
    }

    /**
     * 还原数据库
     * @param  integer $time 时间
     * @param  [type]  $part  大小
     */
    public function restore($time = 0, $part = null, $start = null) {
        //读取备份配置
        $path = Config::get('config.data_backup_path');
        $config = [
            'path' => realpath($path) . DIRECTORY_SEPARATOR,
            'part' => Config::get('config.data_backup_part_size'),
            'compress' => Config::get('config.data_backup_compress'),
            'level' => Config::get('config.data_backup_compress_level'),
        ];
        if (is_numeric($time) && is_null($part) && is_null($start)) { //初始化
            //获取备份文件信息
            $name = date('Ymd-His', $time) . '-*.sql*';
            $path = $config['path'] . $name;
            $files = glob($path);
            $list = [];
            foreach ($files as $name) {
                $basename = basename($name);
                $match = sscanf($basename, '%4s%2s%2s-%2s%2s%2s-%d');
                $gz = preg_match('/^\d{8,8}-\d{6,6}-\d+\.sql.gz$/', $basename);
                $list[$match[6]] = array($match[6], $name, $gz);
            }
            ksort($list);
            //检测文件正确性
            $last = end($list);
            if (count($list) === $last[0]) {
                Session::set('backup_list', $list); //缓存备份列表
                $this->restore(0, 1, 0);
            } else {
                $this->error('备份文件可能已经损坏，请检查！');
            }
        } elseif (is_numeric($part) && is_numeric($start)) {
            $list = Session::get('backup_list');
            $db = new \database\Database($list[$part], $config);
            $start = $db->import($start);
            if (false === $start) {
                $this->error('还原数据出错！');
            } elseif (0 === $start) { //下一卷
                if (isset($list[++$part])) {
                    //$data = array('part' => $part, 'start' => 0);
                    $this->restore(0, $part, 0);
                } else {
                    Session::set('backup_list', null);
                    $this->success('还原完成！', url('Database/import'));
                }
            } else {
                $data = ['part' => $part, 'start' => $start[0]];
                if ($start[1]) {
                    $this->restore(0, $part, $start[0]);
                } else {
                    $data['gz'] = 1;
                    $this->restore(0, $part, $start[0]);
                }
            }
        } else {
            $this->error('参数错误！');
        }
    }

    /**
     * 删除备份文件
     * @param  Integer $time 备份时间
     */
    public function del($time = 0) {
        if ($time) {
            $path = Config::get('config.data_backup_path');
            $name = date('Ymd-His', $time) . '-*.sql*';
            $path = realpath($path) . DIRECTORY_SEPARATOR . $name;
            array_map("unlink", glob($path));
            if (count(glob($path))) {
                $this->error('备份文件删除失败，请检查权限！', url('Database/import'));
            } else {
                $this->success('备份文件删除成功！', url('Database/import'));
            }
        } else {
            $this->error('参数错误！', url('Database/import'));
        }
    }

}
