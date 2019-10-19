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
use app\portal\model\CategoryModel;
use app\portal\model\NewsModel;
use think\cache\driver\Redis;
use app\portal\model\ProductModel;
use app\portal\model\AreaModel;
use think\Request;
use think\Db;

class NewsController extends HomeBaseController
{
    public function _initialize()
    {
		$redis = new Redis();
		$this->daohang();
		$this->dibu();
        $this->CategoryModel = new CategoryModel();
        if (\think\Request::instance()->isMobile()) {
            $this->assign('seo_url', 'http://www.91chuangye.com' . $this->request->url());
        }else{
            $this->assign('seo_url', 'http://m.91chuangye.com' . $this->request->url());
        }
		//新闻导航
		if($redis->get('news_category')){
			$daohang = json_decode($redis->get('news_category'),true);
		}else{
			$daohang = $this->CategoryModel->allList(['parent_id' => 399], 'id,path,name','', 'list_order', 'asc')
            ->toArray();
			$redis->set('news_category',json_encode($daohang));
		}
        $this->assign("daohang",$daohang);
    }
    //文章列表页
    public function index()
    {

        $post = $this->request->param();
        //判断是否是详情页
        if(isset($post['type'])){
            if(is_numeric($post['type'])){
                return $this->article_news($post['type']);
            }
        }
		
        //截取分类名称
        $url = $this->request->url();
        $bagin = strpos($url,'/');
        $end  = strrpos($url,'/');
        $path = (substr_count($url,'/') == 1) ? substr($url,$bagin+1) : substr($url,$bagin+1,$end-1);
        if($path != 'news'){
            return $this->list_news($path,$url);
        }
        $cat = $this->CategoryModel->getOne(['path'=>$path],'id,parent_id,name,seo_title,seo_keywords,seo_description');

        $this->assign('seo_arr',$cat);
        $NewsModel = new NewsModel;
        $ProductModel = new ProductModel;

        if (\think\Request::instance()->isMobile()) {
            $banner = Db::name('advertisement')->where(['type'=>4,'is_delete'=>2,'source'=>2])->select();
            $this->assign('banner',$banner);

            //最新资讯
            $sonIds = $this->CategoryModel->getOneColumn(['parent_id' => 399], 'id');
            $news = $NewsModel->news(['parent_id' => ['in', $sonIds], 'flag' => ['like', '%p%']], 'id,parent_id,class,thumbnail,post_title,post_excerpt,published_time', 9,
                'published_time', 'desc');
            foreach ($news as $key => $value) {
                $a = explode('/', $value['class']);
                if(in_array('news', $a)){
                    $news[$key]['class'] = 'news';
                }else{
                    $news[$key]['class'] = $value['class'];
                }
            }
            $news1 = array_slice($news,0, 1);
            $news2 = array_slice($news,1, 8);

            //资讯导航内容//餐饮资讯
            $cyNews = $NewsModel->navNews(['parent_id'=>['in','401']],'id,parent_id,post_title,class,thumbnail,published_time,post_excerpt,create_time','published_time','desc','9,5');
            //教育资讯
            $jyNews = $NewsModel->navNews(['parent_id'=>['in','402']],'id,parent_id,post_title,class,thumbnail,published_time,post_excerpt,create_time','published_time','desc',5);
            //服装资讯
            $fzNews = $NewsModel->navNews(['parent_id'=>['in','404']],'id,parent_id,post_title,class,thumbnail,published_time,post_excerpt,create_time','published_time','desc',5);
            //家居资讯
            $jjNews = $NewsModel->navNews(['parent_id'=>['in','403']],'id,parent_id,post_title,class,thumbnail,published_time,post_excerpt,create_time','published_time','desc',5);

            //品牌专区
			$this->foot_hytj();

            //综合排行榜
            $zonghe = $ProductModel->cplist(['a.typeid'=>['in', '2,1,3,4,5,7']]);
            //页面中间两个独立项目
            $lick1 = db('portal_xm')->where(['aid'=>['in','75128,75136']])->orderRaw("field(aid,94500,119289)")->field('aid,class,title,litpic,thumbnail')->select();

			//资讯导航
			$arr = '2,1,4,5,7,10,3,6,8,9,312,313,396,420,339,734,742';
			$catess = $this->CategoryModel->allListArray(['id' => ['in', $arr]], 'id,parent_id,name,path', 18, 'list_order', 'asc');
			foreach ($catess as $k => $v) {
				$catess[$k]['name'] = str_replace('加盟', '', $v['name']);
				$catess[$k]['data'] = $this->CategoryModel->allListArray(['parent_id' => ['in', $v['id']]], 'id,parent_id,name,
			path', 21, 'list_order', 'asc');
			}
			$this->assign('catess', $catess);

            $this->assign('cyNews',$cyNews);
            $this->assign('jyNews',$jyNews);
            $this->assign('fzNews',$fzNews);
            $this->assign('jjNews',$jjNews);
            $this->assign('news1',$news1);
            $this->assign('news2',$news2);
            $this->assign('zonghe',$zonghe);
            $this->assign('lick1',$lick1);
            mackHtml($this->fetch(':mobile/news_list1'),'news',2);
            return $this->fetch(':mobile/news_list1');
        }else {
            //新闻整合页调用开始
            $sonIds = $this->CategoryModel->getOneColumn(['parent_id' => 399], 'id');
            $recommend = $NewsModel->conditionlist(['parent_id' => ['in', $sonIds]], 'id,class,thumbnail,post_title', 3,
                'click', 'desc')->toArray();
            foreach ($recommend as $key => $value) {
                $a = explode('/', $value['class']);
                if (in_array('news', $a)) {
                    $recommend[$key]['class'] = 'news';
                } else {
                    $recommend[$key]['class'] = $value['class'];
                }
            }
            //推荐三张图
            $this->assign('recommend', $recommend);

            //创业故事
            $gushi = $NewsModel->conditionlist(['parent_id' => 11], 'id,class,post_title', 9, 'published_time', 'desc');

            $this->assign('gushi', $gushi);

            //创业之道
            $zhidao = $NewsModel->conditionlist(['parent_id' => 32], 'id,class,thumbnail,post_title,post_excerpt,published_time', 5, 'published_time', 'desc');
            $this->assign('zhidao', $zhidao);

            //创业知识
            $zhishiIds = $this->CategoryModel->getOneColumn(['parent_id' => 20], 'id');
            $zhishi = $NewsModel->conditionlist(['parent_id' => ['in', $zhishiIds]], 'id,class,post_title,published_time', 8, 'published_time', 'desc');
            $this->assign('zhishi', $zhishi);

            $zhinanIds = $this->CategoryModel->getOneColumn(['parent_id' => 37], 'id');
            //创业指南
            $zhinan = Db::name('portal_post')->alias('a')->join('portal_category b', 'a.parent_id = b.id')->field('a.id,a.class,a.thumbnail,a.post_title,b.name')->limit(13)->where(['a.parent_id' => ['in', $zhinanIds]])->order('a.published_time desc')->select();
            $this->assign('zhinan', $zhinan);

            //热门产品
            $canyinIds = $this->CategoryModel->getOneColumn(['parent_id' => 2], 'id', 10);
            $aboutProduct = $ProductModel->conditionlist(['typeid' => ['in', $canyinIds]], 'class,aid,title,description,zhishu,invested,logo', 6, 'click', 'desc');
            $this->assign('aboutProduct', $aboutProduct);

            //最新资讯推荐
            $tuijian = $NewsModel->conditionlist(['parent_id' => ['in', $sonIds], 'flag' => ['like', '%p%']], 'id,class,thumbnail,post_title,post_excerpt,published_time', 8,
                'published_time', 'desc')->toArray();
            foreach ($tuijian as $key => $value) {
                $a = explode('/', $value['class']);
                if (in_array('news', $a)) {
                    $tuijian[$key]['class'] = 'news';
                } else {
                    $tuijian[$key]['class'] = $value['class'];
                }
            }
            $this->assign('tuijian', $tuijian);

            //十大排行榜
            $productTen = $ProductModel->typelist(['id' => ['in', 2]]);
            $this->assign('productten', $productTen);

            $canyinnews = $NewsModel->conditionlist(['parent_id' => 401], 'id,class,thumbnail,post_title,post_excerpt,published_time', 5, 'published_time', 'desc')->toArray();
            foreach ($canyinnews as $key => $value) {
                $a = explode('/', $value['class']);
                if (in_array('news', $a)) {
                    $canyinnews[$key]['class'] = 'news';
                } else {
                    $canyinnews[$key]['class'] = $value['class'];
                }
            }
            $this->assign('canyinnews', $canyinnews);

            //本周热门
            $benzhou = $ProductModel->conditionlist([], 'class,aid,title,description,zhishu,invested,litpic', 11, 'click', 'desc');
            $this->assign('benzhou', $benzhou);

            //热点资讯
            $hotnews = Db::name('portal_post')->alias('a')->join('portal_category b', 'a.parent_id = b.id')->where('a.status = 1 and a.post_status = 1')->field('a.id,a.class,a.thumbnail,a.post_title,b.name,a.published_time')->limit(8)->order('a.click desc')->select()->toArray();
            foreach ($hotnews as $key => $value) {
                $hotnews[$key]['path'] = $value['class'];
                $a = explode('/', $value['class']);
                if (in_array('news', $a)) {
                    $hotnews[$key]['class'] = 'news';
                } else {
                    $hotnews[$key]['class'] = $value['class'];
                }
            }
            $this->assign('hotnews', $hotnews);
            //精选品牌
            $jingxuan = $ProductModel->conditionlist(['flag' => ['like', '%c%']], 'class,aid,title,logo,invested', 14, 'click', 'desc');
            $this->assign('jingxuan', $jingxuan);
            //行业资讯
            $hangye = $this->CategoryModel->allListArray(['parent_id' => 399], 'id,name,path', 9, 'list_order', 'asc');
            foreach ($hangye as $k => $v) {
                if($v['id'] == 401){
                    $hangye[$k]['data'] = $NewsModel->conditionlist(['parent_id' => $v['id']], 'id,class,thumbnail,post_title,post_excerpt,published_time,update_time,create_time', '5,5',
                    'update_time', 'desc');
                }else{
                    $hangye[$k]['data'] = $NewsModel->conditionlist(['parent_id' => $v['id']], 'id,class,thumbnail,post_title,post_excerpt,published_time,update_time,create_time', 5,
                    'update_time', 'desc');
                }
                $b = $hangye[$k]['data']->toArray();
                foreach ($b as $key => $value) {
                    $a = explode('/', $value['class']);
                    if (in_array('news', $a)) {
                        $b[$key]['class'] = 'news';
                    } else {
                        $b[$key]['class'] = $value['class'];
                    }
                }
                $hangye[$k]['data'] = $b;
            }
            $this->assign('hangye', $hangye);
			//资讯导航
			$arr = '2,1,4,5,7,10,3,6,8,9,312,313,396,420,339,734,742';
			$catess = $this->CategoryModel->allListArray(['id' => ['in', $arr]], 'id,parent_id,name,path', 13, 'list_order', 'asc');
			foreach ($catess as $k => $v) {
				$catess[$k]['name'] = str_replace('加盟', '', $v['name']);
				$catess[$k]['data'] = $this->CategoryModel->allListArray(['parent_id' => ['in', $v['id']]], 'id,parent_id,name,
			path', 3, 'list_order', 'asc');
			}
			$this->assign('catess', $catess);
            $areaModel = new areaModel;
            $this->assign('area', $areaModel->allarea('(evalue MOD 500)=0'));
            $this->assign('cate', $this->CategoryModel->getCategory());
            $this->zuoce();
            mackHtml($this->fetch(':news_index'),'news');
            return $this->fetch(':news_index');
        }
    }
    public function list_news($path,$url){

        $redis = new Redis();
        //页数
        preg_match('/list_(\d+).html/', $url, $matches);
        $page = count($matches)>0 ? $matches[1] : 1;
        //获取栏目Id及名称
        $cat = $this->CategoryModel->getOne(['path'=>$path],'id,parent_id,name,seo_title,seo_keywords,seo_description');
        //不存在Path则404，but路由进不来
        if(!$cat){
            return $this->error1();
        }
        //创业加盟排行榜
        $name1 = str_replace('资讯','',$cat['name']);
        $name1 = str_replace('加盟','',$name1);

        //查询子类
        $ids = $this->CategoryModel->getOneColumn(['parent_id'=>$cat['id']],'id');
        array_unshift($ids,$cat['id']);

        //面包屑
        $array_reverse = $this->position($cat['id']);

        //实例化模型
        $NewsModel = new NewsModel();
        $ProductModel = new ProductModel();
        $id = $cat['id'];
        //查询文章
        $data = $NewsModel->pagelist(['parent_id' => ['in',$ids]],10,$page);
		$render = $data->render();
		$last = $data->lastPage();

        $this->assign('render',$render);
        if($page > $last){
            return $this->error1();exit;
        }

        $this->assign('seo_arr',$cat);
        if (\think\Request::instance()->isMobile()) {
            if(!file_exists(CMF_ROOT.'/m/news/index.html')){
                $this->makeWapnews();
            }
            $this->assign('data',$data);
            mackHtml($this->fetch(':mobile/listNews1'),$path,2);
            return $this->fetch(':mobile/listNews1');
        }else{
            if(!file_exists(CMF_ROOT.'/public/news/index.html')){
                $this->makePcnews();
            }
            //热门推荐
            if($redis->get('news_data1'.$id)){
                $data1 = json_decode($redis->get('news_data1'.$id),true);
            }else{
                $data1 = $NewsModel->conditionlist(['parent_id' => ['in',$ids],'flag'=>['like','%p%']],'id,class,thumbnail,post_title,post_excerpt,published_time',6,
                    'click','desc')
                    ->toArray();
                foreach ($data1 as $key => $value) {
                    $a = explode('/', $value['class']);
                    if(in_array('news', $a)){
                        $data1[$key]['class'] = 'news';
                    }else{
                        $data1[$key]['class'] = $value['class'];
                    }
                }
                $redis->set('news_data1'.$id,json_encode($data1));
            }

            //创业故事
            if($redis->get('news_data2'.$id)){
                $data2 = json_decode($redis->get('news_data2'.$id),true);
            }else{
                $data2 = $NewsModel->conditionlist(['parent_id'=>11],'id,class,thumbnail,post_title,post_excerpt,published_time',6,'published_time','desc');
                $redis->set('news_data2'.$id,json_encode($data2));
            }

            //创业问答
            if($redis->get('news_data3'.$id)){
                $data3 = json_decode($redis->get('news_data3'.$id),true);
            }else{
                $data3 = $NewsModel->conditionlist(['parent_id'=>392],'id,class,thumbnail,post_title,post_excerpt,published_time',6,'published_time','desc');
                $redis->set('news_data3'.$id,json_encode($data3));
            }

            //大家都在看
            if($redis->get('news_data4'.$id)){
                $data4 = json_decode($redis->get('news_data4'.$id),true);
            }else{
                $data4 = $NewsModel->conditionlist(['parent_id' => ['in',$ids]],'id,class,thumbnail,post_title,post_excerpt,published_time','6,16','click','desc')
                    ->toArray();
                if(!$data4){
                    //热门推荐当内容不存在时调用其他数据
                    $data4 = $NewsModel->conditionlist(['parent_id' => 401],'id,class,thumbnail,post_title,post_excerpt,published_time','6,16','click','desc')->toArray();
                }
                foreach ($data4 as $key => $value) {
                    $a = explode('/', $value['class']);
                    if(in_array('news', $a)){
                        $data4[$key]['class'] = 'news';
                    }else{
                        $data4[$key]['class'] = $value['class'];
                    }
                }
                $redis->set('news_data4'.$id,json_encode($data4));
            }


            //判断创业资讯/其他栏目的推荐项目-猜你喜欢
            $cateWhere = [];
            if($cat['parent_id'] == 399){
                $cateWhere = ['name'=>$name1.'加盟','parent_id'=>0];
                $category = $this->CategoryModel->getOne($cateWhere,'id,name,path');
                //没有对应栏目自动调用创业好项目
                $categoryId = empty($category) ? 63 : $category['id'];
                if($categoryId){
                    $sonIds = $this->CategoryModel->getOneColumn(['parent_id'=>$categoryId],'id');
                    array_push($sonIds,$categoryId);
                    $cateWhere =  ['typeid'=>['in',$sonIds]];
                    $topWhere = ['id'=>$categoryId];
                }
            }
            else{//没有相关栏目排行榜调用餐饮的
                $category = $this->CategoryModel->getOne(['id'=>2],'id,name,path');
                $categoryId = $category['id'];
                $topWhere = ['id'=>$categoryId];
            }

            //增加品牌推荐条件,['flag'=>['like','%a%']]
            if($redis->get('news_aboutProduct'.$id)){
                $aboutProduct = json_decode($redis->get('news_aboutProduct'.$id),true);
            }else{
                $aboutProduct = $ProductModel->conditionlist(array_merge($cateWhere),'class,aid,title,description,zhishu,invested,litpic',4,
                    'aid','desc');
                $redis->set('news_aboutProduct'.$id,json_encode($aboutProduct));
            }

            $this->assign('aboutProduct',$aboutProduct);

            //判断当前分类相关项目
            if($redis->get('news_aboutsix'.$id)){
                $aboutsix = json_decode($redis->get('news_aboutsix'.$id),true);
            }else{
                $aboutsix = $ProductModel->conditionlist($cateWhere,'class,aid,title,litpic',6,'pubdate','desc');
                $redis->set('news_aboutsix'.$id,json_encode($aboutsix));
            }

            $this->assign('aboutsix',$aboutsix);

            //十大排行榜
            if($redis->get('news_productTen'.$id)){
                $productTen = json_decode($redis->get('news_productTen'.$id),true);
            }else{
                $productTen = $ProductModel->typelist($topWhere);
                $redis->set('news_productTen'.$id,json_encode($productTen));
            }

            $this->assign('productten',$productTen);

            $this->assign('cate',$this->CategoryModel->getCategory());
            $this->assign(['name'=>$name1,'page'=>$page,'data'=>$data,'data1'=>$data1,
                'data2'=>$data2,'data3'=>$data3,'data4'=>$data4,'array_reverse'=>$array_reverse]);
            $this->zuoce();
			/*
            if($page == 1){
                mackHtml($this->fetch(':news_list'),$path);
            }
			*/
            return $this->fetch(':news_list');
        }
    }

    //资讯列表更多
    public function moreNews(){
        $post = $this->request->param();
        $page = $post['page'] * 10;
        $a = explode('/',$post['url']);
        $b = ['cy','jy','jj','fz','ls','qc','jr','nx','jc','ceshi'];
        if(!empty($a[2])){
            if(in_array($a[2],$b)){
               $path = $a[1].'/'.$a[2];
            }
        }else{
            $path = str_replace('/','',$post['url']);
        }
        $cate = $this->CategoryModel->categoryData(['path'=>$path]);
        $ids = Db::name('portal_category')->where(['parent_id'=>$cate['id']])->column('id');
        array_push($ids,$cate['id']);
        $data = Db::name('portal_post')->where(['parent_id'=>['in',$ids],'status'=>1,'post_status'=>1])->field('id,post_title,post_excerpt,published_time,thumbnail,class,status,post_status')->order('published_time desc')->limit($page,10)->select()->toArray();
        foreach ($data as $k=>$v){
            $url = geturl($v['id'],$v['class']);
            $data[$k]['href'] = $url;
            $data[$k]['timerImg'] = "/themes/simpleboot3/public/mobile/xin/images/3cd87f6675b141d9d3350de6078f1d61.png";
            $data[$k]['defaultImg'] = "/themes/simpleboot3/public/mobile/xin/images/44feb2a189bb6a55ade0a5349fcccfb2.jpg";
            $data[$k]['timer'] = date('Y-m-d',$v['published_time']);
            $data[$k]['img'] = checkImgurl($v['thumbnail']);
        }
        $datas = array('data'=>$data);
        echo json_encode($datas);
    }

    //文章详情页面
    public function article_news($id)
    {
        $redis = new Redis();
        $path = $this->request->url();
        $bagin = strpos($path,'/');
        $end  = strrpos($path,'/');
        $path = substr($path,$bagin+1,$end-1);

        $id = intval($id);
        //实例化NewsModel
        $NewsModel = new NewsModel();
        if($redis->get('newsdata'.$id)){
            $data = json_decode($redis->get('newsdata'.$id),true);
        }else{
            $data = $NewsModel->archives($id);
            $redis->set('newsdata'.$id,json_encode($data));
        }

        //如无数据则404
        if(!$data) return $this->error1();
        //判断目录是否和数据中的目录匹配
        if($path != 'news' && $path != $data['class']){
            return $this->error1();
        }
        //面包屑
        $array_reverse = $this->position($data['parent_id']);

        //获取上一页下一页
        $bc = db('portal_category')->where('id = '.$data['parent_id'])->field('id,parent_id,path,name')->find();
        $a = explode('/', $bc['path']);
        if(in_array('news', $a)){
            $newsid = db('portal_category')->where('parent_id = '.$bc['parent_id'])->field('id')->select()->toArray();
            $news_id = array_column($newsid,'id');
            $where_news['parent_id'] = ['in',$news_id];
            $where_news['status'] = 1;
            $where_news['post_status'] = 1;
            $news = db('portal_post')->where($where_news)->field('id')->order('published_time desc')->select()->toArray();
            $ida = array_column($news,'id');
            $key = $this->find_by_foreach($ida,$id);

            //上一页
            if(array_key_exists($key-1,$ida)){
                $where_last['id'] = $ida[$key-1];
                $lick4 = db('portal_post')->where($where_last)->where('status = 1 and post_status = 1')->find();
                $class = explode('/', $lick4['class']);
                if(in_array('news', $class)){
                    $lick4['class'] = 'news';
                }
            }else{
                $lick4 = '';
            }

            //下一页
            if(array_key_exists($key+1,$ida)){
                $where_next['id'] = $ida[$key+1];
                $lick5 = db('portal_post')->where($where_next)->where('status = 1 and post_status = 1')->find();
                $class = explode('/', $lick5['class']);
                if(in_array('news', $class)){
                    $lick5['class'] = 'news';
                }
            }else{
                $lick5 = '';
            }

        }else{
            if($bc['parent_id'] == 0){
                $newsid = db('portal_category')->where('parent_id = '.$bc['id'])->field('id')->select()->toArray();
                $news_id = array_column($newsid,'id');
                array_unshift($news_id,$bc['id']);
                $where_news['parent_id'] = ['in',$news_id];
                $where_news['status'] = 1;
                $where_news['post_status'] = 1;
                $cd = db('portal_post')->where($where_news)->field('id')->order('published_time desc')->select()->toArray();
            }else{
                $cd = db('portal_post')->where('parent_id = '.$bc['id'].' and status = 1 and post_status = 1')->order('published_time desc')->field('id')->select()->toArray();
            }

            //获取id的数组
            $de = array_column($cd,'id');
            //获取id所在的key
            $ef = $this->find_by_foreach($de,$id);
            //上一页
            if(array_key_exists($ef-1,$de)){
                $where_last['id'] = $de[$ef-1];
                $lick4 = db('portal_post')->where($where_last)->where('status = 1 and post_status = 1')->find();
            }else{
                $lick4 = '';
            }

            //下一页
            if(array_key_exists($ef+1,$de)){
                $where_next['id'] = $de[$ef+1];
                $lick5 = db('portal_post')->where($where_next)->where('status = 1 and post_status = 1')->find();
            }else{
                $lick5 = '';
            }

        }

        if (\think\Request::instance()->isMobile()) {
            if(!file_exists(CMF_ROOT.'/m/news/index.html')){
                $this->makeWapnews();
            }
            if(!empty($data['did'])){
                $lick2 = db('portal_post')->where('did = '.$data['did'].' and status = 1 and post_status = 1')->field('id,post_title,published_time,class')->order('id desc')->limit(6)->select();
                $xm = Db::name('portal_xm')->where(['aid'=>$data['did']])->field('aid,title,typeid,class')->find();

                $form = '咨询'.$xm['title'].'项目';
                $newsname = $xm['title'].'资讯';
                //猜你喜欢
                $like = Db::name('portal_xm')->where(['aid'=>$data['did'],'status'=>1,'arcrank'=>1])->field('aid,title,typeid,class,litpic')->order('update_time desc')->limit(4)->select();
                //关联项目
                $xiangmu = Db::name('portal_xm')->where(['aid'=>$data['did']])->field('logo,title,invested,aid,typeid,class,companyname')->find();

                $xiangmu['path'] = Db::name('portal_category')->where(['id'=>$xiangmu['typeid']])->value('path');
                $xiangmu['catename'] = Db::name('portal_category')->where(['id'=>$xiangmu['typeid']])->value('name');
            }else{
                $lick2 = db('portal_post')->where('status = 1 and post_status = 1 and parent_id = '.$data['parent_id'])->field('id,post_title,published_time,class')->order('id desc')->limit(6)->select();
                $form = '留言咨询';
                $newsname = '资讯推荐';
                //猜你喜欢
                $like = Db::name('portal_xm')->where(['status'=>1,'arcrank'=>1])->field('aid,title,typeid,class,litpic')->order('update_time desc')->limit(4)->select();
                $xiangmu = '';
            }
           
            $name = $this->CategoryModel->categoryData(['path'=>$path]);
            $this->assign('name',$name['name']);
            $this->assign('form',$form);
            $this->assign('news',$newsname);
            $this->assign('like',$like);
            $this->assign('xiangmu',$xiangmu);
            $this->assign('lick2',$lick2);
            $this->assign('data',$data);
            $this->assign('lick4',$lick4);
            $this->assign('lick5',$lick5);
            $this->assign('array_reverse',$array_reverse);
            return $this->fetch(':mobile/article_news1');
        }else{
            if(!file_exists(CMF_ROOT.'/public/news/index.html')){
                $this->makePcnews();
            }
            $ProductModel = new ProductModel();
            $cat = [];
            if($data['did']){
                $productData = $ProductModel->getone(['aid'=>$data['did']],'aid,title,typeid,logo,companyname,nativeplace,invested');
                //调用地区
                $productData['area'] = db('sys_enum')->where(['evalue'=>$productData['nativeplace']])->value("ename");
				$cat = $this->CategoryModel->getOne(['id'=>$productData['typeid']],'id,path,name,parent_id');
				$productData['path'] = $cat['path'];
                //调用当前项目栏目
                
                $this->assign('productdata',$productData);
                $this->assign('category',$cat);
            }else{
                $category = $this->CategoryModel->getOne(['id'=>$data['parent_id']],'id,name,parent_id');
                if($category['parent_id'] == 399){
                    $name1 = str_replace('资讯','',$category['name']);
                    $cateWhere = ['name'=>$name1,'parent_id'=>0];
                    $category = $this->CategoryModel->getOne($cateWhere,'id,name,path');
                    //没有对应栏目自动调用创业好项目
                    $cat['id'] = empty($category) ? 2 : $category['id'];
                    $cat['name'] = empty($category) ? '餐饮' : $category['name'];
                }
                $this->assign('productdata',['aid'=>'','path'=>'','title'=>'','logo'=>'','invested'=>'','area'=>'','companyname'=>'']);
                $this->assign('category',['name'=>'']);
            }
            $typeid = empty($cat['id']) ? 2 : $cat['id'];
            $name1 = empty($cat['name']) ? '餐饮' : $cat['name'];
            //查询子类
            $ids = $this->CategoryModel->getOneColumn(['parent_id'=>$typeid],'id');

            array_unshift($ids,$typeid);

            $tuijianWhere = $data['did'] ? ['did' => $data['did']] : ['parent_id'=>$data['parent_id']];

            //热门推荐
            if($redis->get('newsdata1'.$id)){
                $data1 = json_decode($redis->get('newsdata1'.$id),true);
            }else{
                $data1 = $NewsModel->conditionlist($tuijianWhere,'id,class,thumbnail,post_title,post_excerpt,published_time',6,
                    'id','desc')
                    ->toArray();
                foreach ($data1 as $key => $value) {
                    $a = explode('/', $value['class']);
                    if(in_array('news', $a)){
                        $data1[$key]['class'] = 'news';
                    }else{
                        $data1[$key]['class'] = $value['class'];
                    }
                }
                $redis->set('newsdata1'.$id,json_encode($data1));
            }

            //创业故事
            if($redis->get('newsdata2'.$id)){
                $data2 = json_decode($redis->get('newsdata2'.$id),true);
            }else{
                $data2 = $NewsModel->conditionlist(['parent_id'=>11],'id,class,thumbnail,post_title,post_excerpt,published_time',6,'published_time','desc')->toArray();
                $redis->set('newsdata2'.$id,json_encode($data2));
            }

            //创业问答
            if($redis->get('newsdata3'.$id)){
                $data3 = json_decode($redis->get('newsdata3'.$id),true);
            }else{
                $data3 = $NewsModel->conditionlist(['parent_id'=>392],'id,class,thumbnail,post_title,post_excerpt,published_time',6,'published_time','desc')->toArray();
                $redis->set('newsdata3'.$id,json_encode($data3));
            }


            //大家都在看
            if($redis->get('newsdata4'.$id)){
                $data4 = json_decode($redis->get('newsdata4'.$id),true);
            }else{
                $aids = $ProductModel->getColumn(['typeid'=>['in',$ids]],'aid');
                $data4 = $NewsModel->conditionlist(['did' => ['in',$aids]],'id,class,thumbnail,post_title,post_excerpt,
            published_time','16','click','desc')->toArray();
                if(!$data4){
                    //热门推荐当内容不存在时调用其他数据
                    $data4 = $NewsModel->conditionlist(['parent_id' => 401],'id,class,thumbnail,post_title,post_excerpt,published_time','6,16','click','desc')->toArray();
                }
                foreach ($data4 as $key => $value) {
                    $a = explode('/', $value['class']);
                    if(in_array('news', $a)){
                        $data4[$key]['class'] = 'news';
                    }else{
                        $data4[$key]['class'] = $value['class'];
                    }
                }
                $redis->set('newsdata4'.$id,json_encode($data4));
            }


            //判断创业资讯/其他栏目的推荐项目-猜你喜欢
            $topWhere = ['id'=>$typeid];

            $cateWhere = $data['did'] || $typeid ? ['typeid'=>['in',$ids]] : [];
            //增加品牌推荐条件,['flag'=>['like','%a%']]
            if($redis->get('aboutProduct'.$id)){
                $aboutProduct = json_decode($redis->get('aboutProduct'.$id),true);
            }else{
                $aboutProduct = $ProductModel->conditionlist($cateWhere,'class,aid,title,description,zhishu,invested,litpic',4,
                    'update_time','desc');
                $redis->set('aboutProduct'.$id,json_encode($aboutProduct));
            }

            $this->assign('aboutProduct',$aboutProduct);

            //判断当前分类相关项目
            if($redis->get('aboutsix'.$id)){
                $aboutsix = json_decode($redis->get('aboutsix'.$id),true);
            }else{
                $aboutsix = $ProductModel->conditionlist($cateWhere,'class,aid,title,litpic',6,'pubdate','desc');
                $redis->set('aboutsix'.$id,json_encode($aboutsix));
            }
            $this->assign('aboutsix',$aboutsix);

            //十大排行榜
            if($redis->get('productTen'.$id)){
                $productTen = json_decode($redis->get('productTen'.$id),true);
            }else{
                $productTen = $ProductModel->typelist($topWhere);
                $redis->set('productTen'.$id,json_encode($productTen));
            }

            $this->assign('productten',$productTen);

            //品牌专区
			$this->foot_hytj();

            $this->assign(['name'=>$name1,'data'=>$data,'data1'=>$data1,
                'data2'=>$data2,'data3'=>$data3,'data4'=>$data4,'array_reverse'=>$array_reverse]);
            $this->assign('lick4',$lick4);
            $this->assign('lick5',$lick5);
            $this->zuoce();
            return $this->fetch(':article_news1');
        }
    }

    public function find_by_foreach($array,$find)
    {
        foreach ($array as $key => $v)
        {
            if($v==$find)
            {
                return $key;
            }
        }
    }

    //下面方法生成静态页面所用，没来及改先这样^_^
    private function makeWapnews(){

        $cat = $this->CategoryModel->getOne(['path'=>'news'],'id,parent_id,name,seo_title,seo_keywords,seo_description');
        $this->assign('seo_arr',$cat);
        $NewsModel = new NewsModel;
        $ProductModel = new ProductModel;
        //最新资讯
        $sonIds = $this->CategoryModel->getOneColumn(['parent_id' => 399], 'id');


        $news = $NewsModel->news(['parent_id' => ['in', $sonIds], 'flag' => ['like', '%p%']], 'id,parent_id,class,thumbnail,post_title,post_excerpt,published_time', 9,
            'published_time', 'desc');
        foreach ($news as $key => $value) {
            $a = explode('/', $value['class']);
            if(in_array('news', $a)){
                $news[$key]['class'] = 'news';
            }else{
                $news[$key]['class'] = $value['class'];
            }
        }
        $news1 = array_slice($news,0, 1);
        $news2 = array_slice($news,1, 8);

        //资讯导航内容//餐饮资讯
        $cyNews = $NewsModel->navNews(['parent_id'=>['in','401']],'id,parent_id,post_title,class,thumbnail,published_time,post_excerpt','published_time','desc','9,5');
        //教育资讯
        $jyNews = $NewsModel->navNews(['parent_id'=>['in','402']],'id,parent_id,post_title,class,thumbnail,published_time,post_excerpt','published_time','desc',5);
        //服装资讯
        $fzNews = $NewsModel->navNews(['parent_id'=>['in','404']],'id,parent_id,post_title,class,thumbnail,published_time,post_excerpt','published_time','desc',5);
        //家居资讯
        $jjNews = $NewsModel->navNews(['parent_id'=>['in','403']],'id,parent_id,post_title,class,thumbnail,published_time,post_excerpt','published_time','desc',5);

        //综合排行榜
        $zonghe = $ProductModel->cplist(['a.typeid'=>['in', '2,1,3,4,5,7']]);
        //页面中间两个独立项目
        $lick1 = db('portal_xm')->where(['aid'=>['in','75128,75136']])->orderRaw("field(aid,94500,119289)")->field('aid,class,title,litpic,thumbnail')->select();

        //资讯导航
        $arr = '2,1,4,5,7,10,3,6,8,9,312,313,396,420,339,734,742';
        $catess = $this->CategoryModel->allListArray(['id' => ['in', $arr]], 'id,parent_id,name,path', 18, 'list_order', 'asc');
        foreach ($catess as $k => $v) {
            $catess[$k]['name'] = str_replace('加盟', '', $v['name']);
            $catess[$k]['data'] = $this->CategoryModel->allListArray(['parent_id' => ['in', $v['id']]], 'id,parent_id,name,
        path', 21, 'list_order', 'asc');
        }
        $this->assign('catess', $catess);

        $this->assign('cyNews',$cyNews);
        $this->assign('jyNews',$jyNews);
        $this->assign('fzNews',$fzNews);
        $this->assign('jjNews',$jjNews);
        $this->assign('news1',$news1);
        $this->assign('news2',$news2);
        $this->assign('zonghe',$zonghe);
        $this->assign('lick1',$lick1);
        mackHtml($this->fetch(':mobile/news_list1'),'news',2);

    }
    private function makePcnews(){
        $cat = $this->CategoryModel->getOne(['path'=>'news'],'id,parent_id,name,seo_title,seo_keywords,seo_description');
        $this->assign('seo_arr',$cat);
        $NewsModel = new NewsModel;
        $ProductModel = new ProductModel;
        //最新资讯
        $sonIds = $this->CategoryModel->getOneColumn(['parent_id' => 399], 'id');
        //新闻整合页调用开始
        $recommend = $NewsModel->conditionlist(['parent_id' => ['in', $sonIds]], 'id,class,thumbnail,post_title', 3,
            'click', 'desc')->toArray();
        foreach ($recommend as $key => $value) {
            $a = explode('/', $value['class']);
            if (in_array('news', $a)) {
                $recommend[$key]['class'] = 'news';
            } else {
                $recommend[$key]['class'] = $value['class'];
            }
        }
        //推荐三张图
        $this->assign('recommend', $recommend);

        //创业故事
        $gushi = $NewsModel->conditionlist(['parent_id' => 11], 'id,class,post_title', 9, 'published_time', 'desc');

        $this->assign('gushi', $gushi);

        //创业之道
        $zhidao = $NewsModel->conditionlist(['parent_id' => 32], 'id,class,thumbnail,post_title,post_excerpt,published_time', 5, 'published_time', 'desc');
        $this->assign('zhidao', $zhidao);

        //创业知识
        $zhishiIds = $this->CategoryModel->getOneColumn(['parent_id' => 20], 'id');
        $zhishi = $NewsModel->conditionlist(['parent_id' => ['in', $zhishiIds]], 'id,class,post_title,published_time', 8, 'published_time', 'desc');
        $this->assign('zhishi', $zhishi);

        $zhinanIds = $this->CategoryModel->getOneColumn(['parent_id' => 37], 'id');
        //创业指南
        $zhinan = Db::name('portal_post')->alias('a')->join('portal_category b', 'a.parent_id = b.id')->field('a.id,a.class,a.thumbnail,a.post_title,b.name')->limit(13)->where(['a.parent_id' => ['in', $zhinanIds]])->order('a.published_time desc')->select();
        $this->assign('zhinan', $zhinan);

        //热门产品
        $canyinIds = $this->CategoryModel->getOneColumn(['parent_id' => 2], 'id', 10);
        $aboutProduct = $ProductModel->conditionlist(['typeid' => ['in', $canyinIds]], 'class,aid,title,description,zhishu,invested,logo', 6, 'click', 'desc');
        $this->assign('aboutProduct', $aboutProduct);

        //最新资讯推荐
        $tuijian = $NewsModel->conditionlist(['parent_id' => ['in', $sonIds], 'flag' => ['like', '%p%']], 'id,class,thumbnail,post_title,post_excerpt,published_time', 8,
            'published_time', 'desc')->toArray();
        foreach ($tuijian as $key => $value) {
            $a = explode('/', $value['class']);
            if (in_array('news', $a)) {
                $tuijian[$key]['class'] = 'news';
            } else {
                $tuijian[$key]['class'] = $value['class'];
            }
        }
        $this->assign('tuijian', $tuijian);

        //十大排行榜
        $productTen = $ProductModel->typelist(['id' => ['in', 2]]);
        $this->assign('productten', $productTen);

        $canyinnews = $NewsModel->conditionlist(['parent_id' => 401], 'id,class,thumbnail,post_title,post_excerpt,published_time', 5, 'published_time', 'desc')->toArray();
        foreach ($canyinnews as $key => $value) {
            $a = explode('/', $value['class']);
            if (in_array('news', $a)) {
                $canyinnews[$key]['class'] = 'news';
            } else {
                $canyinnews[$key]['class'] = $value['class'];
            }
        }
        $this->assign('canyinnews', $canyinnews);

        //本周热门
        $benzhou = $ProductModel->conditionlist([], 'class,aid,title,description,zhishu,invested,litpic', 11, 'click', 'desc');
        $this->assign('benzhou', $benzhou);

        //热点资讯
        $hotnews = Db::name('portal_post')->alias('a')->join('portal_category b', 'a.parent_id = b.id')->where('a.status = 1 and a.post_status = 1')->field('a.id,a.class,a.thumbnail,a.post_title,b.name,a.published_time')->limit(8)->order('a.click desc')->select()->toArray();
        foreach ($hotnews as $key => $value) {
            $hotnews[$key]['path'] = $value['class'];
            $a = explode('/', $value['class']);
            if (in_array('news', $a)) {
                $hotnews[$key]['class'] = 'news';
            } else {
                $hotnews[$key]['class'] = $value['class'];
            }
        }
        $this->assign('hotnews', $hotnews);
        //精选品牌
        $jingxuan = $ProductModel->conditionlist(['flag' => ['like', '%c%']], 'class,aid,title,logo,invested', 14, 'click', 'desc');
        $this->assign('jingxuan', $jingxuan);
        //行业资讯
        $hangye = $this->CategoryModel->allListArray(['parent_id' => 399], 'id,name,path', 9, 'list_order', 'asc');
        foreach ($hangye as $k => $v) {
            if($v['id'] == 401){
				$hangye[$k]['data'] = $NewsModel->conditionlist(['parent_id' => $v['id']], 'id,class,thumbnail,post_title,post_excerpt,published_time,update_time,create_time', '5,5',
				'update_time', 'desc');
			}else{
				$hangye[$k]['data'] = $NewsModel->conditionlist(['parent_id' => $v['id']], 'id,class,thumbnail,post_title,post_excerpt,published_time,update_time,create_time', 5,
				'update_time', 'desc');
			}
            $b = $hangye[$k]['data']->toArray();
            foreach ($b as $key => $value) {
                $a = explode('/', $value['class']);
                if (in_array('news', $a)) {
                    $b[$key]['class'] = 'news';
                } else {
                    $b[$key]['class'] = $value['class'];
                }
            }
            $hangye[$k]['data'] = $b;
        }
        $this->assign('hangye', $hangye);
        //资讯导航
        $arr = '2,1,4,5,7,10,3,6,8,9,312,313,396,420,339,734,742';
        $catess = $this->CategoryModel->allListArray(['id' => ['in', $arr]], 'id,parent_id,name,path', 13, 'list_order', 'asc');
        foreach ($catess as $k => $v) {
            $catess[$k]['name'] = str_replace('加盟', '', $v['name']);
            $catess[$k]['data'] = $this->CategoryModel->allListArray(['parent_id' => ['in', $v['id']]], 'id,parent_id,name,
        path', 3, 'list_order', 'asc');
        }
        $this->assign('catess', $catess);
        $areaModel = new areaModel;
        $this->assign('area', $areaModel->allarea('(evalue MOD 500)=0'));
        $this->assign('cate', $this->CategoryModel->getCategory());
        $this->zuoce();
        mackHtml($this->fetch(':news_index'),'news');
    }
}



?>