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
use think\facade\Config;
use Firebase\JWT\JWT;

class Base extends Controller {
    
    // 是否批量验证
    // protected $batchValidate = true;
    protected $jwt = [];
    
    //受保护的初始化方法
    protected function initialize() {
        parent::initialize();

        // url白名单，不验证token
        $urlWhite = [
            '/login'
        ];

        // 获取头信息
        $info = $this->request->header();

        // 验证token
        if(isset($info['x-token']) && isset($info['x-url-path']) && (!in_array($info['x-url-path'], $urlWhite) || in_array($info['x-url-path'], $urlWhite))) {

            $key = Config::get('cuicmf.uc_auth_key');
            $token = $info['x-token'];
            $notime = time();
            $jwt_refresh = Config::get('cuicmf.jwt_refresh');

            // $token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC93d3cuY3VpY21mLmNvbSIsInN1YiI6MSwiYXVkIjoiaHR0cDpcL1wvd3d3LmN1aWNtZi5jb20iLCJpYXQiOjE1NjU1OTc3MDcsIm5iZiI6MTU2NTU5NzcwNywiZXhwIjoxNTY1NTk3NzY3LCJqdGkiOiJNREF3TURBd01EQXdNSWFDYlhJIn0.y3pM0gMu5c8J1baQ5doK26qQRi4OY3qH4qiMiQagtDc';

            if($token) {
                // 验证token
                try {
                    JWT::$leeway = Config::get('cuicmf.jwt_refresh');
                    $decoded = JWT::decode($token, $key, ['HS256']);
                    if($decoded->exp > $notime) {
                        $this->jwt['userId'] = $decoded->sub;
                    } elseif ($decoded->exp <= $notime && ($notime - $decoded->exp) < $jwt_refresh) {
                        // $jwt_token = [
                        //     'iss' => 'http://www.cuicmf.com', //签发者
                        //     'sub' => $user['id'],
                        //     'aud' => 'http://www.cuicmf.com', //jwt所面向的用户
                        //     'iat' => $nowtime, //签发时间
                        //     'nbf' => $nowtime + Config::get('cuicmf.jwt_nbf'), //在什么时间之后该jwt才可用
                        //     'exp' => $nowtime + Config::get('cuicmf.jwt_exp'), //过期时间-10min
                        //     'jti' => think_encrypt($user['id'], Config::get('cuicmf.uc_auth_key'))
                        // ];
                        // // 生成token
                        // $jwt = JWT::encode($token, $key);
                        // $this->result($jwt, 0, '更新token！', 'json');
                    } else {
                        $this->result('', -2, 'token验证失败，请重新登录！', 'json');
                    }
                } catch(\Exception $e) {
                    $this->result('', -2, 'token验证失败，请重新登录！', 'json');
                }
            } else {
                $this->result('', -2, 'token不合法！', 'json');
            }

        }
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