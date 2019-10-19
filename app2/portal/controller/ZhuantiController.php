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
use FontLib\Table\Type\name;
use think\Db;

use think\Request;
use app\portal\model\AreaModel;
use app\portal\model\CategoryModel;
use app\portal\model\ProductModel;
use app\portal\model\NewsModel;
use think\cache\driver\Redis;
class ZhuantiController extends HomeBaseController
{
    public function _initialize()
    {
        $this->CategoryModel = new CategoryModel();
        $this->daohang();
        $this->dibu();
        $this->zuoce();
        if (\think\Request::instance()->isMobile()) {
            $this->assign('seo_url', 'http://www.91chuangye.com' . $this->request->url());
        }else{
            $this->assign('seo_url', 'http://m.91chuangye.com' . $this->request->url());
        }
    }

    public function index()
    {
        $redis = new Redis();
        $ProductModel = new ProductModel();
        $NewsModel = new NewsModel;
        //项目目录
        $path = $this->request->param('type');
        if($path == 'yangsheng'){
            $param_addr = $this->request->param('num');
            $soncategory = Db::name('portal_category')->where(['path'=>'yangsheng/'.$param_addr])
                ->field('id,name,path')
                ->find();
            if($soncategory){
                $path = $soncategory['path'];
            }
        }


        //项目目录必须存在，不存在404
        if(!isset($path) || empty($path)){
            return $this->error1();
        }

        //允许访问栏目
        $passtypeid = explode(',','2,312,8,10,5,4,7,313,9,1,3,339,6,396,420,734,742,63,350');
        if($path != 'xiangmu' && $path != 'haoxiangmu' && $path != 'article_poster'){
            $category = Db::name('portal_category')->where(['path'=>$path])->field('id,parent_id,name')->find();
            //判断当前目录是否存在
            $path = in_array($category['id'],$passtypeid) ? $path : in_array($category['parent_id'],$passtypeid) ? $path : false;
        }

        //不存在返回404
        if(!$path){
            return $this->error1();
        }
        $this->assign('catename',str_replace('加盟','',$category['name']));
        $this->assign('path',$path);

        $sonIds = $this->CategoryModel->getOneColumn(['parent_id'=>$category['id']],'id');
        array_push($sonIds,$category['id']);

        //创业知识
        $zhishiIds = $this->CategoryModel->getOneColumn(['parent_id' => 20], 'id');
        $zhishi = $NewsModel->conditionlist(['parent_id' => ['in', $zhishiIds]], 'id,class,post_title,post_excerpt,create_time,thumbnail', 5, 'published_time', 'desc');

        //创业之道
        $zhidao = $NewsModel->conditionlist(['parent_id' => ['in','32']], 'id,class,post_title,post_excerpt,create_time,thumbnail', 5, 'published_time', 'desc');

        //创业故事
        $gushi = $NewsModel->conditionlist(['parent_id' => ['in','11']], 'id,class,post_title,post_excerpt,create_time,thumbnail', 5, 'published_time', 'desc');
        $this->assign('zhishi', $zhishi);
        $this->assign('zhidao', $zhidao);
        $this->assign('gushi', $gushi);

        //排行榜
        $Top = $ProductModel->conditionlist(['typeid'=>['in',$sonIds]],'aid,class,litpic,click,sum,invested,title,typeid,description,logo',10,'click','desc');
        $this->assign('Top',$Top);

        //TDK
        $name = str_replace('加盟','',$category['name']);
        if($category['id'] == 2){
            $Seo['Seo_Title'] = '餐饮连锁项目招商加盟条件费用多少钱_餐饮加盟十大品牌排行榜TOP10-91创业网';
            $Seo['Seo_Keywords'] = '餐饮加盟,餐饮加盟费用,餐饮加盟多少钱,餐饮排行榜';
            $Seo['Seo_Description'] = '91创业网餐饮频道为您汇集全国各地特色餐饮美食加盟项目,详细为您介绍各品牌餐饮项目加盟条件以及加盟费用，并为您总结了餐饮行业投资加盟项目排行榜，筛选出餐饮加盟投资十大品牌。';
        }else if($category['id'] == 734){
            $Seo['Seo_Title'] = '商务快捷连锁酒店招商加盟条件费用多少钱_酒店招商加盟项目十大品牌排行榜TOP10-91创业网';
            $Seo['Seo_Keywords'] = '酒店加盟,酒店加盟费用,酒店加盟多少钱,酒店排行榜';
            $Seo['Seo_Description'] = '91创业网酒店加盟频道为您汇集全国各地酒店加盟项目,详细为您介绍各品牌酒店项目加盟条件以及加盟费用,并为您总结了酒店行业投资加盟项目排行榜,筛选出酒店加盟投资十大品牌。';
        }else if($category['id'] == 8){
            $Seo['Seo_Title'] = '母婴用品行业招商加盟条件费用多少钱_母婴店加盟十大品牌排行榜TOP10-91创业网';
            $Seo['Seo_Keywords'] = '母婴加盟,母婴加盟费用,母婴加盟多少钱,母婴排行榜';
            $Seo['Seo_Description'] = '91创业网母婴加盟频道为您汇集全国各地母婴加盟项目,详细为您介绍各品牌母婴项目加盟条件以及加盟费用,并为您总结了母婴行业投资加盟项目排行榜,筛选出母婴加盟投资十大品牌。';
        }else if($category['id'] == 10){
            $Seo['Seo_Title'] = '教育行业培训机构招商加盟条件费用多少钱_教育辅导班加盟十大品牌排行榜top10-91创业网';
            $Seo['Seo_Keywords'] = '教育加盟,教育加盟费用,教育加盟多少钱,教育排行榜';
            $Seo['Seo_Description'] = '91创业网教育加盟频道为您汇集全国各地教育加盟项目,详细为您介绍各品牌教育项目加盟条件以及加盟费用,并为您总结了教育行业投资加盟项目排行榜,筛选出教育加盟投资十大品牌。';
        }else if($category['id'] == 312){
            $Seo['Seo_Title'] = '酒水行业项目招商加盟代理条件费用多少钱_酒水加盟代理十大品牌排行榜TOP10-91创业网';
            $Seo['Seo_Keywords'] = '酒水加盟,酒水加盟费用,酒水加盟多少钱,酒水排行榜';
            $Seo['Seo_Description'] = '91创业网酒水加盟频道为您汇集全国各地酒水加盟项目,详细为您介绍各品牌酒水项目加盟条件以及加盟费用,并为您总结了酒水行业投资加盟项目排行榜,筛选出酒水加盟投资十大品牌。';
        }else if($category['id'] == 5 || $category['id'] == 4 || $category['id'] == 7 || $category['id'] == 9 || $category['id'] == 339 || $category['id'] == 1 || $category['id'] == 313 || $category['id'] == 3){
            $Seo['Seo_Title'] = $name.'连锁项目招商加盟条件费用多少钱_'.$name.'加盟十大品牌排行榜TOP10-91创业网';
            $Seo['Seo_Keywords'] = $name.'加盟，'.$name.'加盟费用，'.$name.'加盟多少钱，'.$name.'排行榜';
            $Seo['Seo_Description'] = '91创业网'.$name.'加盟频道为您汇集全国各地'.$name.'加盟项目,详细为您介绍各品牌'.$name.'项目加盟条件以及加盟费用,并为您总结了'.$name.'行业投资加盟项目排行榜,筛选出'.$name.'加盟投资十大品牌。';
        }else if($category['id'] == 6){
            $Seo['Seo_Title'] = '汽车服务连锁项目招商加盟条件费用多少钱_汽车加盟十大品牌排行榜TOP10-91创业网';
            $Seo['Seo_Keywords'] = '汽车加盟,汽车加盟费用,汽车加盟多少钱,汽车排行榜';
            $Seo['Seo_Description'] = '91创业网汽车加盟频道为您汇集全国各地汽车加盟项目,详细为您介绍各品牌汽车项目加盟条件以及加盟费用,并为您总结了汽车行业投资加盟项目排行榜,筛选出汽车加盟投资十大品牌。';
        }else if($category['id'] == 396){
            $Seo['Seo_Title'] = '金融平台代理招商加盟条件费用多少钱_金融加盟十大品牌排行榜TOP10-91创业网';
            $Seo['Seo_Keywords'] = '金融加盟,金融加盟费用,金融加盟多少钱,金融排行榜';
            $Seo['Seo_Description'] = '91创业网金融加盟频道为您汇集全国各地金融加盟项目,详细为您介绍各品牌金融项目加盟条件以及加盟费用,并为您总结了金融行业投资加盟项目排行榜,筛选出金融加盟投资十大品牌。';
        }else if($category['id'] == 420){
            $Seo['Seo_Title'] = '互联网创业项目代理加盟条件费用多少钱_零成本网络创业项目排行榜top10-91创业网';
            $Seo['Seo_Keywords'] = '互联网加盟,互联网加盟费用,互联网加盟多少钱,互联网排行榜';
            $Seo['Seo_Description'] = '91创业网互联网加盟频道为您汇集全国各地互联网加盟项目,详细为您介绍各品牌互联网项目加盟条件以及加盟费用,并为您总结了互联网行业投资加盟项目排行榜,筛选出互联网加盟投资十大品牌。';
        }else{
            $Seo['Seo_Title'] = $name.'连锁项目招商加盟条件费用多少钱_'.$name.'加盟十大品牌排行榜TOP10-91创业网';
            $Seo['Seo_Keywords'] = $name.'加盟，'.$name.'加盟费用，'.$name.'加盟多少钱，'.$name.'排行榜';
            $Seo['Seo_Description'] = '91创业网'.$name.'加盟频道为您汇集全国各地'.$name.'加盟项目,详细为您介绍各品牌'.$name.'项目加盟条件以及加盟费用,并为您总结了'.$name.'行业投资加盟项目排行榜,筛选出'.$name.'加盟投资十大品牌。';
        }

        $this->assign('seo',$Seo);


        if (\think\Request::instance()->isMobile()) {

            $areaModel = new AreaModel();
            //行业分类
            $hangye = $this->CategoryModel->getSonCate($path);

            //项目调用
            $Xm = $ProductModel->brand(['status'=>1,'arcrank'=>1,'typeid'=>['in',$sonIds]],'aid,typeid,title,class,logo,litpic,invested,companyname,sum',30,'click','desc');
            foreach ($Xm as $k=>$v){
                $Xm[$k]['cate_name'] = db('portal_category')->where(['id'=>$v['typeid']])->value('name');
                $Xm[$k]['cate_path'] = db('portal_category')->where(['id'=>$v['typeid']])->value('path');
            }
            $count = 30 - count($Xm);
            if(count($Xm) < 30){
                $Xm2 = $ProductModel->brand(['status'=>1,'arcrank'=>1],'aid,typeid,title,class,logo,litpic,invested,companyname,sum',$count,'aid','desc');
                foreach ($Xm2 as $k=>$v){
                    $Xm2[$k]['cate_name'] = db('portal_category')->where(['id'=>$v['typeid']])->value('name');
                    $Xm2[$k]['cate_path'] = db('portal_category')->where(['id'=>$v['typeid']])->value('path');
                }
                $Xm = array_merge($Xm,$Xm2);
            }
            //创业资讯
            $where25['parent_id'] = ['in','399,401,402,403,404,405,406,407,408,409,433'];
            $zixun = db('portal_post')->where($where25)->where('post_status = 1 and status = 1')->field('id,post_title,post_excerpt,thumbnail,published_time,class,create_time')->order('published_time desc')->limit(5)->select();


            //热门项目
            $arr = '2,1,4,5,7,10,3,6,8,9,312,313,396,420';
            $catess = db("portal_category")->where('id', 'in', $arr)->where('status = 1 and ishidden = 1')->field('id,name,path')->order('list_order asc')->select();
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
                $val['data'] = db("portal_xm")->where($wheres)->where($where3)->field('aid,title,invested,litpic,class')->order('pubdate asc')->limit(24)->select();
                $datas[] = $val;
            }


            $img = 'http://www.91chuangye.com';
            $this->assign('img',$img);
            $this->assign('zixun',$zixun);
            $this->assign('catess',$catess);
            $this->assign('datas',$datas);
            $this->assign('Xm',$Xm);
            $this->assign('type',$hangye);
            $this->assign('sys',$areaModel->allarea('(evalue MOD 500)=0'));

            return $this->fetch(':mobile/special');
        }else{
            //原行业分类
//            $hangye = $ProductModel->brand(['status'=>1,'arcrank'=>1,'typeid'=>['in',$sonIds]],'aid,title,class',50,'click','desc');
            if($category['parent_id'] == 0){
                $buxianPath = '';
                $buxian = 1;
                $hangye = $this->CategoryModel->allList(['parent_id'=>$category['id']],'id,name,path,parent_id',10);
            }else{
                $buxianPath = $this->CategoryModel->getOne(['id'=>$category['parent_id']],'id,name,path,parent_id');
                $buxian = 2;
                $hangye = $this->CategoryModel->allList(['parent_id'=>$category['parent_id']],'id,name,path,parent_id',10);
            }
            $this->assign('buxian',$buxian);
            $this->assign('buxianPath',$buxianPath);

            $ZtTdk = $this->CategoryModel->getOne(['id'=>$category['id']],'zt_title,zt_keywords,zt_description');
            $this->assign('ZtTdk',$ZtTdk);

            $Xm = $ProductModel->brand(['status'=>1,'arcrank'=>1,'typeid'=>['in',$sonIds]],'aid,title,class,logo,litpic,invested',51,'click','desc');
            $count = 51 - count($Xm);
            if(count($Xm) < 51){
                $Xm2 = $ProductModel->brand(['status'=>1,'arcrank'=>1],'aid,title,class,logo,litpic,invested',$count,'aid','desc');
                $Xm = array_merge($Xm,$Xm2);
            }
//print_r($category['parent_id']);die;
            if(($category['id'] == 3) || ($category['parent_id'] == 3)){
                $logo = 3;
            }else if(($category['id'] == 2) || ($category['parent_id'] == 2)){
                $logo = 2;
            }else{
                $logo = '';
            }
            $this->assign('logo',$logo);
//            print_r($ganxi);die;
            //品牌上榜字
            // $brandz = $ProductModel->brand(['status'=>1,'arcrank'=>1,'flag' => ['like', '%h%'],'typeid'=>['in',$sonIds]],'aid,title,class',4,'aid','desc');

            //品牌上榜图
            // $brandt = $ProductModel->brand(['status'=>1,'arcrank'=>1,'flag' => ['like', '%e%'],'typeid'=>['in',$sonIds],'logo'=>['neq',' ']],'aid,title,class,logo',4,'aid','desc');

            //精品推荐字
            // $boutiquez = $ProductModel->brand(['status'=>1,'arcrank'=>1,'flag' => ['like', '%a%'],'typeid'=>['in',$sonIds]],'aid,title,class',4,'aid','desc');

            //精品推荐图
            // $boutiquet = $ProductModel->brand(['status'=>1,'arcrank'=>1,'flag' => ['like', '%f%'],'typeid'=>['in',$sonIds],'logo'=>['neq',' ']],'aid,title,class,logo',4,'aid','desc');

            //排行榜
//            $topXm = $ProductModel->conditionArray(['typeid'=>['in',$sonIds]],'aid,title,invested,class',10,'click','desc');

            //模块2大图（左一）
            // $banner1 = $ProductModel->brand(['status'=>1,'arcrank'=>1,'flag' => ['like', '%b%'],'typeid'=>['in',$sonIds],'litpic'=>['neq',' ']],'aid,title,class,litpic',1,'aid','desc');


            //模块2大图（右四）
            // $banner2 = $ProductModel->brand(['status'=>1,'arcrank'=>1,'flag' => ['like', '%j%'],'typeid'=>['in',$sonIds],'litpic'=>['neq',' ']],'aid,title,class,litpic,invested',4,'aid','desc');


            //模块3（10个项目）
            $Modular = $ProductModel->conditionArray(['typeid'=>['in',$sonIds],'flag'=>['like','%d%'],'litpic'=>['neq',' ']],'aid,title,invested,class,litpic',10,'aid','desc');
            //最新项目推荐
            $NewsXm = $ProductModel->conditionArray(['typeid'=>['in',$sonIds],'litpic'=>['neq',' ']],'aid,title,invested,class,litpic',8,'update_time','desc');
            //热门推荐
            // $HotXm = $ProductModel->conditionArray(['typeid'=>['in',$sonIds],'flag'=>['like','%z%']],'aid,title,invested,class,litpic',12,'click','desc');
            $HotXm = $ProductModel->conditionArray(['flag'=>['like','%z%']],'aid,title,invested,class,litpic',12,'click','desc');
            //热点资讯推荐

//            if($category['id'] != 0){
                $ids = $ProductModel->GetxmId(['status'=>1,'arcrank'=>1,'typeid'=>$category['id'],'litpic'=>['neq',' ']],'click','desc','aid');
                $HotPost = $NewsModel->conditionarray(['did'=>['in',$ids]],'id,post_title,post_excerpt,thumbnail,class',6,'click','desc');
//                $Cate_Patn = $this->CategoryModel->getOne(['name'=>$category['name'].'资讯','parent_id'=>399],'path');
//            }


//            $ids = $NewsModel->NewsYi(['name'=>$category['name'].'资讯','parent_id'=>399]);
//            $HotPost = $NewsModel->conditionarray(['parent_id'=>['in',$ids]],'id,post_title,post_excerpt,thumbnail,class',6,'click','desc');
            if(empty($HotPost)){
                $ids = $NewsModel->NewsYi(['parent_id'=>['in','11,20,32,37,399']]);
                array_push($ids,'11','20','32','37','399');
                $HotPost = $NewsModel->conditionarray(['parent_id'=>['in',$ids]],'id,post_title,post_excerpt,thumbnail,class',6,'click','desc');
//                $Cate_Patn['path'] = 'news';
            }
            foreach ($HotPost as $key => $value) {
                $a = explode('/', $value['class']);
                if (in_array('news', $a)) {
                    $HotPost[$key]['class'] = 'news';
                } else {
                    $HotPost[$key]['class'] = $value['class'];
                }
            }

            //最新餐饮资讯
            $ida = $NewsModel->NewsYi(['parent_id'=>['in','399']]);
            array_push($ida,'399');
            $Newscanyin = $NewsModel->navNews(['parent_id'=>['in',$ida]],'id,post_title,post_excerpt,class,thumbnail,create_time','published_time','desc',5);
            foreach ($Newscanyin as $key => $value) {
                $a = explode('/', $value['class']);
                if (in_array('news', $a)) {
                    $Newscanyin[$key]['class'] = 'news';
                } else {
                    $Newscanyin[$key]['class'] = $value['class'];
                }
            }
            //创业知识
            $zhishiIds = $this->CategoryModel->getOneColumn(['parent_id' => 20], 'id');
            $zhishi = $NewsModel->conditionlist(['parent_id' => ['in', $zhishiIds]], 'id,class,post_title,post_excerpt,create_time,thumbnail', 5, 'published_time', 'desc');

            //创业之道
            $zhidao = $NewsModel->conditionlist(['parent_id' => ['in','32']], 'id,class,post_title,post_excerpt,create_time,thumbnail', 5, 'published_time', 'desc');

            //创业故事
            $gushi = $NewsModel->conditionlist(['parent_id' => ['in','11']], 'id,class,post_title,post_excerpt,create_time,thumbnail', 5, 'published_time', 'desc');

            //创业指南
            $zhishiIdsa = $this->CategoryModel->getOneColumn(['parent_id' => 37], 'id');
            $zhinan = $NewsModel->conditionlist(['parent_id' => ['in', $zhishiIdsa]], 'id,class,post_title,post_excerpt,create_time,thumbnail', 5, 'published_time', 'desc');


            //热门加盟行业
            $HotHy = $this->CategoryModel->allListArray(['parent_id'=>$category['id']],'id,name,path',32,'list_order','asc');

            //热门加盟项目
            $HotJmxm = $ProductModel->categroyData($category['id'],32);

            //品牌名称
            $where_not['aid'] = ['not in','119750,119350,119089,118566,114088,113567,113544,113537,113319,113226,113167,113153,113137,113126,113106,113097,112877,112769,112432,111952,111939,111522,111446,111435,111417,111308,111286,110994,110948,110894,110815,110754,110649,110606,110539,110472,110446,110409,110406,110303,110274,110054,109940,109883,109873,109871,109766,109688,109516,109350,109283,109273,109161,109005,108874,107705,107685,107676,107660,107551,107484,107259,107201,107100,107078,107005,107002,106974,106753,106752,106657,106643,106591,106448,106445,105784,105633,105436,105183,105145,105074,104940,104740,104643,104352,104351,104345,104271,104197,103775,103721,103490,103458,103259,103142,102970,102658,102143,101781,101659,101437,101318,101000,100878,100870,100529,100449,100321,100315,100273,100265,100245,100029,99874,99862,99766,99680,99627,99529,99526,99391,99385,99314,98778,98706,98398,98390,98275,98261,97833,97728,97651,97625,97454,97226,97100,97046,96860,95182,95174,94642,
94311,93875,93835,93682,93475,93455,93078,92401,92400,91815,89587,88678,88665,88636,87620,87612,87380,87294,87101,86923,86919'];
            $brandName = db('xiangmu_id')->where($where_not)->field('title,aid,class')->limit(300,300)->select();

            $youlian = db("flink")->where("typeid = 9999 and ischeck =1 ")->order("dtime desc")->limit(50)->select();


            //底部项目
            $arr = '2,312,8,10,5,4,7,313,9,1';
            $catess = db("portal_category")->where('id', 'in', $arr)->where('status = 1 and ishidden = 1')->field('id,name,path')->order('list_order asc')->select();
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
                $val['data'] = db("portal_xm")->where($wheres)->where($where3)->field('aid,title,invested,litpic,class')->order('pubdate asc')->limit(12)->select();
                $datas[] = $val;
            }
            //热门加盟地区
            $areaModel = new AreaModel();
            $HotAddress = $areaModel->allarea('(evalue MOD 500)=0');

            $this->assign('Xm',$Xm);
//            $this->assign('brandz',$brandz);
//            $this->assign('brandt',$brandt);
            $this->assign('hangye',$hangye);
//            $this->assign('boutiquez',$boutiquez);
//            $this->assign('boutiquet',$boutiquet);
//            $this->assign('topXm',$topXm);
//            $this->assign('banner1',$banner1);
//            $this->assign('banner2',$banner2);
            $this->assign('Modular',$Modular);
            $this->assign('NewsXm',$NewsXm);
            $this->assign('HotXm',$HotXm);
            $this->assign('datas',$datas);
            $this->assign('catess',$catess);
            $this->assign('HotPost',$HotPost);
            $this->assign('Newscanyin',$Newscanyin);
//            $this->assign('Cate_Patn',$Cate_Patn);
            $this->assign('zhinan', $zhinan);

            $this->assign('HotHy', $HotHy);
            $this->assign('HotJmxm', $HotJmxm);
            $this->assign('brandName', $brandName);
            $this->assign('youlian', $youlian);

            return $this->fetch(':special');
        }

    }
}