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

use app\admin\model\RecycleBinModel;
use app\admin\model\RouteModel;
use cmf\controller\AdminBaseController;
use think\Db;

class RecycleBinController extends AdminBaseController
{
    /**
     * 回收站
     * @adminMenu(
     *     'name'   => '回收站',
     *     'parent' => '',
     *     'display'=> false,
     *     'hasView'=> true,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '回收站',
     *     'param'  => ''
     * )
     */
    function index()
    {
        $content = hook_one('admin_recycle_bin_index_view');

        if (!empty($content)) {
            return $content;
        }
        $param = $this->request->param();
        $page = !isset($param['list']) ? 1 : $param['list'];
        $recycleBinModel = new RecycleBinModel();
        $list = $recycleBinModel->order('create_time desc')->paginate(10);
        #生成分页方法 参数：当前页，总页数，0，'url','参数'
        $PageHtml = FunCommon::page($page,$list->lastPage(),0,'','','list',$list->total());
        // 获取分页显示
        $page = $list->render();
        $this->assign('page', $page);
        $this->assign("PageHtml", $PageHtml);
        $this->assign('list', $list);
        return $this->fetch();
    }

    /**
     * 回收站还原
     * @adminMenu(
     *     'name'   => '回收站还原',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '回收站还原',
     *     'param'  => ''
     * )
     */
    function restore()
    {

        $id     = $this->request->param('id');
        $result = Db::name('recycleBin')->where(['id' => $id])->find();

        $tableName = explode('#', $result['table_name']);
        $tableName = $tableName[0];
        //还原资源
        if ($result) {
            $res = Db::name($tableName)
                ->where(['id' => $result['object_id']])
                ->update(['delete_time' => '0']);
            if ($tableName =='portal_post'){
                Db::name('portal_category_post')->where('post_id',$result['object_id'])->update(['status'=>1]);
                Db::name('portal_tag_post')->where('post_id',$result['object_id'])->update(['status'=>1]);
            }

            if ($res) {
                $re = Db::name('recycleBin')->where('id', $id)->delete();
                if ($re) {
                    $this->success("还原成功！");
                }
            }
        }
    }

    /**
     * 回收站彻底删除
     * @adminMenu(
     *     'name'   => '回收站彻底删除',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '回收站彻底删除',
     *     'param'  => ''
     * )
     */
    function delete()
    {
        $id     = $this->request->param('id');
        $result = Db::name('recycleBin')->where(['id' => $id])->find();
        //删除资源
        if ($result) {

            if($result['table_name'] === 'portal_post'){
                $info = Db::name($result['table_name'])->where(['id'=>$result['object_id']])->find();
                preg_match_all('#src="([^"]+?)"#',$info['post_content'], $the);
                if(!empty($the[1])){
                    foreach ($the[1] as $k=>$v){
                        unlink('./'.$v);

                    }
                }
                $re = Db::name($result['table_name'])->where('id', $result['object_id'])->delete();

            }
            if($result['table_name'] === 'portal_xm'){
                $a = Db::name($result['table_name'])->where(['aid'=>$result['object_id']])->find();
                $uploads_img = db('uploads')->where('arcid = '.$a['aid'])->select()->toArray();
                if(!empty($uploads_img)){
                    foreach ($uploads_img as $k=>$v){
                        unlink('./'.$v['url']);
                    }
                }
                $img  = FunCommon::get_html_attr_by_tag($a['jieshao']);
                $imgs  = FunCommon::get_html_attr_by_tag($a['tiaojian']);
                $imga  = FunCommon::get_html_attr_by_tag($a['liucheng']);
                if(!empty($a['logo'])){
                    unlink('./'.$a['logo']);
                }
                if(!empty($a['thumbnail'])){
                    unlink('./'.$a['thumbnail']);
                }
                if(!empty($img)){
                    unlink('./'.$img);
                }
                if(!empty($imgs)){
                    unlink('./'.$imgs);
                }
                if(!empty($imga)){
                    unlink('./'.$imga);
                }

                $re = Db::name($result['table_name'])->where('aid', $result['object_id'])->delete();
                db('uploads')->where('arcid = '.$result['object_id'])->delete();
            }
            if($result['table_name'] === 'portal_category'){
                $re = Db::name($result['table_name'])->where('id', $result['object_id'])->delete();
            }

            if ($re) {
                $res = Db::name('recycleBin')->where('id', $id)->delete();
                if ($res) {
                    $this->success("删除成功！");
                }

            }
        }
    }
}