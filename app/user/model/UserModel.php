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
namespace app\user\model;

use think\Db;
use think\Model;

class UserModel extends Model
{
    protected $type = [
        'more' => 'array',
    ];
	//留言数据
	public function centerlist($page=1){
		$userId = cmf_get_current_user_id();
		$user      = Db::name('user')->where('id', $userId)->find();
		//根据栏目Path匹配留言数据
		$data = [];
		$looks      = Db::name('user_look')->where(['user_id'=> $userId])->column('look_id');
		if($user['allow_cate']){
			$ids = explode(',',$user['allow_cate']);
			$cate = Db::name('portal_category')->where(['id'=>['in',$ids]])->field('path')->select();
			$where = '';
			foreach($cate as $catewhere){
				$where .= " or url like '%".$catewhere['path']."%'";
			}
			$where = substr($where,4);
			$data = Db::name('user_info')->where($where)->where('id','not in',$looks)->order('inputtime desc')->paginate(10,false,['query' => $data,'page'=>$page]);;
		}
		return $data;
	}
	//已查看数据
	public function looklist($page=1){
		$userId = cmf_get_current_user_id();
		$user      = Db::name('user')->where('id', $userId)->find();
		//根据栏目Path匹配留言数据
		$data = [];
		$looks      = Db::name('user_look')->where(['user_id'=> $userId])->column('look_id');
		if($user['allow_cate']){
			$ids = explode(',',$user['allow_cate']);
			$cate = Db::name('portal_category')->where(['id'=>['in',$ids]])->field('path')->select();
			$where = '';
			foreach($cate as $catewhere){
				$where .= " or url like '%".$catewhere['path']."%'";
			}
			$where = substr($where,4);
			$data = Db::name('user_info')->where($where)->where('id','in',$looks)->order('inputtime desc')->paginate(10,false,['query' => $data,'page'=>$page]);;
		}
		return $data;
	}
	
    public function doMobile($user,$user_type=2)
    {
        $result = $this->where(['mobile'=>$user['mobile'],'user_type'=>$user_type])->find();


        if (!empty($result)) {
            $comparePasswordResult = cmf_compare_password($user['user_pass'], $result['user_pass']);
            $hookParam             = [
                'user'                    => $user,
                'compare_password_result' => $comparePasswordResult
            ];
            hook_one("user_login_start", $hookParam);
            if ($comparePasswordResult) {
                //拉黑判断。
                if ($result['user_status'] == 0) {
                    return 3;
                }
                session('user', $result->toArray());
                $data = [
                    'last_login_time' => time(),
                    'last_login_ip'   => get_client_ip(0, true),
                ];
                $this->where('id', $result["id"])->update($data);
                $token = cmf_generate_user_token($result["id"], 'web');
                if (!empty($token)) {
                    session('token', $token);
                }
                return 0;
            }
            return 1;
        }
        $hookParam = [
            'user'                    => $user,
            'compare_password_result' => false
        ];
        hook_one("user_login_start", $hookParam);
        return 2;
    }

    public function doName($user,$user_type=3)
    {
		$result = $this->where(['user_login'=>$user['user_login'],'user_type'=>$user_type])->find();
        if (!empty($result)) {
            $comparePasswordResult = cmf_compare_password($user['user_pass'], $result['user_pass']);
            $hookParam             = [
                'user'                    => $user,
                'compare_password_result' => $comparePasswordResult
            ];
            hook_one("user_login_start", $hookParam);
            if ($comparePasswordResult) {
                //拉黑判断。
                if ($result['user_status'] == 0) {
                    return 3;
                }
                session('user', $result->toArray());
                $data = [
                    'last_login_time' => time(),
                    'last_login_ip'   => get_client_ip(0, true),
                ];
                $result->where('id', $result["id"])->update($data);
                $token = cmf_generate_user_token($result["id"], 'web');
                if (!empty($token)) {
                    session('token', $token);
                }
                return 0;
            }
            return 1;
        }
        $hookParam = [
            'user'                    => $user,
            'compare_password_result' => false
        ];
        hook_one("user_login_start", $hookParam);
        return 2;
    }

