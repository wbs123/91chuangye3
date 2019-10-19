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

class LoginController extends BaseController
{
    private $stateMap = [
        'ERROR_PHONE_FORMAT'        =>  "手机号格式错误!",
        'ERROR_IMG_CODE'            =>  "图片验证码错误!",
        'ERROR_ACCOUNT_NOT_EXITENT' =>  "账号不存在!",
        'ERROR_ACCOUNT_DISABLE'     =>  "账号已禁用,请联系管理员!",
        'ERROR_PWSSWORD'            =>  "密码错误!",
        'SUCCESS'                   =>  "登陆成功!",
        'ERROR_MOBILE_CODE'         =>  "手机验证码错误或已过期!",
    ];

    public function login(){
        return $this->fetch('/login');
    }
    public function logins(){
        $admin_id = session('MADMIN_USER_ID');
        $user = Db::name('member')->where(['id'=>$admin_id])->find();
        print_r($user);die;
//        return $this->fetch('/login');
    }
    public function RegSelect(){
        return $this->fetch('/RegSelect');
    }

    public function loginCheck(){
        $data = Request::instance()->param();

        if(isset($data['tuxingyzm'])){
            if (!cmf_captcha_check($data['tuxingyzm'])) {
                return false;
            }else{
                return true;
            }
        }
        if(isset($data['personPhone']) && isset($data['pass_word'])){
            $user = db('member')->where(['phone' => $data['personPhone']])->find();
            if($user['status'] == 1){
                if(cmf_compare_password($data['pass_word'], $user['password']) == $user['password']){
                    //记录用户session
                    Session::set('user',$user);
                    return true;
//                    $this->success('登录成功!', '/madmin/login');
                }else{
                    return false;
                }
            }else{
                return false;
            }

        }

//        if($data['flg'] == 1){
//            if(FunCommon::isPhone($data['phone1'])==false){
//                return $this->returnAjaxJson(201,$this->stateMap['ERROR_PHONE_FORMAT']);
//            }
//            //校验图片验证码
//            if(!cmf_captcha_check($data['imgcode1'])){
//                return $this->returnAjaxJson(201,$this->stateMap['ERROR_IMG_CODE']);
//            }
//            //查询账号是否存在
//            $user = db('member')->where(['phone' => $data['phone1']])->find();
//            if($user){
//                if($user['status'] != 1){
//                    return $this->returnAjaxJson(201,$this->stateMap['ERROR_ACCOUNT_DISABLE']);
//                }
//                if(cmf_compare_password($data['password'], $user['password']) == $user['password']){
//                    //记录用户session
//                    Session::set('MADMIN_USER_ID',$user['id']);
//                    return $this->returnAjaxJson(200,$this->stateMap['SUCCESS']);
//                }else{
//                    return $this->returnAjaxJson(201,$this->stateMap['ERROR_PWSSWORD']);
//                }
//            }else{
//                return $this->returnAjaxJson(201,$this->stateMap['ERROR_ACCOUNT_NOT_EXITENT']);
//            }
//        }else{
//            if(FunCommon::isPhone($data['phone2'])==false){
//                return $this->returnAjaxJson(201,$this->stateMap['ERROR_PHONE_FORMAT']);
//            }
//            //查询账号是否存在
//            $user = db('member')->where(['phone' => $data['phone2']])->find();
//            if($user){
//                if($user['status'] != 1){
//                    return $this->returnAjaxJson(201,$this->stateMap['ERROR_ACCOUNT_DISABLE']);
//                }
//                //校验手机验证码
//                if($data['mobileCode'] != Session::get('login_sms_code') || time() > Session::get('login_sms_time')+300){
//                    return $this->returnAjaxJson(201,$this->stateMap['ERROR_MOBILE_CODE']);
//                }
//                //记录用户session
//                Session::set('MADMIN_USER_ID',$user['id']);
//                return $this->returnAjaxJson(200,$this->stateMap['SUCCESS']);
////                if($data['code'] != Session::get('login_sms_code')){
////                    return $this->returnAjaxJson(201,$this->stateMap['ERROR_SEND_SMS']);
////                }
//
//            }else{
//                return $this->returnAjaxJson(201,$this->stateMap['ERROR_ACCOUNT_NOT_EXITENT']);
//            }
//        }

    }


    public function sendLoginSmsCode(){
        $data = Request::instance()->param();

        if(FunCommon::isPhone($data['telephone'])==false){
            return $this->returnAjaxJson(201,$this->stateMap['ERROR_PHONE_FORMAT']);
        }
        //校验图片验证码
//        if (!cmf_captcha_check($data['imgcode'])) {
//            return $this->returnAjaxJson(201,$this->stateMap['ERROR_IMG_CODE']);
//        }

        $login_sms_code = rand(100000,999999);
        $login_sms_time = time();
        $login_sms_phone = $data['telephone'];
        //校验短信验证码
//        if($data['phone'] == Session::get('reg_sms_phone') && time()<(Session::get('reg_sms_time')+60)){
//            return $this->returnAjaxJson(201,$this->stateMap['ERROR_SEND_SMS_REPEAT']);
//        }

        //发送短信验证码
        $flg = 1;

        if($flg){
            Session::set('login_sms_code',$login_sms_code);
            Session::set('login_sms_time',$login_sms_time);
            Session::set('login_sms_phone',$login_sms_phone);
            return $this->returnAjaxJson(200,'发送成功',$login_sms_code);
        }else{
            return $this->returnAjaxJson(202,$this->stateMap['ERROR_SEND_SMS_FAIL']);
        }

    }
    public function loginChecks(){
        $data = Request::instance()->param();
        if(isset($data['code'])){
            if ($data['code'] != Session::get('login_sms_code')) {
                return false;
            }else{
                if(isset($data['telphone'])){
                    $flg = db('member')->where(['phone' => $data['telphone']])->find();
                    if($flg){
                            return true;
                    }else{
                        $member['phone'] = $data['telphone'];
                        $member['password'] = cmf_password('123456789');
                        $member['type'] = 1;
                        $user = db('member')->insert($member);
                        if($user){
                            $this->success('登录成功!', '/user/login');
                        }
                    }
                }
            }
        }
        //校验图片验证码
        if (isset($data['tuxing_yzm'])) {
            if (!cmf_captcha_check($data['tuxing_yzm'])) {
                return false;
            }else{
                return true;
            }
        }

    }
    function center(){

    }
}