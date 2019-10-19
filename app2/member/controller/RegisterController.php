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
use think\Session;
use think\Validate;
use tree\Tree;
use think\Request;
use app\user\model\UserModel;

class RegisterController extends BaseController
{
    private $stateMap = [
        'ERROR_PHONE_FORMAT'        =>  "手机号格式错误!",
        'ERROR_PHONE_EXISTS'        =>  "手机号已存在!",
        'ERROR_PWASSWORD_FORMAT'    =>  "密码位数少于8位!",
        'ERROR_UNKNOWN'             =>  "注册失败!",
        'ERROR_SEND_SMS_FAIL'       =>  "短信发送失败!",
        'ERROR_SEND_SMS'            =>  "请先发送验证码!",
        'ERROR_CODE'                =>  "验证码错误或已过期!",
        'ERROR_SEND_SMS_REPEAT'     =>  "请求频繁，稍后再试!",
        'ERROR_PRODUCT_NAME_EMTPY'  =>  "项目名称不能为空!",
        'SUCCESS'                   =>  "成功!",
        'ERROR_IMG_UPLOAD'          =>  "图片上传失败!",
    ];

    public function register(){
        $where['id'] = ['in','1,2,3,4,5,6,7,8,9,10,312,313,339,350,396,420'];
        $category = db('portal_category')->where($where)->field('id,name')->select();
        $this->assign('category',$category);
        return $this->fetch('/register');
    }
    //ajax获取分类
    public function Ajaxcate(){
        $data = Request::instance()->param();
        $id = $data['sid'];
        $towtype = db('portal_category')->where('parent_id = '.$id)->field('id,name')->select();
        $towtype = $towtype->all();
        $html = '';
        foreach ($towtype as $k=>$v){
            $html.= '<li tag = "'.$v["id"].'" onclick="selectChild($(this));">'.$v["name"].'</li>';
        }
        $datas = array('html'=>$html);
        echo json_encode($datas);
    }
    //验证图片验证码
    public function imgcode(){
        $data = Request::instance()->param();
        if (!cmf_captcha_check($data['imgcode'])) {
            return false;
        }else{
            return true;
        }
    }

    //验证账号是否已注册
    public function Accountnumber(){
       $data = Request::instance()->param();
       $info = db('member')->where("name = "."' $data[username]'")->field('id')->find();
       if($info){
           return false;
       }else{
           return true;
       }
    }

    //验证品牌名称
    public function pinpai(){
        $data = Request::instance()->param();
       $info = db('portal_xm')->where(['status' => 1,  'arcrank' => 1 ,'title' => $data['brand_name'] ])->field('aid,title,class')->find();
       if($info){
           return false;
       }else{
           return true;
       }
    }
    //校验短信验证码
    public function regmsg(){
        $data = Request::instance()->param();
        if($data['tel_code'] != Session::get('reg_sms_code')){
            return -1;
        }else{
            return true;
        }
    }
    //入表
    public function registerCheck(){
        $regex = "/\ |\/|\~|\!|\@|\#|\\$|\%|\^|\&|\*|\(|\)|\_|\+|\{|\}|\:|\<|\>|\?|\[|\]|\,|\.|\/|\;|\'|\`|\-|\=|\\\|\|/";
        $data = Request::instance()->param();
        //保存数据
        $member['phone'] = $data['telephone'];
        preg_match('/^1[3|4|5|6|7|8]\d{9}$/',$data['telephone'],$match);
        if(empty($match)){
            return false;
        }else{
            $member['phone'] = $data['telephone'];
        }

        $member['pinpai'] = $data['brand_name'];
        $member['company_name'] = $data['company_name'];
        $member['typeid'] = $data['industry_child_id'];
        $member['contacts'] = $data['combiner'];
        $name = preg_replace($regex," ",$data['username']);
//        print_r($name);die;
        $member['name'] = strip_tags($name);
        $member['password'] = cmf_password($data['password']);
        $member['type']  = 2;
        $member['reg_time'] = time();
        $phone = db('member')->where('phone = '.$data['telephone'])->field('id')->find();
        if($phone){
            $this->success('注册失败，手机号已存在!', '/user/register');
        }else{
            $user = db('member')->insert($member);
            if($user){
                $this->success('登录成功!', '/user/login');
            }else{
                $this->success('登录失败!', '/user/login');
            }
        }

    }

    //验证手机号
    public function checkTel(){
        $data = $this->request->post();
        $register          = new UserModel();
        if(cmf_check_mobile($data['username']))
        {
            $is = $register->where('mobile', $data['username'])->find();
            if(!$is){
                return true;
            }
        }
        return false;
    }
    //验证图形验证码
    public function checkcaptcha(){
        $data = $this->request->post();
        $captchaId = empty($data['_captcha_id']) ? '' : $data['_captcha_id'];
        if (cmf_captcha_check($data['captcha'], $captchaId)) {
            return true;
        }
        return false;
    }

    public function sendSmsCode(){
        $data = Request::instance()->param();
        if(FunCommon::isPhone($data['telephone'])==false){
            return $this->returnAjaxJson(201,$this->stateMap['ERROR_PHONE_FORMAT']);
        }
        if(isset($data['imgcode'])){
            if (!cmf_captcha_check($data['imgcode'])) {
                return $this->returnAjaxJson(201,$this->stateMap['ERROR_IMG_CODE']);
            }
        }

        $reg_sms_code = rand(100000,999999);
        $reg_sms_time = time();
        $reg_sms_phone = $data['telephone'];
        //校验短信验证码
//        if($data['phone'] == Session::get('reg_sms_phone') && time()<(Session::get('reg_sms_time')+60)){
//            return $this->returnAjaxJson(201,$this->stateMap['ERROR_SEND_SMS_REPEAT']);
//        }

        //发送短信验证码
        $flg = 1;

        if($flg){
            Session::set('reg_sms_code',$reg_sms_code);
            Session::set('reg_sms_time',$reg_sms_time);
            Session::set('reg_sms_phone',$reg_sms_phone);
            return $this->returnAjaxJson(200,$this->stateMap['SUCCESS'],$reg_sms_code);
        }else{
            return $this->returnAjaxJson(202,$this->stateMap['ERROR_SEND_SMS_FAIL']);
        }
    }

    /**
     * 注册查询项目
     * @return mixed
     */
    public function regSelectProduct(){
        $data = Request::instance()->param();
        if(empty($data['name'])){
            return $this->retunAjaxJosn(201,$this->stateMap['ERROR_PRODUCT_NAME_EMTPY']);
        }
        $list = db('portal_xm')->where(['status' => 1,  'arcrank' => 1 ,'title' => ['like',"%".$data['name']."%"] ])->field(['aid','title','class'])->select()->toArray();
        foreach ($list as $k => $v ){
            $list[$k]['url'] = '/'.$v['class'].'/'.$v['aid'].'.html';
        }
        return $this->returnAjaxJson(200,$this->stateMap['SUCCESS'],$list);
    }

    /**注册上传图片
     * @return mixed
     */
    public function uploadShopImg(){
        $data = Request::instance()->param();
        #删除旧图片
        if(!empty($data['old_img']) && FunCommon::get_files_ext($data['old_img'])=='jpg' ){
            $del_flg = FunCommon::del_file($data['old_img']);
        }
        #上传图片流
        $url = FunCommon::base64_upload($data['img']);
        if($url){
            return $this->returnAjaxJson(200,$this->stateMap['SUCCESS'],['img_url' => $url]);
        }else{
            return $this->returnAjaxJson(201,$this->stateMap['ERROR_IMG_UPLOAD'],['img_url' => $url]);
        }

    }
}