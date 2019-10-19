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
use app\portal\model\PortalXmModel;

class TopController extends HomeBaseController
{
    public function index()
    {
        $cate = db("portal_category")->where("parent_id = 391")->order('list_order asc')->limit(15)->select();
        $lick1 = db("portal_xm")->where('typeid = 2')->order('pubdate asc')->limit(16)->select();
        $lick2 = db("portal_xm")->where('typeid = 2')->order('click asc')->limit(10)->select();
        $lick3 = db("portal_xm")->where('typeid', 'in', '2,312,8')->order('click asc')->limit(10)->select();
        $lick4 = db("portal_xm")->where('typeid', 'in', '6,362,265,57')->order('click asc')->limit(10)->select();
        $lick5 = db("portal_xm")->where('typeid', 'in', '2,312,8')->order('pubdate asc')->limit(10)->select();
        $lick6 = db("portal_xm")->where('typeid', 'in', '2,312,8')->order('pubdate desc')->limit(10)->select();
        $arr = '2,312,8,10,5,4,7,313,9,1,3,339,6,396,420';
        $cates = db("portal_category")->where('id', 'in', $arr)->order('list_order asc')->select();
        foreach($cates as $key=>$val)
        {
            $val['data'] = db("portal_xm")->where('typeid = '.$val['id'])->order('click asc')->limit(10)->select();
            $data[] = $val;
        }
        $catess = db("portal_category")->where('id', 'in', $arr)->order('list_order asc')->limit(7)->select();
        foreach($catess as $key=>$v)
        {
            $v['data'] = db("portal_xm")->where('typeid = '.$v['id'])->order('pubdate asc')->limit(10)->select();
            $datas[] = $v;
        }
        $cate1 =  db("portal_category")->where("id = 401")->find();
        $cate2 =  db("portal_category")->where("id = 402")->find();
        $cate3 =  db("portal_category")->where("id = 403")->find();
        $cate4 =  db("portal_category")->where("id = 404")->find();
        $cate5 =  db("portal_category")->where("id = 405")->find();
        $cate6 =  db("portal_category")->where("id = 406")->find();
        $cate7 =  db("portal_category")->where("id = 408")->find();
        $cate8 =  db("portal_category")->where("id = 409")->find();
        $cat = db("portal_category")->where('id', 'in', '401,402')->select();
        foreach($cat as $ke=>$va)
        {
             $va['data'] = db('portal_post')->where("parent_id = ".$va['id'])->order("published_time desc")->limit(5)->select();
             $data1[] = $va;
        }
        $cat = db("portal_category")->where('id', 'in', '403,404')->select();
        foreach($cat as $ke=>$va)
        {
            $va['data'] = db('portal_post')->where("parent_id = ".$va['id'])->order("published_time desc")->limit(5)->select();
            $data2[] = $va;
        }
        $cat = db("portal_category")->where('id', 'in', '405,406')->select();
        foreach($cat as $ke=>$va)
        {
            $va['data'] = db('portal_post')->where("parent_id = ".$va['id'])->order("published_time desc")->limit(5)->select();
            $data3[] = $va;
        }
        $cat = db("portal_category")->where('id', 'in', '408,409')->select();
        foreach($cat as $ke=>$va)
        {
            $va['data'] = db('portal_post')->where("parent_id = ".$va['id'])->order("published_time desc")->limit(5)->select();
            $data4[] = $va;
        }
        $cat1 = db("portal_category")->where('id', 'in', '2,1,3,339,4,5,6,7,8,9,10,312')->select();
        foreach($cat1 as $key=>$val)
        {
            $val['son'] = db("portal_category")->where('parent_id', 'in', $val['id'])->limit(12)->select();
            $val['data'] = db("portal_xm")->where('typeid = '.$val['id'])->order('pubdate asc')->limit(10)->select();
            $data5[] = $val;
        }
        $this->daohang();
        $this->assign('cate',$cate);
        $this->assign('cate1',$cate1);
        $this->assign('cate2',$cate2);
        $this->assign('cate3',$cate3);
        $this->assign('cate4',$cate4);
        $this->assign('cate5',$cate5);
        $this->assign('cate6',$cate6);
        $this->assign('cate7',$cate7);
        $this->assign('cate8',$cate8);
        $this->assign('cat1',$cat1);
        $this->assign('data',$data);
        $this->assign('datas',$datas);
        $this->assign('catess',$catess);
        $this->assign('lick1',$lick1);
        $this->assign('lick2',$lick2);
        $this->assign('lick3',$lick3);
        $this->assign('lick4',$lick4);
        $this->assign('lick5',$lick5);
        $this->assign('lick6',$lick6);
        $this->assign('data1',$data1);
        $this->assign('data2',$data2);
        $this->assign('data3',$data3);
        $this->assign('data4',$data4);
        $this->assign('data5',$data5);
        return $this->fetch(':top');
    }

