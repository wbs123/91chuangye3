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
namespace app\admin\controller;

use cmf\controller\AdminBaseController;
use app\portal\model\PortalPostModel;
use app\portal\service\PostService;
use app\portal\model\PortalCategoryModel;
use think\Db;
use app\admin\model\ThemeModel;

class AdminArticleController extends AdminBaseController
{
    /**
     * 文章列表
     * @adminMenu(
     *     'name'   => '文章管理',
     *     'parent' => 'portal/AdminIndex/default',
     *     'display'=> true,
     *     'hasView'=> true,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '文章列表',
     *     'param'  => ''
     * )
     */
    public function index()
    {
        $content = hook_one('portal_admin_article_index_view');

        if (!empty($content)) {
            return $content;
        }

        $param = $this->request->param();

        $categoryId = $this->request->param('category', 0, 'intval');

        $postService = new PostService();

        #搜索条件URL开始
        $page = !isset($param['page']) ? 1 : $param['page'];
        $param['writer'] = isset($param['writer']) ? $param['writer'] : '';
        $param['flags'] = isset($param['flags']) ? $param['flags'] : '';
        $param['artid'] = isset($param['artid']) ? $param['artid'] : '';
        $param['status'] = isset($param['status']) ? $param['status'] : 0;
        $param['category'] = isset($param['category']) ? $param['category'] : '';
        $param['categorys']= isset($param['categorys']) ? $param['categorys'] : '';
        $param['start_time'] = isset($param['start_time']) ? $param['start_time'] : '';
        $param['end_time'] = isset($param['end_time']) ? $param['end_time'] : '';
        $param['keyword'] = isset($param['keyword']) ? $param['keyword'] : '';
        $url = 'category='.$param['category'].'&categorys='.$param['categorys'].'&start_time='.$param['start_time'].'&end_time='.$param['end_time'].'&keyword='.$param['keyword'].'&status='.$param['status'].'&artid='.$param['artid']."&writer=".$param['writer'].'&flags='.$param['flags'];
        #搜索条件URL结束

        $data        = $postService->adminArticleList($param,$page);
        #生成分页方法 参数：当前页，总页数，0，'url','参数',url参数page/list,总记录数
        $PageHtml = FunCommon::page($page,$data->lastPage(),0,'','&'.$url,'page',$data->total());

        foreach ($data as $key => $value) {
            if($value){
                $a = explode('/', $value['class']);
                if(in_array('news', $a)){
                    $class = 'news';
                }else{
                    $class = $value['class'];
                }
                // $user = db('user')->where('id = '.$value['user_id'])->find();
                // $data[$key]['user'] = $user['user_login'];

                //处理分类
                $cateArr = db('portal_category')->where('id = '.$value['parent_id'])->find();
                $value['cate_name'] = $cateArr['name'];

                //判断http
                $http = $this->is_https() ? 'https://' : 'http://';
                //生成预览URL
                $value['url'] = $http.$_SERVER['HTTP_HOST'].'/'.$class.'/'.$value['id'].'.html';
                $user = db('user')->where('id = '.$value['user_id'])->find();
                $value['user_name'] = $user['user_login'];
            }
        }
        $data->appends($param);

        $portalCategoryModel = new PortalCategoryModel();
        $parentIds = '11,20,32,37,399';
        $categoryTree        = $portalCategoryModel->adminCategoryTree($categoryId,0,$parentIds);

        $this->assign('start_time', isset($param['start_time']) ? $param['start_time'] : '');
        $this->assign('end_time', isset($param['end_time']) ? $param['end_time'] : '');
        $this->assign('keyword', isset($param['keyword']) ? $param['keyword'] : '');
        $this->assign('articles', $data);
        $this->assign('category_tree', $categoryTree);
        $this->assign('category', $categoryId);
        $this->assign('status', $param['status']);
        $this->assign('artid', $param['artid']);
        $this->assign('writer', $param['writer']);
        $this->assign('flags', $param['flags']);
        $this->assign('categorys',$param['categorys']);
        $this->assign('url',$url);
        $this->assign('PageHtml', $PageHtml);


        return $this->fetch();
    }

    /**
     * 添加文章
     * @adminMenu(
     *     'name'   => '添加文章',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> true,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '添加文章',
     *     'param'  => ''
     * )
     */
    public function add()
    {
        $content = hook_one('portal_admin_article_add_view');

        if (!empty($content)) {
            return $content;
        }
        if($_GET){
            $id = $_GET['category'];
            $category = db('portal_category')->where('id = '.$id)->find();
            $this->assign('category',$category['name']);
            $this->assign('categoryid',$category['id']);
        }


        $themeModel        = new ThemeModel();
        $articleThemeFiles = $themeModel->getActionThemeFiles('portal/Article/index');
        $this->assign('article_theme_files', $articleThemeFiles);
        return $this->fetch()   ;
    }

