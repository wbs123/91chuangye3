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
        $this->daohang();
		$this->dibu();
		$this->zuoce();
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
		/*
			综合，年，月，周
			if(in_array($post['type'],['zonghe','year','month','week'])){
				$param['path'] = $post['type'];
				return $this->topall($param);
			}
		*/
        if(!empty($post['type'])){
            //判断参数是否是分页
            preg_match('/list_(\d+)/', $post['type'], $matches);
            if(!$matches){
                $path = 'top/'.$post['type'];
                $id = db('portal_category')->where("path = '$path'")->value('id');
                if(!isset($id)){
                    return $this->error1();
                }
                $category = db('portal_category')->where("parent_id = '$id'")->find();
                if($category){
                    return $this->index_top($post);
                }else{
                    return $this->list_top($post);
                }
            }


            //匹配是否是排行榜综合页
            /*
            preg_match('/^(\w+)_(\w+)$/',$post['type'],$match);
            if(empty($match)){
                $path = 'top/'.$post['type'];
                $id = db('portal_category')->where("path = '$path'")->value('id');
                if(!isset($id)){
                    return $this->error1();
                }
                $category = db('portal_category')->where("parent_id = '$id'")->find();
                if($category){
                    return $this->index_top($post);
                }else{
                    return $this->list_top($post);
                }
            }
            else{
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
            */

        }
        $ProductModel = new ProductModel();
        $redis = new Redis();   //实例化

        //综合排行榜
        if($redis->get('phb_zonghe')){
            $zonghe = json_decode($redis->get('phb_zonghe'),true);
        }else{
            $zonghe = $ProductModel->cplist(['a.typeid'=>['in', '2,1,3,4,5,7']]);
            $redis->set('phb_zonghe',json_encode($zonghe));
        }
        $this->assign('zonghe', $zonghe);
        if (\think\Request::instance()->isMobile()) {
            //栏目Top
            if($redis->get('m_phb_cate')){
                $cates_arr = json_decode($redis->get('m_phb_cate'),true);
            }else{
                $cates_arr = $this->CategoryModel->allList(['parent_id'=>391,'id'=>['notin',['390','428']]],'id,name,path,mobile_thumbnail');
                $redis->set('m_phb_cate',json_encode($cates_arr));
            }
            //单独调用模块
            if($redis->get('m_phb_lick1')){
                $lick1 = json_decode($redis->get('m_phb_lick1'),true);
            }else{
                // $where1['aid'] = ['in', '75128,75136'];
                $topxm_aid = Db::name('advertisement')->where(['type'=>2,'is_delete'=>2,'source'=>2])->value('aid');
                $topxm_aid = explode(',', $topxm_aid);
                $where1['aid'] = ['in', $topxm_aid];
                $lick1 = db('portal_xm')->where($where1)->orderRaw("field(aid,94500,119289)")->field('aid,class,title,litpic,thumbnail')->select();
                $redis->set('m_phb_lick1',json_encode($lick1));
            }
            //餐饮排行榜
            if($redis->get('m_phb_canyin')){
                $canyin = json_decode($redis->get('m_phb_canyin'),true);
            }else{
                $canyin = $ProductModel->categroyData(2,10);
                $redis->set('m_phb_canyin',json_encode($canyin));
            }

            //服装排行榜
            if($redis->get('m_phb_fuzhuang')){
                $fuzhuang = json_decode($redis->get('m_phb_fuzhuang'),true);
            }else{
                $fuzhuang = $ProductModel->categroyData(1,10);
                $redis->set('m_phb_fuzhuang',json_encode($fuzhuang));
            }

            //母婴排行榜
            if($redis->get('m_phb_muying')){
                $muying = json_decode($redis->get('m_phb_muying'),true);
            }else{
                $muying = $ProductModel->categroyData(8,10);
                $redis->set('m_phb_muying',json_encode($muying));
            }

            //教育排行榜
            if($redis->get('m_phb_jiaoyu')){
                $jiaoyu = json_decode($redis->get('m_phb_jiaoyu'),true);
            }else{
                $jiaoyu = $ProductModel->categroyData(10,10);
                $redis->set('m_phb_jiaoyu',json_encode($jiaoyu));
            }

            //相关文章
            if($redis->get('m_phb_anews')){
                $news = json_decode($redis->get('m_phb_anews'),true);
            }else{
                $newsModel = new NewsModel();
                $news = $newsModel->conditionlist([],'id,post_title,class,published_time',9,'published_time','desc');
                $redis->set('m_phb_anews',json_encode($news));
            }

            //最新入驻商家
            if($redis->get('m_phb_shangjia')){
                $newsxm = json_decode($redis->get('m_phb_shangjia'),true);
            }else{
                $newsxm = $ProductModel->conditionlist([],'aid,class,title',15,'aid','desc');
                $redis->set('m_phb_shangjia',json_encode($newsxm));
            }

            $seo = db('portal_category')->where('id = 391')->find();

            $this->assign('seo', $seo);
            $this->assign('cates_arr', $cates_arr);
            $this->assign('lick1', $lick1);
            $this->assign('canyin', $canyin);
            $this->assign('fuzhuang', $fuzhuang);
            $this->assign('muying', $muying);
            $this->assign('jiaoyu', $jiaoyu);
            $this->assign('news', $news);
            $this->assign('newsxm', $newsxm);
            mackHtml($this->fetch(':mobile/top'),'top',2);
            return $this->fetch(':mobile/top');
        } else {

            //截取分类名称
            $url = $this->request->url();
            //页数
            preg_match('/list_(\d+).html/', $url, $matches);
            $page = count($matches)>0 ? $matches[1] : 1;
            //热门排行榜
            if($redis->get('phb_hot')){
                $hot = json_decode($redis->get('phb_hot'),true);
            }else{
                $hot = $ProductModel->conditionlist(['aid'=>['in', '75128,75136,76038,76221,77197,79114,92156,82626,119502,100944']],'aid,class,sum,litpic,address,title','','aid','desc');
                $redis->set('phb_hot',json_encode($hot));
            }
			//年度排行
            if($redis->get('phb_year')){
                $lick3 = json_decode($redis->get('phb_year'),true);
            }else{
                $lick3 = $ProductModel->cplist(['a.typeid'=>['in', '2,312,8']]);
                $redis->set('phb_year',json_encode($lick3));
            }
			//本月排行
            if($redis->get('phb_month')){
                $lick4 = json_decode($redis->get('phb_month'),true);
            }else{
                $lick4 = $ProductModel->cplist(['a.typeid'=>['in', '6,362,265,57']]);
                $redis->set('phb_month',json_encode($lick4));
            }
			//本周排行
            if($redis->get('phb_week')){
                $lick5 = json_decode($redis->get('phb_week'),true);
            }else{
                $lick5 = $ProductModel->cplist(['a.typeid'=>['in', '9,10,11,12']]);
                $redis->set('phb_week',json_encode($lick5));
            }
            //内容
            if($redis->get('phb_index_data')){
                $data = json_decode($redis->get('phb_index_data'),true);
            }else{
                $data = $ProductModel->typelist(['id'=>['in','2,312,8,10,5,4,7,313,9,1,3,6,420,743,742,339']]);
                $redis->set('phb_index_data',json_encode($data));
            }

			//品牌专区
			$this->foot_hytj();
			//排行榜中间八个项目
            if($redis->get('phb_centerEig')){
                $lick6 = json_decode($redis->get('phb_centerEig'),true);
            }else{
                $lick6 = $ProductModel->centerEight();
                $redis->set('phb_centerEig',json_encode($lick6));
            }
            //推荐项目
            if($redis->get('phb_tuijianPro')){
                $tuijian = json_decode($redis->get('phb_tuijianPro'),true);
            }else{
                $tuijian = $ProductModel->conditionlist([],'aid,title,class',50,'aid','desc');
                $redis->set('phb_tuijianPro',json_encode($tuijian));
            }
            //seo title
            if($redis->get('phb_index_seo')){
                $seo = json_decode($redis->get('phb_index_seo'),true);
            }else{
                $seo = db('portal_category')->where('id = 391')->find();
                $redis->set('phb_index_seo',json_encode($seo));
            }
            //友情链接
            if($redis->get('phb_index_youlian')){
                $youlian = json_decode($redis->get('phb_index_youlian'),true);
            }else{
                $youlian = db("flink")->where("typeid = 9999 and ischeck =1 ")->order("dtime desc")->limit(50)->select();
                $redis->set('phb_index_youlian',json_encode($youlian));
            }

            //品牌排行榜
            $pinpai_cate = Db::name('portal_category')->where('parent_id = 391 and status = 1 and ishidden = 1')->column('id');
			$cate_array['id'] = ['in',$pinpai_cate];
			$cate_arrayor['parent_id'] = ['in',$pinpai_cate];
			$pinpai_top = Db::name('portal_category')->where($cate_array)->whereOr($cate_arrayor)->field('id,name,path,seo_description')
				->order('id asc')->paginate(10,false,['query' =>request()->param(),'page'=>$page]);
			//总数量
			$count = $pinpai_top->total();
			$last = $pinpai_top->lastPage();
			$render = $pinpai_top->render();
			//判断分页
			if($page > $last){
				return $this->error1();
			}
            $this->assign('count',$count);
            $this->assign('pinpai_top',$pinpai_top);
            $this->assign('render',$render);
            $this->assign('seo', $seo);
            $this->assign('hot', $hot);
            $this->assign('youlian', $youlian);

            $this->assign('tuijian', $tuijian);
            $this->assign('data', $data);
            $this->assign('lick3', $lick3);
            $this->assign('lick4', $lick4);
            $this->assign('lick5', $lick5);
            $this->assign('lick6', $lick6);
            if($page == 1){
                mackHtml($this->fetch(':top'),'top');
            }
            return $this->fetch(':top');
        }
    }
    //排行榜二级页面
    public function index_top()
    {
		//截取分类名称
		$url = $this->request->url();
		//页数
		preg_match('/list_(\d+).html/', $url, $matches);
		$page = count($matches)>0 ? $matches[1] : 1;
		$redis = new Redis();
        $ProductModel = new ProductModel();
        $post =  $this->request->param();
        $path = 'top/'.$post['type'];
        $catedata = $this->CategoryModel->getOne(['path'=>$path],'name,id');
        if(!$catedata){
            return $this->error1();
        }
        $id = $catedata['id'];
        $name = $catedata['name'];
		$fcate = $this->CategoryModel->getOne(['name'=>$name,'parent_id'=>0],'id,path');
		//获取二级分类及分类下的项目
		if($redis->get('phb_two_data'.$id)){
			$data = json_decode($redis->get('phb_two_data'.$id),true);
		}else{
			$data = $this->CategoryModel->allListArray(['parent_id'=>$fcate['id'],'id'=>['notin',['503','662','645','725','719']]],'id,name,path',12,'list_order','asc');
			foreach($data as $key=>$val)
			{
				$cat = $ProductModel->conditionlist(['typeid'=>$val['id']],'aid,class,litpic,click,sum,invested,title,typeid,description,logo',10,'click','desc');
				if(count($cat) == 10 ){
					$data[$key]['data'] = $cat;
				}else{
					unset($data[$key]);
				}
			}
			$redis->set('phb_two_data'.$id,json_encode($data));
		}
		
		//seo title
		if($redis->get('phb_two_seo'.$id)){
			$seo = json_decode($redis->get('phb_two_seo'.$id),true);
		}else{
			$patha = 'top/'.$post['type'];
			$seo = db('portal_category')->where("path = '$patha'")->find();
			$redis->set('phb_two_seo'.$id,json_encode($seo));
		}
		$this->assign('seo',$seo);
        if (\think\Request::instance()->isMobile()) {
            if(!file_exists(CMF_ROOT.'/m/top/index.html')){
                $this->makeWaptop();
            }

            //获取二级分类名称、图片等
            $cates = $this->CategoryModel->allListArray(['parent_id'=>$id,'id'=>['notin',['503','662','645','725','719']]],'name,path',30,'list_order','asc');
            foreach ($cates as $key => $value) {
                $mobile_pic = db('portal_category')->where(['name'=>$value['name'],'parent_id'=>['neq',$id]])->find();
                $cates[$key]['mobile_thumbnail'] = $mobile_pic['mobile_thumbnail'];
            }

            //加盟项目TOP10
			if($redis->get('phb_two_lick1')){
                $lick1 = json_decode($redis->get('phb_two_lick1'.$id),true);
            }else{
				$ids = $this->CategoryModel->getOneColumn(['parent_id'=>$fcate['id']],'id');
				//年度排行
				$wh['typeid'] = ['in',$ids];
				$lick1 = $ProductModel->cplist($wh,'0,10');
                $redis->set('phb_two_lick1'.$id,json_encode($lick1));
            }
			
            //中间两个商品
			if($redis->get('m_phb_two_center'.$id)){
                $lick2 = json_decode($redis->get('m_phb_two_center'.$id),true);
            }else{
				// $where1['aid'] = ['in','75128,75136'];
                $topxm_aids = Db::name('advertisement')->where(['type'=>2,'is_delete'=>2,'source'=>2])->value('aid');
                $topxm_aid = explode(',', $topxm_aids);
                $where1['aid'] = ['in', $topxm_aid];
				$lick2 = db('portal_xm')->where($where1)->orderRaw("field(aid,$topxm_aids)")->field('aid,class,title,litpic,thumbnail')->select();
                $redis->set('m_phb_two_center'.$id,json_encode($lick2));
            }
            
            $newsModel = new NewsModel();
            //相关文章
			if($redis->get('m_phb_two_aboutnews'.$id)){
                $AboutNews = json_decode($redis->get('m_phb_two_aboutnews'.$id),true);
            }else{
				$newpath = 'news'.'/'.$post['type'];
				$newsid = db('portal_category')->where("path = '$newpath'")->value('id');
				//如果为空则取最新文章
				if(empty($newsid)){
					$AboutNews = $newsModel->conditionlist([],'id,post_title,class,published_time',9,'published_time','desc');
				}else{
					$AboutNews = $newsModel->conditionlist(['parent_id'=>$newsid],'id,post_title,class,published_time',9,'published_time','desc');
				}
                $redis->set('m_phb_two_aboutnews'.$id,json_encode($AboutNews));
            }
			
            //新品入驻商家
			if($redis->get('m_phb_two_newpro'.$id)){
                $newsxm = json_decode($redis->get('m_phb_two_newpro'.$id),true);
            }else{
				$newsxm = $ProductModel->conditionlist($wh,'aid,typeid,title,invested,litpic,description,class',15,'pubdate','desc');
				if(empty($newsxm)){
					$newsxm = $ProductModel->conditionlist([],'aid,typeid,title,invested,litpic,description,class',15,'pubdate','desc');
				}
                $redis->set('m_phb_two_newpro'.$id,json_encode($newsxm));
            }
            
            $this->assign('name',str_replace('加盟','',$name));
            $this->assign('cates',$cates);
            $this->assign('catescount',count($cates));
            $this->assign('lick1',$lick1);
            $this->assign('data0',isset($data[0]) ? $data[0] : []);
            $this->assign('data1',isset($data[1]) ? $data[1] : []);
            $this->assign('data2',isset($data[2]) ? $data[2] : []);
            $this->assign('data3',isset($data[3]) ? $data[3] : []);
            $this->assign('aboutnews',$AboutNews);
            $this->assign('newsxm',$newsxm);
            $this->assign('lick2',$lick2);
            mackHtml($this->fetch(':mobile/index_top'),$path,2);
            return $this->fetch(":mobile/index_top");
        }else{
            if(!file_exists(CMF_ROOT.'/public/top/index.html')){
                $this->makePctop();
            }
            //排行榜中间八个项目
            if($redis->get('phb_two_eight')){
                $lick6 = json_decode($redis->get('phb_two_eight'),true);
            }else{
                // $where17['aid'] = ['in','92383,78364,87182,119059,91803,118878,89574,86544'];
                $eight_id = Db::name('advertisement')->where(['type'=>4,'is_delete'=>2,'source'=>1])->column('aid');
                $id = explode(',',$eight_id[0]);
                $where17 = ['aid'=>['in', $id]];
				$lick6 = db('portal_xm')->where($where17)->orderRaw("field(aid,$eight_id[0])")->field('aid,class,title,click,invested,litpic,sum,companyname,thumbnail')->select();
                $redis->set('phb_two_eight',json_encode($lick6));
            }
            //获取上级id = 0 的同名分类
			if($redis->get('phb_two_lick1')){
                $lick1 = json_decode($redis->get('phb_two_lick1'.$id),true);
                $lick3 = json_decode($redis->get('phb_two_lick3'.$id),true);
                $lick4 = json_decode($redis->get('phb_two_lick4'.$id),true);
                $lick5 = json_decode($redis->get('phb_two_lick5'.$id),true);
            }else{
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
                $redis->set('phb_two_lick1'.$id,json_encode($lick1));
                $redis->set('phb_two_lick3'.$id,json_encode($lick3));
                $redis->set('phb_two_lick4'.$id,json_encode($lick4));
                $redis->set('phb_two_lick5'.$id,json_encode($lick5));
            }
            
			
			//品牌专区
			$this->foot_hytj();
			
			//推荐项目
            if($redis->get('phb_two_tuijianPro'.$id)){
                $tuijian = json_decode($redis->get('phb_two_tuijianPro'.$id),true);
            }else{
                $tuijian = $ProductModel->conditionlist(['typeid'=>$fcate['id']],'aid,title,class',50,'aid','desc');
                $redis->set('phb_two_tuijianPro'.$id,json_encode($tuijian));
            }
			//品牌排行榜
            if($redis->get('phb_two_pinpai_'.$id.'_datas'.$page)){
                $pinpai_top = json_decode($redis->get('phb_two_pinpai_'.$id.'_datas'.$page),true);
                $count = json_decode($redis->get('phb_two_pinpai_'.$id.'_count'));
                $last = json_decode($redis->get('phb_two_pinpai_'.$id.'_lastpage'));
                $render = json_decode($redis->get('phb_two_pinpai_'.$id.'_render'.$page));
                $pinpai_top = $pinpai_top['data'];
            }else{
                $pinpai_cate = Db::name('portal_category')->where('parent_id = '.$id.' and status = 1 and ishidden = 1')->column('id');
                $cate_array['id'] = ['in',$pinpai_cate];
                $cate_arrayor['parent_id'] = ['in',$pinpai_cate];
                $pinpai_top = Db::name('portal_category')->where($cate_array)->whereOr($cate_arrayor)->field('id,name,path,seo_description')
                    ->order('id asc')->paginate(10,false,['query' =>request()->param(),'page'=>$page]);
                //总数量
                $count = $pinpai_top->total();
                $last = $pinpai_top->lastPage();
                $render = $pinpai_top->render();
                $redis->set('phb_two_pinpai_'.$id.'_render'.$page,json_encode($render));
                $redis->set('phb_two_pinpai_'.$id.'_datas'.$page,json_encode($pinpai_top));
                $redis->set('phb_two_pinpai_'.$id.'_count',json_encode($count));
                $redis->set('phb_two_pinpai_'.$id.'_lastpage',json_encode($last));
            }
			//判断分页
			if($page > $last){
				return $this->error1();
			}
			$this->assign('count',$count);
            $this->assign('pinpai_top',$pinpai_top);
            $this->assign('render',$render);
			
            $this->assign('lick6',$lick6);
            $this->assign('tuijian',$tuijian);
            $this->assign('name',$name);
            $this->assign('lick1',$lick1);
            $this->assign('lick3',$lick3);
            $this->assign('lick4',$lick4);
            $this->assign('lick5',$lick5);
            $this->assign('data',$data);
            $this->assign('ids',$fcate);
            $this->assign('type',$post['type']);
            if($page == 1){
                mackHtml($this->fetch(':index_top'),$path);
            }
            return $this->fetch(":index_top");
        }
		
    }
    //排行榜三级页面
   public function list_top()
   {
        //截取分类名称
		$redis = new Redis();
		$post =  $this->request->param();
		$path = 'top/'.$post['type'];
		if($path == 'top/yypxjm'){
			$path = 'yingyupeixunjiameng';
		}else if($path == 'top/blspxb'){
			$path = 'yishu';
		}
		$ProductModel = new ProductModel();
		//当前栏目信息
		$cat = $this->CategoryModel->getOne(['path'=>$path],'id,name,seo_keywords,seo_description,parent_id');
		$this->assign('cat',$cat);
        $names = $cat['name'];
        $id = $cat['id'];
		//父及栏目
        $onename = $this->CategoryModel->getOneValue(['id'=>$cat['parent_id']],'name');
		//同名项目库ID
		$caseId = $this->CategoryModel->getOneValue(['name'=>$names,'parent_id'=>['neq',$cat['parent_id']]],'id');
		//新品入住商家
		if($redis->get('phb_thre_xgxm'.$id)){
			$xgxm = json_decode($redis->get('phb_thre_xgxm'.$id),true);
		}else{
			$xgxm = $ProductModel->conditionlist(['typeid'=>$caseId],'aid,typeid,title,class',30,'aid','desc');
			$redis->set('phb_thre_xgxm'.$id,json_encode($xgxm));
		}
		//排行榜内容
		if($redis->get('phb_thre_lick12'.$id)){
			$lick12 = json_decode($redis->get('phb_thre_lick12'.$id),true);
		}else{
			$lick12 = $ProductModel->conditionArray(['typeid'=>$caseId],'aid,typeid,title,litpic,sum,click,address,companyname,class,invested,jieshao,nativeplace',10,'weight','asc');
			$aids = [];
			foreach ($lick12 as $k=>$v){
				$aids[] = $v['aid'];
				$data = str_replace([' ','&nbsp;','　'],'',htmlspecialchars_decode(strip_tags($v['jieshao'])));
				$lick12[$k]['jieshao'] = msubstr($data,0,90);
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
			$redis->set('phb_thre_lick12'.$id,json_encode($lick12));
		}
		//tdk
		$tdk = db('portal_category')->where("path = '$path'")->find();
		$seo_name = str_replace('加盟','',$names);
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
		$this->assign('tdk',$tdk);
		//品牌资讯
		$names2 = $names;
		$newsModel = new NewsModel();
		if($redis->get('phb_thre_names2'.$id)){
			$names2 = json_decode($redis->get('phb_thre_names2'.$id),true);
		}
		if($redis->get('phb_thre_lick7'.$id)){
			$lick7 = json_decode($redis->get('phb_thre_lick7'.$id),true);
		}else{
			$lick7 = $newsModel->conditionArray(['parent_id'=>['in',$aids]],'id,post_title,class,published_time',10,'published_time','desc');
			if(empty($lick7)){
				$redis->set('phb_thre_names2'.$id,'餐饮加盟');
				$lick7 = $newsModel->conditionArray(['parent_id'=>401],'id,post_title,class,published_time',10,'published_time','desc');
			}
			foreach ($lick7 as $key => $value) {
			   $a = explode('/', $value['class']);
				if(in_array('news', $a)){
					$lick7[$key]['class'] = 'news';
				}else{
					$lick7[$key]['class'] = $value['class'];
				}
			}
			$redis->set('phb_thre_lick7'.$id,json_encode($lick7));
		}
        if (\think\Request::instance()->isMobile()) {
            if(!file_exists(CMF_ROOT.'/m/top/index.html')){
                $this->makeWaptop();
            }
            //中间两个商品
            $where1['aid'] = ['in','75128,75136'];
            $lick2 = db('portal_xm')->where($where1)->orderRaw("field(aid,75128,75136)")->field('aid,class,title,litpic')->select();
			
            $this->assign('AboutNews',$lick7);
            $this->assign('newsxm',$xgxm);
            $this->assign('names',$seo_name);
            $this->assign('lick2',$lick2);
            $this->assign('data',$lick12);
            mackHtml($this->fetch(':mobile/list_top'),$path,2);
            return $this->fetch(":mobile/list_top");
        }else{
            if(!file_exists(CMF_ROOT.'/public/top/index.html')){
                $this->makePctop();
            }
            //品牌专区
			$this->foot_hytj();
			
            //排行榜相关行业	
			if($redis->get('phb_thre_xiangguan'.$id)){
                $xiangguan = json_decode($redis->get('phb_thre_xiangguan'.$id),true);
            }else{
                $xiangguan = $this->CategoryModel->allListArray(['parent_id'=>$cat['parent_id']],'id,path,name',27);
                $redis->set('phb_thre_xiangguan'.$id,json_encode($xiangguan));
            }														
            //品牌排行榜
			if($redis->get('phb_thre_pinpai'.$id)){
                $pinpai_top = json_decode($redis->get('phb_thre_pinpai'.$id),true);
            }else{
                $pinpai_cate = $this->CategoryModel->getOneColumn(['parent_id'=>$cat['parent_id']],'id');
				$cate_array['id'] = ['in',$pinpai_cate];
				$cate_arrayor['parent_id'] = ['in',$pinpai_cate];
				$pinpai_top = Db::name('portal_category')->where($cate_array)->whereOr($cate_arrayor)->field('id,name,path')
					->order('list_order asc')->limit(10)->select();
                $redis->set('phb_thre_pinpai'.$id,json_encode($pinpai_top));
            }
			
			//推荐项目
            if($redis->get('phb_thre_tuijianPro'.$id)){
                $tuijian = json_decode($redis->get('phb_thre_tuijianPro'.$id),true);
            }else{
                $tuijian = $ProductModel->conditionlist(['typeid' => $caseId],'aid,title,class',50,'click','desc');
                $redis->set('phb_thre_tuijianPro'.$id,json_encode($tuijian));
            }

            $this->assign('name1',$names);
            $this->assign('name2',$names2);
            $this->assign('onename',$onename);
            $this->assign('lick12',$lick12);
            $this->assign('seo_name',$seo_name);
            $this->assign('xiangguan',$xiangguan);
            $this->assign('xgxm',$xgxm);
            $this->assign('lick7',$lick7);
            $this->assign('pinpai_top',$pinpai_top);
            $this->assign('tuijian',$tuijian);
            mackHtml($this->fetch(':list_top'),$path);
            return $this->fetch(":list_top");
        }
    }
    //下面方法生成静态页面所用，没来及改先这样^_^
    private function makeWaptop(){
        $ProductModel = new ProductModel();
        //综合排行榜
        $zonghe = $ProductModel->cplist(['a.typeid'=>['in', '2,1,3,4,5,7']]);
        $this->assign('zonghe', $zonghe);

        //栏目Top
        $cates_arr = $this->CategoryModel->allList(['parent_id'=>391,'id'=>['notin',['390','428']]],'id,name,path,mobile_thumbnail');

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

        $this->assign('seo', $seo);
        $this->assign('cates_arr', $cates_arr);
        $this->assign('lick1', $lick1);
        $this->assign('canyin', $canyin);
        $this->assign('fuzhuang', $fuzhuang);
        $this->assign('muying', $muying);
        $this->assign('jiaoyu', $jiaoyu);
        $this->assign('news', $news);
        $this->assign('newsxm', $newsxm);
        mackHtml($this->fetch(':mobile/top'),'top',2);

    }
    private function makePctop(){
        $ProductModel = new ProductModel();
        //综合排行榜
        $zonghe = $ProductModel->cplist(['a.typeid'=>['in', '2,1,3,4,5,7']]);
        $this->assign('zonghe', $zonghe);
        $page =  1;
        //热门排行榜
        $hot = $ProductModel->conditionlist(['aid'=>['in', '75128,75136,76038,76221,77197,79114,92156,82626,119502,100944']],'aid,class,sum,litpic,address,title','','aid','desc');
        $lick3 = $ProductModel->cplist(['a.typeid'=>['in', '2,312,8']]);
        //本月排行
        $lick4 = $ProductModel->cplist(['a.typeid'=>['in', '6,362,265,57']]);
        //本周排行
        $lick5 = $ProductModel->cplist(['a.typeid'=>['in', '9,10,11,12']]);
        //内容
        $data = $ProductModel->typelist(['id'=>['in','2,312,8,10,5,4,7,313,9,1,3,6,420,743,742,339']]);

        //品牌专区
        $this->foot_hytj();
        //排行榜中间八个项目
        $lick6 = $ProductModel->centerEight();

        //推荐项目
        $tuijian = $ProductModel->conditionlist([],'aid,title,class',50,'aid','desc');

        //seo title
        $seo = db('portal_category')->where('id = 391')->find();
        //友情链接
        $youlian = db("flink")->where("typeid = 9999 and ischeck =1 ")->order("dtime desc")->limit(50)->select();

        $pinpai_cate = Db::name('portal_category')->where('parent_id = 391 and status = 1 and ishidden = 1')->column('id');
        $cate_array['id'] = ['in',$pinpai_cate];
        $cate_arrayor['parent_id'] = ['in',$pinpai_cate];
        $pinpai_top = Db::name('portal_category')->where($cate_array)->whereOr($cate_arrayor)->field('id,name,path,seo_description')
            ->order('id asc')->paginate(10,false,['query' =>request()->param(),'page'=>$page]);
        //总数量
        $count = $pinpai_top->total();
        $last = $pinpai_top->lastPage();
        $render = $pinpai_top->render();


        $this->assign('count',$count);
        $this->assign('pinpai_top',$pinpai_top);
        $this->assign('render',$render);
        $this->assign('seo', $seo);
        $this->assign('hot', $hot);
        $this->assign('youlian', $youlian);

        $this->assign('tuijian', $tuijian);
        $this->assign('data', $data);
        $this->assign('lick3', $lick3);
        $this->assign('lick4', $lick4);
        $this->assign('lick5', $lick5);
        $this->assign('lick6', $lick6);
        mackHtml($this->fetch(':top'),'top');
    }
	
    //综合页
	/*
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
        //品牌专区
        $this->foot_hytj();
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
        $this->assign('onename',$onename);
        $this->assign('lick7',$lick7);
        $this->assign('lick8',$lick8);
        $this->assign('xiangguan',$xiangguan);
        $this->assign('name1',$name1);
        $this->assign('seo',$seo);
        $this->assign('AboutNews',$AboutNews);
        $this->assign('lick2',$lick2);
        $this->assign('data',$data);
        if (\think\Request::instance()->isMobile()) {
            return $this->fetch(":mobile/info_top");
        }else{
            return $this->fetch(":info_top");
        }
    }
	*/

}