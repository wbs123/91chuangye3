<?php
// +----------------------------------------------------------------------
// | Caiji
// +----------------------------------------------------------------------
// | Author: Mirng
// +----------------------------------------------------------------------
namespace app\portal\controller;
use cmf\controller\HomeBaseController;
use app\portal\model\AreaModel;
use think\cache\driver\Redis;
use app\portal\model\CategoryModel;
class AreaController extends HomeBaseController
{
    public function _initialize()
    {
        if (\think\Request::instance()->isMobile()) {
            $this->assign('seo_url', 'http://www.91chuangye.com' . $this->request->url());
        }else{
            $this->assign('seo_url', 'http://m.91chuangye.com' . $this->request->url());
        }
    }
    //渲染地区页面
    public function index()
    {
        $AreaModel = new AreaModel();
        $CategoryModel = new CategoryModel();
        //有项目地区
        $area = $AreaModel::havearea();
        $havearea = [];
        foreach($area as $area){
            array_push($havearea,$area['nativeplace']);
        }
        //全部地区
        $nav =$AreaModel::allarea();
        $municipality = ['北京市','上海市','天津市','重庆市'];
        $result = [];
        $numicipalityArea = ['ename'=>'直辖市','evalue'=>0];
        foreach($nav as $key=>$s){
            if(!in_array($s['ename'],$municipality)){
                if($s['evalue'] % 500 == 0 && in_array($s['evalue'],$havearea)){
                    foreach($nav as $city){
                        if($city['evalue'] > $s['evalue'] && $city['evalue'] < ($s['evalue']+500) && in_array
                            ($city['evalue'],$havearea)){
                            $s['city'][] = $city;
                        }
                    }
                    $result[] = $s;
                }
            }else{
                $numicipalityArea['city'][] = $s;
            }
        }
        //导航
        $this->daohang();
		//品牌专区
        $this->foot_hytj();

        $tuijian = db('portal_xm')->where('status = 1 and arcrank = 1')->field('aid,title,class')->order('aid desc')
            ->limit('50')->select();
			
        $youlian = db("flink")->where("typeid = 9999 and ischeck =1 ")->order("dtime desc")->limit(50)->select();
        $assign = [
            'seo_title'=>'全国招商加盟地域大全-91创业网',
            'seo_keywords'=>'全国招商加盟地域',
            'seo_description'=>'91创业网是帮助广大网友解决创业投资问题的招商加盟网站，汇聚全球优秀的创业连锁加盟品牌，其中包括餐饮加盟、服装加盟、教育加盟,等热门加盟项目，是创业者查找加盟项目和商家发布连锁加盟商机的首选网站。',
        ];
        foreach($assign as $k=>$v){$this->assign($k,$v);}

        //创业资讯
        $where25['parent_id'] = ['in','399,401,402,403,404,405,406,407,408,409,433'];
        $zixun = db('portal_post')->where($where25)->where('post_status = 1 and status = 1')->field('id,post_title,post_excerpt,thumbnail,published_time,class')->order('published_time desc')->limit(10)->select();
        //创业知识
        $where26['parent_id'] = ['in','20,22,27,31'];
        $zhishi = db('portal_post')->where($where26)->where('post_status = 1 and status = 1')->field('id,post_title,post_excerpt,thumbnail,published_time,class')->order('published_time desc')->limit(10)->select();
        //创业故事
        $where27['parent_id'] = ['in','11'];
        $gushi = db('portal_post')->where($where27)->where('post_status = 1 and status = 1')->field('id,post_title,post_excerpt,thumbnail,published_time,class')->order('published_time desc')->limit(10)->select();

        $this->assign('zixun',$zixun);
        $this->assign('zhishi',$zhishi);
        $this->assign('gushi',$gushi);
        $this->assign('tuijian',$tuijian);
        $this->assign('youlian',$youlian);
        $this->assign('hotCate',$CategoryModel::getCategory());
        $this->dibu();
        $this->assign('nativeplace',$result);
        $this->assign('numicipalityArea',[0=>$numicipalityArea]);
        if (\think\Request::instance()->isMobile()) {
            mackHtml($this->fetch(':mobile/areas'),'areas',2);
            return $this->fetch(':mobile/areas');
        }
        mackHtml($this->fetch(':areas'),'areas');
        return $this->fetch(':areas');
    }
}