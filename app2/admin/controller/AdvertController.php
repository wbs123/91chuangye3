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

class AdvertController extends AdminBaseController
{
    /*首页广告位*/
    public function index(){
        $param = $this->request->param();
        $page = !isset($param['page']) ? 1 : $param['page'];
        $keyword = isset($param['keyword']) ? trim($param['keyword']) : '';
        $url = 'keyword='.$keyword;
        $where = array();

        $datas = db('advertisement')->order('id desc')->where($where)->where('is_delete = 2 and source = 1')->paginate(15,false,['page'=>$page])->toArray();
        foreach ($datas['data'] as $k => $v){
           //$datas['data'][$k]['type'] = $v['type'] == 1 ? 'top排行榜二级' : ($v['type'] ==2 ? '资讯列表头部' : '项目列表底部');
            switch ($v['type']){
                case 0:
                    $datas['data'][$k]['type_val'] ='默认分类'; break;
                case 1:
                    $datas['data'][$k]['type_val'] ='首页Banner'; break;
                case 2:
                    $datas['data'][$k]['type_val'] ='首页大牌精选'; break;
                case 3:
                    $datas['data'][$k]['type_val'] ='首页项目推荐大图'; break;
                case 4:
                    $datas['data'][$k]['type_val'] = '排行榜中间8图';break;
                case 5:
                    $datas['data'][$k]['type_val'] = '资讯综合页6项目';break;
            }
//            $datas['data'][$k]['status_val'] = $v['status'] == 1 ? '长期有效' : '按投放时间';
            $user = db('user')->find($v['user_id']);
            $datas['data'][$k]['user_name'] = $user['user_login'];
            $datas['data'][$k]['create_time']  = date('Y-m-d H:i:s',$v['inputtime']);
        }


        #生成分页方法 参数：当前页，总页数，0，'url','参数',url参数page/list,总记录数
        $PageHtml = FunCommon::page($page,$datas['last_page'],0,'','&'.$url,'page',$datas['total']);
        $this->assign('datas', $datas['data']);
        $this->assign('keyword', $keyword);
        $this->assign('PageHtml',$PageHtml);
        return $this->fetch();
    }

    public function add(){
        return $this->fetch();
    }

    public function addpost(){
        $data      = $this->request->param();
        $data['post']['inputtime'] = time();
        $data['post']['user_id'] = session('ADMIN_ID');
        $info = db('advertisement')->insert($data['post']);
        $data['post']['source'] = 1;
        if($info){
            $this->success("添加成功！", url("advert/index"));
        }else{
            $this->success("添加失败！", url("advert/add"));
        }
    }

    public function delete()
    {
        $id = $this->request->param('id', 0, 'intval');
        db('advertisement')->where('id = '.$id)->update(['is_delete' => 1]);

        $this->success("删除成功！", url("advert/index"));
    }

    public function edit()
    {
        $id= $this->request->param('id', 0, 'intval');
        $data = db('advertisement')->where('id = '.$id)->where('is_delete = 2 ')->find();
        $this->assign('data', $data);
        return $this->fetch();
    }

    public function editPost()
    {
        $data      = $this->request->param();
//        if(empty($data['post']['timestart']) || empty($data['post']['timeend']) ){
//            unset($data['post']['timestart']);
//            unset($data['post']['timeend']);
//        }else{
//            $data['post']['timestart'] = strtotime($data['post']['timestart']);
//            $data['post']['timeend'] = strtotime($data['post']['timeend']);
//        }
        $flg = db('advertisement')->where('id = '.$data['post']['id'])->where('is_delete = 2 ')->update($data['post']);
        if($flg !== false ){
            $this->success("保存成功！", url("advert/index"));
        }else{
            $this->success("保存失败！", url("advert/edit",array('id' => $data['id'])));
        }

    }

    public function delete_img(){
        $id     = $this->request->param();
        $data['pic'] = '';
        $info = db('advertisement')->where('id = '.$id['id'])->update($data);
        if($info){
            echo 'ok';
        }else{
         echo 2;
        }
    }

    //手机端广告位
    public function indexm(){
        $param = $this->request->param();
        $page = !isset($param['page']) ? 1 : $param['page'];
        $keyword = isset($param['keyword']) ? trim($param['keyword']) : '';
        $url = 'keyword='.$keyword;
        $where = array();
        $datas = db('advertisement')->order('id desc')->where($where)->where('is_delete = 2 and source = 2')->paginate(15,false,['page'=>$page])->toArray();
        foreach ($datas['data'] as $k => $v){
            //$datas['data'][$k]['type'] = $v['type'] == 1 ? 'top排行榜二级' : ($v['type'] ==2 ? '资讯列表头部' : '项目列表底部');
            switch ($v['type']){
                case 0:
                    $datas['data'][$k]['type_val'] ='默认分类'; break;
                case 1:
                    $datas['data'][$k]['type_val'] ='首页Banner'; break;
                case 2:
                    $datas['data'][$k]['type_val'] ='首页大牌精选'; break;
                case 3:
                    $datas['data'][$k]['type_val'] ='首页项目推荐大图'; break;
                case 4:
                    $datas['data'][$k]['type_val'] = '排行榜中间8图';break;
                case 5:
                    $datas['data'][$k]['type_val'] = '资讯综合页6项目';break;
            }
//            $datas['data'][$k]['status_val'] = $v['status'] == 1 ? '长期有效' : '按投放时间';
            $user = db('user')->find($v['user_id']);
            $datas['data'][$k]['user_name'] = $user['user_login'];
            $datas['data'][$k]['create_time']  = date('Y-m-d H:i:s',$v['inputtime']);
        }

        #生成分页方法 参数：当前页，总页数，0，'url','参数',url参数page/list,总记录数
        $PageHtml = FunCommon::page($page,$datas['last_page'],0,'','&'.$url,'page',$datas['total']);
        $this->assign('datas', $datas['data']);
        return $this->fetch();
    }

    public function addm(){
        return $this->fetch();
    }

    public function addPostm(){
        $data      = $this->request->param();
        $data['post']['inputtime'] = time();
        $data['post']['user_id'] = session('ADMIN_ID');
        $data['post']['source'] = 2;
        $info = db('advertisement')->insert($data['post']);
        if($info){
            $this->success("添加成功！", url("advert/index"));
        }else{
            $this->success("添加失败！", url("advert/add"));
        }
    }

    public function editm()
    {
        $id= $this->request->param('id', 0, 'intval');
        $data = db('advertisement')->where('id = '.$id.' and source = 2')->where('is_delete = 2 ')->find();
        $this->assign('data', $data);
        return $this->fetch();
    }

    public function editPostm()
    {
        $data      = $this->request->param();
        $flg = db('advertisement')->where('id = '.$data['post']['id'])->where('is_delete = 2 and source = 2')->update($data['post']);
        if($flg !== false ){
            $this->success("保存成功！", url("advert/indexm"));
        }else{
            $this->success("保存失败！", url("advert/editm",array('id' => $data['id'])));
        }

    }

    public function deletem()
    {
        $id = $this->request->param('id', 0, 'intval');
        db('advertisement')->where('id = '.$id.' and source = 2')->update(['is_delete' => 1]);

        $this->success("删除成功！", url("advert/index"));
    }

    public function delete_imgm(){
        $id     = $this->request->param();
        $data['pic'] = '';
        $info = db('advertisement')->where('id = '.$id['id'])->update($data);
        if($info){
            echo 'ok';
        }else{
            echo 2;
        }
    }

}