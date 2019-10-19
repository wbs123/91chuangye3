<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2018 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: Powerless < wzxaini9@gmail.com>
// +----------------------------------------------------------------------
namespace app\user\controller;

use cmf\lib\Storage;
use think\Validate;
use think\Image;
use cmf\controller\UserBaseController;
use app\user\model\UserModel;
use think\Db;

class ProfileController extends UserBaseController
{

    function _initialize()
    {
        parent::_initialize();
    }

    /**
     * 会员中心首页
     */
    public function center()
    {
		//页数
		$url = $this->request->url();
        preg_match('/list_(\d+).html/', $url, $matches);
        $page = count($matches)>0 ? $matches[1] : 1;
		
        $user = cmf_get_current_user();
        $this->assign($user);

        $userId = cmf_get_current_user_id();
        $userModel = new UserModel();
		$data = $userModel->centerlist($page);
	
		$looks = Db::name('user_look')->where(['user_id'=> $userId])->count();
		//已查看
		$this->assign('looks',$looks);
		//剩余阅读数
		$this->assign('shengyu',$user['all_nums']-$looks);
		//为空输出
		$empty = '<div class="item_message">
					<p style="height: 77px; font-size: 14px;color: #6f9ebd;text-align: center;line-height: 77px;">
						<img src="/static/usercenter/img/nodata.png" style="width: 28px;height: 21px;position: relative;left: -10px;" />暂无相应的结果
					</p>
				</div>';
		$this->assign('empty',$empty);
		$this->assign('data',$data);
		$this->assign('render',!empty($data) ? $data->render() : '');
		$this->assign('user', $user);
        return $this->fetch();
    }

    public function lookas(){
		$url = $this->request->url();
        preg_match('/list_(\d+).html/', $url, $matches);
        $page = count($matches)>0 ? $matches[1] : 1;
		
        $user = cmf_get_current_user();
        $this->assign($user);

        $userId = cmf_get_current_user_id();
        $userModel = new UserModel();
        $data = $userModel->looklist($page);

		$looks = Db::name('user_look')->where(['user_id'=> $userId])->count();
        //已查看
		$this->assign('looks',$looks);
		//剩余阅读数
		$this->assign('shengyu',$user['all_nums']-$looks);
        //为空输出
        $empty = '<div class="item_message">
                    <p style="height: 77px; font-size: 14px;color: #6f9ebd;text-align: center;line-height: 77px;">
                        <img src="/static/usercenter/img/nodata.png" style="width: 28px;height: 21px;position: relative;left: -10px;" />暂无相应的结果
                    </p>
                </div>';
        $this->assign('empty',$empty);
        $this->assign('data',$data);
		$this->assign('render',!empty($data) ? $data->render() : '');
        $this->assign('user', $user);
        return $this->fetch();
    }

    //查看信息记录
    public function information(){
		$userId = cmf_get_current_user_id();
		//共可以查看条数
    	$all_nums = Db::name('user')->where(['id'=>$userId])->value('all_nums');
		//已查看条数
    	$look_nums = Db::name('user_look')->where(['user_id'=>$userId])->count();
		
    	if($all_nums-$look_nums>0){
	        $data = $this->request->post();
	        if($data){
	            $date['look_id'] = $data['id'];
	            $date['user_id'] = $userId;
	            $date['inputtime'] = time();
	
	            $existence = Db::name('user_look')->where(['look_id'=>$data['id'],'user_id'=>$userId])->find();
	            if($existence){
	                $datas = array('data'=>3);
	                echo json_encode($datas);
	            }else{
	                $info_look = Db::name('user_look')->insert($date);
	                if($info_look){
	                    $tel = Db::name('user_info')->where(['id'=>$data['id']])->value('tel');
						$datas = array('data'=>1,'tel'=>$tel);
						echo json_encode($datas);
	                }
	            }
	        }else{
	            $datas = array('data'=>2);
	            echo json_encode($datas);
	        }
    	}else{
            $datas = array('data'=>2);
            echo json_encode($datas);
        }

    }

    /**
     * 编辑用户资料
     */
    public function edit()
    {
        $user = cmf_get_current_user();
        $this->assign('user',$user);
        return $this->fetch('edit');
    }

    /**
     * 编辑用户资料提交
     */
    public function editPost()
    {
        if ($this->request->isPost()) {
            $validate = new Validate([
                'user_nickname' => 'max:32',
                'sex'           => 'between:0,2',
                'birthday'      => 'dateFormat:Y-m-d|after:-88 year|before:-1 day',
                'user_url'      => 'url|max:64',
                'signature'     => 'max:128',
            ]);
            $validate->message([
                'user_nickname.max'   => lang('NICKNAME_IS_TO0_LONG'),
                'sex.between'         => lang('SEX_IS_INVALID'),
                'birthday.dateFormat' => lang('BIRTHDAY_IS_INVALID'),
                'birthday.after'      => lang('BIRTHDAY_IS_TOO_EARLY'),
                'birthday.before'     => lang('BIRTHDAY_IS_TOO_LATE'),
                'user_url.url'        => lang('URL_FORMAT_IS_WRONG'),
                'user_url.max'        => lang('URL_IS_TO0_LONG'),
                'signature.max'       => lang('SIGNATURE_IS_TO0_LONG'),
            ]);

            $data = $this->request->post();
            if (!$validate->check($data)) {
                $this->error($validate->getError());
            }
            $editData = new UserModel();
            if ($editData->editData($data)) {
                $this->success(lang('EDIT_SUCCESS'), "user/profile/edit");
            } else {
                $this->error(lang('NO_NEW_INFORMATION'));
            }
        } else {
            $this->error(lang('ERROR'));
        }
    }

