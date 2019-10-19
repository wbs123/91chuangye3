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
use think\cache\driver\Redis;
use app\portal\model\CategoryModel;
use app\portal\model\NewsModel;
use app\portal\model\ProductModel;
use think\Db;

class TopController extends HomeBaseController
{
    public function _initialize()
    {
        $this->CategoryModel = new CategoryModel();
        //底部
        $this->assign('dibu',$this->CategoryModel->allList(['parent_id'=>['in','52,53']]));
        //导航
        $cates1 = $this->CategoryModel->allListArray(['id'=>['in','2,5,8,396']],'name,id,path');
        foreach ($cates1 as $key=>$cate){
            $cates1[$key]['data'] = $this->CategoryModel->allList(['parent_id'=>$cate['id']],'name,id,path',40);
        }
        $cates2 = $this->CategoryModel->allListArray(['id'=>['in','10,312,4,7,9,313']],'name,id,path');
        foreach ($cates2 as $key=>$cate){
            $cates2[$key]['data'] = $this->CategoryModel->allList(['parent_id'=>$cate['id']],'name,id,path',6);
        }
        $cates3 = $this->CategoryModel->allListArray(['id'=>['in','420,1,3,6,339,734']],'name,id,path');
        foreach ($cates3 as $key=>$cate){
            $cates3[$key]['data'] = $this->CategoryModel->allList(['parent_id'=>$cate['id']],'name,id,path',6);
        }
        $this->assign(['cates1'=>$cates1,'cates2'=>$cates2,'cates3'=>$cates3]);

        if (\think\Request::instance()->isMobile()) {
            $this->assign('seo_url', 'http://www.91chuangye.com' . $this->request->url());
        }else{
            $this->assign('seo_url', 'http://m.91chuangye.com' . $this->request->url());
        }
    }
    //排行榜页面
    public function index()
    {
        $post =  $this->request->param();
        if(in_array($post['type'],['zonghe','year','month','week'])){
            $param['path'] = $post['type'];
            return $this->topall($param);
        }
        if(!empty($post['type'])){
            //匹配是否是排行榜综合页
            preg_match('/^(\w+)_(\w+)$/',$post['type'],$match);
            if(empty($match)){
                $path = 'top/'.$post['type'];
                $info = db('portal_category')->where("path = '$path'")->find();
                if(!isset($info)){
                    return $this->error1();
                }
                session('aid', $info['id']);
                $aid = session('aid');
                $category = db('portal_category')->where("parent_id = '$aid'")->find();
                if($category){
                    return $this->index_top($post);
                }else{
                    return $this->list_top($post);
                }
            }else{
                if($match[1] != 'list'){
                    //判断URL规则
                    $path = 'top/'.$match[1];
                    $info = db('portal_category')->where("path = '$path'")->find();
                    if(!isset($info)){
                        return $this->error1();
                    }elseif(!in_array($match[2],['zonghe','year','month','week'])){
                        return $this->error1();
                    }
                    $param = [
                        'path'=>$match[1],
                        'type'=>$match[2],
                    ];
                    return $this->topall($param);
                }
            }

        }
        $ProductModel = new ProductModel();
        $redis = new Redis();   //实例化
        if (\think\Request::instance()->isMobile()) {
            //栏目Top
            $cates_arr = $this->CategoryModel->allList(['parent_id'=>391,'id'=>['notin',['390','428']]],'id,name,path,mobile_thumbnail');
            //综合排行榜
            $zonghe = $ProductModel->cplist(['a.typeid'=>['in', '2,1,3,4,5,7']]);

            //单独调用模块
            $where1['aid'] = ['in', '75128,75136'];
            $lick1 = db('portal_xm')->where($where1)->orderRaw("field(aid,94500,119289)")->field('aid,class,title,litpic,thumbnail')->select();
            //餐饮排行榜
            $canyin = $ProductModel->categroyData(2,10);
            //服装排行榜
            $fuzhuang = $ProductModel->categroyData(1,10);
            //母婴排行榜
            $muying = $ProductModel->categroyData(8,10);
            //教育排行榜
            $jiaoyu = $ProductModel->categroyData(10,10);
            //相关文章
            $newsModel = new NewsModel();
            $news = $newsModel->conditionlist([],'id,post_title,class,published_time',9,'published_time','desc');
            //最新入驻商家
            $newsxm = $ProductModel->conditionlist([],'aid,class,title',15,'aid','desc');
            $seo = db('portal_category')->where('id = 391')->find();
            //图片调用路径
            $img = 'http://www.91chuangye.com';
            $this->assign('img', $img);
            $this->assign('seo', $seo);
            $this->assign('cates_arr', $cates_arr);
            $this->assign('zonghe', $zonghe);
            $this->assign('lick1', $lick1);
            $this->assign('canyin', $canyin);
            $this->assign('fuzhuang', $fuzhuang);
            $this->assign('muying', $muying);
            $this->assign('jiaoyu', $jiaoyu);
            $this->assign('news', $news);
            $this->assign('newsxm', $newsxm);
            return $this->fetch(':mobile/top');
        } else {
            //截取分类名称
            $url = $this->request->url();
            //页数
            preg_match('/list_(\d+).html/', $url, $matches);
            $page = count($matches)>0 ? $matches[1] : 1;
            if (false) {
                //取出缓存
                $seo = json_decode($redis->get('top_seo'), true);
                $website = json_decode($redis->get('top_website'), true);
                $hot = json_decode($redis->get('top_hot'), true);
                $youlian = json_decode($redis->get('top_youlian'), true);
                $zonghe = json_decode($redis->get('top_zonghe'), true);
                $tuijian = json_decode($redis->get('top_tuijian'), true);
                $data = json_decode($redis->get('top_data'), true);
                $datas = json_decode($redis->get('top_datas'), true);
                $lick3 = json_decode($redis->get('top_lick3'), true);
                $lick4 = json_decode($redis->get('top_lick4'), true);
                $lick5 = json_decode($redis->get('top_lick5'), true);
                $lick6 = json_decode($redis->get('top_lick6'), true);
            } else {
                //热门排行榜
                $hot = $ProductModel->conditionlist(['aid'=>['in', '75128,75136,76038,76221,77197,79114,92156,82626,119502,100944']],'aid,class,sum,litpic,address,title','','aid','desc');
                //综合排行榜
                $zonghe = $ProductModel->cplist(['a.typeid'=>['in', '2,1,3,4,5,7']]);
                //年度排行
                $lick3 = $ProductModel->cplist(['a.typeid'=>['in', '2,312,8']]);
                //本月排行
                $lick4 = $ProductModel->cplist(['a.typeid'=>['in', '6,362,265,57']]);
                //本周排行
                $lick5 = $ProductModel->cplist(['a.typeid'=>['in', '9,10,11,12']]);
                $data = $ProductModel->typelist(['id'=>['in','2,312,8,10,5,4,7,313,9,1,3,6']]);
                $datas = $ProductModel->typelist(['id'=>['in','2,312,8,10,5,4,7,313,9,1']],14,'pubdate','asc');
                //排行榜中间八个项目
                $lick6 = $ProductModel->centerEight();
                $tuijian = $ProductModel->conditionlist([],'aid,title,class',50,'aid','desc');
                $seo = db('portal_category')->where('id = 391')->find();
                $website = DB('website')->where(['id' => 1])->find();
                $youlian = db("flink")->where("typeid = 9999 and ischeck =1 ")->order("dtime desc")->limit(50)->select();


                //加入缓存
                $redis->set('top_flg', 1, 300);
                $redis->set('top_seo', json_encode($seo, JSON_UNESCAPED_UNICODE), 300);
                $redis->set('top_website', json_encode($website, JSON_UNESCAPED_UNICODE), 300);
                $redis->set('top_hot', json_encode($hot, JSON_UNESCAPED_UNICODE), 300);
                $redis->set('top_youlian', json_encode($youlian, JSON_UNESCAPED_UNICODE), 300);
                $redis->set('top_zonghe', json_encode($zonghe, JSON_UNESCAPED_UNICODE), 300);
                $redis->set('top_tuijian', json_encode($tuijian, JSON_UNESCAPED_UNICODE), 300);
                $redis->set('top_data', json_encode($data, JSON_UNESCAPED_UNICODE), 300);
                $redis->set('top_datas', json_encode($datas, JSON_UNESCAPED_UNICODE), 300);
                $redis->set('top_lick3', json_encode($lick3, JSON_UNESCAPED_UNICODE), 300);
                $redis->set('top_lick4', json_encode($lick4, JSON_UNESCAPED_UNICODE), 300);
                $redis->set('top_lick5', json_encode($lick5, JSON_UNESCAPED_UNICODE), 300);
                $redis->set('top_lick6', json_encode($lick6, JSON_UNESCAPED_UNICODE), 300);
            }
            //品牌排行榜
            $pinpai_cate = Db::name('portal_category')->where('parent_id = 391 and status = 1 and ishidden = 1 and id != 390')->column('id');
            $cate_array['parent_id'] = ['in',$pinpai_cate];
            $pinpai_top = Db::name('portal_category')->where($cate_array)->where("seo_description != ' '")->field('id,name,path,seo_description')->paginate(10,false,['query' =>request()->param(),'page'=>$page]);
//            echo Db::name('portal_category')->getLastSql();die;
            //总数量
            $count = $pinpai_top->total();
            //判断分页
//            print_r($pinpai_top->la);die;
            if(($page > $pinpai_top->lastPage()) && ($pinpai_top->lastPage() != 0)){
                return $this->error1();
            }

            $this->assign('count',$count);
            $this->assign('pinpai_top',$pinpai_top);
            $this->assign('seo', $seo);
            $this->assign('website', $website);
            $this->assign('hot', $hot);
            $this->assign('youlian', $youlian);
            $this->assign('zonghe', $zonghe);
            $this->assign('datas', $datas);
            $this->assign('tuijian', $tuijian);
            $this->assign('data', $data);
            $this->zuoce();
            $this->assign('lick3', $lick3);
            $this->assign('lick4', $lick4);
            $this->assign('lick5', $lick5);
            $this->assign('lick6', $lick6);
            return $this->fetch(':top');
        }
    }
    //排行榜二级页面
    public function index_top()
    {
        $ProductModel = new ProductModel();
        $post =  $this->request->param();
        $path = 'top/'.$post['type'];
        $catedata = $this->CategoryModel->getOne(['path'=>$path],'name,id');
        if(!$catedata){
            return $this->error1();
        }
        $id = $catedata['id'];
        $name = $catedata['name'];
        if (\think\Request::instance()->isMobile()) {

            //获取二级分类名称、图片等
            $cates = $this->CategoryModel->allListArray(['parent_id'=>$id,'id'=>['notin',['503','662','645','725','719']]],'name,path',30,'list_order','asc');
            foreach ($cates as $key => $value) {
                $mobile_pic = db('portal_category')->where(['name'=>$value['name'],'parent_id'=>['neq',$id]])->find();
                $cates[$key]['mobile_thumbnail'] = $mobile_pic['mobile_thumbnail'];
            }

            //栏目及数据
            $cates2 = $this->CategoryModel->allListArray(['parent_id'=>$id,'id'=>['notin',['503','662','645','725','719']]],'name,path',20,'list_order','asc');
            foreach($cates2 as $key=>$val)
            {
                $tcate = $this->CategoryModel->getOne(['name'=>$val['name'],'path'=>['neq',$val['path']]],'id');
                $val['data'] = $ProductModel->conditionlist(['typeid'=>$tcate['id']],'aid,class,litpic,click,sum,invested,title,typeid,description,logo',10,'click','desc');
                $data[] = $val;
            }

            //加盟项目TOP10
            $id = $this->CategoryModel->getOne(['name'=>$name,'parent_id'=>0],'id');
            $ids = $this->CategoryModel->getOneColumn(['parent_id'=>$id['id']],'id');
            $where['typeid'] =['in',$ids];
            $where['status'] = 1;
            $where['arcrank'] = 1;
            $lick1 = $ProductModel->conditionlist(['typeid'=>['in', $ids]],'aid,typeid,title,invested,litpic,description,class,logo',10,'aid','desc');
            //中间两个商品
            $where1['aid'] = ['in','75128,75136'];
            $lick2 = db('portal_xm')->where($where1)->orderRaw("field(aid,94500,119289)")->field('aid,class,title,litpic,thumbnail')->select();
            $newsModel = new NewsModel();
            //相关文章
            $newpath = 'news'.'/'.$post['type'];
            $newsid = db('portal_category')->where("path = '$newpath'")->value('id');
            //如果为空则取最新文章
            if(empty($newsid)){
                $AboutNews = $newsModel->conditionlist([],'id,post_title,class,published_time',9,'published_time','desc');
            }else{
                $AboutNews = $newsModel->conditionlist(['parent_id'=>$newsid],'id,post_title,class,published_time',9,'published_time','desc');
            }
            //新品入驻商家
            $newsxm = $ProductModel->conditionlist($where,'aid,typeid,title,invested,litpic,description,class',15,'aid','desc');
            if(empty($newsxm)){
                $newsxm = $ProductModel->conditionlist([],'aid,typeid,title,invested,litpic,description,class',15,'aid','desc');
            }
            $seo = db('portal_category')->where("path = '$path'")->find();
            //图片调用路径
            $img = 'http://www.91chuangye.com';
            $mobile_img = 'http://www.91chuangye.com/upload/';
            $this->assign('img',$img);
            $this->assign('mobile_img',$mobile_img);
            $this->assign('seo',$seo);
            $this->assign('name',str_replace('加盟','',$name));
            $this->assign('cates',$cates);
            $this->assign('lick1',$lick1);
            $this->assign('data0',isset($data[0]) ? $data[0] : []);
            $this->assign('data1',isset($data[1]) ? $data[1] : []);
            $this->assign('data2',isset($data[2]) ? $data[2] : []);
            $this->assign('data3',isset($data[3]) ? $data[3] : []);
            $this->assign('aboutnews',$AboutNews);
            $this->assign('newsxm',$newsxm);
            $this->assign('lick2',$lick2);
            return $this->fetch(":mobile/index_top");
        }else{

            //排行榜中间八个项目
//            $where17['aid'] = ['in','92383,78364,87182,119059,91803,118878,89574,86544'];
            $eight_id = Db::name('advertisement')->where(['type'=>4,'is_delete'=>2])->column('aid');
            $id = explode(',',$eight_id[0]);
            $where17 = ['aid'=>['in', $id]];
            $lick6 = db('portal_xm')->where($where17)->orderRaw("field(aid,$eight_id[0])")->field('aid,class,title,click,invested,litpic,sum,companyname,thumbnail')->select();

            //获取上级id = 0 的同名分类
            $fcate = $this->CategoryModel->getOne(['name'=>$name,'parent_id'=>0],'id,path');
            $ids = $this->CategoryModel->getOneColumn(['parent_id'=>$fcate['id']],'id');
            //年度排行
            $wh['typeid'] = ['in',$ids];
            $count = $ProductModel->getCount($wh);
            $pg = floor($count / 10);
            if($count > 10){
                $begin2 = $pg >= 2 ? 10 : rand(0,$count-10);
                $begin3 = $pg >= 3 ? 20 : rand(0,$count-10);
                $begin4 = $pg >= 4 ? 30 : rand(0,$count-10);
            }
            $lick1 = $ProductModel->cplist($wh,'0,10');
            $lick3 = $ProductModel->cplist($wh,''.$begin2.',10');
            $lick4 = $ProductModel->cplist($wh,''.$begin3.',10');
            $lick5 = $ProductModel->cplist($wh,''.$begin4.',10');

            //获取二级分类及分类下的项目
            $id = db("portal_category")->where("path = '$path'")->value('id');

            $cates = $this->CategoryModel->allListArray(['parent_id'=>$id,'id'=>['notin',['503','662','645','725','719']]],'name,path',12,'list_order','asc');

            foreach($cates as $key=>$val)
            {
                $tcate = $this->CategoryModel->getOne(['name'=>$val['name'],'path'=>['neq',$val['path']]],'id');
                $data = $ProductModel->conditionlist(['typeid'=>$tcate['id']],'aid,class,litpic,click,sum,invested,title,typeid,description,logo',10,'click','desc');
                if(count($data) == 10 ){
                    $cates[$key]['data'] = $data;
                }else{
                    unset($cates[$key]);
                }
            }

            $data = $cates;

            $cates2 = array_slice($cates,0,10);

            foreach($cates2 as $key=>$val)
            {
                $tcate = $this->CategoryModel->getOne(['name'=>$val['name'],'path'=>['neq',$val['path']]],'id');
                $val['data'] = $ProductModel->conditionlist(['typeid'=>$tcate['id']],'aid,class,litpic,click,sum,invested,title,typeid,description,logo',14,'click','desc');
                $data1[] = $val;
            }
            $tuijian = db('portal_xm')->where('status = 1 and arcrank = 1')->where(['typeid'=>$tcate['id']])->field('aid,title,class')->order('aid desc')->limit('50')->select();
            $patha = 'top/'.$post['type'];
            $seo = db('portal_category')->where("path = '$patha'")->find();

            //查询底部数据
            $website = DB('website')->where(['id' => 1])->find();
            $seo = db('portal_category')->where("path = '$patha'")->find();

            $this->assign('website',$website);
            $this->assign('seo',$seo);
            $this->assign('lick6',$lick6);
            $this->assign('tuijian',$tuijian);
            $this->assign('name',$name);
            $this->assign('lick1',$lick1);
            $this->assign('lick3',$lick3);
            $this->assign('lick4',$lick4);
            $this->assign('lick5',$lick5);
            $this->assign('data',$data);
            $this->assign('data1',isset($data1) ? $data1 : []);
            $this->assign('ids',$fcate);
            $this->assign('type',$post['type']);
            $this->zuoce();
            return $this->fetch(":index_top");
        }
    }
    //排行榜三级页面
    public function list_top()
    {
        $url = $_SERVER["QUERY_STRING"];
        if($url){
            $array = explode('/', $url);
            $key = '';
            foreach ($array as $k=>$v){
                if(strpos($v,'list_')  == 0){
                    $key = $k;
                }
            }
            $page = substr($array[$key], 5, 4);
        }else{
            $page = 1;
        }
        if(!$page){
            $page = 1;
        }
        if (\think\Request::instance()->isMobile()) {
            $post=$this->request->param();
            $path = 'top/'.$post['type'];
            if($path == 'top/yypxjm'){
                $path = 'yingyupeixunjiameng';
            }else if($path == 'top/blspxb'){
                $path = 'yishu';
            }
            //获取该分类下的数据
            $names = db('portal_category')->where("path = '$path'")->value('name');
            $ids = db('portal_category')->where("name = '$names' and parent_id != 0")->find();
            $data = db('portal_xm')->where('typeid = '.$ids['id'].' and status = 1 and arcrank = 1')->field('aid,typeid,title,litpic,sum,click,address,company_address,class,invested,description')->order('weight desc')->limit(10)->select();
            //中间两个商品
            $where1['aid'] = ['in','75128,75136'];
            $lick2 = db('portal_xm')->where($where1)->orderRaw("field(aid,75128,75136)")->field('aid,class,title,litpic')->select();
            //相关文章
            $newpath = 'news'.'/'.$post['type'];
            $newsid = db('portal_category')->where("path = '$newpath'")->value('id');
            //如果为空则取最新文章
            if(empty($newsid)){
                $AboutNews = db('portal_post')->where('post_status = 1 and status = 1')->field('id,post_title,class,published_time')->order('published_time desc')->limit(9)->select();
            }else{
                $AboutNews = db('portal_post')->where('parent_id = '.$newsid.' and status = 1 and post_status = 1')->field('id,post_title,class,published_time')->order('id desc')->limit(9)->select();
            }
            //新商户入驻
            $newsxm = db('portal_xm')->where('typeid = '.$ids['id'].' and status = 1 and arcrank = 1')->field('aid,typeid,class,title')->order('aid desc')->limit(15)->select();
            //$tdk = db('portal_category')->where("path = "."'$post[classname]' and status = 1 and ishidden = 1")->where('status = 1')->find();
            $tdk = db('portal_category')->where("path = '$path'")->find();
            $this->assign('AboutNews',$AboutNews);
            $this->assign('newsxm',$newsxm);
            $this->assign('names',str_replace('加盟','',$names));
            $this->assign('lick2',$lick2);
            $this->assign('data',$data);
            $this->assign('tdk',$tdk);
            return $this->fetch(":mobile/list_top");
        }else{
            $post =  $this->request->param();
            $path = 'top/'.$post['type'];
            if($path == 'top/yypxjm'){
                $path = 'yingyupeixunjiameng';
            }else if($path == 'top/blspxb'){
                $path = 'yishu';
            }
            $ProductModel = new ProductModel();

            //获取页面底部分类以及项目
            $arr = '2,312,8,10,5,4,7,313,9,1';
            $datas = $ProductModel->typelist(['id'=>['in','2,312,8,10,5,4,7,313,9,1']],14,'pubdate','asc');

            $names = db('portal_category')->where("path = '$path'")->value('name');
            //排行榜相关行业
            $cateid = db('portal_category')->where("path = '$path'")->value('parent_id');
            $xiangguan = db('portal_category')->where('parent_id = '.$cateid)->field('id,path,name')->limit(14)->select();
            $ids = db('portal_category')->where("name = '$names' and parent_id != 0")->find();
            //获取一级分类的名称
            $onename = db('portal_category')->where("id = ".$ids['parent_id'])->value('name');
            //获取相关项目以及分类名称
            $xmid = db('portal_category')->where('parent_id = '.$ids['parent_id'])->column('id');
            $arrer['typeid'] = $ids['id'];
            $arrer['status'] = 1;
            $arrer['arcrank'] = 1;

            $xgxm = db('portal_xm')->where($arrer)->field('aid,typeid,title,class')->limit(20)->order('pubdate desc')->select()->toArray();
            foreach ($xgxm as $k1=>$v1){
                $info = db('portal_category')->where('id = '.$v1['typeid'])->field('name,path')->find();
                $xgxm[$k1]['name'] = str_replace('加盟','',$info['name']);
                $xgxm[$k1]['path'] = $info['path'];
            }
            $name1 = db('portal_category')->where("id = ".$ids['id'])->value('name');
            $data = db('portal_xm')->where('typeid = '.$ids['id'].' and status = 1 and arcrank = 1')->field('aid,typeid,title,litpic,sum,click,address,company_address,class,invested,companyname,jieshao,nativeplace')->order('weight desc')->paginate(10,false,['page'=>$page]);
            $lick12 = $data->all();
            foreach ($lick12 as $k=>$v){
                $html = $this->cutArticle($v['jieshao'],220);
                $lick12[$k]['jieshao'] = $html;
                $name = db('portal_category')->where('id = '.$v['typeid'])->field('name,path')->find();
                $lick12[$k]['category1'] = str_replace('加盟','',$name['name']);
                $lick12[$k]['path1'] = $name['path'];
                if(isset($v['nativeplace']) && ($v['nativeplace']!='')){
                    $nativeplace = db('sys_enum')->where("evalue = ".$v['nativeplace']." and py != ''")->field("ename,py")->find();
                    $lick12[$k]['address'] = !empty($nativeplace['ename']) ? $nativeplace['ename'] : '';
                    $lick12[$k]['py'] = !empty($nativeplace['py']) ? $nativeplace['py'] : '';
                }else{
                    $lick12[$k]['address'] = '';
                    $lick12[$k]['py'] = '';
                }
            }

            $cates = db("portal_category")->where('id', 'in', $arr)->where('status = 1 and ishidden = 1')->field('id,parent_id,path,name')->order('list_order asc')->select();
            $cates_arr = $cates->all();
            foreach($cates_arr as $k=>$v){
                $path2 = db('portal_category')->where("name = "."'$v[name]' and parent_id != 0 and id != 119 and id != 274 and id != 66 and status = 1 and ishidden = 1")->value('path');
                $cates_arr[$k]['path2'] = $path2;
            }
            $lick7 = db("portal_post")->where('status = 1 and post_status = 1 and parent_id = 401')->field('id,post_title,class,published_time')->limit(10)->select()->toArray();

            foreach ($lick7 as $key => $value) {
                $a = explode('/', $value['class']);
                if(in_array('news', $a)){
                    $lick7[$key]['class'] = 'news';
                }else{
                    $lick7[$key]['class'] = $value['class'];
                }
            }


            $lick8 = db('portal_post')->where('status = 1 and post_status = 1 and parent_id = 401')->field('id,post_title,class,published_time')->limit(10,10)->select()->toArray();

            foreach ($lick8 as $key => $value) {
                $a = explode('/', $value['class']);
                if(in_array('news', $a)){
                    $lick8[$key]['class'] = 'news';
                }else{
                    $lick8[$key]['class'] = $value['class'];
                }
            }

            $tuijian = db('portal_xm')->where('status = 1 and arcrank = 1')->where(['typeid' => $ids['id']])->field('aid,title,class')->order('aid desc')->limit('50')->select();

            $tdk = db('portal_category')->where("path = '$path'")->find();
            $seo_name = str_replace('加盟','',$name1);
            if($tdk['parent_id'] == '427'){
                $tdk['seo_title'] = '互联网创业项目加盟排行榜_2019互联网创业项目代理十大品牌-91创业网';
                $tdk['seo_keywords'] = '互联网创业项目加盟排行榜,互联网创业项目代理十大品牌';
            }else if($tdk['parent_id'] == '386' || $tdk['parent_id'] == '387' || $tdk['parent_id'] == '426' ){
                $tdk['seo_title'] = $seo_name.'项目加盟排行榜_2019'.$seo_name.'加盟十大品牌-91创业网';
                $tdk['seo_keywords'] = $seo_name.'加盟排行榜,'.$seo_name.'加盟十大品牌';
            }else{
                $tdk['seo_title'] = $seo_name.'连锁店项目加盟排行榜_2019'.$seo_name.'加盟十大品牌-91创业网';
                $tdk['seo_keywords'] = $seo_name.'加盟排行榜,'.$seo_name.'加盟十大品牌';
            }

            //查询底部数据
            $website = DB('website')->where(['id' => 1])->find();
            $this->assign('website',$website);


            $this->zuoce();
            $this->assign('onename',str_replace('加盟','',$onename));
            $this->assign('name1',$name1);
            $this->assign('data',$data);
            $this->assign('lick12',$lick12);
            $this->assign('seo_name',$seo_name);
            $this->assign('datas',$datas);
            $this->assign('cates_arr',$cates_arr);
            $this->assign('xiangguan',$xiangguan);
            $this->assign('xgxm',$xgxm);
            $this->assign('lick7',$lick7);
            $this->assign('lick8',$lick8);
            $this->assign('tuijian',$tuijian);
            $this->assign('tdk',$tdk);
            return $this->fetch(":list_top");
        }
    }
    //综合页
    private function topall($param){
        if(empty($param)){
            return $this->error1();
        }
        $ProductModel = new ProductModel();
        $newsModel = new NewsModel();
        $this->CategoryModel = new CategoryModel();
        $passtype = ['zonghe','year','month','week'];
        //中间两个商品
        $where1['aid'] = ['in','75128,75136'];
        $lick2 = db('portal_xm')->where($where1)->orderRaw("field(aid,94500,119289)")->field('aid,class,title,litpic,thumbnail')->select();
        //相关文章
        $newpath = 'news'.'/'.$param['path'];
        $newsid = db('portal_category')->where("path = '$newpath'")->value('id');
        //如果为空则取最新文章
        if(empty($newsid)){
            $AboutNews = $newsModel->conditionlist([],'id,post_title,class,published_time',9,'published_time','desc');
        }else{
            $AboutNews = $newsModel->conditionlist(['parent_id'=>$newsid],'id,post_title,class,published_time',9,'published_time','desc');
        }
        //项目一级综合
        $name1 = '招商加盟';
        if(in_array($param['path'], $passtype) && (!isset($param['type']) || empty($param['type']))){
            switch ($param['path']) {
                case 'zonghe':
                    $where = ['a.typeid'=>['in', '2,1,3,4,5,7']];
                    $onename = '综合';
                    $seo['seo_title'] = '招商加盟项目热度综合排行榜_招商加盟项目热度综合前10名-91创业网';
                    $seo['seo_keywords'] = '招商加盟项目热度综合排行榜,招商加盟项目热度综合前10名';
                    $seo['seo_description'] = '91创业网(91chuangye.com)是一家提供创业加盟项目门户网站,为网友提供2019年十大品牌加盟排行榜加盟信息,汇集2019年10大品牌排行榜,打造优质的加盟平台,帮助创业者找到适合自己的加盟项目,让创业者轻松创业!';
                    break;
                case 'year':
                    $where = ['a.typeid'=>['in', '2,312,8']];
                    $onename = '年度';
                    $seo['seo_title'] = '招商加盟项目年度热门排行榜_招商加盟项目年度热门品牌前10名-91创业网';
                    $seo['seo_keywords'] = '招商加盟项目年度热门排行榜,招商加盟项目年度热门品牌前10名';
                    $seo['seo_description'] = '91创业网(91chuangye.com)是一家提供创业加盟项目门户网站,为网友提供2019年十大品牌【年度】加盟排行榜加盟信息,汇集2019年10大品牌年度排行榜,打造优质的加盟平台,帮助创业者找到适合自己的加盟项目,让创业者轻松创业!';
                    break;
                case 'month':
                    $where = ['a.typeid'=>['in', '6,362,265,57']];
                    $onename = '本月';
                    $seo['seo_title'] = '招商加盟项目月度热门排行榜_招商加盟项目月度热门品牌前10名-91创业网';
                    $seo['seo_keywords'] = '招商加盟项目月度热门排行榜_招商加盟项目月度热门品牌前10名';
                    $seo['seo_description'] = '91创业网(91chuangye.com)是一家提供创业加盟项目门户网站,为网友提供2019年十大品牌月度加盟排行榜加盟信息,汇集2019年10大品牌月度排行榜,打造优质的加盟平台,帮助创业者找到适合自己的加盟项目,让创业者轻松创业!';
                    break;
                default:
                    $where = ['a.typeid'=>['in', '9,10,11,12']];
                    $onename = '本周';
                    $seo['seo_title'] = '招商加盟项目本周热门项目排行榜_招商加盟项目本周热门品牌前10名-91创业网';
                    $seo['seo_keywords'] = '招商加盟项目本周热门项目排行榜,招商加盟项目本周热门品牌前10名';
                    $seo['seo_description'] = '91创业网(91chuangye.com)是一家提供创业加盟项目门户网站,为网友提供2019年十大品牌本周加盟排行榜加盟信息,汇集2019年10大品牌本周排行榜,打造优质的加盟平台,帮助创业者找到适合自己的加盟项目,让创业者轻松创业!';
                    break;
            }

            $data = $ProductModel->cplist($where);

            foreach ($data as $k=>$v){
                $html = $this->cutArticle($v['jieshao'],220);
                $data[$k]['jieshao'] = $html;
                $name = db('portal_category')->where('id = '.$v['typeid'])->field('name,path')->find();
                $data[$k]['category1'] = str_replace('加盟','',$name['name']);
                $data[$k]['path1'] = $name['path'];
                if(!empty($v['nativeplace']) && ($v['nativeplace']!='')){
                    $nativeplace = db('sys_enum')->where("evalue = ".$v['nativeplace']." and py != ''")->field("ename,py")->find();
                    $data[$k]['address'] = !empty($nativeplace['ename']) ? $nativeplace['ename'] : '';
                    $data[$k]['py'] = !empty($nativeplace['py']) ? $nativeplace['py'] : '';
                }else{
                    $data[$k]['address'] = '';
                    $data[$k]['py'] = '';
                }
            }

            //获取相关项目以及分类名称

            $xmid = db('portal_category')->where(['parent_id'=>['in','2,312,8,10,5,4,7,313,9,1']])->column('id');
            $arrer['typeid'] = ['in',$xmid];
            $arrer['status'] = 1;
            $arrer['arcrank'] = 1;
            $xgxm = db('portal_xm')->where($arrer)->field('aid,typeid,title,class')->limit(20)->order('pubdate desc')->select()->toArray();

            foreach ($xgxm as $k1=>$v1){
                $info = db('portal_category')->where('id = '.$v1['typeid'])->field('name,path')->find();
                $xgxm[$k1]['name'] = str_replace('加盟','',$info['name']);
                $xgxm[$k1]['path'] = $info['path'];
            }
            $tuijian = db('portal_xm as a')->where('a.status = 1 and a.arcrank = 1')->where($where)->field('aid,title,class')->order('aid desc')->limit('50')->select();

            $xiangguan = db('portal_category')->where(['parent_id'=>['in','2,312,8,10,5,4,7,313,9,1']])->field('id,path,name')->limit(14)->select();
            $tdk['seo_title'] = '';

        }else{
            if(!in_array($param['type'], $passtype)){
                return $this->error1();
            }
            $path = 'top/'.$param['path'];//分类
            $catedata = $this->CategoryModel->getOne(['path'=>$path],'name,id');
            if(empty($catedata)){
                return $this->error1();
            }
            $name1 = $catedata['name'];
            //获取上级id = 0 的同名分类
            $fcate = $this->CategoryModel->getOne(['name'=>$name1,'parent_id'=>0],'id,path');
            $ids = $this->CategoryModel->getOneColumn(['parent_id'=>$fcate['id']],'id');

            $cateid = db('portal_category')->where("path = '$path'")->value('parent_id');
            $xiangguan = db('portal_category')->where('parent_id = '.$cateid)->field('id,path,name')->limit(14)->select();

            $names = db('portal_category')->where("path = '$path'")->value('name');

            //排行榜相关行业
            $idsa = db('portal_category')->where("name = '$names'")->find();

            //获取一级分类的名称
            $onename = db('portal_category')->where("id = ".$idsa['parent_id'])->value('name');

            //获取相关项目以及分类名称
            $arrer['typeid'] = ['in',$ids];
            $arrer['status'] = 1;
            $arrer['arcrank'] = 1;

            $xgxm = db('portal_xm')->where($arrer)->field('aid,typeid,title,class')->limit(20)->order('pubdate desc')->select()->toArray();

            foreach ($xgxm as $k1=>$v1){
                $info = db('portal_category')->where('id = '.$v1['typeid'])->field('name,path')->find();
                $xgxm[$k1]['name'] = str_replace('加盟','',$info['name']);
                $xgxm[$k1]['path'] = $info['path'];
            }

            //年度排行
            $wh['typeid'] = ['in',$ids];
            switch ($param['type']) {
                case 'zonghe':
                    $data = $ProductModel->cplist($wh,'0,10');
                    $onename = '综合';
                    $seo['seo_title'] = ''.$name1.'热度综合排行榜_'.$name1.'项目热度综合前10名-91创业网';
                    $seo['seo_keywords'] = ''.$name1.'热度综合排行榜,'.$name1.'项目热度综合前10名';
                    $seo['seo_description'] = '91创业网(91chuangye.com)是一家提供创业加盟项目门户网站,为网友提供2019年十大品牌'.$name1.'加盟排行 榜加盟信息,汇集2019年10大'.$name1.'品牌排行榜,打造优质的加盟平台,帮助创业者找到适合自己的餐饮加盟项目,让创业者轻松创业!';
                    break;
                case 'year':
                    $data = $ProductModel->cplist($wh,'10,10');
                    $onename = '年度';
                    $seo['seo_title'] = ''.$name1.'年度热门项目排行榜_'.$name1.'年度热门十大品牌-91创业网';
                    $seo['seo_keywords'] = ''.$name1.'年度热门项目排行榜,'.$name1.'年度热门十大品牌';
                    $seo['seo_description'] = '91创业网(91chuangye.com)是一家提供创业加盟项目门户网站,为网友提供2019年十大品牌'.$name1.'年度加盟排行榜加盟信息,汇集2019年10大'.$name1.'品牌年度排行榜,打造优质的加盟平台,帮助创业者找到适合自己的餐饮加盟项目,让创业者轻松创业!';
                    break;
                case 'month':
                    $data = $ProductModel->cplist($wh,'20,10');
                    $onename = '本月';
                    $seo['seo_title'] = ''.$name1.'月度热门项目排行榜_'.$name1.'月度热门十大品牌-91创业网';
                    $seo['seo_keywords'] = ''.$name1.'月度热门项目排行榜,'.$name1.'月度热门十大品牌';
                    $seo['seo_description'] = '91创业网(91chuangye.com)是一家提供创业加盟项目门户网站,为网友提供2019年十大品牌'.$name1.'月度加盟排行 榜加盟信息,汇集2019年10大'.$name1.'品牌月度排行榜,打造优质的加盟平台,帮助创业者找到适合自己的餐饮加盟项目,让创业者轻松创业!';
                    break;
                default:
                    $data = $ProductModel->cplist($wh,'30,10');
                    $onename = '本周';
                    $seo['seo_title'] = ''.$name1.'本周热门项目排行榜_'.$name1.'本周热门品牌前10名-91创业网';
                    $seo['seo_keywords'] = ''.$name1.'本周热门项目排行榜,'.$name1.'本周热门品牌前10名';
                    $seo['seo_description'] = '91创业网(91chuangye.com)是一家提供创业加盟项目门户网站,为网友提供2019年十大品牌'.$name1.'本周加盟排行 榜加盟信息,汇集2019年10大'.$name1.'品牌本周排行榜,打造优质的加盟平台,帮助创业者找到适合自己的餐饮加盟项目,让创业者轻松创业!';
                    break;
            }

            foreach ($data as $k=>$v){
                $html = $this->cutArticle($v['jieshao'],220);
                $data[$k]['jieshao'] = $html;
                $name = db('portal_category')->where('id = '.$v['typeid'])->field('name,path')->find();
                $data[$k]['category1'] = str_replace('加盟','',$name['name']);
                $data[$k]['path1'] = $name['path'];
                if(!empty($v['nativeplace']) && ($v['nativeplace']!='')){
                    $nativeplace = db('sys_enum')->where("evalue = ".$v['nativeplace']." and py != ''")->field("ename,py")->find();
                    $data[$k]['address'] = !empty($nativeplace['ename']) ? $nativeplace['ename'] : '';
                    $data[$k]['py'] = !empty($nativeplace['py']) ? $nativeplace['py'] : '';
                }else{
                    $data[$k]['address'] = '';
                    $data[$k]['py'] = '';
                }
            }


            $wh = isset($wh) ? $wh : [];
            $tuijian = db('portal_xm as a')->where('a.status = 1 and a.arcrank = 1')->where($wh)->field('aid,title,class')->order('aid desc')->limit('50')->select();
        }

        //获取页面底部分类以及项目
        $arr = '2,312,8,10,5,4,7,313,9,1';
        $cates = db("portal_category")->where('id', 'in', $arr)->where('status = 1 and ishidden = 1')->field('id,parent_id,path,name')->order('list_order asc')->select();
        $cates_arr = $cates->all();
        foreach($cates_arr as $k=>$v){
            $path2 = db('portal_category')->where("name = "."'$v[name]' and parent_id != 0 and id != 119 and id != 274 and id != 66 and status = 1 and ishidden = 1")->value('path');
            $cates_arr[$k]['path2'] = $path2;
        }
        $datas = $ProductModel->typelist(['id'=>['in','2,312,8,10,5,4,7,313,9,1']],14,'pubdate','asc');
        $lick7 = db("portal_post")->where('status = 1 and post_status = 1 and parent_id = 401')->field('id,post_title,class,published_time')->limit(10)->select()->toArray();
        foreach ($lick7 as $key => $value) {
            $a = explode('/', $value['class']);
            if(in_array('news', $a)){
                $lick7[$key]['class'] = 'news';
            }else{
                $lick7[$key]['class'] = $value['class'];
            }
        }
        $lick8 = db('portal_post')->where('status = 1 and post_status = 1 and parent_id = 401')->field('id,post_title,class,published_time')->limit(10,10)->select()->toArray();
        foreach ($lick8 as $key => $value) {
            $a = explode('/', $value['class']);
            if(in_array('news', $a)){
                $lick8[$key]['class'] = 'news';
            }else{
                $lick8[$key]['class'] = $value['class'];
            }
        }
        $this->assign('tuijian',$tuijian);
        $this->assign('xgxm',$xgxm);
        $this->assign('datas',$datas);
        $this->assign('onename',$onename);
        $this->assign('lick7',$lick7);
        $this->assign('lick8',$lick8);
        $this->assign('xiangguan',$xiangguan);
        $this->assign('cates_arr',$cates_arr);
        $this->assign('name1',$name1);
        $this->assign('seo',$seo);
        $this->assign('AboutNews',$AboutNews);
        $this->assign('lick2',$lick2);
        $this->zuoce();
        $this->daohang();
        $this->dibu();
        $this->assign('data',$data);
        if (\think\Request::instance()->isMobile()) {
            return $this->fetch(":mobile/info_top");
        }else{
            return $this->fetch(":info_top");
        }

    }

    function cutArticle($data,$cut=120)
    {
        $str="…";
        $data=strip_tags($data);//去除html标记
        $pattern = "/&amp;[a-zA-Z]+;/";//去除特殊符号
        $data=preg_replace($pattern,"",$data);
        if(!is_numeric($cut)){
            return $data;
        }
        if($cut != 0){
            $data=mb_strimwidth($data,0,$cut,$str);
            return $data;
        }

    }

}