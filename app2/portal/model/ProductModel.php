<?php

namespace app\portal\model;

use think\Model;
use think\Db;

//栏目类
class ProductModel extends Model
{

    //根据条件查询数据
    static public function conditionlist($where=[],$field='',$limit=10,$order='aid',$sort='desc'){

        $where=array_merge(['status' => 1,'arcrank' => 1],$where);
        $data = Db::name('portal_xm')->field($field)->where($where)->limit($limit)->order($order .' '. $sort)->select();
        return $data;
    }
    //return Array
    static public function conditionArray($where=[],$field='',$limit=10,$order='aid',$sort='desc'){

        $where=array_merge(['status' => 1,'arcrank' => 1],$where);

        $data = Db::name('portal_xm')->field($field)->where($where)->limit($limit)->order($order .' '. $sort)->select()->toArray();

        return $data;
    }
    //根据条件查询数据
    static public function getOne($where=[],$field='',$limit=''){

        $where=array_merge(['status' => 1,'arcrank' => 1],$where);

        $data = Db::name('portal_xm')->field($field)->where($where)->limit($limit)->find();

        return $data;
    }
    //根据条件查询数据
    static public function getColumn($where=[],$field=''){

        $where=array_merge(['status' => 1,'arcrank' => 1],$where);

        $data = Db::name('portal_xm')->where($where)->order('aid','desc')->limit(20)->column($field);

        return $data;
    }
    //根据条件查询数据
    static public function getCount($where=[]){

        $where=array_merge(['status' => 1,'arcrank' => 1],$where);

        $data = Db::name('portal_xm')->where($where)->count();

        return $data;
    }
    //查询产品数据及栏目
    public function cplist($where=[],$limit=10){

        $where=array_merge(['a.status' => 1,'a.arcrank' => 1],$where);

        $data = Db::name('portal_xm')
            ->alias('a')
            ->join('portal_category b','a.typeid = b.id')
            ->field('a.aid,a.typeid,a.title,a.class,a.click,a.sum,a.invested,a.description,a.litpic,a.logo,b.name category_name,a.address,a.companyname,a.jieshao,a.nativeplace')
            ->order('click desc')
            ->where($where)
            ->limit($limit)->select()->toArray();
        foreach ($data as $k=>$v){
            $wherea['path'] = [ 'like',"%".'top/'."%"];
            $data[$k]['path2'] = db('portal_category')->where("name = '$v[category_name]' and parent_id != 0")->where($wherea)->value('path');
            $data[$k]['category_name'] = str_replace('加盟','',$v['category_name']);
        }
        return $data;
    }

    //栏目及数据
    static public function typelist($where,$limit=10,$order='click',$sort='desc'){
        $where = array_merge([
            'status'=>1,
            'ishidden'=>1
        ],$where);
        $data = Db::name("portal_category")->where($where)->field('id,name,parent_id,path')->order('list_order asc')->select()
            ->toArray();

        foreach($data as $k=>$v){
            $swhere = [
                'name' => $v['name'],
                'parent_id' => ['neq',$v['parent_id']],
                'ishidden' =>1,
                'status' => 1
            ];
            $data[$k]['path2'] = Db::name('portal_category')->where($swhere)->value('path');
            $cated = Db::name("portal_category")->where(['parent_id' => $v['id'], 'status' => 1, 'ishidden' => 1])->column('id');
            array_push($cated, $v['id']);
            $dwhere = [
                'typeid' => ['in', $cated],
                'status' => 1,
                'arcrank' => 1
            ];

            $product = self::conditionArray($dwhere,'aid,typeid,title,class,sum,click,invested,litpic,description,logo',
                $limit,$order,$sort);
            foreach($product as $ks=>$v){
                $name = Db::name('portal_category')->where(['id'=>$v['typeid']])->value('name');
                $product[$ks]['categoryName'] = str_replace('加盟','',$name);
                $product[$ks]['categoryPath'] = Db::name('portal_category')->where(['id'=>['neq',$v['typeid']],'name'=>$name])->value('path');
            }
            $data[$k]['data'] = $product;
        }

        return $data;
    }
    //调用指定栏目数据
    static public function categroyData($cid,$limit=10,$order='click',$sort='desc'){
        $where = [
            'status'=>1,
            'ishidden'=>1,
            'parent_id'=>$cid
        ];
        $typeid = Db::name("portal_category")->where($where)->field('id,name,path')->order('list_order asc')->column('id');
        array_push($typeid,$cid);
        $typeid = empty($typeid) ? $cid : $typeid;
        $where2['typeid'] = ['in',$typeid];
        $where2['status'] = 1;
        $where2['arcrank'] = 1;
        $data = Db::name('portal_xm')->where($where2)->field('aid,title,class,litpic,invested,description,logo')->order
        ('click desc')->limit($limit)->select();

        return $data;
    }

    //中间推荐八个产品
    static function centerEight(){
        $eight_id = Db::name('advertisement')->where(['type'=>4,'is_delete'=>2])->column('aid');
        $id = explode(',',$eight_id[0]);
        $where = ['aid'=>['in', $id]];
        return Db::name('portal_xm')->where($where)->orderRaw("field(aid,92383,78364,87182,119059,91803,118878,89574,86544)")
            ->field('aid,class,title,click,invested,litpic,sum,companyname,thumbnail')->select();
    }

    //品牌上榜等文字图片
    static function brand($where='',$field='',$limit='4',$order='aid',$sort='desc'){
        return Db::name('portal_xm')
            ->where($where)
            ->field($field)
            ->limit($limit)
            ->order($order,$sort)
            ->select()
            ->toArray();
    }
    //获取项目id
    static public function GetxmId($where,$order,$sort,$column){
        return Db::name('portal_xm')
            ->where($where)
            ->order($order,$sort)
            ->column($column);
    }

}