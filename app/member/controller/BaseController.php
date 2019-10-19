<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2018 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +---------------------------------------------------------------------
// | Author: Dean <zxxjjforever@163.com>
// +----------------------------------------------------------------------
namespace app\member\controller;

use think\Controller;
use think\Request;
use think\Response;
use think\View;
use think\Config;

class BaseController extends Controller
{


    /**
     * 构造函数
     * @param Request $request Request对象
     * @access public
     */
    public function __construct(Request $request = null)
    {

        $this->_initializeView();
        $this->view = View::instance(Config::get('template'), Config::get('view_replace_str'));


        // 控制器初始化
        $this->_initialize();

        // 前置操作方法
        if ($this->beforeActionList) {
            foreach ($this->beforeActionList as $method => $options) {
                is_numeric($method) ?
                    $this->beforeAction($options) :
                    $this->beforeAction($method, $options);
            }
        }
    }

    // 初始化视图配置
    protected function _initializeView()
    {
        $cmfAdminThemePath    = config('cmf_madmin_theme_path');
        $cmfAdminDefaultTheme = config('cmf_madmin_default_theme');
        //$themePath = "{$cmfAdminThemePath}{$cmfAdminDefaultTheme}";
        $themePath = "{$cmfAdminThemePath}";

        $root = cmf_get_root();
        //使cdn设置生效
        $cdnSettings = cmf_get_option('cdn_settings');
        if (empty($cdnSettings['cdn_static_root'])) {
            $viewReplaceStr = [
                '__ROOT__'     => $root,
                '__TMPL__'     => "{$root}/{$themePath}",
                '__STATIC__'   => "{$root}/static",
                '__WEB_ROOT__' => $root
            ];
        } else {
            $cdnStaticRoot  = rtrim($cdnSettings['cdn_static_root'], '/');
            $viewReplaceStr = [
                '__ROOT__'     => $root,
                '__TMPL__'     => "{$cdnStaticRoot}/{$themePath}",
                '__STATIC__'   => "{$cdnStaticRoot}/static",
                '__WEB_ROOT__' => $cdnStaticRoot
            ];
        }

        $viewReplaceStr = array_merge(config('view_replace_str'), $viewReplaceStr);
        config('template.view_base', "$themePath/");
        config('view_replace_str', $viewReplaceStr);
    }

    /**
     * ajax返回封装
     * @param int $code
     * @param string $msg
     * @param array $data
     * @return \think\response\Json
     */
    public function returnAjaxJson($code = 200, $msg = '成功',$data = []  ){
        return json([
            'code' => $code,
            'msg'  => $msg,
            'data' => $data,
        ]);
    }
}