    public function doEmail($user)
    {
        $result = $this->where('user_email', $user['user_email'])->find();

        if (!empty($result)) {
            $comparePasswordResult = cmf_compare_password($user['user_pass'], $result['user_pass']);
            $hookParam             = [
                'user'                    => $user,
                'compare_password_result' => $comparePasswordResult
            ];
            hook_one("user_login_start", $hookParam);
            if ($comparePasswordResult) {

                //拉黑判断。
                if ($result['user_status'] == 0) {
                    return 3;
                }
                session('user', $result->toArray());
                $data = [
                    'last_login_time' => time(),
                    'last_login_ip'   => get_client_ip(0, true),
                ];
                $this->where('id', $result["id"])->update($data);
                $token = cmf_generate_user_token($result["id"], 'web');
                if (!empty($token)) {
                    session('token', $token);
                }
                return 0;
            }
            return 1;
        }
        $hookParam = [
            'user'                    => $user,
            'compare_password_result' => false
        ];
        hook_one("user_login_start", $hookParam);
        return 2;
    }

    public function register($user, $type)
    {
        switch ($type) {
            case 1:
                $result = Db::name("user")->where('user_login', $user['user_login'])->find();
                break;
            case 2:
                $result = Db::name("user")->where('mobile', $user['mobile'])->find();
                break;
            case 3:
                $result = Db::name("user")->where('user_email', $user['user_email'])->find();
                break;
            default:
                $result = 0;
        }

        $userStatus = 1;

        if (cmf_is_open_registration()) {
            $userStatus = 2;
        }
        if (empty($result)) {
            $data   = [
                'user_login'      => empty($user['user_login']) ? '' : $user['user_login'],
                'user_email'      => empty($user['user_email']) ? '' : $user['user_email'],
                'mobile'          => empty($user['mobile']) ? '' : $user['mobile'],
                'user_nickname'   => '',
                'user_pass'       => cmf_password($user['user_pass']),
                'last_login_ip'   => get_client_ip(0, true),
                'create_time'     => time(),
                'last_login_time' => time(),
                'user_status'     => $userStatus,
                "user_type"       => $user['user_type'],//会员
				//会员其他资料
				'category'     => $user['category'],
				'invested'     => $user['invested'],
				'address'     => $user['address'],
            ];
            $userId = Db::name("user")->insertGetId($data);
            $data   = Db::name("user")->where('id', $userId)->find();
            cmf_update_current_user($data);
            $token = cmf_generate_user_token($userId, 'web');
            if (!empty($token)) {
                session('token', $token);
            }
            return 0;
        }
        return 1;
    }
	//企业注册
	public function comregister($user, $type)
    {
        switch ($type) {
            case 1:
                $result = Db::name("user")->where('user_login', $user['user_login'])->find();
                break;
            case 2:
                $result = Db::name("user")->where('mobile', $user['mobile'])->find();
                break;
            case 3:
                $result = Db::name("user")->where('user_email', $user['user_email'])->find();
                break;
            default:
                $result = 0;
        }

        $userStatus = 1;

        if (cmf_is_open_registration()) {
            $userStatus = 2;
        }
        if (empty($result)) {
            $data   = [
                'user_login'      => empty($user['user_login']) ? '' : $user['user_login'],
                'user_email'      => empty($user['user_email']) ? '' : $user['user_email'],
                'mobile'          => empty($user['mobile']) ? '' : $user['mobile'],
                'user_nickname'   => '',
                'user_pass'       => cmf_password($user['user_pass']),
                'last_login_ip'   => get_client_ip(0, true),
                'create_time'     => time(),
                'last_login_time' => time(),
                'user_status'     => $userStatus,
                "user_type"       => $user['user_type'],//会员
				//会员其他资料
				'brand_name'     => $user['brand_name'],
				'company_name'     => $user['company_name'],
				'combiner'     => $user['combiner'],
				'industry_id'     => $user['industry_id'],
				'industry_child_id'     => $user['industry_child_id']
            ];
            $userId = Db::name("user")->insertGetId($data);
            $data   = Db::name("user")->where('id', $userId)->find();
            cmf_update_current_user($data);
            $token = cmf_generate_user_token($userId, 'web');
            if (!empty($token)) {
                session('token', $token);
            }
            return 0;
        }
        return 1;
    }

