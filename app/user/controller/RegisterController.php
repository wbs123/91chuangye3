<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2019 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: Powerless < wzxaini9@gmail.com>
// +----------------------------------------------------------------------
namespace app\user\controller;

use cmf\controller\HomeBaseController;
use think\Validate;
use app\user\model\UserModel;
use think\Session;
use think\Db;

class RegisterController extends HomeBaseController
{

    /**
     * 个人用户注册
     */
    public function index()
    {
        $redirect = $this->request->post("redirect");
        if (empty($redirect)) {
            $redirect = $this->request->server('HTTP_REFERER');
        } else {
            $redirect = base64_decode($redirect);
        }
		$type = Db::name('portal_category')->where(['parent_id'=>0,'channeltype'=>17,'status'=>1,'delete_time'=>0])->field('id,name')->order('list_order asc')->select()->toArray();
        session('login_http_referer', $redirect);
		$this->assign('type',$type);
        if (cmf_is_user_login()) {
            return redirect($this->request->root(). cmf_url('user/Profile/center','',false));
        } else {
            return $this->fetch(":register");
        }
    }
	
	/**
     * 企业用户注册
     */
    public function comRegister()
    {
        $redirect = $this->request->post("redirect");
        if (empty($redirect)) {
            $redirect = $this->request->server('HTTP_REFERER');
        } else {
            $redirect = base64_decode($redirect);
        }
		$type = Db::name('portal_category')->where(['parent_id'=>0,'channeltype'=>17,'status'=>1,'delete_time'=>0])->field('id,name')->order('list_order asc')->select()->toArray();
        session('login_http_referer', $redirect);
		foreach($type as $k=>$v){
			$type[$k]['name'] = str_replace('加盟','',$v['name']);
		}
		$this->assign('type',$type);
        if (cmf_is_user_login()) {
            return redirect($this->request->root(). cmf_url('user/Profile/center','',false));
        } else {
            return $this->fetch(":comregister");
        }
    }

