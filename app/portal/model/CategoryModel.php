<?php

namespace app\portal\model;

use think\Model;
use think\Db;

//栏目类
class CategoryModel extends Model
{

    //根据条件查询分类
    static public function allList($where=[],$field='',$limit='',$order='list_order',$sort='asc'){

        $where = array_merge(['status'=>1,'ishidden'=>1],$where);
        return DB::name("portal_category")->field($field)->where($where)->limit($limit)->order($order.' '.$sort)->select();

    }
    //return array
    static public function allListArray($where=[],$field='',$limit='',$order='list_order',$sort='asc'){

        $where = array_merge(['status'=>1,'ishidden'=>1],$where);
        return DB::name("portal_category")->field($field)->where($where)->limit($limit)->order($order.' '.$sort)
            ->select()
            ->toArray();

    }
    //查询单条数据
    static public function getOne($where=[],$field=''){

        $where = array_merge(['status'=>1,'ishidden'=>1],$where);
        return DB::name("portal_category")->field($field)->where($where)->find();

    }
    //查询单条数据
    static public function getOneColumn($where=[],$field='',$limit=''){

        $where = array_merge(['status'=>1,'ishidden'=>1],$where);
        return DB::name("portal_category")->where($where)->limit($limit)->column($field);

    }
	//查询单条数据
    static public function getOneValue($where=[],$field=''){

        $where = array_merge(['status'=>1,'ishidden'=>1],$where);
        return DB::name("portal_category")->where($where)->value($field);

    }
	//获取分类树
    static public function tree(){
        $where = [
            'status'=>1
            ,'ishidden'=>1
            ,'parent_id'=>0
            ,'channeltype'=>17
            ,'id' =>['notin',['350','63','391','432']]
        ];
        $data =  DB::name("portal_category")
            ->field('id,path,name')->where($where)
            ->order('list_order','asc')
            ->select()->toArray();
		foreach($data as $k=>$v){
			$where = [
				'status'=>1
				,'ishidden'=>1
				,'parent_id'=> $v['id']
			];
			$data[$k]['sondata'] = DB::name("portal_category")
				->field('id,path,mobile_thumbnail,name')->where($where)
				->order('list_order','asc')
				->select()->toArray();
		}
		return $data;
    }

    //获取分类
    static public function getCategory($condition=''){
        $where = [
            'status'=>1
            ,'ishidden'=>1
            ,'parent_id'=>0
            ,'channeltype'=>17
            ,'id' =>['notin',['350','63','391','432']]
        ];

        return DB::name("portal_category")
            ->field('id,path,name')->where($where)->where($condition)
            ->order('list_order','asc')
            ->select()->toArray();
    }
    //获取子分类
    static public function getSonCate($type='',$limit=''){
        $catprint =  DB::name("portal_category")->field('id,parent_id')->where(['path'=>$type])->find();
        $where = [
            'status'=>1
            ,'ishidden'=>1
            ,'parent_id'=> empty($catprint['parent_id']) ? $catprint['id'] : $catprint['parent_id']
        ];
        return DB::name("portal_category")
            ->field('id,path,name')->where($where)
            ->limit($limit)
            ->order('list_order','asc')
            ->select()->toArray();
    }
    //获取分类信息
    static public function categoryData($where){
        return DB::name("portal_category")->where($where)->find();
    }

}