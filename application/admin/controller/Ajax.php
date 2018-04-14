<?php

// +----------------------------------------------------------------------
// | CuiCMF Ajax控制器
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
use think\facade\Cookie;
use think\facade\Env;
use Qiniu\Auth;

class Ajax extends Admin {

    /**
     * 加载上传视图
     */
    public function index() {
        $type = $this->request->get('type');
        $this->assign('type', $type);
        $this->assign('meta_title', '上传组件');
        return $this->fetch();
    }

    /**
     * 获取图片或文件的位置
     */
    public function filePath() {
        $file_id = $this->request->post('val');
        $model = app()->model('File');
        $ret = $model::whereIn('id', $file_id)->select();
        if ($ret) {
            $json = [];
            foreach ($ret as $key => $value) {
                $path = '.' . $value['savepath'] . $value['savename'] . '.' . $value['ext'];
                if (is_file($path)) {
                    $json[$key]['type'] = $value['type'];
                    $json[$key]['path'] = is_url() . $value['savepath'] . $value['savename'] . '.' . $value['ext'];
                    $json[$key]['name'] = $value['name'] . '.' . $value['ext'];
                    $json[$key]['size'] = $value['size'];
                    $json[$key]['mime'] = $value['mime'];
                    $json[$key]['ext'] = $value['ext'];
                    $json[$key]['id'] = $value['id'];
                } else {
                    $model::where(['id' => $value['id']])->delete();
                }
            }
            $this->result($json, 1, '查询成功', 'json');
        } else {
            $this->result('', 0, '查询失败', 'json');
        }
    }

    /**
     * 上传文件
     * @author 崔元欣 <15811506097@163.com>
     */
    public function upload() {
        $type = $this->request->get('type', 'images');
        $fileType = [
            'file' => 0,
            'images' => 1,
            'audio' => 2,
            'video' => 3
        ];
        switch ($fileType[$type]) {
            case 1:
                $this->uploadPicture($fileType[$type]);
                break;
            default:
                $this->uploadFile($fileType[$type]);
                break;
        }
    }

    public function uploadFile($type = 0) {
        $file = $this->request->file('file');
        $model = app()->model('File');
        $ret = $model::where(['sha1' => hash_file('sha1', $file->getInfo('tmp_name')), 'md5' => hash_file('md5', $file->getInfo('tmp_name'))])->find();
        if ($ret['id']) {
            $this->result(['id' => (string) $ret['id'], 'path' => is_url() . $ret['savepath'] . $ret['savename'] . '.' . $ret['ext'], 'ext' => $ret['ext']], 1, '上传成功', 'json');
        } else {
            $info = $file->move('./uploads/file/');
            if ($info) {
                $map['name'] = strstr($file->getInfo('name'), '.', true);
                $map['savename'] = strstr($info->getFilename(), '.', true);
                $map['savepath'] = '/uploads/file/' . strstr($info->getSaveName(), DIRECTORY_SEPARATOR, true) . '/';
                $map['ext'] = $info->getExtension();
                $map['mime'] = $file->getMime();
                $map['size'] = $file->getInfo('size');
                $map['url'] = '';
                $map['location'] = '';
                $map['md5'] = $info->hash('md5');
                $map['sha1'] = $info->hash('sha1');
                $map['file_type'] = $type;
                $res = $model::create($map);
                if ($res) {
                    $this->result(['id' => (string) $res['id'], 'path' => is_url() . $map['savepath'] . $map['savename'] . '.' . $map['ext'], 'ext' => $map['ext']], 1, '上传成功', 'json');
                } else {
                    exit(json_encode(['error' => 1, 'message' => '上传失败']));
                }
            } else {
                exit(json_encode(['error' => 1, 'message' => $file->getError()]));
            }
        }
    }