    /**
     * 个人用户注册提交
     */
    public function doRegister()
    {
        if ($this->request->isPost()) {
            $rules = [
                'username'  => "require",
                'category'  => 'require',
                'invested'  => 'require',
                'address'  => 'require',
                'captcha'  => 'require',
                'code'     => 'require',
                'password' => 'require|min:6|max:16',
            ];

            $isOpenRegistration = cmf_is_open_registration();

            if ($isOpenRegistration) {
                unset($rules['code']);
            }

            $validate = new \think\Validate($rules);
            $validate->message([
                'username.require'     => '手机号码不能为空',
                'category.require'     => '意向分类不能为空',
                'invested.require'     => '投资金额不能为空',
                'address.require'     => '所在地区不能为空',
                'code.require'     => '验证码不能为空',
                'password.require' => '密码不能为空',
                'password.max'     => '密码不能超过16个字符',
                'password.min'     => '密码不能小于6个字符',
                'captcha.require'  => '验证码不能为空',
            ]);

            $data = $this->request->post();
			
            if (!$validate->check($data)) {
                $this->error($validate->getError());
            }
			if(!preg_match('/^(13[0-9]|14[579]|15[0-3,5-9]|16[6]|17[0135678]|18[0-9]|19[89])\d{8}$/',$data['username'])){
				$this->error('手机号码格式不正确');
			}

            $captchaId = empty($data['_captcha_id']) ? '' : $data['_captcha_id'];
            if (!cmf_captcha_check($data['captcha'], $captchaId,false)) {
                $this->error('短信验证码错误');
            }

            if (!$isOpenRegistration) {
                $errMsg = cmf_check_verification_code($data['username'], $data['code']);
                if (!empty($errMsg)) {
                    $this->error($errMsg);
                }
            }
			
            $register          = new UserModel();
            $user['user_pass'] = $data['password'];
            $user['user_type'] = 2;//会员类型
			$user['category'] = $data['category'];
			$user['invested'] = $data['invested'];
			$user['address'] = $data['address'];
            if (Validate::is($data['username'], 'email')) {
                $user['user_email'] = $data['username'];
                $log                = $register->register($user, 3);
            } else if (cmf_check_mobile($data['username'])) {
                $user['mobile'] = $data['username'];
                $log            = $register->register($user, 2);
            } else {
                $log = 2;
            }
            $sessionLoginHttpReferer = session('login_http_referer');
            $redirect                = empty($sessionLoginHttpReferer) ? cmf_get_root() . '/' : $sessionLoginHttpReferer;
            switch ($log) {
                case 0:
                    $this->success('注册成功', $redirect);
                    break;
                case 1:
                    $this->error("您的账户已注册过");
                    break;
                case 2:
                    $this->error("您输入的账号格式错误");
                    break;
                default :
                    $this->error('未受理的请求');
            }

        } else {
            $this->error("请求错误");
        }

    }
	/**
     * 企业用户注册提交
     */
    public function comdoRegister()
    {
        if ($this->request->isPost()) {
            $rules = [
                'brand_name'  => "require",
                'company_name'  => 'require',
                'industry_id'  => 'require|number',
                'industry_child_id'  => 'require|number',
                'mobile'  => 'require',
                'combiner'     => 'require',
                'captcha'     => 'require',
                'code'     => 'require',
                'password' => 'require|min:6|max:16',
            ];

            $isOpenRegistration = cmf_is_open_registration();

            if ($isOpenRegistration) {
                unset($rules['code']);
            }

            $validate = new \think\Validate($rules);
            $validate->message([
                'brand_name.require'     => '品牌名称不能为空',
                'company_name.require'     => '公司名称不能为空',
                'industry_id.require'     => '请选择品牌所属行业',
                'industry_child_id.require'     => '请选择所属行业二级分类',
                'industry_id.number'     => '所属行业位数字格式',
                'industry_child_id.number'     => '行业二级分类为数字格式',
                'mobile.require'     => '手机号不能为空',
                'combiner.require'     => '联系人不能为空',
                'code.require'     => '短信验证码不能为空',
                'password.require' => '密码不能为空',
                'password.max'     => '密码不能超过16个字符',
                'password.min'     => '密码不能小于6个字符',
                'captcha.require'  => '图片验证码不能为空',
            ]);

            $data = $this->request->post();
			
            if (!$validate->check($data)) {
                $this->error($validate->getError());
            }
			if(!preg_match('/^(13[0-9]|14[579]|15[0-3,5-9]|16[6]|17[0135678]|18[0-9]|19[89])\d{8}$/',$data['mobile'])){
				$this->error('手机号码格式不正确');
			}

            $captchaId = empty($data['_captcha_id']) ? '' : $data['_captcha_id'];
            if (!cmf_captcha_check($data['captcha'], $captchaId,false)) {
                $this->error('短信验证码错误');
            }

            if (!$isOpenRegistration) {
                $errMsg = cmf_check_verification_code($data['mobile'], $data['code']);
                if (!empty($errMsg)) {
                    $this->error($errMsg);
                }
            }
            $register          = new UserModel();
            $user['user_pass'] = $data['password'];
            $user['user_type'] = 3;//会员类型
			$user['brand_name'] = $data['brand_name'];
			$user['company_name'] = $data['company_name'];
			$user['combiner'] = $data['combiner'];
			$user['industry_id'] = $data['industry_id'];
			$user['industry_child_id'] = $data['industry_child_id'];
            $user['user_login'] = $data['brand_name'];
            $user['mobile'] = $data['mobile'];

			$log = $register->comregister($user, 1);
            
            $sessionLoginHttpReferer = session('login_http_referer');
            $redirect                = empty($sessionLoginHttpReferer) ? cmf_get_root() . '/' : $sessionLoginHttpReferer;
            switch ($log) {
                case 0:
                    $this->success('注册成功', $redirect);
                    break;
                case 1:
                    $this->error("您的账户已注册过");
                    break;
                case 2:
                    $this->error("您输入的账号格式错误");
                    break;
                default :
                    $this->error('未受理的请求');
            }

        } else {
            $this->error("请求错误");
        }

    }
	//验证手机号
	public function checkTel($tel = ''){
		$data = $this->request->post();
		$data['username'] = empty($tel) ? $data['username'] : $tel;
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
	//验证手机号
	public function checkBrand($brand = ''){
		$data = $this->request->post();
		$data['brand_name'] = empty($brand) ? $data['brand_name'] : $brand;
		$register          = new UserModel();
		$is = $register->where('user_login', $data['brand_name'])->find();
		if(!$is){
			return true;
		}
		return false;
	}
	//验证图形验证码
	public function checkcaptcha(){
		$data = $this->request->post();
		$captchaId = empty($data['_captcha_id']) ? '' : $data['_captcha_id'];
		if (cmf_captcha_check($data['captcha'], $captchaId,false)) {
			return true;
		}
		return false;
	}
	//验证短信验证码
	public function checkcode(){
		$data = $this->request->post();
		$errMsg = cmf_check_verification_code($data['mobile'], $data['code'],false);
		if (!empty($errMsg)) {
			return false;
		}
		return true;
	}
	//获取手机验证码
	public function sendSmsCode(){
        $data = $this->request->post();
		if(empty($data['mobile'])){
			$this->error("请填写手机号！");
		}
		if(!cmf_check_mobile($data['mobile'])){
			$this->error("手机号码格式不正确！");
		}
		if(!$this->checkTel($data['mobile'])){
			$this->error("此手机号码已注册过，请更换手机号或直接登录！");
		}
		if(!cmf_captcha_check($data['captcha'],'',false)){
			$this->error("图形验证码输入错误！");
		}
		//TODO 限制 每个ip 的发送次数
        $code = cmf_get_verification_code($data['mobile']);
        if (empty($code)) {
            $this->error("验证码发送过多,请明天再试!");
        }
		
		$param  = ['mobile' => $data['mobile'], 'code' => $code];
		$result = hook_one("send_mobile_verification_code", $param);

		if ($result !== false && !empty($result['error'])) {
			$this->error($result['message']);
		}

		$expireTime = empty($result['expire_time']) ? 0 : $result['expire_time'];

		cmf_verification_code_log($data['mobile'], $code, $expireTime);

		if (!empty($result['message'])) {
			$this->success($result['message']);
		} else {
			$this->success('验证码已经发送成功!');
		}
    }
	//获取栏目
    public function getcate(){
        $data = $this->request->post();
        $type = Db::name('portal_category')->where(['parent_id'=>$data['sid'],'status'=>1,'delete_time'=>0])->field('id,name')->select()->toArray();
        $html = '';
        foreach ($type as $k=>$v){
            $html.= '<li tag = "'.$v["id"].'" onclick="selectChild($(this));">'.$v["name"].'</li>';
        }
		$data = ['title'=>$type[0]['name'],'content'=>$html];
        echo json_encode($data);
    }
}