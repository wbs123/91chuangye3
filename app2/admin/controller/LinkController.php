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

use cmf\controller\AdminBaseController;
use app\portal\model\PortalCategoryModel;
use app\admin\model\LinkModel;

class LinkController extends AdminBaseController
{
    protected $targets = ["_blank" => "新标签页打开", "_self" => "本窗口打开"];

    /**
     * 友情链接管理
     * @adminMenu(
     *     'name'   => '友情链接',
     *     'parent' => 'admin/Setting/default',
     *     'display'=> true,
     *     'hasView'=> true,
     *     'order'  => 50,
     *     'icon'   => '',
     *     'remark' => '友情链接管理',
     *     'param'  => ''
     * )
     */
    public function index()
    {
        $content = hook_one('admin_link_index_view');

        if (!empty($content)) {
            return $content;
        }
        $param = $this->request->param();
        $page = !isset($param['page']) ? 1 : $param['page'];
        $keyword = isset($param['keyword']) ? trim($param['keyword']) : '';
        // $linkModel = new LinkModel();
        $url = 'keyword='.$keyword;
        $where = array();
        if(!empty($keyword)){
            $regex = "/\ |\/|\~|\!|\@|\#|\\$|\%|\^|\&|\*|\(|\)|\_|\+|\{|\}|\:|\<|\>|\?|\[|\]|\,|\.|\/|\;|\'|\`|\-|\=|\\\|\|/";
            $keyword = preg_replace($regex,"",$keyword);
            $where['webname'] = ['like', "%$keyword%"];
        }
        $links     = db('flink')->order('id desc')->where($where)->paginate(15,false,['page'=>$page]);;
        #生成分页方法 参数：当前页，总页数，0，'url','参数',url参数page/list,总记录数
        $PageHtml = FunCommon::page($page,$links->lastPage(),0,'','&'.$url,'page',$links->total());
        $this->assign('links', $links);
        $this->assign('keyword', $keyword);
        $this->assign('PageHtml',$PageHtml);
        return $this->fetch();
    }

    /**
     * 添加友情链接
     * @adminMenu(
     *     'name'   => '添加友情链接',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> true,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '添加友情链接',
     *     'param'  => ''
     * )
     */
    public function add()
    {
        $portalCategoryModel = new PortalCategoryModel();
        $categoryId = $this->request->param('category', 0, 'intval');
        $categoryTree        = $portalCategoryModel->adminCategoryTree($categoryId);
        $this->assign('category_tree', $categoryTree);
        $this->assign('targets', $this->targets);
        return $this->fetch();
    }

    /**
     * 添加友情链接提交保存
     * @adminMenu(
     *     'name'   => '添加友情链接提交保存',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '添加友情链接提交保存',
     *     'param'  => ''
     * )
     */
    public function addPost()
    {
        $data      = $this->request->param();
        if($data['did'] == 1){
            $date['typeid'] = 9999;
            $date['webname'] = $data['name'];
            $date['url'] = $data['url'];
            $date['rule'] = $data['rule'];
            $date['ischeck'] = 1;
            $date['did'] = $data['did'];
        }else{
            $date['typeid'] = $data['typeid'];
            $date['webname'] = $data['name'];
            $date['url'] = $data['url'];
            $date['rule'] = $data['rule'];
            $date['ischeck'] = 1;
            $date['did'] = $data['did'];
        }
        
        $info = db('flink')->insert($date);
        if($info){
            $this->success("添加成功！", url("link/index"));
        }else{
            $this->success("添加失败！", url("link/add"));
        }

        
    }

    /**
     * 编辑友情链接
     * @adminMenu(
     *     'name'   => '编辑友情链接',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> true,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '编辑友情链接',
     *     'param'  => ''
     * )
     */
    public function edit()
    {
        $id        = $this->request->param('id', 0, 'intval');

        $klink = db('flink')->where('id = '.$id)->find();


        $portalCategoryModel = new PortalCategoryModel();
        $categoryId = $this->request->param('category', 0, 'intval');
        $categoryTree        = $portalCategoryModel->adminCategoryTree($categoryId);
        $cate = db('portal_category')->where('id = '.$klink['typeid'])->find();
        $klink['cate'] = $cate['name'];
        $klink['typeid'] = $cate['id'];
        $this->assign('did',$klink['did']);
        $this->assign('category_tree', $categoryTree);
        $this->assign('targets', $this->targets);
        $this->assign('link', $klink);
        return $this->fetch();
    }