    /**
     * 上传图片
     * @author 崔元欣 <15811506097@163.com>
     */
    public function uploadPicture($type = 1) {
        $data = $this->request->post();
        if (isset($data['mesh_url'])) {
            $ext = @exif_imagetype($data['mesh_url']);
            switch ($ext) {
                case 1:
                    $ext = 'gif';
                    break;
                case 2:
                    $ext = 'jpg';
                    break;
                case 3:
                    $ext = 'png';
                    break;
                default:
                    $ext = 'bmp';
                    break;
            }
            $map = ['ext' => $ext, 'url' => $data['mesh_url']];
        } else {
            $picture = $this->request->file('file');
            $model = app()->model('File');
            $map = ['sha1' => hash_file('sha1', $picture->getInfo('tmp_name')), 'md5' => hash_file('md5', $picture->getInfo('tmp_name'))];
        }
        $ret = $model::where($map)->find();
        if ($ret['id']) {
            $this->result(['id' => (string) $ret['id'], 'path' => is_url() . $ret['savepath'] . $ret['savename'] . '.' . $ret['ext']], 1, '上传成功', 'json');
        } else {
            $info = $picture->move('./uploads/picture/');
            if ($info) {
                $map['name'] = strstr($picture->getInfo('name'), '.', true);
                $map['savename'] = strstr($info->getFilename(), '.', true);
                $map['savepath'] = '/uploads/picture/' . strstr($info->getSaveName(), DIRECTORY_SEPARATOR, true) . '/';
                $map['ext'] = $info->getExtension();
                $map['mime'] = $picture->getMime();
                $map['size'] = $picture->getInfo('size');
                $map['url'] = '';
                $map['location'] = '';
                $map['md5'] = $info->hash('md5');
                $map['sha1'] = $info->hash('sha1');
                $map['file_type'] = $type;
                $res = $model::create($map);
                if ($res) {
                    $this->result(['id' => (string) $res['id'], 'path' => is_url() . $map['savepath'] . $map['savename'] . '.' . $map['ext']], 1, '上传成功', 'json');
                } else {
                    exit(json_encode(['error' => 1, 'message' => '上传失败']));
                }
            } else {
                exit(json_encode(['error' => 1, 'message' => $picture->getError()]));
            }
        }
    }

    /**
     * 历史图片
     * @param type $ft
     */
//    public function imageList($ft = 'image') {
//        $page = $this->request->param('page', 1);
//        $year = $this->request->param('year');
//        $month = $this->request->param('month');
//        $where = [];
//        $where['file_type'] = $this->fileType[$ft];
//        if (!empty($year))
//            $where['year'] = $year;
//        if (!empty($month))
//            $where['month'] = $month;
//        $n = $this->getAttachmentList($page, $where, $ft);
//        if ($n) {
////            $d['items'] = $n;
////            $counts = Db::name('file')->where($where)->count();
////            $page = pagination($counts, $page, $this->pageSize, '', array(
////                'before' => '2',
////                'after' => '2',
////                'ajaxcallback' => 'null'
////            ));
////            $d['page'] = $page;
//        } else {
//            $d['items'] = '';
//            $d['page'] = '';
//        }
//        $message['message'] = $d;
//        $message['error'] = 0;
//        $re['message'] = $message;
//        $re['redirect'] = '';
//        $re['type'] = 'ajax';
//        $this->result($re, 1, '查询成功', 'json');
//    }

    /**
     * 获取历史文件列表
     * @param type $page
     * @param type $where
     * @return boolean
     */
//    protected function getAttachmentList($page = 1, $where = []) {
//        $re = Db::name('file')->where($where)->order("create_time desc")->limit($page - 1, $this->pageSize)->select();
//        if ($re) {
//            $dataInfo = [];
//            foreach ($re as $val) {
//                $n['id'] = $val['id'];
//                $n['filename'] = $val['savename'];
//                $n['url'] = $n['attachment'] = $val['url'];
//                $n['type'] = $val['file_type'];
//                $n['createtime'] = substr($val['create_time'], 0, 10);
//                $dataInfo[$n['id']] = $n;
//            }
//            return $dataInfo;
//        } else {
//            return false;
//        }
//    }

    /**
     * 下载文件
     * @param type $id
     * @author 崔元欣 <15811506097@163.com>
     */
//    public function download($id = null) {
//        if (empty($id) || !is_numeric($id)) {
//            $this->error('参数错误！');
//        }
//
//        $logic = D('Download', 'Logic');
//        if (!$logic->download($id)) {
//            $this->error($logic->getError());
//        }
//    }
}