    /**
     * 添加文章提交
     * @adminMenu(
     *     'name'   => '添加文章提交',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '添加文章提交',
     *     'param'  => ''
     * )
     */
    public function addPost()
    {
        if ($this->request->isPost()) {
            $data = $this->request->param();
            $content = isset($data['post']['post_content']) && !empty($data['post']['post_content']) ? $data['post']['post_content'] : '';
            $id = isset($data['post']['did']) && !empty($data['post']['did']) ? intval($data['post']['did']) : 0;
            if(isset($data['post']['default_img'])){
                $img  = FunCommon::get_html_attr_by_tag($content);
                if(!empty($img) ){
                    $data['post']['thumbnail'] = $img;
                }else{
                    unset($data['post']['default_img']);
                }
            }
            //状态只能设置默认值。未发布、未置顶、未推荐
            //判断是否是超管
            $data['post']['post_status'] = session('ADMIN_ID') == 1 ? 1 : 0;
            $data['post']['is_top']      = 0;
            $data['post']['recommended'] = 0;
            $data['post']['user_id'] = session('ADMIN_ID');
            $data['post']['post_content'] = FunCommon::replace_html_keyword($content,$id);

            // $post = $data['post'];
            //$result = $this->validate($post, 'AdminArticle');
            //if ($result !== true) {
            //    $this->error($result);
            //}
            //处理自定义属性
            if(!isset($data['flags'])) $data['flags']  = [];
            $data['post']['flag'] = '';
            foreach($data['flags'] as $k => $v ){
                $data['post']['flag'] .= $v.',';
            }
            $data['post']['flag'] = substr($data['post']['flag'],0,-1);
            unset($data['flags']);

            $portalPostModel = new PortalPostModel();

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
            $path = db('portal_category')->where('id = '.$data['post']['categories'])->find();
            $data['post']['class'] = $path['path'];
            $data['post']['parent_id'] = $data['post']['categories'];
            $data['post']['status'] = 1;
            if(!empty($data['post']['author'])){
                $data['post']['author'] = $data['post']['author'];
            }else{
                unset($data['post']['author']);
            }
           // dump($data['post']);die;
            $portalPostModel->adminAddArticle($data['post'], $data['post']['categories']);

            $data['post']['id'] = $portalPostModel->id;
            $hookParam          = [
                'is_add'  => true,
                'article' => $data['post']
            ];
            hook('portal_admin_after_save_article', $hookParam);


            $this->success('添加成功!', url('AdminArticle/index', ['id' => $portalPostModel->id]));
        }

    }