    /**
     * 通过邮箱重置密码
     * @param $email
     * @param $password
     * @return int
     */
    public function emailPasswordReset($email, $password)
    {
        $result = $this->where('user_email', $email)->find();
        if (!empty($result)) {
            $data = [
                'user_pass' => cmf_password($password),
            ];
            $this->where('user_email', $email)->update($data);
            return 0;
        }
        return 1;
    }

    /**
     * 通过手机重置密码
     * @param $mobile
     * @param $password
     * @return int
     */
    public function mobilePasswordReset($mobile, $password)
    {
        $result = Db::name("user")->where('mobile', $mobile)->find();
        if (!empty($result)) {
            $data = [
                'user_pass' => cmf_password($password),
            ];
            Db::name("user")->where('mobile', $mobile)->update($data);
            return 0;
        }
        return 1;
    }

    public function editData($user)
    {
        $userId = cmf_get_current_user_id();

        if (isset($user['birthday'])) {
            $user['birthday'] = strtotime($user['birthday']);
        }

        $field = 'user_nickname,sex,birthday,user_url,signature,more';

        if ($this->allowField($field)->save($user, ['id' => $userId])) {
            $userInfo = $this->where('id', $userId)->find();
            cmf_update_current_user($userInfo->toArray());
            return 1;
        }
        return 0;
    }

    /**
     * 用户密码修改
     * @param $user
     * @return int
     */
    public function editPassword($user)
    {
        $userId    = cmf_get_current_user_id();
        $userQuery = Db::name("user");
        if ($user['password'] != $user['repassword']) {
            return 1;
        }
        $pass = $userQuery->where('id', $userId)->find();
        if (!cmf_compare_password($user['old_password'], $pass['user_pass'])) {
            return 2;
        }
        $data['user_pass'] = cmf_password($user['password']);
        Db::name("user")->where('id', $userId)->update($data);
        return 0;
    }

    public function comments()
    {
        $userId               = cmf_get_current_user_id();
        $userQuery            = Db::name("Comment");
        $where['user_id']     = $userId;
        $where['delete_time'] = 0;
        $favorites            = $userQuery->where($where)->order('id desc')->paginate(10);
        $data['page']         = $favorites->render();
        $data['lists']        = $favorites->items();
        return $data;
    }

    public function deleteComment($id)
    {
        $userId              = cmf_get_current_user_id();
        $userQuery           = Db::name("Comment");
        $where['id']         = $id;
        $where['user_id']    = $userId;
        $data['delete_time'] = time();
        $userQuery->where($where)->update($data);
        return $data;
    }

    /**
     * 绑定用户手机号
     */
    public function bindingMobile($user)
    {
        $userId          = cmf_get_current_user_id();
        $data ['mobile'] = $user['username'];
        Db::name("user")->where('id', $userId)->update($data);
        $userInfo = Db::name("user")->where('id', $userId)->find();
        cmf_update_current_user($userInfo);
        return 0;
    }

    /**
     * 绑定用户邮箱
     */
    public function bindingEmail($user)
    {
        $userId              = cmf_get_current_user_id();
        $data ['user_email'] = $user['username'];
        Db::name("user")->where('id', $userId)->update($data);
        $userInfo = Db::name("user")->where('id', $userId)->find();
        cmf_update_current_user($userInfo);
        return 0;
    }
}