    /**
     * 编辑友情链接提交保存
     * @adminMenu(
     *     'name'   => '编辑友情链接提交保存',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '编辑友情链接提交保存',
     *     'param'  => ''
     * )
     */
    public function editPost()
    {
        $data      = $this->request->param();
        if($data['did'] == 1){
           $data['typeid'] = 9999;
        }else{
            $data['typeid'] = $data['typeid'];
        }
        db('flink')->where('id = '.$data['id'])->update($data);

        $this->success("保存成功！", url("link/index"));
    }

    /**
     * 删除友情链接
     * @adminMenu(
     *     'name'   => '删除友情链接',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '删除友情链接',
     *     'param'  => ''
     * )
     */
    public function delete()
    {
        $id = $this->request->param('id', 0, 'intval');
        db('flink')->where('id = '.$id)->delete();

        $this->success("删除成功！", url("link/index"));
    }

    /**
     * 友情链接排序
     * @adminMenu(
     *     'name'   => '友情链接排序',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '友情链接排序',
     *     'param'  => ''
     * )
     */
    public function listOrder()
    {
        $linkModel = new  LinkModel();
        parent::listOrders($linkModel);
        $this->success("排序更新成功！");
    }

    /**
     * 友情链接显示隐藏
     * @adminMenu(
     *     'name'   => '友情链接显示隐藏',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '友情链接显示隐藏',
     *     'param'  => ''
     * )
     */
    public function toggle()
    {
        $data      = $this->request->param();
        // $linkModel = new LinkModel();

        if (isset($data['ids']) && !empty($data["display"])) {
            $ids = $this->request->param('ids/a');
            db('flink')->where(['id' => ['in', $ids]])->update(['ischeck' => 1]);
            $this->success("更新成功！");
        }

        if (isset($data['ids']) && !empty($data["hide"])) {
            $ids = $this->request->param('ids/a');
            db('flink')->where(['id' => ['in', $ids]])->update(['ischeck' => 0]);
            $this->success("更新成功！");
        }


    }


    // excel导入
    public function excel(){
        return $this->fetch();
    }

    public function excels(){
        return $this->fetch();
    }

    /**
    *  数据导入
    * @param string $file excel文件
    * @param string $sheet
     * @return string   返回解析数据
     * @throws PHPExcel_Exception
     * @throws PHPExcel_Reader_Exception
    */ 
    function exceladd($sheet=0){ 
        $file = $_FILES['excel']['tmp_name'];
        $file = iconv("utf-8", "gb2312", $file);   //转码 
        if(empty($file) or !file_exists($file)) { 
            die('file not exists!'); 
        } 
         vendor('PHPExcel.Classes.PHPExcel');
        $objRead = new \PHPExcel_Reader_Excel2007();   //建立reader对象 
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
       foreach ($data as $key => $val) {
        if(isset($val['A'])){
           $date['webname'] = $val['A'];
           $date['url'] = $val['B'];
           $date['typeid'] = $val['C'];
           $date['ischeck'] = 1;
           $date['dtime'] = time();
           db('flink')->insert($date);
        }
          
       }
        $this->success("导入成功！", url("link/excel"));
       
       
    }


    function excelsadd($sheet=0){
        $file = $_FILES['excel']['tmp_name'];
        $file = iconv("utf-8", "gb2312", $file);   //转码
        if(empty($file) or !file_exists($file)) {
            die('file not exists!');
        }
        vendor('PHPExcel.Classes.PHPExcel');
        $objRead = new \PHPExcel_Reader_Excel2007();   //建立reader对象
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
//        print_r($data);die;
        foreach ($data as $key => $val) {
            if(isset($val['A'])){
                $date['webname'] = $val['A'];
                $date['url'] = $val['B'];
                $date['typeid'] = $val['C'];
                $date['ischeck'] = 1;
                $date['dtime'] = time();
                $date['did'] = 3;
                db('flink')->insert($date);
            }
        }
        $this->success("导入成功！", url("link/excels"));
    }

}