    public function list_top()
    {
        $id = $this->request->param('id', 0, 'intval');
        $id = 69;
        $name = db('portal_category')->where("id = $id")->value('name');
        $ids = db('portal_category')->where("id = $id")->value('parent_id');
        $name1 = db('portal_category')->where("id = $ids")->value('name');
        $cate = db("portal_category")->where("parent_id = ".$ids)->order('list_order desc')->select();
        $lick1 = db("portal_xm")->where('typeid', 'in', $id)->order('click desc')->limit(0,1)->select();
        $lick2 = db("portal_xm")->where('typeid', 'in', $id)->order('click desc')->limit(1,9)->select();
        $lick3 = db("portal_xm")->where('typeid', 'in', $id)->order('weight desc')->limit(0,1)->select();
        $lick4 = db("portal_xm")->where('typeid', 'in', $id)->order('weight desc')->limit(1,2)->select();
        $lick5 = db("portal_xm")->where('typeid', 'in', $id)->order('weight desc')->limit(3,7)->select();
        $cate1 = db("portal_category")->where('id', 'in', '2,1,3,339,4,5,6,7,8,9,10,312')->order('list_order asc')->limit(10)->select();
        foreach($cate1 as $key=>$val)
        {
            $val['data'] = db("portal_xm")->where('typeid = '.$val['id'])->order('pubdate asc')->limit(10)->select();
            $data[] = $val;
        }
        $data1 = db("portal_post")->where("parent_id = 399")->limit(1,5)->select();
        $data2 = db("portal_post")->where("parent_id = 399")->limit(10,5)->select();
        $data3 = db("portal_post")->where("parent_id = 399")->limit(15,5)->select();
        $cat2 = db("portal_category")->where('id', 'in', '2,1,3,339,4,5,6,7,8,9,10,312')->select();
        foreach($cat2 as $key=>$val)
        {
            $val['son'] = db("portal_category")->where('parent_id', 'in', $val['id'])->limit(12)->select();
            $val['data'] = db("portal_xm")->where('typeid = '.$val['id'])->order('pubdate asc')->limit(10)->select();
            $data5[] = $val;
        }
        $this->daohang();
        $this->assign('id',$id);
        $this->assign('cate',$cate);
        $this->assign('name',$name);
        $this->assign('name1',$name1);
        $this->assign('lick1',$lick1);
        $this->assign('lick2',$lick2);
        $this->assign('lick3',$lick3);
        $this->assign('lick4',$lick4);
        $this->assign('lick5',$lick5);
        $this->assign('data',$data);
        $this->assign('data1',$data1);
        $this->assign('data2',$data2);
        $this->assign('data3',$data3);
        $this->assign('cat2',$cat2);
        $this->assign('data5',$data5);
        return $this->fetch(":list_top");
    }

    public function index_top()
    {
        $id =  $this->request->param('id', 0, 'intval');
        $id = 2;
        $cate = db("portal_category")->where("parent_id = ".$id)->order('list_order asc')->limit(15)->select();
        $name = db("portal_category")->where("id = ".$id)->value('name');
        $lick1 = db("portal_xm")->where('typeid = '.$id)->order('click asc')->limit(10)->select();
        $lick2 = db("portal_xm")->where('typeid = '.$id)->order('click asc')->limit(0,8)->select();
        $lick3 = db("portal_xm")->where('typeid = '.$id)->order('click asc')->limit(8,8)->select();
        $lick4 = db("portal_xm")->where('typeid = '.$id)->order('click asc')->limit(16,8)->select();
        $lick5 = db("portal_xm")->where('typeid = '.$id)->order('click asc')->limit(24,8)->select();
        $cates =  db("portal_category")->where('parent_id = '.$id)->order('list_order asc')->limit(12)->select();
        foreach($cates as $key=>$val)
        {
            $val['data'] = db("portal_xm")->where('typeid = '.$val['id'])->order('click asc')->limit(10)->select();
            $data[] = $val;
        }
        $catess =  db("portal_category")->where('parent_id = '.$id)->order('list_order asc')->limit(10)->select();
        foreach($cates as $key=>$val)
        {
            $val['data'] = db("portal_xm")->where('typeid = '.$val['id'])->order('click asc')->limit(8)->select();
            $data1[] = $val;
        }
        $cat1 = db("portal_category")->where('id', 'in', '401,402,403,404')->select();
        foreach($cat1 as $key=>$val)
        {
            $val['data'] = db("portal_post")->where('parent_id = '.$val['id'])->limit(1)->select();
            $val['data1'] = db("portal_post")->where('parent_id = '.$val['id'])->limit(1,10)->select();
            $data2[] = $val;
        }
        $cat2 = db("portal_category")->where('id', 'in', '2,1,3,339,4,5,6,7,8,9,10,312')->select();
        foreach($cat2 as $key=>$val)
        {
            $val['son'] = db("portal_category")->where('parent_id', 'in', $val['id'])->limit(12)->select();
            $val['data'] = db("portal_xm")->where('typeid = '.$val['id'])->order('pubdate asc')->limit(10)->select();
            $data5[] = $val;
        }
        $this->daohang();
        $this->assign('cate',$cate);
        $this->assign('name',$name);
        $this->assign('lick1',$lick1);
        $this->assign('lick2',$lick2);
        $this->assign('lick3',$lick3);
        $this->assign('lick4',$lick4);
        $this->assign('lick5',$lick5);
        $this->assign('data',$data);
        $this->assign('data1',$data1);
        $this->assign('data2',$data2);
        $this->assign('cat2',$cat2);
        $this->assign('data5',$data5);

        return $this->fetch(":index_top");
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