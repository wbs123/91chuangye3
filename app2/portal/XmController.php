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

class XmController extends HomeBaseController
{
    public function index()
    {
        $post=$this->request->param();
        $array_reverse = "";
        if(isset($post['id']) && ($post['id']!='')){
            $array_reverse = $this->position($post['id']);
            $this->assign('array_reverse',$array_reverse);
        }
        if(isset($post['ids']) && ($post['ids']!='')){
            $array_reverse = $this->position($post['ids']);
        }
        $where=[];
        if(isset($post['q']) && ($post['q']!=''))
        {
           $where['a.title'] = [ 'like', "%".$post['q']."%"];
        }
        if(isset($post['num']) && ($post['num']!=''))
        {
            if($post['num'] == 100)
            {
                $post['invested'] = $post['num'].'万以上';
            }else{
                $post['invested'] = $post['num'].'万';
            }
            $where['a.invested'] = ['=',$post['invested']];
        }
        if(isset($post['address']) && ($post['address']!=''))
        {
            $py = $post['address'];
            $nativeplace = db('sys_enum')->where("py = '$py'")->value("disorder");
            $where['a.nativeplace'] = ['=',$nativeplace];
        }
        if(isset($post['id']) && ($post['id']!='')){
            $where['a.typeid'] = $post['id'];
            $ids = $post['id'];
            $cates =  db("portal_category")->where("parent_id = $ids")->select();
        }else{
            $cates = '';
        }
        if(isset($post['ids']) && ($post['ids']!='')){
           // $where['a.id']=['=',$post['ids']];
            $ids = $post['ids'];
            $where['a.typeid'] = $post['ids'];
            $ids = db("portal_category")->where("id = $ids")->value("parent_id");
            $cates =  db("portal_category")->where("parent_id = $ids")->select();
        }
        if(isset($post['sum']) && ($post['sum']!='')){
            $where['a.sum']=['=',$post['sum']];
        }
        $where['a.arcrank'] = ['=',1];
      //  print_r($where) ;die;
//        SELECT * FROM `#@__sys_enum` WHERE egroup='nativeplace' AND (evalue MOD 500)=0 ORDER BY disorder ASC
        $sys = db('sys_enum')->where("egroup= 'nativeplace' AND (evalue MOD 500)=0")->order('disorder asc')->select();
        $cate = db("portal_category")->where("parent_id = 0")->order('list_order asc')->limit(16)->select();
        $data = db('portal_xm a')->where($where)->paginate(15);
        $lick5 = db('portal_xm')->order('click desc')->limit(20,5)->select();
        $lick1 = db('portal_xm')->order('click desc')->limit(6)->select();
        $lick2 = db('portal_xm')->order('aid asc')->limit(10)->select();
        $lick3 = db('portal_post')->order('id asc')->limit(10)->select();
        $this->daohang();
        $this->assign('data',$data);
        $this->assign('sys',$sys);
        $this->assign('cate',$cate);
        $this->assign('cates',$cates);
        $this->assign('lick5',$lick5);
        $this->assign('lick1',$lick1);
        $this->assign('lick2',$lick2);
        $this->assign('lick3',$lick3);
        $this->assign('array_reverse',$array_reverse);
        return $this->fetch(':list');
    }

    public function article_xm()
    {
        $id = $this->request->param('id', 0, 'intval');
        $array_reverse = "";
        $data = db('portal_xm')->where("aid = $id")->find();
        $typeid = $data['typeid'];
        $array_reverse = $this->position($typeid);
        $lick1 = db('portal_xm')->where("typeid = $typeid")->order('click asc')->limit(3)->select();
        $lick2 = db('portal_xm')->where("typeid = $typeid")->order('pubdate asc')->limit(3)->select();
        $lick3 = db('portal_xm')->where("typeid = $typeid")->order('weight asc')->limit(3)->select();
        $lick4 = db('portal_post')->where("parent_id = 51")->order("published_time desc")->limit(10)->select();
        $lick5 = db('portal_xm')->where("typeid = $typeid")->order('click asc')->limit(5)->select();
        $lick6 = db('portal_post')->where("parent_id = 399")->order("published_time desc")->limit(10)->select();
        $typeid = db('portal_xm')->where("aid = $id")->value('typeid');
        $imgs = db("uploads")->where("arcid = ".$id)->select();
        $lick7 = db("portal_post")->where("post_title",'like',$data['title'])->limit(7)->select();
        $this->daohang();
        $this->assign("imgs",$imgs);
        $this->assign('data',$data);
        $this->assign('lick1',$lick1);
        $this->assign('lick2',$lick2);
        $this->assign('lick3',$lick3);
        $this->assign('lick4',$lick4);
        $this->assign('lick5',$lick5);
        $this->assign('lick6',$lick6);
        $this->assign("lick7",$lick7);
        $this->assign('array_reverse',$array_reverse);
//        $this->assign('url',$url);
        return $this->fetch(':article_xm');

    }

    public function article_jiameng()
    {
        $this->fetch(':article_jiameng');
    }

    public function url()
    {
        $this->redirect('article_xm');
    }

    //面包屑导航

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