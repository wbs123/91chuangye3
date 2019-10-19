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
namespace app\portal\controller;

use cmf\controller\HomeBaseController;
use think\Db;
use think\Request;
use app\admin\model\ThemeModel;
use app\portal\model\PortalNewsModel;

class NewsController extends HomeBaseController
{
    public function index()
    {
        $data = db('portal_post')->where('post_status = 1')->order('sortrank asc')->paginate(10);
        $data1 = db('portal_post')->where('parent_id = 11')->order('sortrank asc')->limit(10)->select();
        $data2 = db('portal_post')->where('parent_id = 399')->order('sortrank asc')->limit(10)->select();
        $where = [11,399];
        $data3 = db('portal_xm')->where("typeid","in","2,1,3,4,5,6,7,8,9,10,20,339,312,313,350,396,420")->order('sortrank asc')->limit(10)->select();
        $cate = db("portal_category")->where('id', 'in', '2,1,3,339,4,5,6,7,8,9,10,312')->select();
        foreach($cate as $key=>$val)
        {
            $val['data'] = db("portal_xm")->where("typeid = ".$val['id'])->order("click asc")->limit(35)->select();
            $data4[] = $val;
        }
        $this->daohang();
        $this->assign('data',$data);
        $this->assign('data1',$data1);
        $this->assign('data2',$data2);
        $this->assign('data3',$data3);
        $this->assign('data4',$data4);
        return $this->fetch(':news');
    }

    public function article_news()
    {
        $id = $this->request->param('id', 0, 'intval');
        $data = db('portal_post')->where("id = $id")->find();
        $array_reverse = $this->position($data['parent_id']);
        $lick1 = db('portal_post')->where("parent_id = 399")->order('published_time asc')->limit(10)->select();
        $lick2 = db('portal_post')->order('published_time asc')->limit(10)->select();
        $lick3 = db('portal_xm')->where("typeid = 9")->order('click asc')->limit(10)->select();
        $cate = db("portal_category")->where('id', 'in', '2,1,3,339,4,5,6,7,8,9,10,312')->select();
        foreach($cate as $key=>$val)
        {
            $val['data'] = db("portal_xm")->where("typeid = ".$val['id'])->order("click asc")->limit(35)->select();
            $data4[] = $val;
        }
        //上一页
        $lick4 = db('portal_post')->where("id = ($id-1)")->find();
        //下一页
        $lick5 = db('portal_post')->where("id = ($id+1)")->find();
        $this->daohang();
        $this->assign('lick1',$lick1);
        $this->assign('lick2',$lick2);
        $this->assign('lick3',$lick3);
        $this->assign('data',$data);
        $this->assign('lick4',$lick4);
        $this->assign('lick5',$lick5);
        $this->assign('data4',$data4);
        $this->assign('array_reverse',$array_reverse);
       return $this->fetch(':article_news');
    }

    public function position($cid){//传递当前栏目id

        static $pos=array();//创建接受面包屑导航的数组

        if(empty($pos)){//哦，这个就比较重要了，如果需要把当前栏目也放到面包屑导航中的话就要加上

            $cates=db('portal_category')->field('id,name,parent_id')->find($cid);

            $pos[]=$cates;

        }

        $data=db('portal_category')->field('id,name,parent_id')->select();//所有栏目信息

        $cates=db('portal_category')->field('id,name,parent_id')->find($cid);//当前栏目信息

        foreach ($data as $k => $v) {

            if($cates['parent_id']==$v['id']){

                $pos[]=$v;

                $this->position($v['id']);

            }

        }

        return array_reverse($pos);

    }

    public function daohang()
    {
        $cates1 = db("portal_category")->where("id = 2")->select();
        foreach($cates1 as $key=>$val)
        {
            $val['data'] = db("portal_category")->where("parent_id =".$val['id'])->limit(40)->select();
            $c1[] = $val;
        }
        $cates2 = db("portal_category")->where("id ","in","312,8,10,5,396")->select();
        foreach($cates2 as $key=>$val)
        {
            $val['data'] = db("portal_category")->where("parent_id =".$val['id'])->limit(6)->select();
            $c2[] = $val;
        }
        $cates3 = db("portal_category")->where("id","in", " 4,7,313,9,420")->select();
        foreach($cates3 as $key=>$val)
        {
            $val['data'] = db("portal_category")->where("parent_id =".$val['id'])->limit(6)->select();
            $c3[] = $val;
        }
        $cates4 = db("portal_category")->where("id","in"," 1,3,339,6")->select();
        foreach($cates4 as $key=>$val)
        {
            $val['data'] = db("portal_category")->where("parent_id =".$val['id'])->limit(6)->select();
            $c4[] = $val;
        }

        $this->assign("cates1",$c1);
        $this->assign("cates2",$c2);
        $this->assign("cates3",$c3);
        $this->assign("cates4",$c4);
    }


}



?>