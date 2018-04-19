<?php

// +----------------------------------------------------------------------
// | CuiCMF 后台Form标签库
// +----------------------------------------------------------------------
// | Copyright (c) 2014-2018 All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: cuiyuanxin <15811506097@163.com>
// +----------------------------------------------------------------------

namespace app\admin\taglib;

use think\template\TagLib;

class Form extends TagLib {

    /**
     * 定义标签列表
     */
    protected $tags = [
        // 标签定义： attr 属性列表 close 是否闭合（0 或者1 默认1） alias 标签别名 level 嵌套层次
        'input' => ['attr' => 'name, class, value, placeholder, required, disabled', 'close' => 0],
        'close' => ['attr' => 'time,format', 'close' => 0], //闭合标签，默认为不闭合
        'open' => ['attr' => 'name,type', 'close' => 1],
    ];

    /**
     * input框标签
     * @param type $tag
     * @return html
     */
    public function tagInput($tag) {
        
        $name = $tag['name'];
        $class = empty($tag['class']) ? '' : $tag['class'];
        $value = empty($tag['value']) ? '' : $tag['value'];
        $placeholder = empty($tag['placeholder']) ? '' : $tag['placeholder'];
        $required = empty($tag['required']) ? '' : ' required="required"';
        $disabled = empty($tag['disabled']) ? '' : ' disabled="disabled"';
        $parse = '<?php ';
        $parse .= 'echo "<input type=\'text\' name=\'' . $name . '\' class=\'form-control ' . $class . '\' id=\'' . $name . '\' placeholder=\'' . $placeholder . '\' value=\'' . $value . '\'  />";';
        $parse .= ' ?>';
        return $parse ;
    }

    /**
     * 这是一个闭合标签的简单演示
     */
    public function tagClose($tag) {
        $format = empty($tag['format']) ? 'Y-m-d H:i:s' : $tag['format'];
        $time = empty($tag['time']) ? time() : $tag['time'];
        $parse = '<?php ';
        $parse .= 'echo date("' . $format . '",' . $time . ');';
        $parse .= ' ?>';
        return $parse;
    }

    /**
     * 这是一个非闭合标签的简单演示
     */
    public function tagOpen($tag, $content) {
        $type = empty($tag['type']) ? 0 : 1; // 这个type目的是为了区分类型，一般来源是数据库
        $name = $tag['name']; // name是必填项，这里不做判断了
        $parse = '<?php ';
        $parse .= '$test_arr=[[1,3,5,7,9],[2,4,6,8,10]];'; // 这里是模拟数据
        $parse .= '$__LIST__ = $test_arr[' . $type . '];';
        $parse .= ' ?>';
        $parse .= '{volist name="__LIST__" id="' . $name . '"}';
        $parse .= $content;
        $parse .= '{/volist}';
        return $parse;
    }

}
