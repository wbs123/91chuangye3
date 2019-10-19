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

use app\member\controller\BaseController;
use think\Controller;
use think\Request;
use think\Response;
use think\Session;
use think\View;
use think\Config;

class MemberAdminController extends BaseController
{

    public function __construct(Request $request = null)
    {
        //判断是否登录
        if(!Session::get('MADMIN_USER_ID')){
            if(\request()->isAjax()){
                abort(404);
            }else{
                return $this->redirect('/user/login');
            }
        }

    }
    
}