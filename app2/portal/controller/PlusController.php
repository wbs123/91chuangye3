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
use think\view;
use think\Session;
use think\Db;
use think\Route;
use think\Request;
use app\admin\model\ThemeModel;
use app\portal\model\PortalXmModel;
class PlusController extends HomeBaseController
{
    public function _initialize()
    {
         if (\think\Request::instance()->isMobile()) {
            if(defined('VIEW_PATH')){
            }else{
                define('VIEW_PATH',PUBLIC_PATH .'themes/mobile/');
            }
            }else{
                if(defined('VIEW_PATH')){}else{
                    define('VIEW_PATH',PUBLIC_PATH .'themes/');
                }
            }
    }
    public function index(){
		
        $path = explode('/',VIEW_PATH);
        if(in_array('mobile',$path)){
           $post=$this->request->param();

            if($post && isset($post['q'])){
                $post['q'] = str_replace('加盟','',$post['q']);
                Session::set('q',$post['q']);
                $q = session('q');
            }else{
                $q = session('q');
            }
            $url = $_SERVER["QUERY_STRING"];
            $array = explode('/', $url);
            $key = '';
            foreach ($array as $k=>$v){
                if(strpos($v,'list_')  == 0){
                    $key = $k;
                }
            }
            $page = substr($array[$key], 5, 1);
            $where=[];
            if(isset($q) && ($q!=''))
            {
                $q = $q;
                $where['a.title'] = [ 'like', "%".$q."%"];
            }else{
                $q = '';
            }
            $where['a.arcrank'] = 1;
            $where['a.status'] = 1;

            $where_word['word'] = $q;
            $word = db('sensitive_words')->where($where_word)->value('id');
            if(!empty($word)){
                $datas = db('portal_xm a')->where($where)->paginate(15,false,['query' => request()->param(),'page'=>$page]);
                $data = $datas;
                $data2 = 'word';
            }else{

                $datas = db('portal_xm a')->where($where)->paginate(15,false,['query' => request()->param(),'page'=>$page]);
                $dataArray = $datas->all();
                foreach ($dataArray as $k=>$v){
                    $category = db('portal_category')->where('id = '.$v['typeid'].' and status = 1 and ishidden = 1')->find();
                    $dataArray[$k]['category_name'] = $category['name'];
                }
                $data = $dataArray;
                $data2 = '';
            }
			
            $lick5 = db('portal_xm')->where('status = 1 and arcrank = 1')->order('click desc')->limit(20,5)->select();
            $youlian = db("flink")->where("typeid = 9999")->order("dtime desc")->limit(50)->select();
            $lick3 = db('portal_xm')->where("typeid",'in','2,1,3,4,5,6,7,8,9,10,20,339,312,313,350,396,420')->where('status = 1 and arcrank = 1')->order('sortrank asc')->limit(11,16)->select();
            // $catess = db("portal_category")->where("parent_id = 0 and status = 1 and ishidden = 1")->order('list_order asc')->limit(12)->select();
            $catess = db('portal_xm')->where('status = 1 and arcrank = 1')->order('click desc')->limit(21)->select();
            //创业资讯
            $where25['parent_id'] = ['in','399,401,402,403,404,405,406,407,408,409,433'];
            $zixun = db('portal_post')->where($where25)->where('post_status = 1 and status = 1')->field('id,post_title,post_excerpt,thumbnail,published_time,class')->order('published_time desc')->limit(10)->select();
            //创业知识
            $where26['parent_id'] = ['in','20,22,27,31'];
            $zhishi = db('portal_post')->where($where26)->where('post_status = 1 and status = 1')->field('id,post_title,post_excerpt,thumbnail,published_time,class')->order('published_time desc')->limit(10)->select();
            //创业故事
            $where27['parent_id'] = ['in','11'];
            $gushi = db('portal_post')->where($where27)->where('post_status = 1 and status = 1')->field('id,post_title,post_excerpt,thumbnail,published_time,class')->order('published_time desc')->limit(10)->select();
            $this->daohang();
            $this->dibu();
            $this->assign('q',$q);
            $this->assign('data',$data);
            if(isset($data[0]) && ($data[0])){
                $data1 = 1;
            }else{
                $data1 = 0;
            }
            //查询底部数据
            $website = DB('website')->where(['id' => 1])->find();
            $this->assign('website',$website);
            $this->assign('data1',$data1);
            $this->assign('data2',$data2);
            $this->assign('datas',$datas);
            $this->assign('catess',$catess);
            $this->assign('lick5',$lick5);
            $this->assign('youlian',$youlian);
            $this->assign('lick3',$lick3);
            $this->assign('zixun',$zixun);
            $this->assign('zhishi',$zhishi);
            $this->assign('gushi',$gushi);
            echo $this->fetch(':mobile/plus/search');
        }else{
            $post = $this->request->param();

            $keys = implode(',',array_keys($post));
            $array = ['keyword'];
            if(!empty($keys) && !in_array($keys,$array)){
               return $this->error1();
            }

            if($post && isset($post['keyword'])){
                 if($post['keyword'] == '加盟'){
                    $info = $post['keyword'];
                }
                $post['q'] = str_replace('加盟','',$post['keyword']);
                Session::set('q',$post['q']);
                $q = session('q');
            }else{
                $q = session('q');
            }
            $url = $_SERVER["QUERY_STRING"];
            preg_match('/^(.*)list_(\d+).html$/',$url,$match);
            $page = empty($match) ? 1 : $match[2];
            $where=[];
            if(isset($q) && ($q!=''))
            {
                $q = $q;
                $where['a.title'] = [ 'like', "%".$q."%"];
                
            }else{
                 $q = '';
                if(isset($info)){
                    $q = $info;
                }
            }

            $where['a.arcrank'] = 1;
            $where['a.status'] = 1;
            $where_word['word'] = $q;
            $word = db('sensitive_words')->where($where_word)->value('id');

            if(!empty($word)){
                $data = db('portal_xm a')->where($where)->paginate(10,false,['query' => request()->param(),'page'=>$page]);
                $dataa = [];
                $data2 = 'word';
                $infos = [];
            }else{
                $data = db('portal_xm a')->where($where)->order('pubdate desc')->paginate(10,false,['query' => request()->param(),'page'=>$page]);
                $dataa = $data->all();
                $infos = $data->all();
                foreach($dataa as $kr=>$vr){
                  $infp = db('portal_category')->where("id = ".$vr['typeid'])->find();
                  $dataa[$kr]['category'] = $infp['name'];
                  $cate_info = db('portal_category')->where('id = '.$vr['typeid'].' and status = 1 and ishidden = 1')->field('name,path')->find();
                    $dataa[$kr]['categoryname'] = $cate_info['name'];
                    $dataa[$kr]['path'] = $cate_info['path'];
                    if(isset($vr['nativeplace']) && ($vr['nativeplace']!='')){
                        $nativeplace = db('sys_enum')->where("evalue = ".$vr['nativeplace']." and py != ''")->field("ename,py")->find();
                        $dataa[$kr]['py'] = !empty($nativeplace['py']) ? $nativeplace['py'] : '';
                    }else{
                        $dataa[$kr]['py'] = '';
                    }
                }
                $data2 = '';
            }
            $data_info = '';
            if(!empty($dataa)){
                if(count($dataa) < 5){
                    $data_info = db('portal_xm')->where('arcrank = 1 and status = 1 and typeid = '.$dataa[0]['typeid'])->limit(10-count($dataa))->orderRaw('rand()')->select()->toArray();
                    foreach($data_info as $ke=>$va){
                        $infpa = db('portal_category')->where("id = ".$va['typeid'])->find();
                        $data_info[$ke]['category'] = $infpa['name'];
                        $cates_info = db('portal_category')->where('id = '.$va['typeid'].' and status = 1 and ishidden = 1')->field('name,path')->find();
                        $data_info[$ke]['categoryname'] = $cates_info['name'];
                        $data_info[$ke]['path'] = $cates_info['path'];
                        if(isset($va['nativeplace']) && ($va['nativeplace']!='')){
                            $nativeplace = db('sys_enum')->where("evalue = ".$va['nativeplace']." and py != ''")->field("ename,py")->find();
                            $data_info[$ke]['py'] = !empty($nativeplace['py']) ? $nativeplace['py'] : '';
                        }else{
                            $data_info[$ke]['py'] = '';
                        }
                    }
                }
            }

            //最新资讯
            $lick3 = db('portal_post')->where('status = 1 and post_status = 1')->field('id,post_title,class,published_time')->order('id desc')->limit(10)->select();
            //热门专题
            $lick4 = db('portal_post')->where('status = 1 and post_status = 1')->field('id,post_title,class,published_time')->order('click desc')->limit(10)->select();
            //十大品牌
            $lick5 = db('portal_post')->where('status = 1 and post_status = 1')->field('id,post_title,class,published_time')->order('id desc')->limit(10,10)->select();
            //底部项目
            $arr = '2,312,8,10,5,4,7,313,9,1';
            $catess = db("portal_category")->where('id', 'in', $arr)->where('status = 1 and ishidden = 1')->field('id,name')->order('list_order asc')->select();
            $cates = $catess->all();
            foreach($cates as $keys=>$v)
            {
                $cated = db('portal_category')->where(['parent_id' => $v['id'],'ishidden' => 1,'status' => 1])->column('id');
                array_unshift($cated, $v['id']);
                $cates[$keys]['ids'] = implode(',', $cated);
            }
            foreach ($cates as $key => $val) {
                $wheres['typeid'] = array('in', $val['ids']);
                $where3['status'] = 1;
                $where3['arcrank'] = 1;
                $val['data'] = db("portal_xm")->where($wheres)->where($where3)->field('aid,title,invested,litpic,class')->order('pubdate asc')->limit(14)->select();
                $datas[] = $val;
            }

            $where18['aid'] = ['in','59586,92858,103409'];
            $dapai = db('portal_xm')->where($where18)->where('status = 1 and arcrank = 1')->field('aid,title,thumbnail,invested,typeid,sum,class')->select();

            //导航行业以及热门行业
            $type = db("portal_category")->where("parent_id = 0 and status = 1 and ishidden = 1 and id != 350")->field('id,name,path')->order('list_order asc')->limit(15)->select();


            if(isset($post['classname'])){
                $catename = db('portal_category')->where("path="."'$post[classname]'")->field('name')->find();
                $catename = str_replace('加盟','',$catename['name']);
            }else{
                $catename = '热门';
            }


            if(!empty($dataa)){
                $lick1 = db('portal_xm')->where('status = 1 and arcrank = 1 and typeid = '.$dataa[0]['typeid'])->where("litpic != ' '")->order('click desc')->limit(6)->select();
                $lick2 = db('portal_xm')->where('status = 1 and arcrank = 1 and typeid = '.$dataa[0]['typeid'])->order('weight desc')->limit(10)->select();
                //推荐项目品牌
                $tuijian = db('portal_xm')->where('status = 1 and arcrank = 1 and typeid = '.$dataa[0]['typeid'])->field('aid,title,class')->order('aid desc')->limit('22')->select();
            }else{
                $lick1 = db('portal_xm')->where('status = 1 and arcrank = 1')->where("litpic != ' '")->order('click desc')->limit(6)->select();
                $lick2 = db('portal_xm')->where('status = 1 and arcrank = 1')->order('weight desc')->limit(10)->select();
                //推荐项目品牌
                $tuijian = db('portal_xm')->where('status = 1 and arcrank = 1')->field('aid,title,class')->order('aid desc')->limit('22')->select();
            }
            // $lick3 = db('portal_post')->where('status = 1 and post_status = 1')->order('id desc')->limit(10)->select();
            // $lick3 = $lick3->all();
            // foreach ($lick3 as $k => $v) {
            //     $lick3[$k]['classs'] = substr($v['class'],0,4);
            // }
            // $lick5 = db('portal_xm')->where('status = 1 and arcrank = 1')->order('click desc')->limit(20,5)->select();
			
            $youlian = db("flink")->where("typeid = 9999")->order("dtime desc")->limit(50)->select();
            $cate = db("portal_category")->where("parent_id = 0 and status = 1 and ishidden = 1")->order('list_order asc')->limit(16)->select();
			
            $this->daohang();
            $this->dibu();
            $this->assign('cate',$cate);
            $this->assign('lick1',$lick1);
            $this->assign('lick2',$lick2);
            $this->assign('q',empty($q) ? '全部' : $q);
            $this->assign('page',$page);
            $this->assign('data',$data);
          	$this->assign('dataa',$dataa);
            $this->assign('data2',$data2);
            if(isset($data[0]) && ($data[0])){
                $data1 = 1;
            }else{
                $data1 = 0;
            }

            //查询底部数据
            $website = DB('website')->where(['id' => 1])->find();
            $this->assign('website',$website);
            $this->assign('data1',$data1);
            $this->assign('lick5',$lick5);
            $this->assign('youlian',$youlian);
            $this->assign('lick3',$lick3);
			
            $this->assign('data_info',$data_info);

            $this->assign('catename',$catename);

            $this->assign('dapai',$dapai);
            $this->assign('infos',$infos);
            $this->assign('type',$type);

            $this->assign('lick3',$lick3);
            $this->assign('lick4',$lick4);
            $this->assign('lick5',$lick5);
            $this->assign('datas',$datas);
			
            $this->assign('catess',$catess);
            $this->assign('tuijian',$tuijian);
            return $this->fetch(':plus/search');
        }
    }
    //搜索结果ajax点击加载更多
    public function ajaxkeyword(){
        $post=$this->request->param();
        $q = $post['keyword'];
        $page = $post['page'] * 10;
        $where['a.title'] = [ 'like', "%".$q."%"];
        $where['a.arcrank'] = 1;
        $where['a.status'] = 1;
        $data = db('portal_xm a')->where($where)->order('pubdate desc')->limit($page,10)->select()->toArray();
        foreach ($data as $k=>$v){
            $category = db('portal_category')->where('id = '.$v['typeid'])->find();
            $data[$k]['category_name'] = $category['name'];
        }
        $html='';
        $html='';
        foreach ($data as $k=>$v){
            $html.='<li>';
            $html.='<div class="img">';
            $url = cmf_url('portal/common/index',['id'=>$v['aid'],'classname'=>$v['class']]);
            $html.='<a href="'.$url.'">';
            $html.='<img class="lazy" src="/themes/simpleboot3/public/mobile/xin/images/44feb2a189bb6a55ade0a5349fcccfb2.jpg" data-original="'.mobileimg($v['litpic']).'" alt="">';
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
            $html.='<a href="javascript:;">'.$v['category_name'].'</a>';
            $html.='<a href="javascript:;">'.$v['company_address'].'</a>';
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
    public function daohang()
    {
        $cates1 = db("portal_category")->where("id = 2 and status = 1 and ishidden = 1")->select();
        foreach($cates1 as $key=>$val)
        {
            $val['data'] = db("portal_category")->where("parent_id =".$val['id'])->limit(40)->select();
            $c1[] = $val;
        }
        $cates2 = db("portal_category")->where("id ","in","312,8,10,5,396")->where('status = 1 and ishidden = 1')->select();
        foreach($cates2 as $key=>$val)
        {
            $val['data'] = db("portal_category")->where("parent_id =".$val['id'])->limit(6)->select();
            $c2[] = $val;
        }
        $cates3 = db("portal_category")->where("id","in", " 4,7,313,9,420")->where('status = 1 and ishidden = 1')->select();
        foreach($cates3 as $key=>$val)
        {
            $val['data'] = db("portal_category")->where("parent_id =".$val['id'])->limit(6)->select();
            $c3[] = $val;
        }
        $cates4 = db("portal_category")->where("id","in"," 1,3,339,6")->where('status = 1 and ishidden = 1')->select();
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
    public function dibu()
    {
        $dibu = db("portal_category")->where("parent_id",'in','52,53')->select();
        $this->assign('dibu',$dibu);
    }
      public function error1(){
        $youlian = db("flink")->where("typeid = 9999")->order("dtime desc")->limit(50)->select();
        $this->assign('youlian', $youlian);
        $request = \think\Request::instance();
        $pathinfo = $request->pathinfo();

        //判断是否有静态页面
        if(is_file('./html/'.$pathinfo)){
            if (\think\Request::instance()->isMobile()) {
                $assign = ':mhtml/'.$request->path();
                return $this->fetch($assign);
            }else{
                $assign = ':html/'.$request->path();
                return $this->fetch($assign);
            }
        }else{
            $cate = db("portal_category")->where("parent_id = 0 and status = 1 and ishidden = 1")->order('list_order asc')->limit(16)->select();
            $this->assign('cate',$cate);
            $website = DB('website')->where(['id' => 1])->find();
            $this->assign('website',$website);
            $this->daohang();
            $this->dibu();
            return $this->fetch(":error1");
        }
        //判断是否有静态页面
//        if (\think\Request::instance()->isMobile()) {
//            return $this->fetch(":mobile/error1");
//        }else{
//            $cate = db("portal_category")->where("parent_id = 0 and status = 1 and ishidden = 1")->order('list_order asc')->limit(16)->select();
//            $website = DB('website')->where(['id' => 1])->find();
//            $this->assign('website',$website);
//            $this->assign('cate',$cate);
//            return $this->fetch(":error1");
//        }
    }
}