    /**
     * 个人中心修改密码
     */
    public function password()
    {
        $user = cmf_get_current_user();
        $this->assign('user',$user);
        return $this->fetch();
    }

    /**
     * 个人中心修改密码提交
     */
    public function passwordPost()
    {
        if ($this->request->isPost()) {
            $validate = new Validate([
                'old_password' => 'require|min:6|max:32',
                'password'     => 'require|min:6|max:32',
                'repassword'   => 'require|min:6|max:32',
            ]);
            $validate->message([
                'old_password.require' => lang('old_password_is_required'),
                'old_password.max'     => lang('old_password_is_too_long'),
                'old_password.min'     => lang('old_password_is_too_short'),
                'password.require'     => lang('password_is_required'),
                'password.max'         => lang('password_is_too_long'),
                'password.min'         => lang('password_is_too_short'),
                'repassword.require'   => lang('repeat_password_is_required'),
                'repassword.max'       => lang('repeat_password_is_too_long'),
                'repassword.min'       => lang('repeat_password_is_too_short'),
            ]);

            $data = $this->request->post();
            if (!$validate->check($data)) {
                $this->error($validate->getError());
            }

            $login = new UserModel();
            $log   = $login->editPassword($data);
            switch ($log) {
                case 0:
                    $this->success(lang('change_success'));
                    break;
                case 1:
                    $this->error(lang('password_repeat_wrong'));
                    break;
                case 2:
                    $this->error(lang('old_password_is_wrong'));
                    break;
                default :
                    $this->error(lang('ERROR'));
            }
        } else {
            $this->error(lang('ERROR'));
        }

    }

    // 用户头像编辑
    public function avatar()
    {
        $user = cmf_get_current_user();
        $this->assign('user',$user);
        return $this->fetch();
    }

    // 用户头像上传
    public function avatarUpload()
    {
        $file   = $this->request->file('file');
        $result = $file->validate([
            'ext'  => 'jpg,jpeg,png',
            'size' => 1024 * 1024
        ])->move('.' . DS . 'upload' . DS . 'avatar' . DS);

        if ($result) {
            $avatarSaveName = str_replace('//', '/', str_replace('\\', '/', $result->getSaveName()));
            $avatar         = 'avatar/' . $avatarSaveName;
            session('avatar', $avatar);

            return json_encode([
                'code' => 1,
                "msg"  => "上传成功",
                "data" => ['file' => $avatar],
                "url"  => ''
            ]);
        } else {
            return json_encode([
                'code' => 0,
                "msg"  => $file->getError(),
                "data" => "",
                "url"  => ''
            ]);
        }
    }

    // 用户头像裁剪
    public function avatarUpdate()
    {
        $avatar = session('avatar');
        if (!empty($avatar)) {
            $w = $this->request->param('w', 0, 'intval');
            $h = $this->request->param('h', 0, 'intval');
            $x = $this->request->param('x', 0, 'intval');
            $y = $this->request->param('y', 0, 'intval');

            $avatarPath = "./upload/" . $avatar;

            $avatarImg = Image::open($avatarPath);
            $avatarImg->crop($w, $h, $x, $y)->save($avatarPath);

            $result = true;
            if ($result === true) {
                $storage = new Storage();
                $result  = $storage->upload($avatar, $avatarPath, 'image');

                $userId = cmf_get_current_user_id();
                Db::name("user")->where(["id" => $userId])->update(["avatar" => $avatar]);
                session('user.avatar', $avatar);
                $this->success("头像更新成功！");
            } else {
                $this->error("头像保存失败！");
            }

        }
    }

    /**
     * 绑定手机号或邮箱
     */
    public function binding()
    {
        $user = cmf_get_current_user();
        $this->assign($user);
        return $this->fetch();
    }

    /**
     * 绑定手机号
     */
    public function bindingMobile()
    {
        if ($this->request->isPost()) {
            $validate = new Validate([
                'username'          => 'require|number|unique:user,mobile',
                'verification_code' => 'require',
            ]);
            $validate->message([
                'username.require'          => '手机号不能为空',
                'username.number'           => '手机号只能为数字',
                'username.unique'           => '手机号已存在',
                'verification_code.require' => '验证码不能为空',
            ]);

            $data = $this->request->post();
            if (!$validate->check($data)) {
                $this->error($validate->getError());
            }
            $errMsg = cmf_check_verification_code($data['username'], $data['verification_code']);
            if (!empty($errMsg)) {
                $this->error($errMsg);
            }
            $userModel = new UserModel();
            $log       = $userModel->bindingMobile($data);
            switch ($log) {
                case 0:
                    $this->success('手机号绑定成功');
                    break;
                default :
                    $this->error('未受理的请求');
            }
        } else {
            $this->error("请求错误");
        }
    }

    /**
     * 绑定邮箱
     */
    public function bindingEmail()
    {
        if ($this->request->isPost()) {
            $validate = new Validate([
                'username'          => 'require|email|unique:user,user_email',
                'verification_code' => 'require',
            ]);
            $validate->message([
                'username.require'          => '邮箱地址不能为空',
                'username.email'            => '邮箱地址不正确',
                'username.unique'           => '邮箱地址已存在',
                'verification_code.require' => '验证码不能为空',
            ]);

            $data = $this->request->post();
            if (!$validate->check($data)) {
                $this->error($validate->getError());
            }
            $errMsg = cmf_check_verification_code($data['username'], $data['verification_code']);
            if (!empty($errMsg)) {
                $this->error($errMsg);
            }
            $userModel = new UserModel();
            $log       = $userModel->bindingEmail($data);
            switch ($log) {
                case 0:
                    $this->success('邮箱绑定成功');
                    break;
                default :
                    $this->error('未受理的请求');
            }
        } else {
            $this->error("请求错误");
        }
    }



}