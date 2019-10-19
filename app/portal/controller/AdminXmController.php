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

        $content = hook_one('portal_admin_Xm_index_view');

        $data = $this->request->param();
        $categoryId = $this->request->param('category', 0, 'intval');

        $category = empty($data['category']) ? 0 : intval($data['category']);
        if (!empty($category)) {
            $where['typeid'] = ['eq', $category];
        }

        $categorys = empty($data['categorys']) ? '' : $data['categorys'];
        if (!empty($categorys)) {
            $category_arr = db('portal_category')->where("name = '$categorys'")->find();
            $where['typeid'] = ['eq', $category_arr['id']];
        }

        $startTime = empty($data['start_time']) ? 0 : strtotime($data['start_time']);
        $endTime   = empty($data['end_time']) ? 0 : strtotime($data['end_time']);
        if (!empty($startTime) && !empty($endTime)) {
            $where['setup_time'] = [['>= time', $startTime], ['<= time', $endTime]];
        } else {
            if (!empty($startTime)) {
                $where['setup_time'] = ['>= time', $startTime];
            }
            if (!empty($endTime)) {
                $where['setup_time'] = ['<= time', $endTime];
            }
        }

        $keyword = empty($data['keyword']) ? '' : $data['keyword'];
        if (!empty($keyword)) {
            $where['title'] = [ 'like',"%".$keyword."%"];
        }
        $where['status'] = 1;

        
        if (!empty($content)) {
            return $content;
        }
        $portalXmModel = new PortalXmModel();
        // print_r($data);die;
        $xms           = $portalXmModel->where($where)->order('aid','desc')->paginate(50,false,['query' => $data,'page'=>$page]);
        foreach ($xms as $k => $v){
            $category = db('portal_category')->where('id = '.$v['typeid'])->find();
            $xms[$k]['category'] = $category['name'];

            //判断http
            $http = $this->is_https() ? 'https://' : 'http://';
            //生成预览URL
            $v['url'] = $http.$_SERVER['SERVER_NAME'].'/'.$v['class'].'/'.$v['aid'].'.html';
        }

        $portalCategoryModel = new PortalCategoryModel();
        $categoryTree        = $portalCategoryModel->adminCategoryTree($categoryId);
        $this->assign('category_tree', $categoryTree);
        $this->assign("xms", $xms);
        $this->assign('categorys',$categorys);
        // $xms->appends($data);
        $this->assign('page', $xms->render());
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
            $data['post']['post_status'] = 0;
            $data['post']['is_top']      = 0;
            $data['post']['recommended'] = 0;

            $post = $data['post'];

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

            $portalXmModel = new PortalXmModel();

            if (!empty($data['photo_names']) && !empty($data['photo_urls'])) {
                $data['post']['more']['photos'] = [];
                foreach ($data['photo_urls'] as $key => $url) {
                    $photoUrl = cmf_asset_relative_url($url);
                    array_push($data['post']['more']['photos'], ["url" => $photoUrl, "name" => $data['photo_names'][$key]]);
                }
            }

            if (!empty($data['logo_names']) && !empty($data['logo_urls'])) {
                $data['post']['more']['logo'] = [];
                    $photoUrl = cmf_asset_relative_url($data['logo_urls']);
                    array_push($data['post']['more']['logo'], ["url" => $photoUrl, "name" => $data['logo_names']]);
            }

            if (!empty($data['file_names']) && !empty($data['file_urls'])) {
                $data['post']['more']['files'] = [];
                foreach ($data['file_urls'] as $key => $url) {
                    $fileUrl = cmf_asset_relative_url($url);
                    array_push($data['post']['more']['files'], ["url" => $fileUrl, "name" => $data['file_names'][$key]]);
                }
            }

            // foreach ($_SESSION as $k => $v){
            //    $data['post']['writer'] = $v['name'];
            // }
//             dump($data['post']);die;
            $portalXmModel->adminAddArticle($data['post'], $data['post']['typeid']);

            $data['post']['id'] = $portalXmModel->aid;
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
        $post['flags'] = explode(',',$post['flag']);
        //  $postCategories  = $post->categories()->alias('a')->column('a.name', 'a.id');
        $typeid = $post['typeid'];
        $postCategories  = Db('portal_category')->alias('a')->where("id = $typeid")->column('a.name', 'a.id');
        $postCategoryIds = implode(',', array_keys($postCategories));

        $themeModel        = new ThemeModel();
        $articleThemeFiles = $themeModel->getActionThemeFiles('portal/Xm/index');
        $this->assign('article_theme_files', $articleThemeFiles);
        $this->assign('post', $post);
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

            $portalXmModel = new PortalXmModel();

            if (!empty($data['photo_names']) && !empty($data['photo_urls'])) {
                $data['post']['more']['photos'] = [];
                foreach ($data['photo_urls'] as $key => $url) {
                    $photoUrl = cmf_asset_relative_url($url);
                    array_push($data['post']['more']['photos'], ["url" => $photoUrl, "name" => $data['photo_names'][$key]]);
                }
            }

            if (!empty($data['file_names']) && !empty($data['file_urls'])) {
                $data['post']['more']['files'] = [];
                foreach ($data['file_urls'] as $key => $url) {
                    $fileUrl = cmf_asset_relative_url($url);
                    array_push($data['post']['more']['files'], ["url" => $fileUrl, "name" => $data['file_names'][$key]]);
                }
            }
            // foreach ($_SESSION as $k => $v){
            //     $data['post']['writer'] = $v['name'];
            // }
            print_r($data['post']);die;
            //$portalXmModel->adminEditArticle($data['post'], $data['post']['categories']);
            db('portal_xm')->where('aid = '.$data['post']['aid'])->update($data['post']);
            $hookParam = [
                'is_add'  => false,
                'article' => $data['post']
            ];
            hook('portal_admin_after_save_article', $hookParam);

            $this->success('保存成功!');

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
        $id = $this->request->param('id', 0, 'intval');
        if (isset($id)) {
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
                Db::name('portal_xm')->where(['aid' => $id])->update(['status' => 0]);
                Db::name('recycleBin')->insert($data);
            }
//            $resultPortal = $portalXmModel
//                ->where(['aid' => $id])
//                ->update(['delete_time' => time()]);
//            if ($resultPortal) {
//                Db::name('portal_category_post')->where(['post_id' => $id])->update(['status' => 0]);
//                Db::name('portal_tag_post')->where(['post_id' => $id])->update(['status' => 0]);
//
//                Db::name('recycleBin')->insert($data);
//            }
            $this->success("删除成功！", '');

        }

        if (isset($param['ids'])) {
            $ids     = $this->request->param('ids/a');
            $result = $portalXmModel->where(['aid' => ['in', $ids]])->select();
            if ($result) {
                Db::name('portal_category_post')->where(['post_id' => ['in', $ids]])->update(['status' => 0]);
                Db::name('portal_tag_post')->where(['post_id' => ['in', $ids]])->update(['status' => 0]);
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

            $portalXmModel->where(['aid' => ['in', $ids]])->update(['arcrank' => 1, 'pubdate' => time()]);

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
