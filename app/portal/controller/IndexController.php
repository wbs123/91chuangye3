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
use app\portal\model\AreaModel;


class IndexController extends HomeBaseController
{
    public function index()
    {
		if($this->request->param()){
			return $this->error1();
		}
        if (\think\Request::instance()->isMobile()) {
			
            $this->daohang();
			$areaModel = new AreaModel();
            set_time_limit(0);
            $banner = Db::name('advertisement')->where(['type'=>1,'is_delete'=>2,'source'=>2])->select();
            $this->assign('banner',$banner);

            //首页分类
            $where['id'] = ['in','2,312,1,3,5,4,7,9,6,10,8,339,313,420,396'];
            $type = db('portal_category')->where($where)->field('name,path')->orderRaw("field(id,2,312,1,3,5,4,7,9,6,10,8,339,313,420,396)")->select();
            //火爆招商
//          $lick7 = db('portal_xm')->where('arcrank =1 and status = 1 and thumbnail!="" and find_in_set("a",flag) ')->limit(3,10)->select();
           $where19['aid'] = ['in','120632,103525,119056,94500,119289,1169,86522,90909,119539,119502,98102,94311,84335,92183,93276,77596,86879,86886,86877,118441'];
            $lick7 = db('portal_xm')->where($where19)->orderRaw("field(aid,120632,103525,119056,94500,119289,1169,86522,90909,119539,119502,98102,94311,84335,92183,93276,77596,86879,86886,86877,118441)")->field('aid,typeid,class,title,click,invested,litpic,sum,companyname,thumbnail')->select()->toArray();
			foreach ($lick7 as $k=>$v){
                $lick7[$k]['cate_name'] = db('portal_category')->where(['id'=>$v['typeid']])->value('name');
                $lick7[$k]['cate_path'] = db('portal_category')->where(['id'=>$v['typeid']])->value('path');
            }
          
            //项目推荐
           $where20['aid'] = ['in','93055,72114,86936,86993,9050,10120,9214,83968,104352,93996,119350,118876,80367,97174,86895,8968,86931,91118,78235,91119'];
            $tuijian = db('portal_xm')->where($where20)->orderRaw("field(aid,93055,72114,86936,86993,9050,10120,9214,83968,104352,93996,119350,118876,80367,97174,86895,8968,86931,91118,78235,91119)")->field('aid,typeid,class,title,sum,companyname,litpic,invested,thumbnail,litpic')->select()->toArray();
			foreach ($tuijian as $k=>$v){
                $tuijian[$k]['cate_name'] = db('portal_category')->where(['id'=>$v['typeid']])->value('name');
                $tuijian[$k]['cate_path'] = db('portal_category')->where(['id'=>$v['typeid']])->value('path');
            }
          
            //最新入驻
            $newruzhu = db('portal_xm')->where('arcrank = 1 and status = 1')->field('aid,title,invested,litpic,class')->order('update_time desc')->limit(10)->select();

            //创业资讯
            $where25['parent_id'] = ['in','399,401,402,403,404,405,406,407,408,409,433'];
            $zixun = db('portal_post')->where($where25)->where('post_status = 1 and status = 1')->field('id,post_title,post_excerpt,thumbnail,published_time,class')->order('published_time desc')->limit(5)->select();


            //创业知识
            $where26['parent_id'] = ['in','20,22,27,31'];
            $zhishi = db('portal_post')->where($where26)->where('post_status = 1 and status = 1')->field('id,post_title,post_excerpt,thumbnail,published_time,class')->order('published_time desc')->limit(5)->select();


            //创业故事
            $where27['parent_id'] = ['in','11'];
            $gushi = db('portal_post')->where($where27)->where('post_status = 1 and status = 1')->field('id,post_title,post_excerpt,thumbnail,published_time,class')->order('published_time desc')->limit(5)->select();


            //创业之道
            $where28['parent_id'] = ['in','32'];
            $zhidao = db('portal_post')->where($where28)->where('post_status = 1 and status = 1')->field('id,post_title,post_excerpt,thumbnail,published_time,class')->order('published_time desc')->limit(5)->select();

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
            $this->assign('catess',$catess);
            $this->assign('datas',$datas);
			//品牌名称
            $xiangmu_name = Db::name('xiangmu_name')->select()->toArray();
                $sort = 80-count($xiangmu_name);
              //$brandName = db('portal_xm')->where(['status' => 1,'arcrank'=>1])->field('title,aid,class')->limit(72)->orderRaw('rand()')->select();
           $where_not['aid'] = ['not in','119750,119350,119089,118566,114088,113567,113544,113537,113319,113226,113167,113153,113137,113126,113106,113097,112877,112769,112432,111952,111939,111522,111446,111435,111417,111308,111286,110994,110948,110894,110815,110754,110649,110606,110539,110472,110446,110409,110406,110303,110274,110054,109940,109883,109873,109871,109766,109688,109516,109350,109283,109273,109161,109005,108874,107705,107685,107676,107660,107551,107484,107259,107201,107100,107078,107005,107002,106974,106753,106752,106657,106643,106591,106448,106445,105784,105633,105436,105183,105145,105074,104940,104740,104643,104352,104351,104345,104271,104197,103775,103721,103490,103458,103259,103142,102970,102658,102143,101781,101659,101437,101318,101000,100878,100870,100529,100449,100321,100315,100273,100265,100245,100029,99874,99862,99766,99680,99627,99529,99526,99391,99385,99314,98778,98706,98398,98390,98275,98261,97833,97728,97651,97625,97454,97226,97100,97046,96860,95182,95174,94642,
94311,93875,93835,93682,93475,93455,93078,92401,92400,91815,89587,88678,88665,88636,87620,87612,87380,87294,87101,86923,86919,107714107677,107629,107611,107431,107424,107359,106902,105681,105460,105270,105224,105091,105031,104827,104289,104159,104137,104066,103844,103813,103676,103669,103648,103375,103320,103029,102964,102807,102773,102299,102090,101981,101654,101193,100676,100420,100311,100171,99432,98965,98443,98158
'];
            $brandName = db('xiangmu_id')->where($where_not)->field('title,aid,class')->limit(174,$sort)->select()->toArray();
            $brandName = array_merge($xiangmu_name,$brandName);
            //调用图片地址路径
            $this->assign('brandName',$brandName);
            $this->assign('zixun',$zixun);
            $this->assign('zhishi',$zhishi);
            $this->assign('gushi',$gushi);
            $this->assign('zhidao',$zhidao);
            $this->assign('newruzhu',$newruzhu);
            $this->assign('tuijian',$tuijian);
            $this->assign('lick7',$lick7);
            $this->assign('type',$type);
          	$this->assign('sys',$areaModel->allarea('(evalue MOD 500)=0'));
             //查询数据
            $website = DB('website')->where(['id' => 1])->find();
            $this->assign('website',$website);
			
            mackHtml($this->fetch(':mobile/index'),'.','2');

            return $this->fetch(':mobile/index');
        } else {
            //首页banner
            $banner = Db::name('advertisement')->where(['type'=>1,'is_delete'=>2,'source'=>1])->order('inputtime asc')->select();
            $this->assign('banner',$banner);

            //分类右侧俩大图
            $arr_id['aid'] = ['in','119371,119288'];
            $arr_id['status'] = 1;
            $arr_id['arcrank'] = 1;

            $where17['parent_id'] =['in','2,1,3,4,5,7,10'];
            $cateid = db('portal_category')->where($where17)->field('id')->select()->toArray();
            $ids = array_column($cateid,'id');
            array_push($ids,'2','1','3','4','5','7','10');
            $zuice = db("portal_xm")->where(['typeid'=>['in',$ids]])->where('status = 1 and arcrank = 1')->order('click desc')->limit(10)->select();

            $news = db('portal_post')->where('post_status = 1 and status = 1')->order('click desc')->limit(7)->select();
            $news = $news->all();
            $news_hot = array_slice($news,0,2);
            $news_hot2 = array_slice($news,2,5);

            //大牌精选
            $gg_aid = Db::name('advertisement')->where(['type'=>2,'is_delete'=>2,'source'=>1])->value('aid');
            $aid = explode(',',$gg_aid);
            $where18['aid'] = ['in',$aid];
            // $where18['aid'] = ['in','59586,92858,103409'];
            $dapai = db('portal_xm')->where($where18)->where('status = 1 and arcrank = 1')->field('aid,title,thumbnail,invested,typeid,sum,class')->select()->toArray();
            foreach ($dapai as $ks => $vs){
                $paths = db('portal_category')->where('id = '.$vs['typeid'])->field('id,name,path')->find();
                $dapai[$ks]['paths'] = str_replace('加盟','',$paths['name']);
                $dapai[$ks]['class2'] = $paths['path'];
            }

            $lick6 = db('portal_xm')->where('arcrank =1 and status = 1 and find_in_set("c",flag) ')->order('update_time desc')->limit(30)->select();
            //火爆招商
//                $lick7 = db('portal_xm')->where('arcrank =1 and status = 1 and thumbnail!="" and find_in_set("a",flag) ')->limit(3,10)->select();
            $where19['aid'] = ['in','123678,120632,103525,119056,94500,119289,86522,90909,119539,119502'];//1169
			$lick7 = db('portal_xm')->where($where19)->orderRaw("field(aid,123678,120632,103525,119056,94500,119289,86522,90909,119539,119502)")->select()->toArray();
            //项目推荐
            $tj_aid = Db::name('advertisement')->where(['type'=>3,'is_delete'=>2,'source'=>1])->value('aid');
            $ban = db('portal_xm')->where(['aid'=>$tj_aid])->find();
            $mothhot = db('portal_xm')->where('arcrank =1 and status = 1')->field('aid,title,class')->order('click desc')->limit(8)->select();
            //热门
            $where20['aid'] = ['in','98102,94311,84335,119350,118876,80367,78235,93250,93996'];
            $hot = db('portal_xm')->where($where20)->select();
            $hot = $hot->all();
            foreach ($hot as $k=>$v){
                $name = db('portal_category')->where('id = '.$v['typeid'])->field('name')->find();
                $hot[$k]['cate'] = $name['name'];
            }
            //餐饮美食
            $where21['aid'] = ['in','92183,93276,77596,93055,72114,97174,91118,91119,104352'];
            $meishi = db('portal_xm')->where($where21)->select();
            $meishi = $meishi->all();
            foreach ($meishi as $k=>$v){
                $name = db('portal_category')->where('id = '.$v['typeid'])->field('name')->find();
                $meishi[$k]['cate'] = $name['name'];
            }
            //教育培训
            $where22['aid'] = ['in','86879,86886,86877,86936,86993,86895,86913,86931,83968'];
            $peixun = db('portal_xm')->where($where22)->select();
            $peixun = $peixun->all();
            foreach ($peixun as $k=>$v){
                $name = db('portal_category')->where('id = '.$v['typeid'])->field('name')->find();
                $peixun[$k]['cate'] = $name['name'];
            }
            //母婴推荐
            $where23['aid'] = ['in','118441,9130,9057,9365,8968,9050,10120,87245,9214'];
            $muying = db('portal_xm')->where($where23)->select();
            $muying = $muying->all();
            foreach ($muying as $k=>$v){
                $name = db('portal_category')->where('id = '.$v['typeid'])->field('name')->find();
                $muying[$k]['cate'] = $name['name'];
            }
            //创业专题项目
            $zhuantixm = db('portal_xm')->where('arcrank =1 and status = 1')->order('click desc')->limit('3','4')->select();
            //行业分类
            $where24['id'] = ['in','2,1,4,5,7,10,3,6,8,9,312,313,420'];
            $categ = db("portal_category")->where($where24)->where("ishidden =1 and status =1")->orderRaw("field(id,2,1,4,5,7,10,3,6,8,9,312,313,396,420)")->select();

            foreach ($categ as $key => $val) {
                if($val['id'] == 2){
                    $limit = ($val['id']==2) ? 28 : '';
                }else{
                    $limit = ($val['id'] == 10 && $val['id']!=2) ? 8 : 11;
                }
                if($val['id'] == 420){
                        $licai = db('portal_category')->where('id = 397')->select()->toArray();
                        $val['cate'] = db("portal_category")->where("parent_id", 'in', $val['id'])->where('status =1 and ishidden = 1')->limit($limit)->order('list_order','asc')->select()->toArray();
                        $val['cate'] = array_merge($val['cate'],$licai);
                        $class1[] = $val;
                    }else{
                        $val['cate'] = db("portal_category")->where("parent_id", 'in', $val['id'])->where('status =1 and ishidden = 1')->limit($limit)->order('list_order','asc')->select()->toArray();
                        $class1[] = $val;
                    }
            }
            $newsxm = db('portal_xm')->where('arcrank =1 and status = 1')->field('aid,typeid,title,class')->order
            ('update_time desc')->limit(28)->select();
            $newsxm = $newsxm->all();
            foreach($newsxm as $k=>$v){
                $type = db('portal_category')->where('id = '.$v['typeid'])->field('name,path')->find();
                $newsxm[$k]['cate'] = $type['name'];
                $newsxm[$k]['paths'] = $type['path'];
            }
            //创业资讯
            $where25['parent_id'] = ['in','399,401,402,403,404,405,406,407,408,409,433'];
            $zixun = db('portal_post')->where($where25)->where('post_status = 1 and status = 1')->order('published_time desc')->limit(11)->select();
            $zixun = $zixun->all();
            $zixun1 = array_slice($zixun,0, 1);
            $zixun2 = array_slice($zixun,1, 10);

            //创业知识
            $where26['parent_id'] = ['in','20,22,27,31'];
            $zhishi = db('portal_post')->where($where26)->where('post_status = 1 and status = 1')->order('published_time desc')->limit(11)->select();
            $zhishi = $zhishi->all();
            $zhishi1 = array_slice($zhishi,0, 1);
            $zhishi2 = array_slice($zhishi,1, 10);

            //创业故事
            $where27['parent_id'] = ['in','11'];
            $gushi = db('portal_post')->where($where27)->where('post_status = 1 and status = 1')->order('published_time desc')->limit(11)->select();
            $gushi = $gushi->all();
            $gushi1 = array_slice($gushi,0, 1);
            $gushi2 = array_slice($gushi,1, 10);

            //创业之道
            $where28['parent_id'] = ['in','32'];
            $zhidao = db('portal_post')->where($where28)->where('post_status = 1 and status = 1')->order('published_time desc')->limit(11)->select();
            $zhidao = $zhidao->all();
            $zhidao1 = array_slice($zhidao,0, 1);
            $zhidao2 = array_slice($zhidao,1, 10);

            //创业指南
            $where29['parent_id'] = ['in','37,38,41,43,392'];
            $zhinan = db('portal_post')->where($where29)->where('post_status = 1 and status = 1')->order('published_time desc')->limit(11)->select();
            $zhinan = $zhinan->all();
            $zhinan1 = array_slice($zhinan,0, 1);
            $zhinan2 = array_slice($zhinan,1, 10);

            //查询数据
            $website = DB('website')->where(['id' => 1])->find();
            $youlian = db("flink")->where("typeid = 9999 and ischeck =1 ")->order("dtime desc")->limit(50)->select();

            //品牌名称
            $xiangmu_name = Db::name('xiangmu_name')->select()->toArray();
                $sort = 300-count($xiangmu_name);
                $where_not['aid'] = ['not in','119750,119350,119089,118566,114088,113567,113544,113537,113319,113226,113167,113153,113137,113126,113106,113097,112877,112769,112432,111952,111939,111522,111446,111435,111417,111308,111286,110994,110948,110894,110815,110754,110649,110606,110539,110472,110446,110409,110406,110303,110274,110054,109940,109883,109873,109871,109766,109688,109516,109350,109283,109273,109161,109005,108874,107705,107685,107676,107660,107551,107484,107259,107201,107100,107078,107005,107002,106974,106753,106752,106657,106643,106591,106448,106445,105784,105633,105436,105183,105145,105074,104940,104740,104643,104352,104351,104345,104271,104197,103775,103721,103490,103458,103259,103142,102970,102658,102143,101781,101659,101437,101318,101000,100878,100870,100529,100449,100321,100315,100273,100265,100245,100029,99874,99862,99766,99680,99627,99529,99526,99391,99385,99314,98778,98706,98398,98390,98275,98261,97833,97728,97651,97625,97454,97226,97100,97046,96860,95182,95174,94642,
94311,93875,93835,93682,93475,93455,93078,92401,92400,91815,89587,88678,88665,88636,87620,87612,87380,87294,87101,86923,86919,107714107677,107629,107611,107431,107424,107359,106902,105681,105460,105270,105224,105091,105031,104827,104289,104159,104137,104066,103844,103813,103676,103669,103648,103375,103320,103029,102964,102807,102773,102299,102090,101981,101654,101193,100676,100420,100311,100171,99432,98965,98443,98158
'];
                $brandName = db('xiangmu_id')->where($where_not)->field('title,aid,class')->limit(600,$sort)->select()->toArray();
                $brandName = array_merge($xiangmu_name,$brandName);
            //最新餐饮加盟
            $canyin_id = Db::name('portal_category')->where(['parent_id'=>2])->column('id');
            array_push($canyin_id,'2');
            $where30['typeid'] = ['in',$canyin_id];
            $where30['status'] = 1;
            $where30['arcrank'] = 1;
            $news_canyin = Db::name('portal_xm')->where($where30)->field('title,aid,class,typeid')->order('aid desc')->limit(26)->select()->toArray();
            foreach($news_canyin as $k=>$v){
                $type = db('portal_category')->where('id = '.$v['typeid'])->field('name,path')->find();
                $news_canyin[$k]['cate'] = $type['name'];
                $news_canyin[$k]['paths'] = $type['path'];
            }
            $this->assign('news_canyin',$news_canyin);
            // $this->assign('datu',$datu);
            $this->zuoce();
            $this->assign('brandName',$brandName);
            $this->assign('zuice',$zuice);
            $this->assign('news_hot',$news_hot);
            $this->assign('news_hot2',$news_hot2);
            $this->assign('dapai',$dapai);
            $this->assign('lick6',$lick6);
            $this->assign('lick7',$lick7);
            $this->assign('ban',$ban);
            $this->assign('hot',$hot);
            $this->assign('meishi',$meishi);
            $this->assign('peixun',$peixun);
            $this->assign('muying',$muying);
            $this->assign('zhuantixm',$zhuantixm);
            $this->assign('class1',$class1);
            $this->assign('mothhot',$mothhot);
            $this->assign('newsxm',$newsxm);
            $this->assign('zixun1',$zixun1);
            $this->assign('zixun2',$zixun2);
            $this->assign('zhishi1',$zhishi1);
            $this->assign('zhishi2',$zhishi2);
            $this->assign('gushi1',$gushi1);
            $this->assign('gushi2',$gushi2);
            $this->assign('zhidao1',$zhidao1);
            $this->assign('zhidao2',$zhidao2);
            $this->assign('zhinan1',$zhinan1);
            $this->assign('zhinan2',$zhinan2);
            $this->assign('youlian',$youlian);
            $this->assign('website',$website);
            $this->daohang();
            $this->dibu();
            mackHtml($this->fetch(':index'),'.');
            return $this->fetch(':index');
        }
    }

    

   
}
