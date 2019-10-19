<?php

namespace app\portal\model;

use think\Model;
use think\Db;

class AreaModel extends Model
{

    //当前地区下项目
    public function projectData($param){

        $areaValue = DB::name('sys_enum')->field('evalue')->where(['py'=>$param['area']])->find();

        if(empty($areaValue['evalue'])){
            return false;
        }
        if($areaValue['evalue'] % 500 == 0){
            $fareavalue = intval($areaValue['evalue']);
            $maxvalue = $fareavalue+500;
            $where['evalue'] = [['gt',$fareavalue],['lt',($fareavalue+500)]];
            $sareavalue = DB::name('sys_enum')->field('evalue')->where('evalue > '.$fareavalue.' and evalue < '
                    .$maxvalue)->group('evalue')->select();
            $areaAll = [];
            foreach ($sareavalue as $value){
                if(!in_array(floor($value['evalue']),$areaAll)){
                    $areaAll[] = floor($value['evalue']);
                }
            }
            $areaAll[] = $areaValue['evalue'];
            $where = [
                'por.arcrank' => 1,
                'por.status' => 1,
                'por.nativeplace'=>['in',implode(',',$areaAll)]
            ];
        }else{
            $where = [
                'por.arcrank' => 1,
                'por.status' => 1,
                'por.nativeplace'=>$areaValue['evalue']
            ];
        }
        //页数
        $page = isset($param['page']) ? str_replace('list_','',$param['page']) : '';

        if(!empty($param['type'])){
            if(isset($param['catid'])){
                $where['cat.id'] = ['in',implode(',',$param['catid'])];
            }else{
                $where['cat.path'] = $param['type'];
            }

        }
        if(isset($param['price']) && !empty($param['price'])){
            $where['por.invested'] = $param['price'].'万';
        }

        $data = DB::name('portal_xm')
                ->alias('por')
                ->field('por.*,cat.name as categoryname')
                ->join('portal_category cat','por.typeid = cat.id')
                ->order('por.pubdate')
                ->where($where)->paginate(15,false,['query' => $param,'page'=>$page]);

        return $data;

    }

    //获取项目中地区
    static public function havearea(){
        $where = [
            'arcrank' => 1,
            'status' => 1
        ];
       return DB::name('portal_xm')->field('nativeplace')->group('nativeplace')->where($where)->select();
    }
    //获取所有地区
    static public function allarea($where=''){
        return DB::name('sys_enum')->field('ename,py,evalue')->where(['egroup'=>'nativeplace','id'=>['notin',['21020','21019','21018']]])->where($where)->order
        ('id asc')->select();
    }
    //获取地区名称
    static public function areaName($where){
        return DB::name('sys_enum')->field('ename,evalue')->where(['egroup'=>'nativeplace'])->where($where)->find();
    }

}