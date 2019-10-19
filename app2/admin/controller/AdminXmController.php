<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2018 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author:kane < chengjin005@163.com>
// +----------------------------------------------------------------------
namespace app\admin\controller;

use cmf\controller\AdminBaseController;
use app\portal\model\PortalPostModel;
use app\portal\service\PostService;
use think\Db;
use app\portal\model\PortalCategoryModel;
use app\admin\model\ThemeModel;
use app\portal\model\PortalXmModel;

/**
 * Class AdminXmController 项目管理控制器
 * @package app\portal\controller
 */
class AdminXmController extends AdminBaseController
{
    /**
     * 项目管理
     * @adminMenu(
     *     'name'   => '项目',
     *     'parent' => 'portal/AdminIndex/default',
     *     'display'=> true,
     *     'hasView'=> true,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '项目',
     *     'param'  => ''
     * )
     */
    public function index()
    {


        $content = hook_one('portal_admin_Xm_index_view');

        $data = $this->request->param();
        $categoryId = $this->request->param('category', 0, 'intval');

        #搜索条件URL开始
        $page = !isset($data['page']) ? 1 : $data['page'];
        $data['writer'] = isset($data['writer']) ? $data['writer'] : '';
        $data['flags'] = isset($data['flags']) ? $data['flags'] : '';
        $data['artid'] = isset($data['artid']) ? $data['artid'] : '';
        $data['status'] = isset($data['status']) ? $data['status'] : 0;
        $data['category'] = isset($data['category']) ? $data['category'] : '';
        $data['categorys']= isset($data['categorys']) ? $data['categorys'] : '';
        $data['start_time'] = isset($data['start_time']) ? $data['start_time'] : '';
        $data['end_time'] = isset($data['end_time']) ? $data['end_time'] : '';
        $data['keyword'] = isset($data['keyword']) ? $data['keyword'] : '';
        $url = 'category='.$data['category'].'&categorys='.$data['categorys'].'&start_time='.$data['start_time'].'&end_time='.$data['end_time'].'&keyword='.$data['keyword'].'&status='.$data['status'].'&artid='.$data['artid']."&writer=".$data['writer'].'&flags='.$data['flags'];
        #搜索条件URL结束

        $category = empty($data['category']) ? 0 : intval($data['category']);
        if (!empty($category)) {
            $where['a.typeid'] = ['eq', $category];
        }

        $categorys = empty($data['categorys']) ? '' : $data['categorys'];
        if (!empty($categorys)) {
            $where['c.name'] = ['like', "%".$categorys."%"];
        }

        $startTime = empty($data['start_time']) ? 0 : strtotime($data['start_time']);
        $endTime   = empty($data['end_time']) ? 0 : strtotime($data['end_time']);
        if (!empty($startTime) && !empty($endTime)) {
            $where['a.setup_time'] = [['>= time', $startTime], ['<= time', $endTime]];
        } else {
            if (!empty($startTime)) {
                $where['a.setup_time'] = ['>= time', $startTime];
            }
            if (!empty($endTime)) {
                $where['a.setup_time'] = ['<= time', $endTime];
            }
        }
        $status = isset($data['status']) ? $data['status'] : 0 ;
        if($status>0){
            $where['a.arcrank'] = 1;
            if($status > 1 ){
                $where['a.arcrank'] = 0;
            }
        }
        $artid = empty($data['artid']) ? '' : $data['artid'];
        if(!empty($artid)){
            $where['a.aid'] = $artid;
        }
        $keyword = empty($data['keyword']) ? '' : $data['keyword'];
        if (!empty($keyword)) {
            $where['a.title'] = [ 'like',"%".$keyword."%"];
        }
        if (!empty(trim($data['writer']))) {
            // $where['b.user_login'] = [ 'like',"%".$data['writer']."%"];
            $where['a.writer'] = [ 'like',"%".$data['writer']."%"];
        }
        $where1 = '';
        if (!empty($data['flags'])) {
            $where1 = 'FIND_IN_SET("'.$data['flags'].'",`a`.`flag`)';
//            $where['a.flag'] = [ 'find_in_set',$data['flags'] ];
        }
        $where['a.status'] = 1;


        if (!empty($content)) {
            return $content;
        }
        $portalXmModel = new PortalXmModel();
//        print_r($where);die;
        $xms = $portalXmModel
            ->alias('a')
            // ->join('user b ','a.user_id = b.id','LEFT')
            ->join('portal_category c','a.typeid = c.id','LEFT')
            ->where($where)
            ->where($where1)
            ->order('a.update_time','desc')
            ->paginate(50,false,['query' => $data,'page'=>$page]);
        //echo $portalXmModel->getLastSql();die;
        #生成分页方法 参数：当前页，总页数，0，'url','参数',url参数page/list,总记录数
        $PageHtml = FunCommon::page($page,$xms->lastPage(),0,'','&'.$url,'page',$xms->total());
        foreach ($xms as $k => $v){
            $category = db('portal_category')->where('id = '.$v['typeid'])->find();
            $xms[$k]['category'] = $category['name'];

            //判断http
            $http = $this->is_https() ? 'https://' : 'http://';
            //生成预览URL
            $v['url'] = $http.$_SERVER['HTTP_HOST'].'/'.$v['class'].'/'.$v['aid'].'.html';
            $user = db('user')->where('id = '.$v['user_id'])->find();
            $v['user_name'] = $user['user_login'];
        }

        $portalCategoryModel = new PortalCategoryModel();
        $categoryTree        = $portalCategoryModel->adminCategoryTree($categoryId,391);
        $this->assign('category_tree', $categoryTree);
        $this->assign("xms", $xms);
        $this->assign("writer", $data['writer']);
        $this->assign('categorys',$categorys);
        $this->assign('keyword',$data['keyword']);
        $this->assign('start_time', isset($data['start_time']) ? $data['start_time'] : '');
        $this->assign('end_time', isset($data['end_time']) ? $data['end_time'] : '');
        $this->assign('status',$data['status']);
        $this->assign('artid', $data['artid']);
        $this->assign('flags', $data['flags']);
        $this->assign('PageHtml', $PageHtml);
        $this->assign('url',$url);
        return $this->fetch();
    }

