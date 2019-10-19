<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2018 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 老猫 <thinkcmf@126.com>
// +----------------------------------------------------------------------
namespace app\admin\controller;

use cmf\controller\AdminBaseController;
use app\portal\model\PortalPostModel;
use app\portal\service\PostService;
use app\portal\model\PortalCategoryModel;
use think\Db;
use app\admin\model\ThemeModel;

class AdminJmController extends AdminBaseController
{
    //加盟注册信息列表
    public function index()
    {
        $url = $_SERVER["QUERY_STRING"];
        $check = strpos($url, 'list_');
        if($check){
            preg_match('/^(.*)list_(\d+).html$/',$url,$match);
            $page = $match[2];
        }else{
            $page = 1;
        }
        $user_info = db('user')->where('user_type = 3')->order('id desc')->paginate(30,false,['page'=>$page]);
        $this->assign('user_info', $user_info);
        $this->assign('page', $user_info->render());
        return $this->fetch();
    }

    public function edit()
    {
        $id = $this->request->param('id', 0, 'intval');
        $post = db('member')->where('id = '.$id)->find();
        if($post['type'] == 2){
            $caname = db('portal_category')->where('id = '.$post['typeid'])->value('name');
            $post['category'] = $caname;
            $this->assign('post', $post);
            return $this->fetch();
        }else{
            $this->assign('post',$post);
            return $this->fetch(':admin_jm/edits');
        }
    }

    public function delete()
    {
        $param = $this->request->param();
         if(isset($param['ids'])) {
            $ids = $this->request->param('ids/a');
            $result = Db::name('user')->where(['id' => ['in', $ids]])->select();
            if ($result) {
                foreach ($result as $value) {
                    Db::name('user')->where('id = '.$value['id'])->delete();
                }
                $this->success("删除成功！", '');
            }
        }else{
            $id = $this->request->param('id', 0, 'intval');
            $info = db('user')->where('id = '.$id)->delete();
            if($info){
                $this->success("删除成功！", '');
            }else{
                 $this->error("删除失败！", '');
            }
        }
        
    }

    public function index2(){
        $url = $_SERVER["QUERY_STRING"];
        $check = strpos($url, 'list_');
        if($check){
            preg_match('/^(.*)list_(\d+).html$/',$url,$match);
            $page = $match[2];
        }else{
            $page = 1;
        }
        $user_info = db('user')->where('user_type = 2')->order('id desc')->paginate(30,false,['page'=>$page]);
		foreach($user_info as $k=>$v){
			$id = Db::name('portal_category')->where(['name'=>$v['category']])->value('id');
			Db::name('user')->where(['id'=>$id])->update(['category'=>$id]);
		}
        $this->assign('user_info', $user_info);
        $this->assign('page', $user_info->render());
        return $this->fetch();
    }


    public function edit2()
    {
        $id = $this->request->param('id', 0, 'intval');
        $post = db('member')->where('id = '.$id)->find();
        $this->assign('post',$post);
        return $this->fetch(':admin_jm/edits');

    }

    public function delete2()
    {
        $param = $this->request->param();
        if(isset($param['ids'])) {
            $ids = $this->request->param('ids/a');
            $result = Db::name('user')->where(['id' => ['in', $ids]])->select();
            if ($result) {
                foreach ($result as $value) {
                    Db::name('user')->where('id = '.$value['id'])->delete();
                }
                $this->success("删除成功！", '');
            }
        }else{
            $id = $this->request->param('id', 0, 'intval');
            $info = db('user')->where('id = '.$id)->delete();
            if($info){
                $this->success("删除成功！", '');
            }else{
                $this->error("删除失败！", '');
            }
        }

    }



}
