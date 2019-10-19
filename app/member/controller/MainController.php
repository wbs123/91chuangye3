<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2018 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 老猫 <zxxjjforever@163.com>
// +----------------------------------------------------------------------
namespace app\member\controller;

use app\member\controller\MemberAdmin;
use app\admin\model\ThemeModel;
use think\Db;
use think\Request;
use think\Session;
use think\Validate;
use tree\Tree;

class MainController extends MemberAdminController
{
    public function main(){
        echo "管理后台";
    }
}