    /**
     * 添加项目内容
     * @adminMenu(
     *     'name'   => '添加项目',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> true,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '添加项目',
     *     'param'  => ''
     * )
     */
    public function add()
    {
        $content = hook_one('portal_admin_article_add_view');
        $sys = db('sys_enum')->where("egroup= 'nativeplace' AND (evalue MOD 500)=0")->order('disorder asc')->select();
        if (!empty($content)) {
            return $content;
        };

        if($_GET){
            $id = $_GET['category'];
            $category = db('portal_category')->where('id = '.$id)->find();
            $this->assign('category',$category['name']);
            $this->assign('categoryid',$category['id']);
        }

        $this->assign('sys',$sys);
        return $this->fetch();
    }

    /**
     * 添加项目提交
     * @adminMenu(
     *     'name'   => '添加项目提交',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '添加项目提交',
     *     'param'  => ''
     * )
     */
    public function addPost()
    {
        if ($this->request->isPost()) {
            $data = $this->request->param();
            //状态只能设置默认值。未发布、未置顶、未推荐
            $data['post']['arcrank'] = session('ADMIN_ID') == 1 ? 1 : 0;
            $data['post']['is_top']      = 0;
            $data['post']['recommended'] = 0;
            $data['post']['setup_time'] = time();
            $data['post']['user_id'] = session('ADMIN_ID');
            if(session('ADMIN_ID') == 1 ){
                $data['post']['pubdate'] = time();
            }

            if(isset($data['post']['default_img']) && isset($data['post']['jieshao'])){
                $img  = isset($data['post']['jieshao']) ? FunCommon::get_html_attr_by_tag($data['post']['jieshao']) : '';
                $imgs  = isset($data['post']['tiaojian']) ? FunCommon::get_html_attr_by_tag
                ($data['post']['tiaojian']) : '';
                $imga  = isset($data['post']['liucheng']) ? FunCommon::get_html_attr_by_tag
                ($data['post']['liucheng']) : '';
                if(!empty($img) ){
                    $data['post']['litpic'] = $img;
                }else if(!empty($imgs)){
                    $data['post']['litpic'] = $imgs;
                }else if(!empty($imga)){
                    $data['post']['litpic'] = $imga;
                }else{
                    $data['post']['litpic'] = '';
                }
            }
            unset($data['post']['default_img']);
            //

//            $result = $this->validate($post, 'AdminArticle');
//            if ($result !== true) {
//                $this->error($result);
//           }
            //处理自定义属性
            if(!isset($data['flags'])) $data['flags']  = [];
            $data['post']['flag'] = '';
            foreach($data['flags'] as $k => $v ){
                $data['post']['flag'] .= $v.',';
            }
            $data['post']['flag'] = substr($data['post']['flag'],0,-1);
            unset($data['flags']);
            $addressArr = explode('--',$data['post']['address']);
            if(count($addressArr) > 1 ){
                $data['post']['address'] = $addressArr['1'];
            }
            $portalXmModel = new PortalXmModel();
            $data['post']['logo'] = isset($data['post']['logo_name']) ? $data['post']['logo_name'] : '';
            $data['post']['userip'] = $_SERVER["REMOTE_ADDR"];
            $path = db('portal_category')->where('id = '.$data['post']['typeid'])->find();
            $data['post']['class'] = $path['path'];
            $data['post']['setup_time'] = time();

            //获取地区的id值
            if($data['post']['nativeplace_son']){
                $enum = $data['post']['nativeplace_son'];
            }else{
                $enum = $data['post']['address'];
            }
            $enums = db('sys_enum')->where("ename = '$enum'")->value('evalue');
            $data['post']['nativeplace'] = $enums;
            $portalXmModel->adminAddArticle($data['post'], $data['post']['typeid']);

            $data['post']['id'] = $portalXmModel->aid;
            if(isset($data['photo_name'])){
                foreach ($data['photo_name'] as $key => $value) {
                    $date['arcid'] = $data['post']['id'];
                    $date['url'] = $value;
                    Db::name('uploads')->insert($date);
                }
            }



            $hookParam          = [
                'is_add'  => true,
                'article' => $data['post']
            ];
            hook('portal_admin_after_save_article', $hookParam);


            $this->success('添加成功!', url('AdminXm/index'));
        }
    }

