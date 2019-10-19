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
use app\portal\model\CategoryModel;

class SinglepageController extends HomeBaseController
{

    public function _initialize()
    {
        if (\think\Request::instance()->isMobile()) {
            $this->assign('seo_url', 'http://www.91chuangye.com' . $this->request->url());
        }else{
            $this->assign('seo_url', 'http://m.91chuangye.com' . $this->request->url());
        }
    }
    public function guanyuwomen()
    {
        if (\think\Request::instance()->isMobile()) {
          mackHtml($this->fetch(':mobile/guanyuwomen'),'guanyuwomen',2);
          return $this->fetch(':mobile/guanyuwomen');
        }else {
          $this->dibu();
          $this->daohang();
          $this->zuoce();
          mackHtml($this->fetch(':guanyuwomen'),'guanyuwomen');
          return $this->fetch(":guanyuwomen");
        }
    }
    public function lianxiwomen(){
        if (\think\Request::instance()->isMobile()) {
            mackHtml($this->fetch(':mobile/lianxiwomen'),'lianxiwomen',2);
            return $this->fetch(':mobile/lianxiwomen');
        }else {
			$this->dibu();
			$this->daohang();
            $this->zuoce();
            mackHtml($this->fetch(':lianxiwomen'),'lianxiwomen');
            return $this->fetch(":lianxiwomen");
        }
    }
    public function mianzeshengming(){
        if (\think\Request::instance()->isMobile()) {
            mackHtml($this->fetch(':mobile/mianzeshengming'),'mianzeshengming',2);
            return $this->fetch(':mobile/mianzeshengming');
        }else {
			$this->dibu();
			$this->daohang();
            $this->zuoce();
            mackHtml($this->fetch(':mianzeshengming'),'mianzeshengming');
            return $this->fetch(":mianzeshengming");
        }
    }
    public function falvguwen(){
        if (\think\Request::instance()->isMobile()) {
            mackHtml($this->fetch(':mobile/falvguwen'),'falvguwen',2);
            return $this->fetch(':mobile/falvguwen');
        }else {
			$this->dibu();
			$this->daohang();
            $this->zuoce();
            mackHtml($this->fetch(':falvguwen'),'falvguwen');
            return $this->fetch(":falvguwen");
        }
    }
    public function youqinglianjie(){
        if (\think\Request::instance()->isMobile()) {
            mackHtml($this->fetch(':mobile/youqinglianjie'),'youqinglianjie',2);
            return $this->fetch(':mobile/youqinglianjie');
        }else {
			$this->dibu();
			$this->daohang();
            $this->zuoce();
            mackHtml($this->fetch(':youqinglianjie'),'youqinglianjie');
            return $this->fetch(":youqinglianjie");
        }
    }
    public function tousushanchu(){
        if (\think\Request::instance()->isMobile()) {
            mackHtml($this->fetch(':mobile/tousushanchu'),'tousushanchu',2);
            return $this->fetch(':mobile/tousushanchu');
        }else {
			$this->dibu();
			$this->daohang();
            $this->zuoce();
            mackHtml($this->fetch(':tousushanchu'),'tousushanchu');
            return $this->fetch(":tousushanchu");
        }
    }
    //服务条款
    public function explain(){
       if (\think\Request::instance()->isMobile()) {
            mackHtml($this->fetch(':mobile/explain'),'explain',2);
            return $this->fetch(':mobile/explain');
        }else{
           mackHtml($this->fetch(':explain'),'explain');
            return $this->fetch(':explain');
        }
    }
    //nav页面
    public function nav(){
		
        $arr = '2,312,8,10,5,4,7,313,9,1,3,339,6,396,420,734,742,63';
        if (\think\Request::instance()->isMobile()) {
            $CategoryModel = new CategoryModel();
			$this->assign('catedata',$CategoryModel->tree());
            mackHtml($this->fetch(':mobile/nav'),'nav',2);
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
            //品牌专区
			$this->foot_hytj();
			$this->dibu();
			$this->daohang();
            mackHtml($this->fetch(':nav'),'nav');
            return $this->fetch(":nav");
        }
    }

}