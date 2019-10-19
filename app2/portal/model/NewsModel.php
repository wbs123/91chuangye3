<?php

namespace app\portal\model;

use think\Model;
use think\Db;

//栏目类
class NewsModel extends Model
{

    //最新资讯
    static public function news($where1=[],$field,$limit=9,$order='id',$sort='desc'){
        $where = array_merge(['status'=>1,'post_status'=>1],$where1);
        $data = Db::name('portal_post')->where($where)->field($field)->limit($limit)->order($order .' '. $sort)->select()->toArray();
        foreach ($data as $k=>$v){
            $data[$k]['cateName'] = Db::name('portal_category')->where(['id'=>$v['parent_id']])->value('name');
            $data[$k]['catePath'] = Db::name('portal_category')->where(['id'=>$v['parent_id']])->value('path');
        }
        return $data;
    }
    //导航资讯内容
    static public function navNews($where=[],$field,$order='published_time',$sort='desc',$limit=5){
        $where = array_merge(['status' =>1,'post_status'=>1],$where);
        $data = Db::name('portal_post')->where($where)->field($field)->limit($limit)->order($order .' '. $sort)->select()->toArray();
        return $data;
    }

    static public function pagelist2($where=[],$total=10){
        $where = array_merge(['status' =>1,'post_status'=>1],$where);
        return Db::name('portal_post')->where($where)->order('published_time desc')->limit($total)->select();
    }


    //分页
    static public function pagelist($where=[],$total=10,$page=1){
        $where = array_merge(['status' =>1,'post_status'=>1],$where);
        return Db::name('portal_post')->where($where)->order('published_time desc')->field('id,class,thumbnail,post_title,post_excerpt,published_time,click,create_time')->paginate($total,false,
            ['page'=>$page]);
    }

    //根据条件查询数据
    static public function conditionlist($where=[],$field='',$limit=10,$order='id',$sort='desc'){
        $where = array_merge(['status'=>1,'post_status'=>1],$where);
        $data = Db::name('portal_post')->where($where)->limit($limit)->field($field)->order($order .' '. $sort)->select();

        return $data;
    }
    //根据条件查询数据Array
    static public function conditionarray($where=[],$field='',$limit=10,$order='id',$sort='desc'){
        $where = array_merge(['status'=>1,'post_status'=>1],$where);
        $data = Db::name('portal_post')->where($where)->limit($limit)->field($field)->order($order .' '. $sort)->select()->toArray();

        return $data;
    }
    //根据条件查询数据
    static public function archives($id){
        Db::name('portal_post')->where('id', $id)->setInc('click');
        return Db::name('portal_post')->where(['id'=>$id])->find();
    }
    //上一篇
    static public function pre($id){
        $id = Db::name('portal_post')->where("id < $id ")->max('id');
        if($id){
            return Db::name('portal_post')->where('status = 1 and post_status = 1')->find($id);
        }
    }
    //下一篇
    static public function next($id){
        $id = Db::name('portal_post')->where("id > $id ")->min('id');
        if($id){
            return Db::name('portal_post')->where('status = 1 and post_status = 1')->find($id);
        }
    }

    //根据一级查询二级id
    static public function NewsYi($where){
        $where = array_merge(['status' =>1,'ishidden'=>1],$where);
        $data = Db::name('portal_category')->where($where)->column('id');
        return $data;
    }

}