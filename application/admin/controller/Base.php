<?php

// +----------------------------------------------------------------------
// | cuicmf 后台Base公共模块
// +----------------------------------------------------------------------
// | Copyright (c) 2018-2019 All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 崔元欣 <15811506097@163.com>
// +----------------------------------------------------------------------

namespace app\admin\controller;

use think\Controller;

class Base extends Controller {
    
    // 是否批量验证
    // protected $batchValidate = true;
    
    //受保护的初始化方法
    protected function initialize() {
        parent::initialize();
    }

    /**
     * 表单验证
     * @return [type] [description]
     */
    public function validation () {
    	// 获取post数据
        $fun = $this->request->post('fun', '', 'trim');
        $data = [
        	'username' => $this->request->post('username', '', 'trim'),
        	'password' => $this->request->post('password', '', 'trim')
        ];
        if(!$fun) {
        	$this->result('', -1, '接口请求出错，请联系管理员！', 'json');
        }
        // 验证数据
        switch($fun) {
        	case 'login':
        		$result = $this->validate($data, 'app\admin\validate\User.login');
        		break;
        }
        
        if (true !== $result) {
            $this->result($result, 10, '', 'json');
        } else {
            $this->result('', 0, '', 'json');
        }
    }
    
}