    /**
     * 编辑项目
     * @adminMenu(
     *     'name'   => '编辑项目',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> true,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '编辑项目',
     *     'param'  => ''
     * )
     */
    public function edit()
    {
        $content = hook_one('portal_admin_xm_edit_view');

        if (!empty($content)) {
            return $content;
        }

        $id = $this->request->param('id', 0, 'intval');
        $portalXmModel = new PortalXmModel();
        $post            = $portalXmModel->where('aid', $id)->find();


        if(isset($post['nativeplace']) && ($post['nativeplace'] != '')){
            $evalue = $post['nativeplace'] - $post['nativeplace'] % 500;
            $nativeplace = db('sys_enum')->where("evalue = ".$evalue." and py != ''")->field('ename,evalue')->find();
            $post['ename'] = $nativeplace['ename'];
        }else{
            $post['ename'] = '';
        }


        $post['flags'] = explode(',',$post['flag']);
        $img = db('uploads')->where('arcid = '.$id)->select();
        //  $postCategories  = $post->categories()->alias('a')->column('a.name', 'a.id');
        $typeid = $post['typeid'];
        $postCategories  = Db('portal_category')->alias('a')->where("id = $typeid")->column('a.name', 'a.id');
        $postCategoryIds = implode(',', array_keys($postCategories));

        $themeModel        = new ThemeModel();
        $articleThemeFiles = $themeModel->getActionThemeFiles('portal/Xm/index');
        $this->assign('article_theme_files', $articleThemeFiles);
        $this->assign('post', $post);
        $this->assign('img',$img);
        $this->assign('post_categories', $postCategories);
        $this->assign('post_category_ids', $postCategoryIds);

        return $this->fetch();
    }

