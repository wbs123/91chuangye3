<?php
/**
 * Created by PhpStorm.
 * User: LENOVO
 * Date: 2018/12/25
 * Time: 14:45
 */
namespace app\portal\controller;
use cmf\controller\HomeBaseController;
use think\Db;
use think\Request;
class UrlController extends HomeBaseController
{
    public function paramFun($id='1')
    {
        echo '路由參數 -'.$id;
        print_r(Request::param());
    }
}