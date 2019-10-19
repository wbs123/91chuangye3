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
use think\Session;
use think\Db;
use think\Request;
use think\cache\driver\Redis;
use app\portal\model\ProductModel;
use app\portal\model\NewsModel;
use app\portal\model\CategoryModel;
class PlusController extends HomeBaseController
{
    public function index(){
		$redis = new Redis();
		$ProductModel = new ProductModel();
		$NewsModel = new NewsModel();
		$CategoryModel = new CategoryModel();
        
		
		$keyword = $this->request->param('keyword');
		if(isset($keyword)){
			Session::set('keyword',$keyword);
		}
		$keyword = empty($keyword) ? Session('keyword') : $keyword;
		//日志
		$log = fopen("../data/log/search_log.txt", "a") or die("Unable to open file!");
		$txt = "关键词：“".$keyword."” 来源：手机端 "." 时间：".date('Y-m-d H:i:s',time())."\n\r";
        fwrite($log, $txt);
		fclose($log);
		//分页
		preg_match('/^(.*)list_(\d+).html$/',$this->request->url(),$match);
		$page = empty($match) ? 1 : $match[2];
		$total = 0;
		$aboutdata = $dataa = $render = $info ='';
		//违禁词
        $banned = Db::name('sensitive_words')->where(['word'=>$keyword])->count();
		if(!$banned){
			$where['por.title'] = [ 'like', "%".$keyword."%"];
			//图片为空不调用
			//$where['por.litpic'] = ['neq',''];
			//$where['por.litpic'] = ['neq','/uploads/jd/qjnone.gif'];
			//项目列表数据
			$data = Db::name('portal_xm')
				->alias('por')
				->field('por.aid,por.class,por.litpic,por.thumbnail,por.title,por.typeid,por.sum,por.invested,por.companyname,por.address,por.nativeplace,cat.name as categoryname,cat.path,por.description,por.jieshao')
				->join('portal_category cat','por.typeid = cat.id')
				->where($where)->where(['por.status' => 1,'por.arcrank' => 1])->order('update_time desc')->paginate(20,
					false,['query' =>request()->param(),
					'page'=>$page]);
			
			//当分页数量大于总页数404
			if($page != 1 && $page > $data->lastPage()){
				return $this->error1();
			}
			$render = $data->render();
			$total = $data->total();
			$dataa = $data->all();
			foreach ($dataa as $key => $value) {
					// $pattern = "#<img[^>]+>#";
					$html = $this->cutArticle($value['jieshao'],240);
					$dataa[$key]['jieshao'] = $html;
				if(isset($value['nativeplace']) && ($value['nativeplace']!='')){
					$nativeplace = db('sys_enum')->where("evalue = ".$value['nativeplace']." and py != ''")->field("ename,py")->find();
					$dataa[$key]['address'] = !empty($nativeplace['ename']) ? $nativeplace['ename'] : '';
					$dataa[$key]['py'] = !empty($nativeplace['py']) ? $nativeplace['py'] : '';
				}else{
					$dataa[$key]['address'] = '';
					$dataa[$key]['py'] = '';
				}
			}
			$aboutdata = [];
			//当检索条件不满足时，显示该分类下的十个项目
			if(empty($dataa) || count($dataa) < 6){
				if(empty($dataa)){
					$aboutwhere['cat.parent_id'] = ['notin',['350','63','391','432']];
					$aboutwhere['por.typeid'] = ['notin',['350','63','391','432']];
					$info = "抱歉，没有找到和您查询条件相符的项目信息。";
				}else{
					$aboutwhere['por.typeid'] = $dataa[0]['typeid'];
				}
				//查询相关数据
				$aboutdata = Db::name('portal_xm')
					->alias('por')
					->field('por.aid,por.class,por.litpic,por.thumbnail,por.title,por.sum,por.invested,por.companyname,por.address,por.nativeplace,cat.name as categoryname,cat.path,por.description,por.jieshao')
					->join('portal_category cat','por.typeid = cat.id')
					->where('por.arcrank = 1 and por.status = 1')
					->where($aboutwhere)->order('update_time desc')->limit(100)->select()->toArray();
				$count = (count($aboutdata) > 10) ? 10 : count($aboutdata);
				if($count > 1){
					$rand = array_rand($aboutdata,$count);
				}

				foreach ($aboutdata as $key => $value) {
					$html = $this->cutArticle($value['jieshao'],220);
					$aboutdata[$key]['jieshao'] = $html;
					$aboutdata[$key]['class'] = substr($value['class'],0,1) == '/' ? substr($value['class'],1) : $value['class'];
					if(isset($value['nativeplace']) && ($value['nativeplace']!='')){
						$nativeplace = db('sys_enum')->where("evalue = ".$value['nativeplace']." and py != ''")->field("ename,py")->find();
						$aboutdata[$key]['address'] = !empty($nativeplace['ename']) ? $nativeplace['ename'] : '';
						$aboutdata[$key]['py'] = !empty($nativeplace['py']) ? $nativeplace['py'] : '';
					}else{
						$aboutdata[$key]['address'] = '';
						$aboutdata[$key]['py'] = '';
					}
					if($count > 1) {
						if (!in_array($key, $rand)) {
							unset($aboutdata[$key]);
						}
					}
				}
			}
		}else{
			$info = '抱歉，您搜索的关键词中有违禁词！';
		}
		
        //最新资讯
		if($redis->get('zxNews_')){
			$lick3 = json_decode($redis->get('zxNews_'),true);
		}else{
			$lick3 = $NewsModel->conditionArray([],'id,post_title,class,published_time',10,'published_time','desc');
			foreach ($lick3 as $key => $value) {
				$lick3[$key]['class'] = strpos($value['class'],'/') ? 'news' :$value['class'];
			}
			$redis->set('zxNews_',json_encode($lick3));
		}
		//热门专题
		if($redis->get('rmZhuanti_')){
			$lick4 = json_decode($redis->get('rmZhuanti_'),true);
		}else{
			$lick4 = $NewsModel->conditionArray([],'id,post_title,class,published_time',10,'click','desc');
			foreach ($lick4 as $key => $value) {
				$lick4[$key]['class'] = strpos($value['class'],'/') ? 'news' :$value['class'];
			}
			$redis->set('rmZhuanti_',json_encode($lick4));
		}
		//创业聚焦
		if($redis->get('cyJujiao_')){
			$lick5 = json_decode($redis->get('cyJujiao_'),true);
		}else{
			$lick5 = $NewsModel->conditionlist(['parent_id'=>'11'],'',10,'click','desc')->toArray();
			foreach ($lick5 as $key => $value) {
				$lick5[$key]['class'] = strpos($value['class'],'/') ? 'news' :$value['class'];
			}
			$redis->set('cyJujiao_',json_encode($lick5));
		}
		$this->assign('total',$total);
		$this->assign('lick3',$lick3);
		$this->assign('lick4',$lick4);
		$this->assign('lick5',$lick5);	
		$this->assign('dataa',$dataa);
		$this->assign('aboutdata',$aboutdata);
		$this->assign('info',$info);
		$this->assign('keyword',$keyword);
		
        if (\think\Request::instance()->isMobile()) {
            return $this->fetch(':mobile/search');
		}else{
			$this->daohang();
			$this->dibu();
			//品牌专区
			$this->foot_hytj();
			
			if($redis->get('daPai_')){
				$dapai = json_decode($redis->get('daPai_'),true);
			}else{
				$where18['aid'] = ['in','59586,92858,103409'];
				$dapai = db('portal_xm')->where($where18)->where('status = 1 and arcrank = 1')->field('aid,title,thumbnail,invested,typeid,sum,class')->select();
				$redis->set('daPai_',json_encode($dapai));
			}

			//导航行业以及热门行业
			if($redis->get('remenhy')){
				$type = json_decode($redis->get('remenhy'),true);
			}else{
				$type = db("portal_category")->where("parent_id = 0 and status = 1 and ishidden = 1 and id != 350")->field('id,name,path')->order('list_order asc')->limit(18)->select();
				$redis->set('remenhy',json_encode($type));
			}
			
			$catename = '热门';
			
			$linkwhere = [];
			if(!empty($dataa)){
				$linkwhere = ['typeid'=>$dataa[0]['typeid']];				
			}
			$lick1 = $ProductModel->conditionlist($linkwhere,'aid,title,class,invested,litpic,click,sum',6,'click','desc');
			//十大餐饮排行榜
			$lick2 = $ProductModel->conditionArray($linkwhere,'aid,typeid,title,class,invested',10,'weight','desc');
			foreach ($lick2 as $kes=>$v){
				$name = db('portal_category')->where('id = '.$v['typeid'])->field('name')->find();
				$path2 = db('portal_category')->where("name like '$name[name]' and id > 391")->value('path');
				$lick2[$kes]['path2'] = !empty($path2) ? $path2:'';

				$lick2[$kes]['catename'] = str_replace('加盟','',$name['name']);
			}
			$tuijian = $ProductModel->conditionlist($linkwhere,'aid,title,class,invested,litpic,click,sum',50,'aid','desc');;
			$youlian = db("flink")->where("ischeck = 1")->where($linkwhere)->field('webname,url')->order("dtime desc")->limit(30)->select();
				
			//分类
			if($redis->get('xiangmuCat')){
				$cate = json_decode($redis->get('xiangmuCat'),true);
			}else{
				$cate = $CategoryModel::getCategory();
				$redis->set('xiangmuCat',json_encode($cate));
			}
			$this->assign('youlian',$youlian);
			$this->assign('cate',$cate);
			$this->assign('render',$render);
			$this->assign('dapai',$dapai);
			$this->assign('type',$type);
			$this->assign('lick1',$lick1);
			$this->assign('lick2',$lick2);
			$this->assign('tuijian',$tuijian);
			return $this->fetch(':search');
        }
    }
    //搜索结果ajax点击加载更多
    public function ajaxkeyword(){
        $post=$this->request->param();
        $q = $post['keyword'];
        $page = $post['page']+1;
        $where['por.title'] = [ 'like', "%".$q."%"];
        $data = Db::name('portal_xm')
				->alias('por')
				->field('por.aid,por.class,por.litpic,por.thumbnail,por.title,por.typeid,por.sum,por.invested,por.companyname,por.address,por.nativeplace,cat.name as categoryname,cat.path,por.description,por.jieshao')
				->join('portal_category cat','por.typeid = cat.id')
				->where($where)->where(['por.status' => 1,'por.arcrank' => 1])->order('update_time desc')->paginate(20,
					false,['query' =>request()->param(),
					'page'=>$page]);
		$dataa = $data->all();
		foreach ($dataa as $key => $value) {
				// $pattern = "#<img[^>]+>#";
				$html = $this->cutArticle($value['jieshao'],240);
				$dataa[$key]['jieshao'] = $html;
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
            $html.='<a href="'.$url.'">';
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
            $html.='<a href="/'.$v['class'].'/'.$v['py'].'/">'.str_replace('--','',$v['address']).'</a>';
            $html.='</div>';
            $html.='<div class="desc">'.$v['companyname'].'</div>';
            $html.='</div>';
            $html.='<div class="right">';
            $html.='<div class="join"><a href="'.$url.'">咨询</a></div>';
            $html.='</div>';
            $html.='</div>';
            $html.='</li>';
        }
        $result = array('html'=>$html);
        echo json_encode($result);
    }
    function cutArticle($data,$cut=120)
    {
        $str="…";
        $data=trim(strip_tags($data));//去除html标记
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