    /**
     * 编辑文章提交
     * @adminMenu(
     *     'name'   => '编辑文章提交',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '编辑文章提交',
     *     'param'  => ''
     * )
     */
    public function editPost()
    {

        if ($this->request->isPost()) {

            $data = $this->request->param();
            if(isset($data['post']['default_img']) && isset($data['post']['jieshao'])){
                $img  = FunCommon::get_html_attr_by_tag($data['post']['jieshao']);
                $img_arr = explode('/',$img);
                if(in_array('www.91chuangye.com',$img_arr)){
                    unset($img_arr[0]);
                    unset($img_arr[1]);
                    unset($img_arr[2]);
                    $img = '/'.implode('/',$img_arr);
                }

                $imgs = '';
                if(isset($data['post']['tiaojian']) && !empty($data['post']['tiaojian'])){
                    $imgs  = FunCommon::get_html_attr_by_tag($data['post']['tiaojian']);
                    $img_arrs = explode('/',$imgs);
                    if(in_array('www.91chuangye.com',$img_arrs)){
                        unset($img_arrs[0]);
                        unset($img_arrs[1]);
                        unset($img_arrs[2]);
                        $imgs = '/'.implode('/',$img_arrs);
                    }
                }

                $imga = '';
                if(isset($data['post']['liucheng']) && !empty($data['post']['liucheng'])) {
                    $imga = FunCommon::get_html_attr_by_tag($data['post']['liucheng']);
                    $img_arra = explode('/', $imga);
                    if (in_array('www.91chuangye.com', $img_arra)) {
                        unset($img_arra[0]);
                        unset($img_arra[1]);
                        unset($img_arra[2]);
                        $imga = '/' . implode('/', $img_arra);
                    }
                }


                if(!empty($img) ){
                    //获取域名
                    //$serviceName = $_SERVER['SERVER_NAME'];
                    //$serviceArr  = explode($serviceName,$img);
                    $data['post']['litpic'] = $img;
                }else if(!empty($imgs)){
                    $data['post']['litpic'] = $imgs;
                }else if(!empty($imga)){
                    $data['post']['litpic'] = $imga;
                }else{
                    $data['post']['litpic'] = '';
                }
            }
            unset($data['post']['default_img']);
            //需要抹除发布、置顶、推荐的修改。
//            unset($data['post']['post_status']);
//            unset($data['post']['is_top']);
//            unset($data['post']['recommended']);

//            $post   = $data['post'];
//            $result = $this->validate($post, 'AdminArticle');
//            if ($result !== true) {
//                $this->error($result);
//            }
            //处理自定义属性
            if(!isset($data['flags'])) $data['flags']  = [];
            $data['post']['flag'] = '';
            foreach($data['flags'] as $k => $v ){
                $data['post']['flag'] .= $v.',';
            }
            $data['post']['flag'] = substr($data['post']['flag'],0,-1);
            unset($data['flags']);
            $addressArr = explode('--',$data['post']['address']);
            if(count($addressArr) > 1 ){
                $data['post']['address'] = $addressArr['1'];
            }
            // $data['post']['setup_time'] = strtotime($data['post']['setup_time']);

            $portalXmModel = new PortalXmModel();
            if(isset($data['photo_name'])){
                foreach ($data['photo_name'] as $key => $value) {
                    $date['arcid'] = $data['post']['aid'];
                    $date['url'] = $value;
                    Db::name('uploads')->insert($date);
                }
            }
            $path = db('portal_category')->where('id = '.$data['post']['typeid'])->find();
            $data['post']['class'] = $path['path'];
            $data['post']['update_time'] = time();


            //获取地区的id值

//            if($data['post']['nativeplace_son']){
//                $enum = $data['post']['nativeplace_son'];
//            }else{
//                $enum = $data['post']['nativeplace_top'];
//            }
//            print_r($enum);die;
//            $enums = db('sys_enum')->where("ename = '$enum'")->value('evalue');
//            $data['post']['nativeplace'] = $enums;


            $enum1 = $data['post']['nativeplace_son'];
            $enum2 = db('sys_enum')->where("ename = '$enum1'")->value('evalue');

            $enums1 = $data['post']['nativeplace_top'];
            $enums2 = db('sys_enum')->where("ename = '$enums1'")->value('evalue');

//            print_r($enum2);echo'--';
//            print_r($enums2);die;

            $evalue = $enum2 - $enum2 % 500;
            if($evalue == $enums2){
              $data['post']['nativeplace'] = $enum2;
            }else{
              $data['post']['nativeplace'] = $enums2;
            }


            $portalXmModel->adminEditArticle($data['post'], $data['post']['typeid']);
            $hookParam = [
                'is_add'  => false,
                'article' => $data['post']
            ];
            hook('portal_admin_after_save_article', $hookParam);

            $this->success('保存成功!', url('AdminXm/index'));

        }
    }

    /**
     * 文章删除
     * @adminMenu(
     *     'name'   => '文章删除',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '文章删除',
     *     'param'  => ''
     * )
     */
    public function delete()
    {
        $portalXmModel = new PortalXmModel();
        $param           = $this->request->param();

        if (isset($param['id'])) {
            $id = $this->request->param('id', 0, 'intval');
            $result       = $portalXmModel->where(['aid' => $id])->find();
            $data         = [
                'object_id'   => $result['aid'],
                'create_time' => time(),
                'table_name'  => 'portal_xm',
                'name'        => $result['title'],
                'user_id'     => cmf_get_current_admin_id()
            ];
            if($result)
            {
                Db::name('portal_xm')->where(['aid' => $id])->update(['status' => 0,'delete_time'=>time()]);
                Db::name('recycleBin')->insert($data);
            }
            $this->success("删除成功！", '');
        }

        if (isset($param['ids'])) {
            $ids     = $this->request->param('ids/a');
            $result = $portalXmModel->where(['aid' => ['in', $ids]])->select();
            if ($result) {
                Db::name('portal_xm')->where(['aid' => ['in', $ids]])->update(['status' => 0,'delete_time'=>time()]);
                foreach ($result as $value) {
                    $data = [
                        'object_id'   => $value['aid'],
                        'create_time' => time(),
                        'table_name'  => 'portal_xm',
                        'name'        => $value['title'],
                        'user_id'     => cmf_get_current_admin_id()
                    ];
                    Db::name('recycleBin')->insert($data);
                }
                $this->success("删除成功！", '');
            }
        }
    }

