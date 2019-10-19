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

class AboutController extends HomeBaseController
{
      public function index()
      {
          $array_reverse = '';
          $post=$this->request->param();
          if(isset($post['classname']) && ($post['classname']!=''))
          {
              $dd = db("portal_category")->where("path="."'$post[classname]'")->find('id');
              $array_reverse = $this->position($dd['id']);
          }
          $data = db("portal_category")->where("parent_id",'in','52,53')->select();
          $this->dibu();
          $this->daohang();
          $this->assign('data',$data);
          $this->assign('dd',$dd);
          $this->assign('array_reverse',$array_reverse);
          return $this->fetch(":about");

      }

    public function position($cid){//传递当前栏目id

        static $pos=array();//创建接受面包屑导航的数组

        if(empty($pos)){//哦，这个就比较重要了，如果需要把当前栏目也放到面包屑导航中的话就要加上

            $cates=db('portal_category')->field('id,name,parent_id,path')->find($cid);

            $pos[]=$cates;

        }

        $data=db('portal_category')->field('id,name,parent_id,path')->select();//所有栏目信息

        $cates=db('portal_category')->field('id,name,parent_id,path')->find($cid);//当前栏目信息

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

    public function liuyan()
    {
        echo "留言成功";

    }

    public function dibu()
    {
        $dibu = db("portal_category")->where("parent_id",'in','52,53')->select();
        $this->assign('dibu',$dibu);
    }
}