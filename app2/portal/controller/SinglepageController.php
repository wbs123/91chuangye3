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

class SinglepageController extends HomeBaseController
{

    public function _initialize()
    {
        //底部
        $dibu = db("portal_category")->where("parent_id",'in','52,53')->select();
        $this->assign('dibu',$dibu);
        //导航
        $where1['id'] = ['in','2,5,8,396'];
        $cates1 = db("portal_category")->where($where1)->where("ishidden = 1 and status =1 ")->select();
        foreach($cates1 as $key=>$val)
        {
            $val['data'] = db("portal_category")->where("parent_id =".$val['id'])->where('status =1 and ishidden = 1')->limit(40)->select();
            $c1[] = $val;
        }

        $where2['id'] = ['in','10,312,4,7,9,313'];
        $cates2 = db("portal_category")->where($where2)->where('status =1 and ishidden = 1')->select();
        foreach($cates2 as $key=>$val)
        {
            $val['data'] = db("portal_category")->where("parent_id =".$val['id'])->where('status =1 and ishidden = 1')->limit(6)->select();
            $c2[] = $val;
        }
        $where3['id'] = ['in','420,1,3,6,339,734'];
        $cates3 = db("portal_category")->where($where3)->select();
        foreach($cates3 as $key=>$val)
        {
            $val['data'] = db("portal_category")->where("parent_id =".$val['id'])->where('status =1 and ishidden = 1')->limit(6)->select();
            $c3[] = $val;
        }
        $this->assign("cates1",$c1);
        $this->assign("cates2",$c2);
        $this->assign("cates3",$c3);
      
  
        if (\think\Request::instance()->isMobile()) {
            $this->assign('seo_url', 'http://www.91chuangye.com' . $this->request->url());
        }else{
            $this->assign('seo_url', 'http://m.91chuangye.com' . $this->request->url());
        }

    }
    public function guanyuwomen()
    {
        if (\think\Request::instance()->isMobile()) {
          return $this->fetch(':mobile/guanyuwomen');
        }else {
          $this->zuoce();
          return $this->fetch(":guanyuwomen");
        }
    }
    public function lianxiwomen(){
        if (\think\Request::instance()->isMobile()) {
            return $this->fetch(':mobile/lianxiwomen');
        }else {
            $this->zuoce();
            return $this->fetch(":lianxiwomen");
        }
    }
    public function mianzeshengming(){
        if (\think\Request::instance()->isMobile()) {
            return $this->fetch(':mobile/mianzeshengming');
        }else {
            $this->zuoce();
            return $this->fetch(":mianzeshengming");
        }
    }
    public function falvguwen(){
        if (\think\Request::instance()->isMobile()) {
            return $this->fetch(':mobile/falvguwen');
        }else {
            $this->zuoce();
            return $this->fetch(":falvguwen");
        }
    }
    public function youqinglianjie(){
        if (\think\Request::instance()->isMobile()) {
            return $this->fetch(':mobile/youqinglianjie');
        }else {
            $this->zuoce();
            return $this->fetch(":youqinglianjie");
        }
    }
    public function tousushanchu(){
        if (\think\Request::instance()->isMobile()) {
            return $this->fetch(':mobile/tousushanchu');
        }else {
            $this->zuoce();
            return $this->fetch(":tousushanchu");
        }
    }
    //服务条款
    public function explain(){
       if (\think\Request::instance()->isMobile()) {
            return $this->fetch(':mobile/explain');
        }else{
            return $this->fetch(':explain');
        }
    }
    //nav页面
    public function nav(){

        $arr = '2,312,8,10,5,4,7,313,9,1,3,339,6,396,420,734,63';
        if (\think\Request::instance()->isMobile()) {
            $catess = db("portal_category")->where('id', 'in', $arr)->where('status = 1 and ishidden = 1')->field('id,name,path')->order('list_order asc')->select();
            $cated = db('portal_category')->where(['parent_id' => 2,'ishidden' => 1,'status' => 1])->field('id,path,name,mobile_thumbnail')->order('list_order asc')->select();
            $img = 'http://www.91chuangye.com/upload/';
            $this->assign('img',$img);
            $this->assign('catess',$catess);
            $this->assign('cated',$cated);
            return $this->fetch(':mobile/nav');
        }else{
            $where24['id'] = ['in',$arr];
            $categ = db("portal_category")->where($where24)->where("ishidden =1 and status =1")->order('list_order asc')->select();
            foreach ($categ as $key => $val) {
                $val['cate'] = db("portal_category")->where("parent_id", 'in', $val['id'])->where('status =1 and ishidden = 1')->field('id,name,path')->order('list_order asc')->select();
                $class1[] = $val;
            }
            $this->assign('class1',$class1);
            //排行榜
            $categ = db("portal_category")->where(['parent_id'=>391,'id'=>['notin',['428','390']]])->where("ishidden =1 and status =1")->order
            ('list_order 
            asc')
                ->select();
            foreach ($categ as $key => $val) {
                $val['cate'] = db("portal_category")->where("parent_id", 'in', $val['id'])->where('status =1 and ishidden = 1')->field('id,name,path')->order('list_order asc')->select();
                $class2[] = $val;
            }
            $this->assign('class2',$class2);
            //标题
            $website = DB('website')->where(['id' => 1])->find();
            $this->assign('website',$website);

            return $this->fetch(":nav");
        }
    }
}