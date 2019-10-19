<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2018 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 小夏 < 449134904@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\controller;

use app\admin\model\RouteModel;
use cmf\controller\AdminBaseController;
use app\portal\model\PortalCategoryModel;
use think\Db;
use app\admin\model\ThemeModel;


class AdminCategoryController extends AdminBaseController
{
    /**
     * 文章分类列表
     * @adminMenu(
     *     'name'   => '分类管理',
     *     'parent' => 'portal/AdminIndex/default',
     *     'display'=> true,
     *     'hasView'=> true,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '文章分类列表',
     *     'param'  => ''
     * )
     */
    public function index()
    {
        $content = hook_one('portal_admin_category_index_view');

        if (!empty($content)) {
            return $content;
        }

        $portalCategoryModel = new PortalCategoryModel();
        $keyword             = $this->request->param('keyword','','trim');

        if (empty($keyword)) {
            $categoryTree = $portalCategoryModel->adminCategoryTableTree();
            $this->assign('category_tree', $categoryTree);
        } else {
            $categories = db('portal_category')->where(['name'=>['like','%'.$keyword.'%'],'delete_time'=>0])
                ->select()->toArray();
            foreach ($categories as $key => $val){
              $categories[$key]['path'] = substr($val['path'],0,1) == '/' ? substr($val['path'],1) : $val['path'];
                if($val['parent_id'] == 0){
                    $ids = db('portal_category')->order("list_order ASC")->where(['parent_id'=>$val['id']])->column('id');
                    array_unshift($ids, $val['id']);
                    $ids_str = implode(',',$ids);
                   $wherep['status'] = 1;
                  $wherep['post_status'] = 1;
                  $categories[$key]['zixunz'] = db('portal_post')->where(['parent_id'=>['in',$ids_str]])->where($wherep)->count();
                  $wheret['arcrank'] = 1;
                  $wheret['status'] = 1;
                  $categories[$key]['xiangmuz'] = db('portal_xm')->where(['typeid'=>['in',$ids_str]])->where($wheret)->count();
                }else{
                    $categories[$key]['zixunz'] = db('portal_post')->where('parent_id = '.$val['id'].' and status = 1 and post_status = 1')->count();
                    $categories[$key]['xiangmuz'] = db('portal_xm')->where('typeid = '.$val['id'].' and arcrank = 1 and status = 1')->count();
                }
            }
            $this->assign('categories', $categories);
        }
      	$http = $this->is_https() ? 'https://' : 'http://';
		$this->assign('http',$http);
        $this->assign('keyword', $keyword);

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
  
    /**
     * 添加文章分类
     * @adminMenu(
     *     'name'   => '添加文章分类',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> true,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '添加文章分类',
     *     'param'  => ''
     * )
     */
    public function add()
    {
        $content = hook_one('portal_admin_category_add_view');

        if (!empty($content)) {
            return $content;
        }

        $parentId            = $this->request->param('parent', 0, 'intval');
        $portalCategoryModel = new PortalCategoryModel();
        $categoriesTree      = $portalCategoryModel->adminCategoryTree($parentId);

        $themeModel        = new ThemeModel();
        $listThemeFiles    = $themeModel->getActionThemeFiles('portal/List/index');
        $articleThemeFiles = $themeModel->getActionThemeFiles('portal/Article/index');

        $this->assign('list_theme_files', $listThemeFiles);
        $this->assign('article_theme_files', $articleThemeFiles);
        $this->assign('categories_tree', $categoriesTree);
        return $this->fetch();
    }

    /**
     * 添加文章分类提交
     * @adminMenu(
     *     'name'   => '添加文章分类提交',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '添加文章分类提交',
     *     'param'  => ''
     * )
     */
    public function addPost()
    {
        // $portalCategoryModel = new PortalCategoryModel();
        $data = $this->request->param();
        $date['name'] = $data['name'];
        $date['parent_id'] = $data['parent_id'];
        $date['path'] = $data['path'];
        $date['seo_title'] = $data['seo_title'];
        $date['seo_keywords'] = $data['seo_keywords'];
        $date['seo_description'] = $data['seo_description'];
        $date['list_order'] = $data['list_order'];
        $date['channeltype'] = $data['channeltype'];
        $date['description'] = $data['description'];
        $date['zt_title'] = $data['zt_title'];
        $date['zt_keywords'] = $data['zt_keywords'];
        $date['zt_description'] = $data['zt_description'];
        // $result = $this->validate($data, 'PortalCategory');
        // if ($result !== true) {
        //     $this->error($result);
        // }
        // $result = $portalCategoryModel->addCategory($data);
        $result = Db::name('portal_category')->insert($data);
        if ($result === false) {
            $this->error('添加失败!');
        }

        $this->success('添加成功!', url('AdminCategory/index'));

    }

    /**
     * 编辑文章分类
     * @adminMenu(
     *     'name'   => '编辑文章分类',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> true,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '编辑文章分类',
     *     'param'  => ''
     * )
     */
    public function edit()
    {

        $content = hook_one('portal_admin_category_edit_view');

        if (!empty($content)) {
            return $content;
        }

        $id = $this->request->param('id', 0, 'intval');
        if ($id > 0) {
            $category = PortalCategoryModel::get($id)->toArray();

            $portalCategoryModel = new PortalCategoryModel();
            $categoriesTree      = $portalCategoryModel->adminCategoryTree($category['parent_id'], $id);

            $themeModel        = new ThemeModel();
            $listThemeFiles    = $themeModel->getActionThemeFiles('portal/List/index');
            $articleThemeFiles = $themeModel->getActionThemeFiles('portal/Article/index');

            $routeModel = new RouteModel();
            $alias      = $routeModel->getUrl('portal/List/index', ['id' => $id]);

            $category['alias'] = $alias;
            $this->assign($category);
            $this->assign('list_theme_files', $listThemeFiles);
            $this->assign('article_theme_files', $articleThemeFiles);
            $this->assign('categories_tree', $categoriesTree);
            return $this->fetch();
        } else {
            $this->error('操作错误!');
        }

    }

    /**
     * 编辑文章分类提交
     * @adminMenu(
     *     'name'   => '编辑文章分类提交',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '编辑文章分类提交',
     *     'param'  => ''
     * )
     */
    public function editPost()
    {
        $data = $this->request->param();
        $date['name'] = $data['name'];
        $date['parent_id'] = $data['parent_id'];
        $date['path'] = $data['path'];
        $date['seo_title'] = $data['seo_title'];
        $date['seo_keywords'] = $data['seo_keywords'];
        $date['seo_description'] = $data['seo_description'];
        $date['list_order'] = $data['list_order'];
        $date['channeltype'] = $data['channeltype'];
        $date['description'] = $data['description'];
        $date['zt_title'] = $data['zt_title'];
        $date['zt_keywords'] = $data['zt_keywords'];
        $date['zt_description'] = $data['zt_description'];
        if(isset($data['mobile_thumbnail']) && (!empty($data['mobile_thumbnail']))){
            $date['mobile_thumbnail'] = $data['mobile_thumbnail'];
        }else{
            unset($date['mobile_thumbnail']);
        }
        if(isset($data['pc_thumbnail']) && (!empty($data['pc_thumbnail']))){
            $date['pc_thumbnail'] = $data['pc_thumbnail'];
        }else{
            unset($date['pc_thumbnail']);
        }
        // $result = $this->validate($data, 'PortalCategory');

        // if ($result !== true) {
        //     $this->error($result);
        // }

        // $portalCategoryModel = new PortalCategoryModel();

        // $result = $portalCategoryModel->editCategory($data);
        // print_r($date);die;
        $result = db('portal_category')->where('id = '.$data['id'])->update($date);
        // echo db('portal_category')->getLastSql();die;
        if ($result === false) {
            $this->error('保存失败!');
        }

        $this->success('保存成功!');
    }

    /**
     * 文章分类选择对话框
     * @adminMenu(
     *     'name'   => '文章分类选择对话框',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> true,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '文章分类选择对话框',
     *     'param'  => ''
     * )
     */
    public function select()
    {
        $ids                 = $this->request->param('ids');
        $flg                 = $ids                 = $this->request->param('flg');  //如果为1 就是查询指定父类的内容 否则查询全部
        $selectedIds         = explode(',', $ids);
        $portalCategoryModel = new PortalCategoryModel();
        $parentIds = '';
        if($flg  == 1){
            $parentIds = '11,20,32,37,399';
        }
        $tpl = <<<tpl
<tr class='data-item-tr'>
    <td>
        <input type='checkbox' class='js-check' data-yid='js-check-y' data-xid='js-check-x' name='ids[]'
               value='\$id' data-name='\$name' >
    </td>
    <td>\$id</td>
    <td>\$spacer <a href='javascript:;' target='_blank'>\$name</a></td>
</tr>
tpl;
//\$url
        $categoryTree = $portalCategoryModel->adminCategoryTableTree($selectedIds, $tpl,$parentIds);

        $where      = ['delete_time' => 0];
        // $cate_arr = db('portal_category')->where("id","in","11,20,32,37,399")->column('id');
        // $a = json_encode($cate_arr);
        // $cate_arr = json_decode($a,true);
        // print_r($cate_arr);die;
        $where['id'] = ["neq",391];
        $categories = $portalCategoryModel->where($where)->select();

        $this->assign('categories', $categories);
        $this->assign('selectedIds', $selectedIds);
        $this->assign('categories_tree', $categoryTree);
        return $this->fetch();
    }

    /**
     * 文章分类排序
     * @adminMenu(
     *     'name'   => '文章分类排序',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '文章分类排序',
     *     'param'  => ''
     * )
     */
    public function listOrder()
    {
        parent::listOrders(Db::name('portal_category'));
        $this->success("排序更新成功！", '');
    }

    /**
     * 文章分类显示隐藏
     * @adminMenu(
     *     'name'   => '文章分类显示隐藏',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '文章分类显示隐藏',
     *     'param'  => ''
     * )
     */
    public function toggle()
    {
        $data                = $this->request->param();
        $portalCategoryModel = new PortalCategoryModel();

        if (isset($data['ids']) && !empty($data["display"])) {
            $ids = $this->request->param('ids/a');
            $portalCategoryModel->where(['id' => ['in', $ids]])->update(['status' => 1]);
            $this->success("更新成功！");
        }

        if (isset($data['ids']) && !empty($data["hide"])) {
            $ids = $this->request->param('ids/a');
            $portalCategoryModel->where(['id' => ['in', $ids]])->update(['status' => 0]);
            $this->success("更新成功！");
        }

    }

    /**
     * 删除文章分类
     * @adminMenu(
     *     'name'   => '删除文章分类',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '删除文章分类',
     *     'param'  => ''
     * )
     */
    public function delete()
    {
        $portalCategoryModel = new PortalCategoryModel();
        $id                  = $this->request->param('id');
        //获取删除的内容
        $findCategory = $portalCategoryModel->where('id', $id)->find();

        if (empty($findCategory)) {
            $this->error('分类不存在!');
        }
//判断此分类有无子分类（不算被删除的子分类）
        $categoryChildrenCount = $portalCategoryModel->where(['parent_id' => $id,'delete_time' => 0])->count();

        if ($categoryChildrenCount > 0) {
            $this->error('此分类有子类无法删除!');
        }

        $categoryPostCount = Db::name('portal_category_post')->where('category_id', $id)->count();

        if ($categoryPostCount > 0) {
            $this->error('此分类有文章无法删除!');
        }

        $data   = [
            'object_id'   => $findCategory['id'],
            'create_time' => time(),
            'table_name'  => 'portal_category',
            'name'        => $findCategory['name']
        ];
        $result = $portalCategoryModel
            ->where('id', $id)
            ->update(['delete_time' => time(),'ishidden' => 2]); //ishidden 1正常  2删除
        if ($result) {
            Db::name('recycleBin')->insert($data);
            $this->success('删除成功!');
        } else {
            $this->error('删除失败');
        }
    }
    public function excel(){
        return $this->fetch();
    }

    function exceledit($sheet=0){
        $file = $_FILES['excel']['tmp_name'];
        $file = iconv("utf-8", "gb2312", $file);   //转码
        if(empty($file) or !file_exists($file)) {
            die('file not exists!');
        }
        vendor('PHPExcel.Classes.PHPExcel');//引用类文件
        $objRead = new \PHPExcel_Reader_Excel2007();   //实例化 建立reader对象
        if(!$objRead->canRead($file)){
            $objRead = new \PHPExcel_Reader_Excel5();
            if(!$objRead->canRead($file)){
                die('No Excel!');
            }
        }

        $cellName = array('A', 'B', 'C', 'D');

        $obj = $objRead->load($file);  //建立excel对象
        $currSheet = $obj->getSheet($sheet);   //获取指定的sheet表
        $columnH = $currSheet->getHighestColumn();   //取得最大的列号
        $columnCnt = array_search($columnH, $cellName);
        $rowCnt = $currSheet->getHighestRow();   //获取总行数

        $data = array();
        for($_row=2; $_row<=$rowCnt; $_row++){  //读取内容
            for($_column=0; $_column<=$columnCnt; $_column++){
                $cellId = $cellName[$_column].$_row;
                $cellValue = $currSheet->getCell($cellId)->getValue();
                if($cellValue instanceof PHPExcel_RichText){   //富文本转换字符串
                    $cellValue = $cellValue->__toString();
                }

                $data[$_row][$cellName[$_column]] = $cellValue;
            }
        }
// print_r($data);die;
       foreach ($data as $k=>$v){
                $date['zt_title'] = $v['B'];
                $date['zt_keywords'] = $v['C'];
                $date['zt_description'] = $v['D'];
                Db::name('portal_category')->where(['id'=>$v['A']])->update($date);
            }
        $this->success("导入成功！", url("AdminCategory/excel"));

    }

    //即点即改排序
    public function update_list_order()
    {
        $post = $this->request->param();
        $id = $post['id'];
        $date['list_order'] = $post['list_order'];
        if(db('portal_category')->where('id =' .$id)->update($date)){
            echo 'ok';
        }else{
            echo 'fail';
        }
    }
}