    /**
     * 编辑文章
     * @adminMenu(
     *     'name'   => '编辑文章',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> true,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '编辑文章',
     *     'param'  => ''
     * )
     */
    public function edit()
    {
        $content = hook_one('portal_admin_article_edit_view');

        if (!empty($content)) {
            return $content;
        }

        $id = $this->request->param('id', 0, 'intval');

        $portalPostModel = new PortalPostModel();
        $post            = $portalPostModel->where('id', $id)->find();
        $post['flags'] = explode(',',$post['flag']);
//        $postCategories  = $post->categories()->alias('a')->column('a.name', 'a.id');
        $postCategories  = Db('portal_category')->alias('a')->where("id = $post[parent_id]")->column('a.name', 'a.id');
        $postCategoryIds = implode(',', array_keys($postCategories));
        $themeModel        = new ThemeModel();
        $articleThemeFiles = $themeModel->getActionThemeFiles('portal/Article/index');
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
            if(isset($data['post']['default_img'])){
                $img  = FunCommon::get_html_attr_by_tag($data['post']['post_content']);
                if(!empty($img) ){
                    //获取域名
//                    $serviceName = $_SERVER['SERVER_NAME'];
//                    $serviceArr  = explode($serviceName,$img);
                    $data['post']['thumbnail'] = $img;
                }else{
                    $data['post']['thumbnail'] = '';
                }
            }else{
                $data['post']['thumbnail'] = '';
            }
            unset($data['post']['default_img']);
            //需要抹除发布、置顶、推荐的修改。
            unset($data['post']['post_status']);
            unset($data['post']['is_top']);
            unset($data['post']['recommended']);

            // $post   = $data['post'];
            // $result = $this->validate($post, 'AdminArticle');
            // if ($result !== true) {
            //     $this->error($result);
            // }
            //处理自定义属性
            if(!isset($data['flags'])) $data['flags']  = [];
            $data['post']['flag'] = '';
            foreach($data['flags'] as $k => $v ){
                $data['post']['flag'] .= $v.',';
            }
            $data['post']['flag'] = substr($data['post']['flag'],0,-1);
            unset($data['flags']);

            $portalPostModel = new PortalPostModel();

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

            $path = db('portal_category')->where('id = '.$data['post']['categories'])->find();
            $data['post']['class'] = $path['path'];
            $data['post']['parent_id'] = $data['post']['categories'];

            $portalPostModel->adminEditArticle($data['post'], $data['post']['categories']);

            $hookParam = [
                'is_add'  => false,
                'article' => $data['post']
            ];
            hook('portal_admin_after_save_article', $hookParam);

           // $this->success('保存成功!');
          $this->success('保存成功!', url('AdminArticle/index'));

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
        $param           = $this->request->param();
        $portalPostModel = new PortalPostModel();

        if (isset($param['id'])) {
            $id           = $this->request->param('id', 0, 'intval');
            $result       = $portalPostModel->where(['id' => $id])->find();
            $data         = [
                'object_id'   => $result['id'],
                'create_time' => time(),
                'table_name'  => 'portal_post',
                'name'        => $result['post_title'],
                'user_id'     => cmf_get_current_admin_id()
            ];
            $resultPortal = $portalPostModel
                ->where(['id' => $id])
                ->update(['delete_time' => time(),'status' => 0]);
            // if ($resultPortal) {
                Db::name('portal_post')->where(['id' => $id])->update(['status' => 0]);
                // Db::name('portal_tag_post')->where(['post_id' => $id])->update(['status' => 0]);
                Db::name('recycleBin')->insert($data);
            // }
            $this->success("删除成功！", '');

        }

        if (isset($param['ids'])) {
            $ids     = $this->request->param('ids/a');
            $recycle = $portalPostModel->where(['id' => ['in', $ids]])->select();
            $result  = $portalPostModel->where(['id' => ['in', $ids]])->update(['delete_time' => time()]);
            if ($result) {
                Db::name('portal_post')->where(['id' => ['in', $ids]])->update(['status' => 0]);
                // Db::name('portal_tag_post')->where(['post_id' => ['in', $ids]])->update(['status' => 0]);
                foreach ($recycle as $value) {
                    $data = [
                        'object_id'   => $value['id'],
                        'create_time' => time(),
                        'table_name'  => 'portal_post',
                        'name'        => $value['post_title'],
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
     *     'name'   => '文章发布',
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
        $portalPostModel = new PortalPostModel();

        if (isset($param['ids']) && isset($param["yes"])) {
            $ids = $this->request->param('ids/a');

            $portalPostModel->where(['id' => ['in', $ids]])->update(['post_status' => 1, 'published_time' => time()]);

            $this->success("发布成功！", '');
        }

        if (isset($param['ids']) && isset($param["no"])) {
            $ids = $this->request->param('ids/a');

            $portalPostModel->where(['id' => ['in', $ids]])->update(['post_status' => 0]);

            $this->success("取消发布成功！", '');
        }

    }

    /**
     * 文章置顶
     * @adminMenu(
     *     'name'   => '文章置顶',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '文章置顶',
     *     'param'  => ''
     * )
     */
    public function top()
    {
        $param           = $this->request->param();
        $portalPostModel = new PortalPostModel();

        if (isset($param['ids']) && isset($param["yes"])) {
            $ids = $this->request->param('ids/a');

            $portalPostModel->where(['id' => ['in', $ids]])->update(['is_top' => 1]);

            $this->success("置顶成功！", '');

        }

        if (isset($_POST['ids']) && isset($param["no"])) {
            $ids = $this->request->param('ids/a');

            $portalPostModel->where(['id' => ['in', $ids]])->update(['is_top' => 0]);

            $this->success("取消置顶成功！", '');
        }
    }

    /**
     * 文章推荐
     * @adminMenu(
     *     'name'   => '文章推荐',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '文章推荐',
     *     'param'  => ''
     * )
     */
    public function recommend()
    {
        $param           = $this->request->param();
        $portalPostModel = new PortalPostModel();

        if (isset($param['ids']) && isset($param["yes"])) {
            $ids = $this->request->param('ids/a');

            $portalPostModel->where(['id' => ['in', $ids]])->update(['recommended' => 1]);

            $this->success("推荐成功！", '');

        }
        if (isset($param['ids']) && isset($param["no"])) {
            $ids = $this->request->param('ids/a');

            $portalPostModel->where(['id' => ['in', $ids]])->update(['recommended' => 0]);

            $this->success("取消推荐成功！", '');

        }
    }

    /**
     * 文章排序
     * @adminMenu(
     *     'name'   => '文章排序',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '文章排序',
     *     'param'  => ''
     * )
     */
    public function listOrder()
    {
        parent::listOrders(Db::name('portal_category_post'));
        $this->success("排序更新成功！", '');
    }

    public function move()
    {

    }

    public function copy()
    {

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
