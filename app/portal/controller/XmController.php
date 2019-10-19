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
use app\portal\model\AreaModel;
use app\portal\model\CategoryModel;
class XmController extends HomeBaseController
{
    public function index()
    {
        $post=$this->request->param();
		if(empty($post)){
			return $this->error1();
		}
        $array_reverse = "";
        $youlian = "";
        if(isset($post['classname']) && ($post['classname']!='')){
            $id = db("portal_category")->where("path="."'$post[classname]'")->value('id');
            $array_reverse = $this->position($id);
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
        if(isset($post['classname']) && ($post['classname']!='')){
            $id = db("portal_category")->where("path="."'$post[classname]'")->value('id');
            $parent_id = db("portal_category")->where("path="."'$post[classname]'")->value('parent_id');
            if($parent_id == 0)
            {
                $where['a.typeid1'] = $id;
                $cates =  db("portal_category")->where("parent_id = $id")->select();
                $youlian = db("flink")->where("typeid = ".$id)->order("dtime desc")->limit(30)->select();
            }else{
            $where['a.typeid'] = $id;
            $ids = db("portal_category")->where("id = $id")->value("parent_id");
            $cates =  db("portal_category")->where("parent_id = $ids")->select();
            $youlian = db("flink")->where("typeid = ".$id)->order("dtime desc")->limit(30)->select();
            }
        }else{
            $cates = '';
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
        $this->dibu();
        $this->assign('youlian',$youlian);
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
    public function classtype()
    {
        $post=$this->request->param();
		if(empty($post)){
			return $this->error1();
		}
        $array_reverse = "";
        $youlian = "";
        if(isset($post['classname']) && ($post['classname']!='')){
            $id = db("portal_category")->where("path="."'$post[classname]'")->value('id');
            $array_reverse = $this->position($id);
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
        if(isset($post['classname']) && ($post['classname']!='')){
            $id = db("portal_category")->where("path="."'$post[classname]'")->value('id');
            $parent_id = db("portal_category")->where("path="."'$post[classname]'")->value('parent_id');
            if($parent_id == 0)
            {
                $where['a.typeid1'] = $id;
                $cates =  db("portal_category")->where("parent_id = $id")->select();
                $youlian = db("flink")->where("typeid = ".$id)->order("dtime desc")->limit(30)->select();
            }else{
            $where['a.typeid'] = $id;
            $ids = db("portal_category")->where("id = $id")->value("parent_id");
            $cates =  db("portal_category")->where("parent_id = $ids")->select();
            $youlian = db("flink")->where("typeid = ".$id)->order("dtime desc")->limit(30)->select();
            }
        }else{
            $cates = '';
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
        $this->dibu();
        $this->assign('youlian',$youlian);
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
		if(empty($id)){
			return $this->error1();
		}
        $array_reverse = "";
        $data = db('portal_xm')->where("aid = $id")->find();
		if(empty($data)){
			return $this->error1();
		}
        $typeid = $data['typeid'];
        $name = db("portal_category")->where("id = ".$typeid)->value("name");
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
        $this->dibu();
        $this->assign("name",$name);
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
        $regex = "/\ |\/|\~|\!|\@|\#|\\$|\%|\^|\&|\*|\(|\)|\_|\+|\{|\}|\:|\<|\>|\?|\[|\]|\,|\.|\/|\;|\'|\`|\-|\=|\\\|\|/";
        $post=$this->request->param();
        if(isset($post['sex']) && ($post['sex'] != '')) {
            $name = preg_replace($regex,"",$post['name']);
            $data['name'] = $name;
            $data['sex'] = $post['sex'];
            $data['tel'] = $post['tel'];

            preg_match('/^1[3|4|5|6|7|8]\d{9}$/',$post['tel'],$match);
            if(empty($match)){
                $datas = array('data'=>2);
                echo json_encode($datas);exit;
            }
            $data['invested'] = $post['jine'];
            $data['email'] = $post['Email'];
            $data['address'] = $post['Address'];
            $data['rule'] = $post['msg'];
            $data['url'] = $post['url'];
            $data['inputtime'] = time();
            $data['source'] = 1;
            if(!empty($post['NewsXm'])){
                $data['type'] = $post['NewsXm'];
            }else{
                $data['type'] = 'xm';
            }
            // $type = explode("/", $data['url']);
            // if(in_array('news',$type)){
            //      $data['type'] = 'news';
            // }else{
            //      $data['type'] = 'xm';
            // }

            $user_time = db('user_info')->where(['tel'=>$data['tel']])->value('inputtime');
            //$time = $user_time+(24*3600);
            if(isset($user_time)){
                $datas = array('data'=>3);
                echo json_encode($datas);
            }else{
                $info = Db::name('user_info')->insert($data);
                if ($info) {
                   $datas = array('data'=>1);
                    echo json_encode($datas);
                } else {
                    $datas = array('data'=>2);
                    echo json_encode($datas);
                }
            }

            
        }else if(isset($post['name']) && isset($post['tel'])){
            $name = preg_replace($regex,"",$post['name']);
            $data['name'] = $name;
            $data['tel'] = $post['tel'];
            preg_match('/^1[3|4|5|6|7|8]\d{9}$/',$post['tel'],$match);
            if(empty($match)){
                $datas = array('data'=>2);
                echo json_encode($datas);
            }
            $data['url'] = $post['url'];
            $data['inputtime'] = time();
            $data['source'] = 1;
            if(!empty($post['NewsXm'])){
                $data['type'] = $post['NewsXm'];
            }else{
                $data['type'] = 'xm';
            }
            $user_time = db('user_info')->where(['tel'=>$data['tel']])->value('inputtime');
            //$time = $user_time+(24*3600);
            if(isset($user_time)){
                $datas = array('data'=>3);
                echo json_encode($datas);
            }else{
                $info = Db::name('user_info')->insert($data);
                if ($info) {
                   $datas = array('data'=>1);
                    echo json_encode($datas);
                } else {
                     $datas = array('data'=>2);
                    echo json_encode($datas);
                }
            }
            
        }else{
            preg_match('/^1[3|4|5|6|7|8]\d{9}$/',$post['tel'],$match);
            if(empty($match)){
                $datas = array('data'=>2);
                echo json_encode($datas);
            }
            $data['tel'] = $post['tel'];
            $data['url'] = $post['url'];
            $data['inputtime'] = time();
            $data['source'] = 1;
            if(!empty($post['NewsXm'])){
                $data['type'] = $post['NewsXm'];
            }else{
                $data['type'] = 'xm';
            }
            $user_time = db('user_info')->where(['tel'=>$data['tel']])->value('inputtime');
            //$time = $user_time+(24*3600);
            if(isset($user_time)){
                $datas = array('data'=>3);
                echo json_encode($datas);
            }else{
                $info = Db::name('user_info')->insert($data);
                if($info){
                    $datas = array('data'=>1);
                    echo json_encode($datas);
                }else{
                     $datas = array('data'=>2);
                    echo json_encode($datas);
                }
            }

            
        }
    }
    public function liuyan2()
    {
        $post=$this->request->param();
        $regex = "/\ |\/|\~|\!|\@|\#|\\$|\%|\^|\&|\*|\(|\)|\_|\+|\{|\}|\:|\<|\>|\?|\[|\]|\,|\.|\/|\;|\'|\`|\-|\=|\\\|\|/";
        if($post){
            $name = preg_replace($regex,"",$post['name']);
            $data['name'] = $name;
            $data['tel'] = $post['tel'];
            preg_match('/^1[3|4|5|6|7|8]\d{9}$/',$post['tel'],$match);
            if(empty($match)){
                $datas = array('data'=>2);
                echo json_encode($datas);
            }
            $data['rule'] = $post['msg'];
            $data['url'] = $post['urls'];
            $data['inputtime'] = time();
            $data['source'] = 2;
            if(!empty($post['NewsXm'])){
                $data['type'] = $post['NewsXm'];
            }else{
                $data['type'] = 'xm';
            }
            $user_time = db('user_info')->where(['tel'=>$data['tel']])->value('inputtime');
            //$time = $user_time+(24*3600);
            if(isset($user_time)){
                 $datas = array('data'=>3);
                    echo json_encode($datas);
            }else{
                $info = db('user_info')->insert($data);
                if($info){
                    $datas = array('data'=>1);
                    echo json_encode($datas);
                }else{
                     $datas = array('data'=>2);
                    echo json_encode($datas);
                }
            }
            
        }else{
             $datas = array('data'=>2);
             echo json_encode($datas);
        }

    }
     public function liuyan3()
    {
        $regex = "/\ |\/|\~|\!|\@|\#|\\$|\%|\^|\&|\*|\(|\)|\_|\+|\{|\}|\:|\<|\>|\?|\[|\]|\,|\.|\/|\;|\'|\`|\-|\=|\\\|\|/";
        $post=$this->request->param();
        if($post){
            $data['tel'] = $post['tel'];
            preg_match('/^1[3|4|5|6|7|8]\d{9}$/',$post['tel'],$match);
            if(empty($match)){
                $datas = array('data'=>2);
                echo json_encode($datas);exit;
            }
            $data['url'] = $post['urls'];
            $data['inputtime'] = time();
            $data['source'] = 2;

            if(!empty($post['NewsXm'])){
                $data['type'] = $post['NewsXm'];
            }else{
                $data['type'] = 'xm';
            }
            $user_time = db('user_info')->where(['tel'=>$data['tel']])->value('inputtime');

            //$time = $user_time+(24*3600);
            if(isset($user_time)){
                    $datas = array('data'=>3);
                    echo json_encode($datas);
            }else{
                $info = db('user_info')->insert($data);
                if($info){
                    $datas = array('data'=>1);
                    echo json_encode($datas);
                }else{
                    $datas = array('data'=>2);
                    echo json_encode($datas);
                }
            }

            
        }else{
            $datas = array('data'=>2);
             echo json_encode($datas);
        }
    }
  public function liuyan4(){
        $post=$this->request->param();
        $data['tel'] = $post['tel'];
       
        preg_match('/^1[3|4|5|6|7|8]\d{9}$/',$post['tel'],$match);
        if(empty($match)){
          $datas = array('data'=>2);
          echo json_encode($datas);
        }
        $data['url'] = $post['url'];
        $data['inputtime'] = time();
        $data['source'] = 1;
      	if(!empty($post['NewsXm'])){
                $data['type'] = $post['NewsXm'];
            }else{
                $data['type'] = 'xm';
            }
    	
    	 $user_time = db('user_info')->where(['tel'=>$data['tel']])->value('inputtime');
        // $time = $user_time+(24*3600);
    	if(isset($user_time)){
          $datas = array('data'=>3);
          echo json_encode($datas);
        }else{
        	$info = Db::name('user_info')->insert($data);
          if($info){
              $datas = array('data'=>1);
              echo json_encode($datas);
          }else{
              $datas = array('data'=>2);
              echo json_encode($datas);
          }
        }
    	
        
    }
    public function dibu()
    {
        $dibu = db("portal_category")->where("parent_id",'in','52,53')->select();
        $this->assign('dibu',$dibu);
    }
//ajax 接收处理数据
    public function ajaxtype(){
        $post=$this->request->param();
        $id = $post['id'];
        $info = db('portal_category')->where('parent_id = '.$id.' and status = 1 and ishidden = 1')->order('list_order asc')->field('id,name,path,mobile_thumbnail')->select();
        $ids = db('portal_category')->where('id = '.$id)->field('path')->order('list_order asc')->find();
        $html='';
        foreach($info as $k=>$v){
            if($k == 0){
                $html.='<li>';
                $html.='<a href="/'.$ids['path'].'/">';
                $html.='<div class="img">';
                $html.='<img src="/themes/simpleboot3/public/mobile/xin/images/eeaf6aa9385bda5e72b89033812cd7f5.png" class="lazy" data-original="/themes/simpleboot3/public/mobile/xin/images/eeaf6aa9385bda5e72b89033812cd7f5.png" alt="">';
                $html.='</div>';
                $html.='<span>全部</span>';
                $html.='</a></li>';
            }
            $html.='<li>';
            $url = cmf_url("portal/common/index",["classname"=>$v['path']],'');
            $html.="<a href='".$url."/'>";
            $html.='<div class="img">';
            $html.="<img src='/themes/simpleboot3/public/mobile/xin/images/f3d98677b149cee3971cf6331de6d8f6.jpg' class='lazy'  data-original='".checkImgurl($v['mobile_thumbnail'])."' alt='".$v['name']."'>";
            $html.='</div>';
            $name = str_replace('加盟','',$v['name']);
            $html.='<span>'.$name.'</span>';
            $html.='</a></li>';
        }
        $datas = array('html'=>$html);
        echo json_encode($datas);
    }
        //项目列表分类ajax
    public function xmajax(){
        $post=$this->request->param();
        $id = $post['id'];
        $info = db('portal_category')->where('parent_id = '.$id)->where('status = 1 and ishidden = 1')->order('list_order asc')->field('id,name,path,mobile_thumbnail')->select();
        $ids = db('portal_category')->where('id = '.$id)->field('id,path')->order('list_order asc')->find();
        $html='';
        foreach($info as $k=>$v){
            if($k == 0){
                if($ids['id'] == $post['cateid']){
                    $html.='<li class="active">';
                }else{
                    $html.='<li>';
                }
                $html.='<h1>';
                $html.='<a href="/'.$ids['path'].'/">';
                $html.='<span>全部</span>';
                $html.='</a>';
                $html.='</h1></li>';
            }

            if($v['id'] == $post['cateid']){
                $html.='<li class="active">';
            }else{
                $html.='<li>';
            }
            $url = cmf_url("portal/common/index",["classname"=>$v['path']],'');
            $html.="<a href='".$url."/' attr=".$v['id'].">";
            $nas = str_replace('加盟','',$v['name']);
            $html.='<span>'.$nas.'</span>';
            $html.='</a></li>';
        }
        $datas = array('html'=>$html);
        echo json_encode($datas);
    }
    //项目列表点击加载
    public function listajax(){

        $post=$this->request->param();
        $url = $post['url'];
        $array = explode('/', $url);
        $key = '';
        foreach ($array as $k=>$v){
            if(strpos($v,'list_')  == 0){
                $key = $k;
            }
        }
        $pages = substr($array[$key], 5, 4);
        $sessionPage = session('page');
        $pages = isset($sessionPage) ? session('page') : $this->findNum($pages);


        if(isset($pages) && ($pages != '')){
            $page = $pages * 20;
            session('page',$pages+1);
        }else{
            $page = $post['page'] * 20;
        }

        $areaModel = new AreaModel();
        $CategoryModel = new CategoryModel();
        //地区
        if(!empty($post['address'])){
            $areaHave = $areaModel->areaName(['py'=>$post['address']]);
            $areaValue = $areaHave['evalue'];
            if($areaValue % 500 == 0){
                $fareavalue = intval($areaValue);
                $maxvalue = $fareavalue+500;
                $sareavalue = DB::name('sys_enum')->field('evalue')->where('evalue > '.$fareavalue.' and evalue < '
                    .$maxvalue)->group('evalue')->select();
                $areaAll = [];
                foreach ($sareavalue as $value){
                    if(!in_array(floor($value['evalue']),$areaAll)){
                        $areaAll[] = floor($value['evalue']);
                    }
                }
                $areaAll[] = $areaValue;
                $where = ['por.nativeplace'=>['in',implode(',',$areaAll)]];
            }else{
                $where['por.nativeplace'] = $areaHave['evalue'];
            }
        }
        //金额参数
        if(!empty($post['num']))
        {
            $where['por.invested'] =  ($post['num'] == 100) ? $post['num'].'万以上' : $post['num'].'万';
        }

        //当栏目不是xiangmu时
        if($post['classname']!='xiangmu'){
            $category = $CategoryModel::getone(['path'=>$post['classname']],'id,path,parent_id');
            if(!$category){
                return $this->error1();
            }
            $id = $category['id'];
            if($category['parent_id'] == 0)
            {
                //子栏目ID
                $sonIds =  $CategoryModel::getOneColumn(['parent_id'=>$id],'id');
                $where['por.typeid'] = ['in',$sonIds];
            }else{
                $where['por.typeid'] = $id;
            }
        }else{
            //排除养生保健、创业好项目、休闲娱乐、加盟排行榜、采集栏目
            $where['cat.parent_id'] = ['notin',['350','63','742','391','432']];
            $where['por.typeid'] = ['notin',['350','63','742','391','432']];
        }

        //项目列表数据
        $data = Db::name('portal_xm')
            ->alias('por')
            ->field('por.aid,por.class,por.litpic,por.thumbnail,por.title,por.sum,por.invested,por.companyname,por.address,por.nativeplace,cat.name as categoryname,cat.path')
            ->join('portal_category cat','por.typeid = cat.id')
            ->where($where)->where('por.status = 1 and por.arcrank = 1')->order('update_time desc')->limit($page,20)
            ->select();
        $dataa = $data->all();

        foreach ($dataa as $key => $value) {
             if(isset($value['nativeplace']) && ($value['nativeplace']!='')){
                $nativeplace = db('sys_enum')->where("evalue = ".$value['nativeplace']." and py != ''")->field("ename,py")->find();
                $dataa[$key]['address'] = !empty($nativeplace['ename']) ? $nativeplace['ename'] : '';
                $dataa[$key]['py'] = !empty($nativeplace['py']) ? $nativeplace['py'] : '';
            }else{
                $dataa[$key]['address'] = '';
                $dataa[$key]['py'] = '';
            }
        }
        $html='';
        foreach ($dataa as $k=>$v){
            $html.='<li>';

            $html.='<div class="img">';
            $url = cmf_url('portal/common/index',['id'=>$v['aid'],'classname'=>$v['class']]);
            $html.='<a href="'.$url.'" rel="nofollow">';
            $html.='<img class="lazy" src="/themes/simpleboot3/public/mobile/xin/images/44feb2a189bb6a55ade0a5349fcccfb2.jpg" data-original="'.checkImgurl($v['litpic']).'" alt="">';
            $html.='</a></div>';

            $html.='<div class="text">';
            $html.='<div class="left">';
            $html.='<div class="title">';
            $html.='<h2>';
            $html.='<a href="'.$url.'">'.$v['title'].'</a>';
            $html.='</h2>';
            $html.='</div>';
            $html.='<div class="price">￥'.$v['invested'].'</div>';
            $html.='<div class="smallTab">';
            $html.='<a href="/'.$v['path'].'/">'.$v['categoryname'].'</a>';
            $html.='<a href="/'.$v['class'].'/'.$v['py'].'">'.$v['address'].'</a>';
            $html.='</div>';
            $html.='<div class="desc">'.$v['companyname'].'</div>';
            $html.='</div>';
            $html.='<div class="right">';
            $html.='<div class="join"><a href="'.$url.'">咨询</a></div>';
            $html.='</div>';
            $html.='</div>';
            $html.='</li>';
        }
        $dataa = array('html'=>$html);
        echo json_encode($dataa);
    }
    function findNum($str=''){
        $str=trim($str);
        if(empty($str)){
            return '';
        }
        $temp=array('1','2','3','4','5','6','7','8','9','0');
        $result='';
        for($i=0;$i<strlen($str);$i++){
            if(in_array($str[$i],$temp)){
                $result.=$str[$i];
            }
        }
        return $result;
    }
    public function reload(){
        $post=$this->request->param();
        if(isset($post['id'])){
            session('page',null);
        }
    }
}