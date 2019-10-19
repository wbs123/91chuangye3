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

use app\member\controller\BaseController;
use app\admin\model\ThemeModel;
use think\Db;
use think\Request;
use think\Session;
use think\Validate;
use tree\Tree;

class EnterpriseController extends BaseController
{
    private $stateMap = [
        'ERROR_PHONE_FORMAT'        =>  "手机号格式错误!",
        'ERROR_IMG_CODE'            =>  "图片验证码错误!",
        'ERROR_ACCOUNT_NOT_EXITENT' =>  "品牌名称或密码错误!",
        'ERROR_ACCOUNT_DISABLE'     =>  "账号已禁用,请联系管理员!",
        'ERROR_PWSSWORD'            =>  "密码错误!",
        'SUCCESS'                   =>  "登陆成功!",
        'ERROR_MOBILE_CODE'         =>  "手机验证码错误或已过期!",
    ];

    public function login(){
        return $this->fetch('/login_qy');
    }
    //校验验证码
    public function imgcoder(){
        $data = Request::instance()->param();
        if(isset($data['code'])){
            if(!cmf_captcha_check($data['code'])){
                return false;
            }else{
                return true;
            }
        }
    }
    //登录验证
    public function loginChecked(){
        $data = Request::instance()->param();
        $pinpai = $data['username'];
        $info = db('member')->where("pinpai = "."'$pinpai'")->field('id,password')->find();
        if($info){
            if(cmf_compare_password($data['password'], $info['password']) == $info['password']){
                Session::set('MADMIN_USER_ID',$info['id']);
                return $this->returnAjaxJson(2,$this->stateMap['SUCCESS']);
            }else{
                return $this->returnAjaxJson(0,$this->stateMap['ERROR_PWSSWORD']);
            }
        }else{
            return $this->returnAjaxJson(0,$this->stateMap['ERROR_ACCOUNT_NOT_EXITENT']);
        }
    }

}