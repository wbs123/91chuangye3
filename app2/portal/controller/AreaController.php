<?php
// +----------------------------------------------------------------------
// | Caiji
// +----------------------------------------------------------------------
// | Author: Mirng
// +----------------------------------------------------------------------
namespace app\portal\controller;
use cmf\controller\HomeBaseController;
use app\portal\model\AreaModel;
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
        //热门品牌
        $where19['aid'] = ['in','75136,76038,77197,92156,119502'];
        $lick7 = db('portal_xm')->where($where19)->field('aid,litpic,title,class')->select();
        $this->assign('lick7',$lick7);
        //小帅
        $lick1 = db('portal_xm')->where('status = 1 and arcrank = 1')->where("litpic != ' '")->field('aid,title,class,invested,litpic,click,sum')->order('click desc')->limit(4)->select();
        $lick2 = db('portal_xm')->where('status = 1 and arcrank = 1')->field('aid,typeid,title,class,invested')->order('weight desc')->limit(10)->select();
        //最新资讯
        $lick3 = db('portal_post')->where('status = 1 and post_status = 1')->field('id,post_title,class,published_time')->order('id desc')->limit(10)->select();
        //热门专题
        $lick4 = db('portal_post')->where('status = 1 and post_status = 1')->field('id,post_title,class,published_time')->order('click desc')->limit(10)->select();
        //十大品牌
        $lick5 = db('portal_post')->where('status = 1 and post_status = 1')->field('id,post_title,class,published_time')->order('id desc')->limit(10,10)->select();
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
        $tuijian = db('portal_xm')->where('status = 1 and arcrank = 1')->field('aid,title,class')->order('aid desc')->limit('22')->select();
        $youlian = db("flink")->where("typeid = 9999 and ischeck =1 ")->order("dtime desc")->limit(50)->select();
        $website = DB('website')->where(['id' => 1])->find();
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
        $this->assign('website',$website);
        $this->assign('lick1',$lick1);
        $this->assign('lick2',$lick2);
        $this->assign('lick3',$lick3);
        $this->assign('lick4',$lick4);
        $this->assign('lick5',$lick5);
        $this->assign('catess',$catess);
        $this->assign('datas',$datas);
        $this->assign('tuijian',$tuijian);
        $this->assign('youlian',$youlian);
        $this->assign('hotCate',$CategoryModel::getCategory());
        $dibu = db("portal_category")->where("parent_id",'in','52,53')->select();
        $this->assign('dibu',$dibu);
        $this->assign('nativeplace',$result);
        $this->assign('numicipalityArea',[0=>$numicipalityArea]);
        if (\think\Request::instance()->isMobile()) {
            return $this->fetch(':mobile/areas');
        }
        return $this->fetch(':areas');
    }
    //筛选产品
    public function project(){
        $AreaModel = new AreaModel();
        $CategoryModel = new CategoryModel();
        $param = $this->request->param();
        $param['type'] = $param['price'] = '';
        if(!empty($param['param'])){
            if(strstr($param['param'],'list_')){
                $param['page'] = $param['param'];
            }else{
                //参数转换数组
                $syn = explode('_',$param['param']);
                //加盟费用
                $priceArray = ['0-1','1-5','5-10','10-20','20-50','100'];
                if(count($syn) == 1){
                    //判断分类是否存在
                    $incat = $AreaModel::categoryData(['path'=>$syn[0]]);
                    if($incat){
                        $param['type'] = $syn[0];
                    }elseif(in_array($syn[0],$priceArray)){
                        $param['price'] = $syn[0];
                    }
                }else{
                    $incat = $AreaModel::categoryData(['path'=>$syn[0]]);
                    if($incat){
                        $param['type'] = $syn[0];
                    }
                    if(in_array($syn[1],$priceArray)){
                        $param['price'] = $syn[1];
                    }
                }
            }
        }
        $areaName = $AreaModel::areaName(['py'=>$param['area']]);
        if(empty($param['type'])){
            $cat = $CategoryModel::getCategory();
            foreach ($cat as $k=>$v){
                $cat[$k]['url'] = !empty($param['price']) ? $v['path'].'_'.$param['price'] : $v['path'];
            }
        }else{
            $cat = $CategoryModel::getSonCate($param['type']);
            $allpath = [];
            foreach ($cat as $k=>$v){
                $cat[$k]['url'] = !empty($param['price']) ? $v['path'].'_'.$param['price'] : $v['path'];
                $allpath[] = $v['id'];
            }
            $category = $CategoryModel::categoryData(['path'=>$param['type']]);
            if(empty($category['parent_id'])){
                $param['catid'] = $allpath;
                $this->assign('tallstyle',1);
            }
        }
        //加盟费用
        $price = [
            ['text'=>'0-1'],
            ['text'=>'1-5'],
            ['text'=>'5-10'],
            ['text'=>'10-20'],
            ['text'=>'20-50'],
            ['text'=>'50-100'],
            ['text'=>'100']
        ];
        foreach ($price as $k=>$v){
            $price[$k]['url'] = !empty($param['type']) ? $param['type'].'_'.$v['text'] : $v['text'];
        }
        $areaModel = new AreaModel();
        $projectDate = $areaModel->projectData($param);
        unset($param['catid']);
        $this->assign('projectDate',$projectDate);
        $assign = [
            'seo_title'=>'【'.$areaName['ename'].'加盟】'.$areaName['ename'].'加盟项目大全_招商加盟项目推荐-91创业网',
            'seo_keywords'=>'',
            'seo_description'=>'',
            'price' => $price
        ];
        foreach($assign as $k=>$v){$this->assign($k,$v);}
        $this->assign('cate',$cat);
        //导航
        self::daohang();
        //热门品牌
        $where19['aid'] = ['in','75136,76038,77197,92156,119502'];
        $lick7 = db('portal_xm')->where($where19)->field('aid,litpic,title,class')->select();
        $this->assign('lick7',$lick7);
        //小帅
        $lick1 = db('portal_xm')->where('status = 1 and arcrank = 1')->where("litpic != ' '")->field('aid,title,class,invested,litpic,click,sum')->order('click desc')->limit(4)->select();
        $lick2 = db('portal_xm')->where('status = 1 and arcrank = 1')->field('aid,typeid,title,class,invested')->order('weight desc')->limit(10)->select();
    //最新资讯
$lick3 = db('portal_post')->where('status = 1 and post_status = 1')->field('id,post_title,class,published_time')->order('id desc')->limit(10)->select();
    //热门专题
$lick4 = db('portal_post')->where('status = 1 and post_status = 1')->field('id,post_title,class,published_time')->order('click desc')->limit(10)->select();
    //十大品牌
$lick5 = db('portal_post')->where('status = 1 and post_status = 1')->field('id,post_title,class,published_time')->order('id desc')->limit(10,10)->select();
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
        $tuijian = db('portal_xm')->where('status = 1 and arcrank = 1')->field('aid,title,class')->order('aid desc')->limit('22')->select();
        $youlian = db("flink")->where("typeid = 9999 and ischeck =1 ")->order("dtime desc")->limit(50)->select();
        $website = DB('website')->where(['id' => 1])->find();
        $this->assign('website',$website);
        $this->assign('lick1',$lick1);
        $this->assign('lick2',$lick2);
        $this->assign('lick3',$lick3);
        $this->assign('lick4',$lick4);
        $this->assign('lick5',$lick5);
        $this->assign('catess',$catess);
        $this->assign('datas',$datas);
        $this->assign('tuijian',$tuijian);
        $this->assign('youlian',$youlian);
        $this->assign('areaName',$areaName['ename']);
        $this->assign('hotCate',$CategoryModel::getCategory());
        $this->assign('param',$param);
		$dibu = db("portal_category")->where("parent_id",'in','52,53')->select();
        $this->assign('dibu',$dibu);
        return $this->fetch(':area_list');
    }

    public function mulu(){
        $param = $this->request->param();
        dump($param);
    }
}