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

namespace app\admin\controller;

use cmf\controller\AdminBaseController;
use think\Db;

/**
 * Class AdminIndexController
 * @package app\user\controller
 *
 * @adminMenuRoot(
 *     'name'   =>'用户管理',
 *     'action' =>'default',
 *     'parent' =>'',
 *     'display'=> true,
 *     'order'  => 10,
 *     'icon'   =>'group',
 *     'remark' =>'用户管理'
 * )
 *
 * @adminMenuRoot(
 *     'name'   =>'用户组',
 *     'action' =>'default1',
 *     'parent' =>'user/AdminIndex/default',
 *     'display'=> true,
 *     'order'  => 10000,
 *     'icon'   =>'',
 *     'remark' =>'用户组'
 * )
 */
class AdminIndexController extends AdminBaseController
{

    /**
     * 后台本站用户列表
     * @adminMenu(
     *     'name'   => '本站用户',
     *     'parent' => 'default1',
     *     'display'=> true,
     *     'hasView'=> true,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '本站用户',
     *     'param'  => ''
     * )
     */
   public function index()
    {
        $content = hook_one('user_admin_index_view');

        if (!empty($content)) {
            return $content;
        }
        $where   = [];
        $request = input('request.');
        #搜索条件URL开始
        $page = !isset($request['page']) ? 1 : $request['page'];
        $url = '';
        if (!empty($request['uid'])) {
            $where['id'] = intval($request['uid']);
            $url .= 'uid='.$request['uid'];
        }
        $keywordComplex = [];
        if (!empty($request['keyword'])) {
            $keyword = $request['keyword'];
            $url .= 'keyword='.$keyword;
            $keywordComplex['user_login|user_nickname|user_email|mobile']    = ['like', "%$keyword%"];
        }
        $usersQuery = Db::name('user');

        $list = $usersQuery->whereOr($keywordComplex)->where($where)->order("create_time DESC")->paginate(10,false,['page'=>$page]);
        $PageHtml = FunCommon::page($page,$list->lastPage(),0,'','&'.$url,'page',$list->total());
        $this->assign('list', $list);
        $this->assign('PageHtml', $PageHtml);

        // 渲染模板输出
        return $this->fetch();
    }

    /**
     * 本站用户拉黑
     * @adminMenu(
     *     'name'   => '本站用户拉黑',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '本站用户拉黑',
     *     'param'  => ''
     * )
     */
    public function ban()
    {
        $id = input('param.id', 0, 'intval');
        if ($id) {
            $result = Db::name("user")->where(["id" => $id, "user_type" => ['in',[2,3]]])->setField('user_status', 0);
            if ($result) {
                $this->success("会员拉黑成功！", "adminIndex/index");
            } else {
                $this->error('会员拉黑失败,会员不存在,或者是管理员！');
            }
        } else {
            $this->error('数据传入失败！');
        }
    }

    /**
     * 本站用户启用
     * @adminMenu(
     *     'name'   => '本站用户启用',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '本站用户启用',
     *     'param'  => ''
     * )
     */
    public function cancelBan()
    {
        $id = input('param.id', 0, 'intval');
        if ($id) {
            Db::name("user")->where(["id" => $id, "user_type" => ['in',[2,3]]])->setField('user_status', 1);
            $this->success("会员启用成功！", '');
        } else {
            $this->error('数据传入失败！');
        }
    }
	
	public function update()
    {
        $param = $this->request->param();
        if (!isset($param['post'])) {
			$data = Db::name("user")->where(["id" =>$param['id']])->field('id,allow_cate,all_nums,mobile')->find();
            $this->assign('post',$data);
            return $this->fetch();
        }else{
			$nums = Db::name("user_look")->where(["user_id" =>$param['post']['id']])->count();
			if($param['post']['all_nums'] <= $nums){
				$this->error('查看条数要大于已查看条数:'.$nums);
			}else{
				$result = Db::name("user")->where(["id" =>$param['post']['id']])->update($param['post']);
				if ($result) {
					$this->success("会员授权成功！");
				} else {
					$this->error('会员授权失败！');
				}
			}
			
		}
    }
}
