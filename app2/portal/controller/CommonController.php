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
use app\portal\model\ProductModel;
use app\portal\model\NewsModel;
class CommonController extends HomeBaseController
{
    public function _initialize()
    {
        //底部
        $dibu = db("portal_category")->where("parent_id",'in','52,53')->where(['status'=>1,'ishidden'=>1])->select();
        $this->assign('dibu',$dibu);
        //导航
        $where1['id'] = ['in','2,5,8,396'];
        $cates1 = db("portal_category")->where($where1)->where("ishidden = 1 and status =1 ")->select();
        foreach($cates1 as $key=>$val)
        {
            $val['data'] = db("portal_category")->where("parent_id =".$val['id'])->where('status =1 and ishidden = 1')->limit(40)->select();
            $c1[] = $val;
        }

        $where2['id'] = ['in','10,312,4,7,9,313'];
        $cates2 = db("portal_category")->where($where2)->where('status =1 and ishidden = 1')->select();
        foreach($cates2 as $key=>$val)
        {
            $val['data'] = db("portal_category")->where("parent_id =".$val['id'])->where('status =1 and ishidden = 1')->limit(6)->select();
            $c2[] = $val;
        }
        $where3['id'] = ['in','420,1,3,6,339,734,742'];
        $cates3 = db("portal_category")->where($where3)->select();
        foreach($cates3 as $key=>$val)
        {
            $val['data'] = db("portal_category")->where("parent_id =".$val['id'])->where('status =1 and ishidden = 1')->limit(6)->select();
            $c3[] = $val;
        }
        $this->assign("cates1",$c1);
        $this->assign("cates2",$c2);
        $this->assign("cates3",$c3);
        if (\think\Request::instance()->isMobile()) {
            $this->assign('seo_url', 'http://www.91chuangye.com' . $this->request->url());
        }else{
            $this->assign('seo_url', 'http://m.91chuangye.com' . $this->request->url());
        }
    }

    public function index()
    {
        //获取参数
        if (!Request::instance()->isGet()){
            $this->error1();
        }
        //实例化AreaModel
        $AreaModel = new AreaModel();
        //项目目录
        $path = $this->request->param('classname');
        //项目目录必须存在，不存在404
        if(!isset($path) || empty($path)){

            return $this->error1();
        }
        //指定参数/项目金额/地址
        $area = $price = '';
        $page = 1;
        //允许访问栏目
        $passtypeid = explode(',','2,312,8,10,5,4,7,313,9,1,3,339,6,396,420,734,742,63,350');
        if($path != 'xiangmu' && $path != 'haoxiangmu' && $path != 'article_poster'){
            $category = Db::name('portal_category')->where(['path'=>$path])->field('id,parent_id')->find();
            //判断当前目录是否存在
            $path = in_array($category['id'],$passtypeid) ? $path : in_array($category['parent_id'],$passtypeid) ? $path : false;
        }

        //不存在返回404
        if(!$path){

            return $this->error1();
        }

        //项目地址/筛选金额
        $param_addr = $this->request->param('id');
        $post = $this->request->param();
//        print_r($post);die;
        //项目地址/筛选金额
        $param_price = $this->request->param('num');

        //养生栏目与其他栏目规则不符，单独判断
        if($path == 'yangsheng'){
            $soncategory = Db::name('portal_category')->where(['parent_id'=>$category['id'],'path'=>'yangsheng/'.$param_addr])
                ->field('id,name,path')
                ->find();
            if($soncategory){
                $pathinfo = str_replace($soncategory['path'],'',$this->request->path());
                $first = strpos($pathinfo,'/');
                $pathinfo = $first == 0 ? substr($pathinfo,1) : $pathinfo;
                $param = explode('/',$pathinfo);
                $param_addr = $this->request->param('num');
                $param_price = (isset($param[1]) && !empty($param[1])) ? $param[1] : '';
                $path = $soncategory['path'];

            }
        }

        //判断海报页面
        if($path == 'article_poster'){
            return $this->article_poster($param_addr);
        }


        //判断是否是ID，跳转详情页
        if((isset($param_addr) && !empty($param_addr)) && (!isset($param_price) || empty($param_price))){
            if(is_numeric($param_addr)){
                $data = db('portal_xm')->where(['aid'=>$param_addr])->find();
                if($data){
                    return $this->article_xm($param_addr);
                }
            }
        }
        //前两个参数都存在时
        if((isset($param_addr)&&!empty($param_addr)) && (isset($param_price)&&!empty($param_price))){
            //防止参数相同漏洞
            if($param_addr == $param_price){

                return $this->error1();
            }
            preg_match('/^list_(\d+)(.html)?$/',$param_price,$match);
            //如第三参数不是page
            if(empty($match)){
                //第二参数不是地区404
                $passarea = $AreaModel::areaName(['py' => $param_addr]);
                if (!$passarea) {

                    return $this->error1();
                }
            }
        }
        $passprice = ['0-1','1-5','5-10','10-20','20-50','50-100','100-200'];
//        print_r($param_addr);die;
        //判断参数类型$param_addr
        if((isset($param_addr)&&!empty($param_addr))){
            preg_match('/^list_(\d+)(.html)?$/',$param_addr,$match);
            if(empty($match)){
                if(in_array($param_addr,$passprice)){
//                    echo 123;die;
                    $price = $param_addr;

                }else{
                    $passarea = $AreaModel::areaName(['py' => $param_addr]);
                    if ($passarea) {
                        $area =  $param_addr;
                    }else{

                        return $this->error1();
                    }
                }
            }else{
                $page = $match[1];
            }
        }
        //判断参数类型$param_price
        if((isset($param_price)&&!empty($param_price))){
            preg_match('/^list_(\d+)(.html)?$/',$param_price,$match);
            if(empty($match)){
                if(in_array($param_price,$passprice)){
                    $price = $param_price;
                }else{
                    $passarea = $AreaModel::areaName(['py' => $param_price]);
                    if ($passarea) {
                        $area =  $param_addr;
                    }else{

                        return $this->error1();
                    }
                }
            }else{
                //如果分页存在证明两个参数都是list_\d.html 则404
                if($page != 1){

                    return $this->error1();
                }
                $page = $match[1];
            }
        }
        //分页
        if($page == 1){
            preg_match('/^(.*)\/list_(\d+)(.html)?$/',$this->request->url(),$match);
            if(!empty($match)){
                $page = $match[2];
            }
        }
        //list_0 404
        if($page == 0){
            return $this->error1();
        }
        $param = [
            'classname'=>$path,
            'address'=>$area,
            'num'=>$price,
            'page'=>$page,
        ];
        return $this->xm($param);
    }
    //项目列表
    public function xm($post)
    {
        $page = $post['page'];
        $array_reverse = $youlian = $selcttag1 ="";
        $areaModel = new AreaModel();
        $CategoryModel = new CategoryModel();
        $ProductModel = new ProductModel();
        $NewsModel = new NewsModel();
        //地区
        $showsheng = '';
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
                //相关地区项目条件
                $aboutwhere = ['por.nativeplace'=>['in',implode(',',$areaAll)]];
            }else{
                $evalue = $areaValue - $areaValue % 500;
                $showsheng = DB::name('sys_enum')->where(['evalue'=>$evalue,'egroup'=>'nativeplace'])->value('py');
                $where['por.nativeplace'] = $areaHave['evalue'];
                //相关地区项目条件
                $aboutwhere['por.nativeplace'] = $areaHave['evalue'];
            }
        }

        //金额参数
        if(!empty($post['num']))
        {
            $where['por.invested'] = $priceshow =  ($post['num'] == '100-200') ? '100-200万' : $post['num'].'万';
        }
        //分类
        if(empty($post['classname']) || $post['classname']=='xiangmu'){
            $cate = $CategoryModel::getCategory();
        }else{
            $categoryData = $CategoryModel::categoryData(['path'=>$post['classname']]);
            if(empty($categoryData['parent_id'])){
                $cate = $CategoryModel::getSonCate($post['classname']);
            }else{
                $categoryData = $CategoryModel::categoryData(['id'=>$categoryData['parent_id']]);
                $cate = $CategoryModel::getSonCate($categoryData['path']);
            }
        }

        //当栏目不是xiangmu时
        if($post['classname']!='xiangmu'){
            $category = $CategoryModel::getone(['path'=>$post['classname']],'id,path,parent_id');

            if(!$category){
                return $this->error1();
            }
            $id = $category['id'];
            //面包屑
            $array_reverse = $this->position($id);
            //友情链接
            $youlian = db("flink")->where("typeid = ".$id." and ischeck = 1")->field('webname,url')->order("dtime desc")->limit(30)->select();
            if($category['parent_id'] == 0)
            {
                //子栏目ID
                $sonIds =  $CategoryModel::getOneColumn(['parent_id'=>$id],'id');
                $where['por.typeid'] = ['in',$sonIds];
            }else{
                $where['por.typeid'] = $id;
                $selcttag1=$post['classname'];
            }
            //推荐项目品牌
            $tuijian = Db::name('portal_xm')
                ->alias('por')
                ->where($where)
                ->where('status = 1 and arcrank = 1')
                ->field('aid,title,class')
                ->order('aid desc')->limit('50')->select();
        }else{

            //排除养生保健、创业好项目、休闲娱乐、加盟排行榜、采集栏目
            $where['cat.parent_id'] = ['notin',['350','63','391','432']];
            $where['por.typeid'] = ['notin',['350','63','391','432']];
            $tuijian = Db::name('portal_xm')
                ->alias('por')
                ->where(['por.typeid'=>['notin',['350','63','391','432']]])
                ->where('status = 1 and arcrank = 1')
                ->field('aid,title,class')
                ->order('aid desc')->limit('50')->select();
        }
        //图片为空不调用
        $where['por.litpic'] = ['neq',''];
        $where['por.litpic'] = ['neq','/uploads/jd/qjnone.gif'];