    public function delete_img()
    {
        $post = $this->request->param();
        $aid = $post['aid'];
        $date['logo'] = null;
        if(db('portal_xm')->where('aid =' .$aid)->update($date)){
            echo 'ok';
        }else{
            echo 'fail';
        }
    }

    public function deletel_img()
    {
        $post = $this->request->param();
        $aid = $post['aid'];
        $date['thumbnail'] = null;
        if(db('portal_xm')->where('aid =' .$aid)->update($date)){
            echo 'ok';
        }else{
            echo 'fail';
        }
    }
    public function delete_imgall()
    {
        $post = $this->request->param();
        $aid = $post['aid'];
        if(db('uploads')->where('aid =' .$aid)->delete()){
            echo 'ok';
        }else{
            echo 'fail';
        }
    }

    /**
     * 文章发布
     * @adminMenu(
     *     'name'   => '项目发布',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '文章发布',
     *     'param'  => ''
     * )
     */
    public function publish()
    {
        $param           = $this->request->param();
        $portalXmModel = new PortalXmModel();

        if (isset($param['ids']) && isset($param["yes"])) {
            $ids = $this->request->param('ids/a');

            $portalXmModel->where(['aid' => ['in', $ids]])->update(['arcrank' => 1, 'update_time' => time()]);

            $this->success("发布成功！", '');
        }
        if (isset($param['ids']) && isset($param["no"])) {
            $ids = $this->request->param('ids/a');

            $portalXmModel->where(['aid' => ['in', $ids]])->update(['arcrank' => 0]);

            $this->success("取消发布成功！", '');
        }

    }

    /**
     * 更新文章标签状态
     * @adminMenu(
     *     'name'   => '更新标签状态',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '更新标签状态',
     *     'param'  => ''
     * )
     */
    public function upStatus()
    {

    }

    public function liandong()
    {
        $post=Request::instance()->request();
        $post['set']=isset($post['set'])?$post['set']:'10';
        $post['name']=isset($post['name'])?$post['name']:'';
        $where=[];
        if($post['name']!=''){
            $where['a.name']=['like','%'.$post['name'].'%'];
        }

        if(isset($post['grade_id']) && ($post['grade_id']!='')){
            $where['a.grade_id']=['=',$post['grade_id']];
        }

        if(isset($post['serie_id']) && ($post['serie_id']!='')){
            $where['a.serie_id']=['=',$post['serie_id']];
        }

        if(isset($post['category_id']) && ($post['category_id']!='')){
            $where['a.category_id']=['=',$post['category_id']];
        }

        $data=Db::table('cable_cc_type')->alias('a')
            ->join('cable_species_grades b','b.id = a.grade_id')
            ->join('cable_species_series c','c.id = a.serie_id')
            ->join('cable_species_category d','d.id = a.category_id')
            ->where('a.deleted_at','null')
            ->where($where)
            ->field('a.*,b.name names,c.name namess,d.name namesss')
            ->order('a.sort')
            ->paginate($post['set'],false,['query'=>$post]);
// var_dump($post);
//分类
        $arr=Db::name('cc_category')->where('deleted_at','null')->field('name,id,sort')->order('sort')->select();
        $this->assign('arr',$arr);

        if(isset($post['category_id'])){
            $list=Db::name('cc_series')->where('category_id',$post['category_id'])->where('deleted_at','null')->field('id,name,category_id,sort')->order('sort')->select();
            $this->assign('list',$list);
        }
//电压
        if(isset($post['serie_id'])){
            $ste=Db::name('cc_series')->where('id',$post['serie_id'])->value('grade_id');
            $ste=explode(',',$ste);
            $str='';
            foreach($ste as $k=>$v){
                $str[$k]=Db::name('cc_grades')->where('id',$v)->field('id,name')->find();
            }
            $this->assign('ste',$str);
        }

        $post=isset($post)?$post:'';
        $page=$data->render();
        $this->assign('data',$data);
        $this->assign('page',$page);
        $this->assign('post',$post);
        return $this->fetch('model/quota_model');
    }

    public function liuyan()
    {
        return $this->fetch();

    }

    private function is_https() {
        if ( !empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off') {
            return true;
        } elseif ( isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https' ) {
            return true;
        } elseif ( !empty($_SERVER['HTTP_FRONT_END_HTTPS']) && strtolower($_SERVER['HTTP_FRONT_END_HTTPS']) !== 'off') {
            return true;
        }
        return false;
    }

}
