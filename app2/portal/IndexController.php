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

class IndexController extends HomeBaseController
{
    public function index()
    {
        //左侧二级导航
        $cate = db("portal_category")->where("parent_id = 0")->order('list_order asc')->limit(15)->select();
        $cates = db("portal_category")->where("parent_id = 0")->order('list_order asc')->limit(12)->select();
        //品牌上榜
        $lick1 = db('portal_xm')->where("typeid",'in','2,1,3,4,5,6,7,8,9,10,20,339,312,313,350,396,420')->order('pubdate asc')->limit(16,5)->select();
        $lick2 = db('portal_xm')->where("typeid",'in','2,1,3,4,5,6,7,8,9,10,20,339,312,313,350,396,420')->order('pubdate desc')->limit(4)->select();
        $lick3 = db('portal_xm')->where("typeid",'in','2,1,3,4,5,6,7,8,9,10,20,339,312,313,350,396,420')->order('sortrank asc')->limit(11,16)->select();
        $lick4 = db('portal_post')->where('parent_id=399')->order('published_time asc')->limit(8)->select();
        $lick5 = db('portal_xm')->where("typeid",'in','2,1,3,4,5,6,7,8,9,10,20,339,312,313,350,396,420')->order('aid asc')->limit(12)->select();
        $lick6 = db('portal_xm')->where("typeid",'in','2,1,3,4,5,6,7,8,9,10,20,339,312,313,350,396,420')->order('aid desc')->limit(100,16)->select();
        //品牌推荐
        $lick7 = db('portal_xm')->where("typeid",'in','2,1,3,4,5,6,7,8,9,10,20,339,312,313,350,396,420')->order('sortrank desc')->limit(100,8)->select();
        //新品上线
        $lick8 = db('portal_xm')->where("typeid",'in','2,1,3,4,5,6,7,8,9,10,20,339,312,313,350,396,420')->order('sortrank asc')->limit(100,10)->select();
        //热门投资项目
        $lick9 = db('portal_category')->where("id",'in','2,1,3')->select();
        //创业故事
        $cate1 = db('portal_category')->where("id",'in','11,32')->select();
        foreach($cate1 as $key=>$val)
        {
        //创业之道
             $val['data'] = db('portal_post')->where("parent_id",'in',$val['id'])->order('published_time desc')->limit(8)->select();
            //热门品牌
            $val['img'] =  db('portal_post')->where("parent_id",'in',$val['id'])->order('published_time desc')->limit(1)->select();
            $data1[] = $val;
        }
        $cate2 = db('portal_category')->where("id",'in','20,38')->order('id desc')->select();
        foreach($cate2 as $key=>$val)
        {
            //创业之道
            $val['data'] = db('portal_post')->where("parent_id",'in',$val['id'])->order('published_time desc')->limit(1,3)->select();
            $val['img'] = db('portal_post')->where("parent_id",'in',$val['id'])->order('published_time desc')->limit(0,1)->select();
            $data2[] = $val;
        }
        $lick13 = db('portal_post')->where('parent_id=392')->order('sortrank desc')->limit(0,1)->select();
        $lick14 = db('portal_post')->where('parent_id=392')->order('sortrank desc')->limit(1,12)->select();

        $cate3 = db('portal_category')->where("id",'in','401,403,404')->order('id desc')->select();
        foreach($cate3 as $key=>$val)
        {

            $val['img'] = db('portal_post')->where("parent_id",'in',$val['id'])->order('sortrank desc')->limit(0,1)->select();
            $val['data'] = db('portal_post')->where("parent_id",'in',$val['id'])->order('sortrank desc')->limit(1,7)->select();
            $data3[] = $val;
        }
        $lick15 = db("portal_post")->where("parent_id = 399")->order("sortrank asc")->limit(10)->select();
        foreach($cates as $key=>$val)
        {
            $val['pp1'] = db('portal_xm')->where("typeid = ".$val['id'])->order('sortrank desc')->limit(0,10)->select();
            $val['pp2'] = db('portal_xm')->where("typeid = ".$val['id'])->order('sortrank desc')->limit(10,10)->select();
            $val['pp3'] = db('portal_xm')->where("typeid = ".$val['id'])->order('sortrank desc')->limit(20,10)->select();
            $val['pp4'] = db('portal_xm')->where("typeid = ".$val['id'])->order('sortrank desc')->limit(30,10)->select();
            $val['pp5'] = db('portal_xm')->where("typeid = ".$val['id'])->order('sortrank desc')->limit(40,10)->select();
            $val['pp6'] = db('portal_xm')->where("typeid = ".$val['id'])->order('sortrank desc')->limit(50,10)->select();
            $cates1[] = $val;
        }
        //投资推荐
        $tz1 = db('portal_xm')->where(['invested' => ['like','1-5万'], 'typeid' => ['in','2,69,,74'],])->limit('0,14')->select();
        $tz2 = db('portal_xm')->where(['invested' => ['like','5-10万'], 'typeid' => ['in','2,69,,74'],])->limit('0,14')->select();
        $tz3 = db('portal_xm')->where(['invested' => ['like','10-20万'], 'typeid' => ['in','2,69,,74'],])->limit('0,14')->select();
        $tz4 = db('portal_xm')->where(['invested' => ['like','20-50万'], 'typeid' => ['in','2,69,,74'],])->limit('0,14')->select();
        $tz5 = db('portal_xm')->where(['invested' => ['like','50-100万']])->limit('0,14')->select();
        $tz6 = db('portal_xm')->where(['invested' => ['like','100万以上']])->limit('0,14')->select();
        //行业分类
        $cate4 = db("portal_category")->where("id= 2")->select();
        foreach($cate4 as $key=>$val)
        {
            $val['cate'] = db("portal_category")->where("parent_id",'in',$val['id'])->limit(300)->select();
            $class1[] = $val;
        }
        $cate5 = db("portal_category")->where("id","in","1,4,5,7,10")->select();
        foreach($cate5 as $key=>$val)
        {
            $val['cate'] = db("portal_category")->where("parent_id",'in',$val['id'])->limit(100)->select();
            $class2[] = $val;
        }
        $cate6 = db("portal_category")->where("id","in","3,6,8,9,63,312,313,396,420")->select();
        foreach($cate6 as $key=>$val)
        {
            $val['cate'] = db("portal_category")->where("parent_id",'in',$val['id'])->limit(100)->select();
            $class3[] = $val;
        }
        $youlian = db("flink")->where("typeid = 9999")->order("dtime desc")->limit(50)->select();
        //品牌名称


        $this->daohang();
        $this->assign('cate',$cate);
        $this->assign('lick1',$lick1);
        $this->assign('lick2',$lick2);
        $this->assign('lick3',$lick3);
        $this->assign('lick4',$lick4);
        $this->assign('lick5',$lick5);
        $this->assign('lick6',$lick6);
        $this->assign('lick7',$lick7);
        $this->assign('lick8',$lick8);
        $this->assign('lick9',$lick9);
        $this->assign('lick13',$lick13);
        $this->assign('lick14',$lick14);
        $this->assign('lick15',$lick15);
        $this->assign('tz1',$tz1);
        $this->assign('tz2',$tz2);
        $this->assign('tz3',$tz3);
        $this->assign('tz4',$tz4);
        $this->assign('tz5',$tz5);
        $this->assign('tz6',$tz6);
        $this->assign('cates',$cates1);
        $this->assign('data1',$data1);
        $this->assign('data2',$data2);
        $this->assign('data3',$data3);
        $this->assign('class1',$class1);
        $this->assign('class2',$class2);
        $this->assign('class3',$class3);
        $this->assign('youlian',$youlian);
        return $this->fetch(':index');
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