//        print_r($where);die;
        //项目列表数据
        $data = Db::name('portal_xm')
            ->alias('por')
            ->field('por.aid,por.class,por.litpic,por.thumbnail,por.title,por.sum,por.invested,por.companyname,por.address,por.nativeplace,cat.name as categoryname,cat.path,por.description,por.jieshao')
            ->join('portal_category cat','por.typeid = cat.id')
            ->where($where)->where(['por.status' => 1,'por.arcrank' => 1])->order('update_time desc')->paginate(20,
                false,['query' =>request()->param(),
                    'page'=>$page]);


        //当分页数量大于总页数404
        if($page != 1 && $page > $data->lastPage()){
            return $this->error1();
        }
        $infos = $data->all();
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
            //xiangmu
            if($post['classname'] == 'xiangmu'){
                $aboutwhere['cat.parent_id'] = ['notin',['350','63','391','432']];
                $aboutwhere['por.typeid'] = ['notin',['350','63','391','432']];

            }else{
                $aboutwhere['por.typeid'] = $id;
                if($category['parent_id'] == 0){
                    $aboutwhere['por.typeid'] = ['in',$sonIds];
                }
                unset($aboutwhere['por.nativeplace']);
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
                // $pattern = "#<img[^>]+>#";
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
        //查询底部数据
        $website = DB('website')->where(['id' => 1])->find();
        //TDK
        $nativeplace = db('sys_enum')->where(['py'=>$post['address']])->value("ename");
        $nativeplace = str_replace('省','',$nativeplace);
        $nativeplace = str_replace('市','',$nativeplace);

        $seo = db("portal_category")->where("path="."'$post[classname]' and status = 1 and ishidden = 1")->find();
        $selcttag3 = $post['num'];
        if(isset($post['classname']) && ($post['classname']=='xiangmu')){
            if($selcttag3 || $nativeplace){
                if(!empty($selcttag3)){
                    $selcttag3 = $selcttag3.'万';
                }
                $seo_title = $nativeplace.$selcttag3."招商加盟项目大全_2019 ".$nativeplace.$selcttag3."热门招商加盟项目推荐-91创业网 ";
                $seo_keywords = $nativeplace.$selcttag3.'加盟项目'.", ".$nativeplace.$selcttag3."招商加盟项目";
                $seo_description = "91创业网-汇集各种".$nativeplace.$selcttag3."加盟,".$nativeplace.$selcttag3."连锁加盟,".$nativeplace.$selcttag3."十大品牌排行榜等".$nativeplace.$selcttag3."加盟费信息,帮助广大创业者找到适合自己的加盟项目,选择好的".$nativeplace.$selcttag3."加盟项目,让创业者轻松创业！";
            }else {
                $seo_title = "招商加盟项目大全_2019热门招商加盟项目推荐-91创业网";
                $seo_keywords = "加盟项目,招商加盟项目";
                $seo_description = "91创业网-汇集各种品牌加盟项目大全,招商连锁加盟,品牌加盟十大排行榜等2019招商加盟费信息,帮助广大创业者找到适合自己的加盟项目,选择好的品牌加盟项目,让创业者轻松创业";
            }
        }else{
            //一级分类的判断
            //判断金额存在
            if((!empty($selcttag3))&&(empty($nativeplace)) || ($seo['parent_id'] != 0) && (!empty($selcttag3)) && (empty($nativeplace))){
                $selcttag3 = $selcttag3 . '万';
                $seo_name = str_replace('加盟', '', $seo['name']);
                if($seo['id'] == 734 || $seo['parent_id'] == 734){
                    $seo_title = $selcttag3 . $seo_name.'品牌连锁店项目加盟_'.$selcttag3.$seo_name.'品牌加盟条件-91创业网';
                    $seo_keywords = $selcttag3 . $seo_name.'加盟,'.$selcttag3.$seo_name.'连锁店加盟';
                }else if($seo['id'] == 10){
                    $seo_title = $selcttag3 . $seo_name.'项目加盟_'.$selcttag3.$seo_name.'项目加盟条件-91创业网';
                    $seo_keywords = $selcttag3 . $seo_name.'加盟';
                }else if($seo['id'] == 312){
                    $seo_title = $selcttag3 . $seo_name.'酒水加盟项目_'.$selcttag3.$seo_name.'酒水加盟店排行榜-91创业网';
                    $seo_keywords = $selcttag3 . $seo_name.'酒水加盟,'.$selcttag3 . $seo_name.'酒水加盟店,'.$selcttag3 . $seo_name.'酒水加盟排行榜,'.$selcttag3 . $seo_name.'水加盟十大品牌';
                }else if($seo['id'] == 396){
                    $seo_title = $selcttag3 . $seo_name.'项目加盟_'.$selcttag3.$seo_name.'项目加盟条件-91创业网';
                    $seo_keywords = $selcttag3 . $seo_name.'加盟,'.$selcttag3 . $seo_name.'代理';
                }else if($seo['id'] == 420){
                    $seo_title = $selcttag3.'互联网创业项目加盟_'.$selcttag3.'网络创业项目代理条件-91创业网';
                    $seo_keywords = $selcttag3 .'互联网创业项目加盟,'.$selcttag3 .'互联网创业项目代理';
                }else{
                    $seo_title = $selcttag3 . $seo_name.'品牌连锁店项目加盟_'.$selcttag3.$seo_name.'品牌加盟条件-91创业网';
                    $seo_keywords = $selcttag3 . $seo_name.'加盟,'.$selcttag3 . $seo_name.'连锁店加盟';
                }
                $seo_description = "91创业网-汇集各种" . $selcttag3 . $seo_name . '加盟' . "," . $selcttag3 . $seo_name . '加盟' . "连锁品牌," . $selcttag3 . $seo_name . "加盟十大品牌排行榜等" . $selcttag3 . $seo_name . "加盟费信息,帮助广大创业者找到适合自己的加盟项目,选择好的" . $selcttag3 . $seo_name . "加盟项目,让创业者轻松创业！";

                //判断地区存在
            }else if((!empty($nativeplace)) && (empty($selcttag3))|| ($seo['parent_id'] != 0) && (!empty($nativeplace)) && (empty($selcttag3))) {
                $seo_name = str_replace('加盟', '', $seo['name']);
                if($seo['id'] == 734){
                    $seo_title = $nativeplace.$seo_name.'品牌连锁店加盟_'.$nativeplace.$seo_name.'加盟费用_多少钱-91创业网';
                    $seo_keywords = $nativeplace.$seo_name.'加盟,'.$nativeplace.$seo_name.'品牌连锁店加盟,'.$nativeplace.$seo_name.'连锁店加盟,'.$nativeplace.$seo_name.'品牌加盟';
                }else if($seo['id'] == 10){
                    $seo_title = $nativeplace.$seo_name.'加盟_'.$nativeplace.$seo_name.'加盟费用多少钱-91创业网';
                    $seo_keywords = $nativeplace.$seo_name.'加盟,'.$nativeplace.$seo_name.'加盟费用,'.$nativeplace.$seo_name.'加盟多少钱';
                }else if($seo['id'] == 312){
                    $seo_title = $nativeplace.$seo_name.'加盟项目_'.$nativeplace.$seo_name.'加盟店排行榜-91创业网';
                    $seo_keywords = $nativeplace.$seo_name.'加盟,'.$nativeplace.$seo_name.'加盟店,'.$nativeplace.$seo_name.'加盟排行榜,'.$nativeplace.$seo_name.'加盟十大品牌';
                }else if($seo['id'] == 396){
                    $seo_title = $nativeplace.$seo_name.'项目代理加盟_'.$nativeplace.$seo_name.'项目加盟费用多少钱-91创业网';
                    $seo_keywords = $nativeplace.$seo_name.'加盟,'.$nativeplace.$seo_name.'代理,'.$nativeplace.$seo_name.'代理费用';
                }else if($seo['id'] == 420){
                    $seo_title = $nativeplace.'互联网创业项目加盟_'.$nativeplace.'零成本网络创业项目招商代理-91创业网';
                    $seo_keywords = $nativeplace.'互联网项目加盟,'.$nativeplace.'网络创业项目加盟,'.$nativeplace.'互联网创业项目代理,'.$nativeplace.'网络创业项目代理';
                }else{
                    $seo_title = $nativeplace.$seo_name.'品牌连锁店加盟_'.$nativeplace.$seo_name.'加盟费用_多少钱-91创业网';
                    $seo_keywords = $nativeplace.$seo_name.'加盟,'.$nativeplace.$seo_name.'品牌连锁店加盟,'.$nativeplace.$seo_name.'连锁店加盟,'.$nativeplace.$seo_name.'品牌加盟';
                }

                $seo_description = "91创业网-汇集各种" . $nativeplace . $seo_name . '加盟' . "," . $nativeplace . $seo_name . '加盟' . "连锁品牌," . $nativeplace . $seo_name . "加盟十大品牌排行榜等" . $nativeplace . $seo_name . "加盟费信息,帮助广大创业者找到适合自己的加盟项目,选择好的" . $nativeplace . $seo_name . "加盟项目,让创业者轻松创业！";

                //判断金额和地区都存在
            }else if((!empty($selcttag3)) && (!empty($nativeplace)) || ($seo['parent_id'] != 0) && (!empty($selcttag3)) && (!empty($nativeplace))){
                $selcttag3 = $selcttag3 . '万';
                $seo_name = str_replace('加盟', '', $seo['name']);
                if($seo['id'] == 734) {
                    $seo_title = $nativeplace . $selcttag3 . $seo_name . '品牌连锁店项目加盟_' . $nativeplace . $selcttag3 . $seo_name . '品牌加盟条件-91创业网';
                    $seo_keywords = $nativeplace . $selcttag3 . $seo_name . '加盟,' . $nativeplace . $selcttag3 . $seo_name . '连锁店加盟';
                }else if($seo['id'] == 10){
                    $seo_title = $nativeplace . $selcttag3 . $seo_name . '项目加盟_' . $nativeplace . $selcttag3 . $seo_name . '项目加盟条件-91创业网';
                    $seo_keywords = $nativeplace . $selcttag3 . $seo_name . '加盟,' . $nativeplace . $selcttag3 . $seo_name . '项目加盟';
                }else if($seo['id'] == 312){
                    $seo_title = $nativeplace . $selcttag3 . $seo_name . '加盟项目_' . $nativeplace . $selcttag3 . $seo_name . '加盟店排行榜-91创业网';
                    $seo_keywords = $nativeplace . $selcttag3 . $seo_name . '加盟,' . $nativeplace . $selcttag3 . $seo_name . '加盟店,'. $nativeplace . $selcttag3 . $seo_name.'加盟排行榜,'.$nativeplace . $selcttag3 . $seo_name.'加盟十大品牌';
                }else if($seo['id'] == 396){
                    $seo_title = $nativeplace . $selcttag3 . $seo_name . '加盟项目_' . $nativeplace . $selcttag3 . $seo_name . '项目加盟条件-91创业网';
                    $seo_keywords = $nativeplace . $selcttag3 . $seo_name . '加盟,' . $nativeplace . $selcttag3 . $seo_name . '代理';
                }else if($seo['id'] == 420){
                    $seo_title = $nativeplace . $selcttag3 . $seo_name . '互联网创业项目加盟_' . $nativeplace . $selcttag3 . $seo_name . '网络创业项目代理条件-91创业网';
                    $seo_keywords = $nativeplace . $selcttag3 . $seo_name . '互联网创业项目加盟,' . $nativeplace . $selcttag3 . $seo_name . '联网创业项目代理';
                }else{
                    $seo_title = $nativeplace . $selcttag3 . $seo_name . '品牌连锁店项目加盟_' . $nativeplace . $selcttag3 . $seo_name . '品牌加盟条件-91创业网';
                    $seo_keywords = $nativeplace . $selcttag3 . $seo_name . '加盟,' . $nativeplace . $selcttag3 . $seo_name . '连锁店加盟';
                }
                $seo_description = "91创业网-汇集各种" . $nativeplace . $selcttag3 . $seo_name . '加盟' . "," . $nativeplace . $selcttag3 . $seo_name . '加盟' . "连锁品牌," . $nativeplace . $selcttag3 . $seo_name . "加盟十大品牌排行榜等" . $nativeplace . $selcttag3 . $seo_name . "加盟费信息,帮助广大创业者找到适合自己的加盟项目,选择好的" . $nativeplace . $selcttag3 . $seo_name . "加盟项目,让创业者轻松创业！";
            }else{
                $seo_title = $seo['seo_title'];
                $seo_keywords = $seo['seo_keywords'];
                $seo_description = $seo['seo_description'];
            }
        }

        if(isset($post['classname'])){
            $catename = db('portal_category')->where("path="."'$post[classname]'")->field('name')->find();
            $catename = str_replace('加盟','',$catename['name']);
        }else{
            $catename = '热门';
        }
        if((isset($post['classname'])) && ($post['classname'] != 'xiangmu')){
            $cate_Name = db('portal_category')->where("path="."'$post[classname]'")->value('name');
        }else{
            $cate_Name = '项目库';
        }
        //导航行业以及热门行业
        $type = db("portal_category")->where("parent_id = 0 and status = 1 and ishidden = 1 and id != 350")->field('id,name,path')->order('list_order asc')->limit(18)->select();
        $this->assign('selcttag3',$selcttag3);
        $this->assign('nativeplace',$nativeplace);
        $this->assign('cate_Name',$cate_Name);
        $this->assign('catename',$catename);
        $this->assign('showsheng',$showsheng);
        $this->assign('lastpage',$data->lastPage());
        $this->assign('seo_title',str_replace('_第1页','',$seo_title));
        $this->assign('seo_keywords',$seo_keywords);
        $this->assign('seo_description',$seo_description);
        $this->assign('website',$website);
        $this->assign('category',$post['classname']);
        $this->assign('selcttag1',$selcttag1);
        $this->assign('param',$post);
        $this->assign('youlian',$youlian);
        $this->assign('data',$data);
        $this->assign('total',$data->total());
        $this->assign('dataa',$dataa);
        $this->assign('aboutdata',$aboutdata);
        $this->assign('infos',$infos);
        $this->assign('sys',$areaModel->allarea('(evalue MOD 500)=0'));
        $this->assign('cate',$cate);
        $this->assign('type',$type);

        $this->assign('array_reverse',$array_reverse);
        if (\think\Request::instance()->isMobile()) {
            $cate = $CategoryModel::categoryData(['path'=>$post['classname']]);
            if($cate['id']==63||$cate['parent_id']==63){
                //获取创业好项目
                $catess = db("portal_category")->where(['id'=>63])->where('status = 1 and ishidden = 1')->field('id,parent_id,name,path')->order('list_order asc')->select();

                $cated = db('portal_category')->where(['parent_id' => 63,'ishidden' => 1,'status' => 1])->field('id,path,name,mobile_thumbnail')->select();
            }else{
                //获取所有一级分类
                $arr = '2,1,4,5,7,10,3,6,8,9,312,313,396,420,339,734,742';
                $catess = db("portal_category")->where('id', 'in', $arr)->where('status = 1 and ishidden = 1')->field('id,parent_id,name,path')->order('list_order asc')->select();
                $cated = db('portal_category')->where(['parent_id' => 2,'ishidden' => 1,'status' => 1])->field('id,path,name,mobile_thumbnail')->select();
            }

            //创业资讯
            $where25['parent_id'] = ['in','399,401,402,403,404,405,406,407,408,409,433'];
            $zixun = db('portal_post')->where($where25)->where('post_status = 1 and status = 1')->field('id,post_title,post_excerpt,thumbnail,published_time,class')->order('published_time desc')->limit(10)->select();
            //创业知识
            $where26['parent_id'] = ['in','20,22,27,31'];
            $zhishi = db('portal_post')->where($where26)->where('post_status = 1 and status = 1')->field('id,post_title,post_excerpt,thumbnail,published_time,class')->order('published_time desc')->limit(10)->select();
            //创业故事
            $where27['parent_id'] = ['in','11'];
            $gushi = db('portal_post')->where($where27)->where('post_status = 1 and status = 1')->field('id,post_title,post_excerpt,thumbnail,published_time,class')->order('published_time desc')->limit(10)->select();
            $cateid = $cateparentid = 0;

            if($post['classname'] != 'xiangmu')
            {
                $cateid = isset($category['id']) ? $category['id'] : 0;
                $cateparentid = isset($category['parent_id']) ? $category['parent_id'] : 0;
            }
            print_r($priceshow);die;
            $show = [
                'classname' => isset($cate['name']) ? str_replace('加盟','',$cate['name']) : '',
                'price' => isset($priceshow) ? $priceshow : '',
                'address' => isset($areaHave['ename']) ? $areaHave['ename'] : '',
            ];
            $this->assign('show',$show);
            $this->assign('param',$post);
            $this->assign('cateid',$cateid);
            $this->assign('cateparentid',$cateparentid);
            $this->assign('zixun',$zixun);
            $this->assign('catess',$catess);
            $this->assign('cated',$cated);
            $this->assign('zhishi',$zhishi);
            $this->assign('gushi',$gushi);
            return $this->fetch(':mobile/list');
        }else{

            if(($post['classname'] == 'xiangmu')){
                //热门品牌加盟
                $lick7 = $ProductModel->conditionlist(['aid'=>['in','1169,86522,90909,119539,119502']],'aid,class,litpic,title,thumbnail',5,'click','desc');
            }else{
                $categorys = db('portal_category')->where(['status'=>1,'ishidden'=>1,'path'=>$post['classname']])->field('id,parent_id')->find();
                if($categorys['parent_id'] == 0){
                    $cated = db('portal_category')->where(['parent_id' => $categorys['id'],'ishidden' => 1,'status' => 1])->column('id');
                    array_unshift($cated, $categorys['id']);
                    $a['typeid'] = ['in',$cated];
                    $lick7 = db('portal_xm')->where("status = 1 and arcrank = 1")->where($a)->field('aid,class,litpic,title,thumbnail')->limit(5)->order('click desc')->select();
                }else{
                    $lick7 = db('portal_xm')->where("status = 1 and arcrank = 1 and typeid = ".$categorys['id'])
                        ->field('aid,class,litpic,title,thumbnail')->limit(5)->order('click desc')->select();
                }
            }


            //好项目加盟
            $linkwhere = [];
            if($post['classname']!='xiangmu'){
                $linkwhere['typeid'] = $id;
                if($category['parent_id'] == 0){
                    $linkwhere['typeid'] = ['in',$sonIds];
                }
            }
            $lick1 = $ProductModel->conditionlist($linkwhere,'aid,title,class,invested,litpic,click,sum',4,'click','desc');

            //十大餐饮排行榜
            $lick2 = $ProductModel->conditionArray($linkwhere,'aid,typeid,title,class,invested',10,'weight','desc');
            foreach ($lick2 as $kes=>$v){
                $name = db('portal_category')->where('id = '.$v['typeid'])->field('name')->find();
                $path2 = db('portal_category')->where("name like '$name[name]' and id > 391")->value('path');
                $lick2[$kes]['path2'] = !empty($path2) ? $path2:'';

                $lick2[$kes]['catename'] = str_replace('加盟','',$name['name']);
            }

            //最新资讯
            $lick3 = $NewsModel->conditionArray([],'id,post_title,class,published_time',10,'published_time','desc');
            foreach ($lick3 as $key => $value) {
                $lick3[$key]['class'] = strpos($value['class'],'/') ? 'news' :$value['class'];
            }
            //热门专题
            $lick4 = $NewsModel->conditionArray([],'id,post_title,class,published_time',10,'click','desc');
            foreach ($lick4 as $key => $value) {
                $lick4[$key]['class'] = strpos($value['class'],'/') ? 'news' :$value['class'];
            }
            //创业聚焦
            $lick5 = $NewsModel->conditionlist(['parent_id'=>'11'],'',10,'click','desc')->toArray();
            foreach ($lick5 as $key => $value) {
                $lick5[$key]['class'] = strpos($value['class'],'/') ? 'news' :$value['class'];

            }


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
                $val['data'] = db("portal_xm")->where($wheres)->where($where3)->field('aid,title,invested,litpic,class')->order('pubdate asc')->limit(14)->select();
                $datas[] = $val;
            }

//            $where18['aid'] = ['in','59586,92858,103409'];
//            $dapai = db('portal_xm')->where($where18)->where('status = 1 and arcrank = 1')->field('aid,title,thumbnail,invested,typeid,sum,class')->select();
            if($post['classname']=='xiangmu'){
                $where18['thumbnail'] = ['neq',' '];
            }else{
                if($category['parent_id'] == 0)
                {
                    //子栏目ID
                    $sonIds =  $CategoryModel::getOneColumn(['parent_id'=>$id],'id');
                    $where18['typeid'] = ['in',$sonIds];
                    $where18['thumbnail'] = ['neq',' '];
                }else{
                    $where18['typeid'] = $id;
                    $where18['thumbnail'] = ['neq',' '];
                }
            }

            $dapai = $ProductModel->conditionArray($where18,'aid,typeid,title,class,invested,thumbnail',3,'click','desc');
//            print_r($dapai);die;
            $this->assign('datas',$datas);
            $this->assign('catess',$catess);
            $this->assign('lick1',$lick1);
            $this->assign('lick2',$lick2);
            $this->assign('lick3',$lick3);
            $this->assign('lick4',$lick4);
            $this->assign('lick5',$lick5);
            $this->assign('lick7',$lick7);
            $this->assign('dapai',$dapai);
            $this->assign('tuijian',$tuijian);
            return $this->fetch(':list');
        }
    }


    function html_msubstr($str='', $start=0, $length=NULL, $suffix=false, $charset="utf-8") {
        if (is_language() && 'cn' != get_current_lang()) {
            $length = $length * 2;
        }
        $str = eyou_htmlspecialchars_decode($str);
        $str = checkStrHtml($str);
        return msubstr($str, $start, $length, $suffix, $charset);
    }


    //项目详情
    public function article_xm($id)
    {

        if (\think\Request::instance()->isMobile()) {
            // $array_reverse = "";
            Db::name('portal_xm')->where('aid', $id)->setInc('click');
            $data = db('portal_xm')->where("aid = $id")->find();
            $category = db('portal_category')->where('id = '.$data['typeid'])->field('name,path')->find();
            $data['category'] = $category['name'];
            $post=$this->request->param();
            //判断当前class是不是对应的
            if($data['class'] != $post['classname'] && $post['classname'] != 'yangsheng'){
                return $this->error1();
            }
            if($data['nativeplace'])
            {
                $nativeplace = db('sys_enum')->where("evalue = ".$data['nativeplace']." and py != ''")->value("ename");
                $data['address'] = $nativeplace;
            }else{
                $data['address'] = $data['address'];
            }
            $typeid = $data['typeid'];
            $name = db("portal_category")->where("id = ".$typeid.' and status = 1 and ishidden = 1')->value("name");
            //项目咨询
            $did = db('portal_post')->where('did = '.$id.' and post_status = 1 and status = 1')->field('id,post_title,published_time,class')->limit(5)->select()->toArray();

            if(empty($did) || count($did)<5){
                $where['a.post_title'] = [ 'like', "%".$data['title']."%"];
                $where['a.post_status'] = 1;
                $where['a.status'] = 1;
                $did = db('portal_post a')->where($where)->field('id,post_title,published_time,class')->limit(5)->select()->toArray();
            }

            if(empty($did)){
                $did = db('portal_post')->where('post_status = 1 and status = 1')->field('id,post_title,published_time,class')->order('id desc')->limit(5)->select();
            }
            //项目推荐
            $txiangmu = db('portal_xm')->where('typeid = '.$data['typeid'].' and status = 1 and arcrank = 1')->field('aid,title,sum,companyname,invested,class,litpic')->limit(10)->select();

            $typeid = db('portal_xm')->where("aid = $id and status = 1 and arcrank = 1")->value('typeid');
            $imgs = db("uploads")->where("arcid = ".$id)->select();
            $imgs_arr  = $this->get_pic_url(htmlspecialchars_decode($data['jieshao'].$data['tiaojian'].$data['liucheng']));
            $imgs_arrs = [];
            foreach ($imgs_arr as $k => $v ){
                $imgs_arrs[$k]['url'] = $v;
                $imgs_arrs[$k]['title'] = '';
            }
            $img = 'http://www.91chuangye.com';
            $this->assign('img',$img);
            $this->assign("name",$name);
            $this->assign('imgs_arrs',$imgs_arrs);
            $this->assign("imgs",$imgs);
            $this->assign('data',$data);
            $this->assign('did',$did);
            $this->assign('id',$id);
            $this->assign('txiangmu',$txiangmu);
            return $this->fetch(':mobile/article_xm');
        }else{
            $array_reverse = "";
            $post=$this->request->param();
            $data = db('portal_xm')->where("aid = $id")->find();
            //判断当前class是不是对应的
            if($data['class'] != $post['classname'] && $post['classname'] != 'yangsheng'){
                return $this->error1();
            }

            if($data['nativeplace'])
            {
                $nativeplace = db('sys_enum')->where("evalue = ".$data['nativeplace']." and py != ''")->value("ename");
                $data['address'] = $nativeplace;
            }else{
                $data['address'] = $data['address'];
            }
            $typeid = $data['typeid'];
            $name = db("portal_category")->where("id = ".$typeid.' and status = 1 and ishidden = 1')->field('name,path')->find();
            $array_reverse = $this->position($typeid);
            //相关项目
            $lick1 = db('portal_xm')->where("typeid = $typeid and status = 1 and arcrank = 1")->field('aid,title,litpic,invested,sum,class')->order('click asc')->limit(0,4)->select();
            //品牌项目
            $pinpai = db('portal_xm')->where("invested = "."'$data[invested]' and typeid = "."'$data[typeid]' and status = 1 and arcrank = 1")->field('aid,title,litpic,invested,sum,class')->order('click desc')->limit(4)->select();
            //相关分类
            $about = db('portal_category')->where('id = '.$data['typeid'])->field('parent_id')->find();
            $abouttype = db('portal_category')->where('parent_id = '.$about['parent_id'])->field('name,path')->limit(14)->select();

            //热门品牌
            $fenlei = db('portal_category')->where('id = '.$typeid)->value('parent_id');
            $cates =  db("portal_category")->where("parent_id = $fenlei and status = 1 and ishidden = 1")->field('id')->select()->toArray();
            $ids = array_column($cates,'id');
            $where['typeid'] = array('in',$ids);
            $hotpinpai = db('portal_xm')->where('status = 1 and arcrank = 1')->where($where)->field('aid,title,typeid,class,invested,litpic')->order('click desc')->limit(19)->select();
            $hotpinpai = $hotpinpai->all();
            foreach ($hotpinpai as $k=>$v){
                $catetype = db('portal_category')->where('id = '.$v['typeid'])->field('name,path')->find();
                $hotpinpai[$k]['cate'] = $catetype['name'];
                $hotpinpai[$k]['class2'] = $catetype['path'];
            }
            $hotpinpai1 = array_slice($hotpinpai,0, 3);
            $hotpinpai2 = array_slice($hotpinpai,3, 16);
            //品牌排行
            $pinpaipaihang = db('portal_xm')->where("class = "."'$data[class]' and status = 1 and arcrank = 1")->field('aid,title,invested,litpic,sum,class')->order('click desc')->limit(10)->select();

            //品牌推荐
            // $w = "FIND_IN_SET('a',flag)";
            $lick5 = db('portal_xm')->where('status = 1 and arcrank = 1 and typeid = '.$data['typeid'])->field('aid,title,class,litpic,click,invested')->limit(0,5)->select();

            //项目关联图片表
            $imgs = db("uploads")->where("arcid = ".$id)->select()->toArray();
            $imgs_arr  = $this->get_pic_url(htmlspecialchars_decode($data['jieshao'].$data['tiaojian'].$data['liucheng']));
            $imgs_arrs = [];
            foreach ($imgs_arr as $k => $v ){
                $imgs_arrs[$k]['url'] = $v;
                $imgs_arrs[$k]['title'] = '';
            }
            //项目相关新闻
            $lick7 = db("portal_post")->where('did = '.$data['aid'].' and status = 1 and post_status = 1')->field('id,post_title,class,published_time')->limit(6)->select()->toArray();

            if(empty($lick7) || count($lick7)<5){
                $wherew['post_title'] = ['like','%'.$data['title'].'%'];
                $wherew['status'] = 1;
                $wherew['post_status'] = 1;
                $lick7 = db("portal_post")->where($wherew)->field('id,post_title,class,published_time')->limit(6)
                    ->select()->toArray();
            }
            if(empty($lick7)){
                $lick7 = db("portal_post")->where('status = 1 and post_status = 1 and parent_id = 401')->field('id,post_title,class,published_time')->limit(6)->select()->toArray();
            }

            foreach ($lick7 as $key => $value) {
                $lick7[$key]['class'] = strpos($value['class'],'/') ? 'news' :$value['class'];
            }



            //专题新闻
            $lick8 = db('portal_post')->where('status = 1 and post_status = 1 and parent_id = 401')->field('id,post_title,class,published_time')->limit(6,6)->select()->toArray();

            foreach ($lick8 as $key => $value) {
                $lick8[$key]['class'] = strpos($value['class'],'/') ? 'news' :$value['class'];
            }

            //导航行业以及热门行业
            $type = db("portal_category")->where("parent_id = 0 and status = 1 and ishidden = 1 and id != 350")
                ->field('id,name,path')->order('list_order asc')->limit(16)->select();
            //更多内容
            $neirong = db('portal_xm')->where('typeid = '.$typeid.' and status = 1 and arcrank = 1')->field('aid,class,title')->orderRaw('rand()')->limit(50)->select();
            //最新资讯
            $newsInfo = db('portal_post')->where('status = 1 and post_status = 1')->field('id,class,post_title,published_time')->order('id desc')->limit(10)->select()->toArray();
            foreach ($newsInfo as $key => $value) {
                $a = explode('/', $value['class']);
                if(in_array('news', $a)){
                    $newsInfo[$key]['class'] = 'news';
                }else{
                    $newsInfo[$key]['class'] = $value['class'];
                }
            }
            //问答
            $newsWenda = db('portal_post')->where('parent_id = 392 and status = 1 and post_status = 1')->field('id,class,post_title,published_time')->order('id desc')->limit(10)->select();
            //友链
            $youlian = db("flink")->where("typeid = ".$id." and ischeck = 1")->order("dtime desc")->limit(50)->select();
            //查询底部数据
            $website = DB('website')->where(['id' => 1])->find();
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
            //图片调用路径
            $img = 'http://www.91chuangye.com';
            $this->assign('img',$img);
            $this->assign('website',$website);
            $this->assign("name",$name);
            $this->assign('imgs_arrs',$imgs_arrs);
            $this->assign("imgs",$imgs);
            $this->assign('data',$data);
            $this->assign('lick1',$lick1);
            $this->assign('pinpai',$pinpai);
            $this->assign('abouttype',$abouttype);
            $this->assign('hotpinpai1',$hotpinpai1);
            $this->assign('hotpinpai2',$hotpinpai2);
            $this->assign('pinpaipaihang',$pinpaipaihang);
            $this->assign('neirong',$neirong);
            $this->assign('newsInfo',$newsInfo);
            $this->assign('newsWenda',$newsWenda);
            $this->assign('lick5',$lick5);
            $this->assign("lick7",$lick7);
            $this->assign("lick8",$lick8);
            $this->assign('array_reverse',$array_reverse);
            $this->assign('type',$type);
            $this->assign('catess',$catess);
            $this->assign('datas',$datas);
            $this->assign('id',$id);
            $this->assign('youlian',$youlian);
            return $this->fetch(':article_xm');
        }
    }

    //项目详情招商海报页面
    public function article_poster($id)
    {
        if (\think\Request::instance()->isMobile()) {
            $data = db('portal_xm')->where("aid = $id")->find();
            $category = db('portal_category')->where('id = '.$data['typeid'])->field('name,path')->find();
            $data['category'] = $category['name'];
            $post=$this->request->param();
            //判断当前class是不是对应的
//            if($data['class'] != $post['classname'] && $post['classname'] != 'yangsheng'){
//                return $this->error1();
//            }
            if($data['nativeplace'])
            {
                $nativeplace = db('sys_enum')->where("evalue = ".$data['nativeplace']." and py != ''")->value("ename");
                $data['address'] = $nativeplace;
            }else{
                $data['address'] = $data['address'];
            }
            $typeid = $data['typeid'];
            $name = db("portal_category")->where("id = ".$typeid.' and status = 1 and ishidden = 1')->value("name");
            //项目咨询
            $did = db('portal_post')->where('did = '.$id.' and post_status = 1 and status = 1')->field('id,post_title,published_time,class')->limit(5)->select()->toArray();

            if(empty($did) || count($did)<5){
                $where['a.post_title'] = [ 'like', "%".$data['title']."%"];
                $where['a.post_status'] = 1;
                $where['a.status'] = 1;
                $did = db('portal_post a')->where($where)->field('id,post_title,published_time,class')->limit(5)->select()->toArray();
            }

            if(empty($did)){
                $did = db('portal_post')->where('post_status = 1 and status = 1')->field('id,post_title,published_time,class')->order('id desc')->limit(5)->select();
            }
            //项目推荐
            $txiangmu = db('portal_xm')->where('typeid = '.$data['typeid'].' and status = 1 and arcrank = 1')->field('aid,title,sum,companyname,invested,class,litpic')->limit(10)->select();

            $typeid = db('portal_xm')->where("aid = $id and status = 1 and arcrank = 1")->value('typeid');
            $imgs = db("uploads")->where("arcid = ".$id)->select();
            $imgs_arr  = $this->get_pic_url(htmlspecialchars_decode($data['jieshao'].$data['tiaojian'].$data['liucheng']));
            $imgs_arrs = [];
            foreach ($imgs_arr as $k => $v ){
                $imgs_arrs[$k]['url'] = $v;
                $imgs_arrs[$k]['title'] = '';
            }

            $this->assign('id',$id);
            $this->assign("name",$name);
            $this->assign('imgs_arrs',$imgs_arrs);
            $this->assign("imgs",$imgs);
            $this->assign('data',$data);
            $this->assign('did',$did);
            $this->assign('txiangmu',$txiangmu);
            return $this->fetch(':mobile/article_poster');
        }else{
            $array_reverse = "";
            $post=$this->request->param();
            $data = db('portal_xm')->where("aid = $id")->find();
            if($data['nativeplace'])
            {
                $nativeplace = db('sys_enum')->where("evalue = ".$data['nativeplace']." and py != ''")->value("ename");
                $data['address'] = $nativeplace;
            }else{
                $data['address'] = $data['address'];
            }
            $typeid = $data['typeid'];
            $name = db("portal_category")->where("id = ".$typeid.' and status = 1 and ishidden = 1')->field('name,path')->find();
            $array_reverse = $this->position($typeid);
            //相关项目
            $lick1 = db('portal_xm')->where("typeid = $typeid and status = 1 and arcrank = 1")->field('aid,title,litpic,invested,sum,class')->order('click asc')->limit(0,4)->select();
            //品牌项目
            $pinpai = db('portal_xm')->where("invested = "."'$data[invested]' and status = 1 and arcrank = 1")->field('aid,title,litpic,invested,sum,class')->order('click desc')->limit(4)->select();
            //相关分类
            $about = db('portal_category')->where('id = '.$data['typeid'])->field('parent_id')->find();
            $abouttype = db('portal_category')->where('parent_id = '.$about['parent_id'])->field('name,path')->limit(14)->select();
            $hotpinpai = db('portal_xm')->where('status = 1 and arcrank = 1')->field('aid,title,typeid,class,invested,litpic')->order('click desc')->limit(19)->select();
            $hotpinpai = $hotpinpai->all();
            foreach ($hotpinpai as $k=>$v){
                $catetype = db('portal_category')->where('id = '.$v['typeid'])->field('name,path')->find();
                $hotpinpai[$k]['cate'] = $catetype['name'];
                $hotpinpai[$k]['class2'] = $catetype['path'];
            }
            $hotpinpai1 = array_slice($hotpinpai,0, 3);
            $hotpinpai2 = array_slice($hotpinpai,3, 16);
            //品牌排行
            $pinpaipaihang = db('portal_xm')->where("class = "."'$data[class]' and status = 1 and arcrank = 1")->field('aid,title,invested,litpic,sum,class')->order('click desc')->limit(10)->select();
            $w = "FIND_IN_SET('a',flag)";
            $lick5 = db('portal_xm')->where($w)->where('status = 1 and arcrank = 1')->field('aid,title,class,litpic,click,invested')->limit(0,5)->select();
            //项目关联图片表
            $imgs = db("uploads")->where("arcid = ".$id)->select();
            $imgs_arr  = $this->get_pic_url(htmlspecialchars_decode($data['jieshao'].$data['tiaojian'].$data['liucheng']));
            $imgs_arrs = [];
            foreach ($imgs_arr as $k => $v ){
                $imgs_arrs[$k]['url'] = $v;
                $imgs_arrs[$k]['title'] = '';
            }
            //项目相关新闻
            $lick7 = db("portal_post")->where('did = '.$data['aid'].' and status = 1 and post_status = 1')->field('id,post_title,class,published_time')->limit(7)->select();
            if(empty($lick7)){
                $wherew['post_title'] = ['like','%'.$data['title'].'%'];
                $wherew['status'] = 1;
                $wherew['post_status'] = 1;
                $lick7 = db("portal_post")->where($wherew)->field('id,post_title,class,published_time')->limit(10)->select();
            }
            if(isset($lick7)){
                $lick7 = db("portal_post")->where('status = 1 and post_status = 1 and parent_id = 401')->field('id,post_title,class,published_time')->limit(10)->select();
            }
            //专题新闻
            $lick8 = db('portal_post')->where('status = 1 and post_status = 1 and parent_id = 401')->field('id,post_title,class,published_time')->limit(10,10)->select();
            //导航行业以及热门行业
            $type = db("portal_category")->where("parent_id = 0 and status = 1 and ishidden = 1 and id != 350")->field('id,name,path')->order('list_order asc')->limit(15)->select();
            //更多内容
            $neirong = db('portal_xm')->where('status = 1 and arcrank = 1')->field('aid,class,title')->order('click desc')->limit(15)->select();
            //最新资讯
            $newsInfo = db('portal_post')->where('status = 1 and post_status = 1')->field('id,class,post_title,published_time')->order('id desc')->limit(6)->select();
            //问答
            $newsWenda = db('portal_post')->where('parent_id = 392 and status = 1 and post_status = 1')->field('id,class,post_title,published_time')->order('id desc')->limit(6)->select();
            //项目海报
            $haibao = db('uploads')->where('arcid = '.$id)->field('url')->select();
            //友链
            $youlian = db("flink")->where("typeid = ".$id." and ischeck = 1")->order("dtime desc")->limit(50)->select();
            //查询底部数据
            $website = DB('website')->where(['id' => 1])->find();
            $this->assign('website',$website);
            $this->assign("name",$name);
            $this->assign('imgs_arrs',$imgs_arrs);
            $this->assign("imgs",$imgs);
            $this->assign('data',$data);
            $this->assign('lick1',$lick1);
            $this->assign('pinpai',$pinpai);
            $this->assign('abouttype',$abouttype);
            $this->assign('hotpinpai1',$hotpinpai1);
            $this->assign('hotpinpai2',$hotpinpai2);
            $this->assign('pinpaipaihang',$pinpaipaihang);
            $this->assign('neirong',$neirong);
            $this->assign('newsInfo',$newsInfo);
            $this->assign('newsWenda',$newsWenda);
            $this->assign('lick5',$lick5);
            $this->assign("lick7",$lick7);
            $this->assign("lick8",$lick8);
            $this->assign('array_reverse',$array_reverse);
            $this->assign('type',$type);
            $this->assign('id',$id);
            $this->assign('haibao',$haibao);
            $this->assign('youlian',$youlian);
            return $this->fetch(':article_poster');
        }
    }

    private function get_pic_url($content){
        $pattern="/<[img|IMG].*?src=[\'|\"](.*?(?:[\.gif|\.jpg]))[\'|\"].*?[\/]?>/";//正则
        preg_match_all($pattern,$content,$match);//匹配图片
        return $match[1];//返回所有图片的路径
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