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
namespace app\portal\controller;

use cmf\controller\HomeBaseController;
use think\cache\driver\Redis;
use think\Db;
use app\portal\model\AreaModel;

class IndexController extends HomeBaseController
{
    function findNum($str=''){
        $str=trim($str);
        if(empty($str)){
            return '';
        }
        $temp=array('1','2','3','4','5','6','7','8','9','0');
        $result='';
        for($i=0;$i<strlen($str);$i++){
            if(in_array($str[$i],$temp)){
                $result.=$str[$i];
            }
        }
        return $result;
    }
    public function index()
    {
//        //修改状态
//        $date['source'] = '';
//        $date['arcrank'] = 1;
//        Db::name('portal_xm')->where(['arcrank'=>0,'source'=>'前景'])->limit(1)->update($date);
//
//
//        //数据入库
//        $a = Db::name('tz_archives')->limit('10,1')->select()->toArray();
//        $log = fopen('./log.txt','a');
//        foreach ($a as $k=>$v){
//            $Xm = Db::name('portal_xm')->where(['title'=>['like','%'.$v['title'].'%']])->value('aid');
//            if(!$Xm){
//                $jmqj = str_replace('前景','91创业网',$v['jmqj']);
//                $jmqj = str_replace('https://www.qj.com.cn/','http://www.91chuangye.com/',$jmqj);
//                $jmqjs = str_replace('4008-755-888','',$jmqj);
//
//                $jmtj = str_replace('前景','91创业网',$v['jmtj']);
//                $jmtj = str_replace('https://www.qj.com.cn/','http://www.91chuangye.com/',$jmtj);
//                $jmtjs = str_replace('4008-755-888','',$jmtj);
//
//
//                $jmlc = str_replace('前景','91创业网',$v['jmlc']);
//                $jmlc = str_replace('https://www.qj.com.cn/','http://www.91chuangye.com/',$jmlc);
//                $jmlcs = str_replace('4008-755-888','',$jmlc);
//                $diqu = Db::name('sys_enum')->where(['ename'=>['like','%'.$v['fyd'].'%']])->value('evalue');
//
//                $date['title'] = $v['title'];
//                $date['typeid'] = 44;
//                $date['litpic'] = $v['litpic'];
//                $date['invested'] = str_replace(' ','',$v['price']);
//                $date['logo'] = $v['logo'];
//                $date['nativeplace'] = $diqu;
//                $date['address'] = $v['fyd'];
//                $date['companyname'] = $v['gongsi'];
//                $date['zhuangtai'] = '开业';
//                $date['setup_time'] = time();
//                $date['flag'] = 'p';
//                $date['click'] = rand(1000, 10000);
//                $date['zhishu'] = rand(1000, 2000);
//                $date['writer'] = '91创业网';
//                $date['source'] = '前景';
//                $date['status'] = 1;
//                $date['arcrank'] = 0;
//                $date['is_org'] = 1;
//                $date['sum'] = rand(100, 999);
//                $date['jieshao'] = isset($jmqjs) ? $jmqjs : '';
//                $date['tiaojian'] = isset($jmtjs) ? $jmtjs : '';
//                $date['liucheng'] = isset($jmlcs) ? $jmlcs : '';
//                $date['company_leixing'] = '国有企业/私有企业/其他企业';
//                $date['ziben'] = 100;
//                $date['shziben'] = 100;
//                $date['weight'] = 100;
//                $date['class'] = 'tongzhuangjiameng';
//                $date['company_address'] = str_replace('<b>公司地址：</b>','',$v['gsarea']);
//                if(empty($jmtjs) && empty($jmlcs)){
////                    echo 123;die;
//                    fwrite($log,$v['title'].'条件、流程为空'."\r\n");
//                }else{
//
//                    $r = Db::name('portal_xm')->insert($date);
//                    if($r){
//                        fwrite($log,$v['title'].'成功'."\r\n");
//                    }else{
//                        fwrite($log,$v['title'].'失败'."\r\n");
//                    }
//                }
//            }else{
//                fwrite($log,$v['title'].'存在'."\r\n");
//            }
//
//        }



//        $a = $this->CategoryModel->getOneColumn(['parent_id'=>['in','399,11,32,20,37,51']],'path');
//        $b = $this->CategoryModel->getOneColumn(['id'=>['in','399,11,32,20,37,51']],'path');
//
////        $a['parent_id'] = ['in','399,11,32,20,37,51'];
////        $b['id'] = ['in','399,11,32,20,37,51'];
////        $cateyi = Db::name('portal_category')->where($b)->column('path');
////        $cate = Db::name('portal_category')->where($a)->column('path');
//        $cate = array_merge($a,$b);
//        print_r($cate);die;
//        if(in_array('news',$cate)){
//echo 123;die;
//        }else{
//            echo 234;die;
//        }
        //4002,3959,3770,3713,3642,3185,
//        $a = Db::name('user_info')->field('url,id')->limit(4000,1000)->select()->toArray();
//        foreach ($a as $k=>$v){
//            $v['url'] = substr($v['url'],-15);
//            $num = $this->findNum($v['url']);
//            $xm = Db::name('portal_xm')->where(['aid'=>$num])->value('typeid');
//            Db::name('user_info')->where(['id'=>$v['id']])->update(['typeid'=>$xm]);
//
//        }

//        $info = db('portal_post','db1')->field('id,create_time,author')->order('id asc')->limit(5000,1000)->select();
//        $log = fopen('./log.txt','a');
//        foreach ($info as $k=>$v){
//            $y = Db::name('archives')->where(['id'=>$v['id']])->field('senddate,writer')->find();
//            $r = db('portal_post','db1')->where(['id'=>$v['id']])->update(['author'=>$y['writer'],'create_time'=>$y['senddate']]);
//            if($r){
//                fwrite($log,$v['id'].'成功'."\r\n");
//            }else{
//                fwrite($log,$v['id'].'失败'."\r\n");
//            }
//        }
//        fclose($log);
//        exit;

//        $data = Db::name('diyform1')->limit(10)->select();
//        dump($data);
//
//        $log = fopen('./log.txt','a');
//        foreach ($data as $val){
//            $insertdata = [
//                'name'=>$val['name'],
//                'tel'=>$val['tel'],
//                'sex'=>$val['sex'],
//                'url'=>$val['yemian'],
//                'source'=>3,
//                'inputtime'=>strtotime($val['time']),
//                'type'=>'old',
//                'msg'=>$val['msg']
//
//            ];
//            $r =  db('user_info','db1')->insert($insertdata);
//            $y = Db::name('addonjiameng')->where(['aid'=>$val['aid']])->field('tiaojian,liucheng,fenxi')->find();
//            $r = db('portal_xm','db1')->where(['aid'=>$val['aid']])->update(['tiaojian'=>$y['tiaojian'],'liucheng'=>$y['liucheng'],'fenxi'=>$y['fenxi']]);
//            if($r){
//                fwrite($log,$val['aid'].'成功'."\r\n");
//            }else{
//                fwrite($log,$val['aid'].'失败'."\r\n");
//            }
//        }
//        fclose($log);
//        exit;


        if (\think\Request::instance()->isMobile()) {
		
            set_time_limit(0);
            $areaModel = new AreaModel();
            //首页分类
            $where['id'] = ['in','2,312,1,3,5,4,7,9,6,10,8,339,313,420,396'];
            $type = db('portal_category')->where($where)->field('name,path')->orderRaw("field(id,2,312,1,3,5,4,7,9,6,10,8,339,313,420,396)")->select();
            //火爆招商
//          $lick7 = db('portal_xm')->where('arcrank =1 and status = 1 and thumbnail!="" and find_in_set("a",flag) ')->limit(3,10)->select();
            $where19['aid'] = ['in','120632,103525,119056,94500,119289,1169,86522,90909,119539,119502,98102,94311,84335,92183,93276,77596,86879,86886,86877,118441'];
            $lick7 = db('portal_xm')->where($where19)->orderRaw("field(aid,120632,103525,119056,94500,119289,1169,86522,90909,119539,119502,98102,94311,84335,92183,93276,77596,86879,86886,86877,118441)")->field('aid,typeid,class,title,click,invested,litpic,sum,companyname,thumbnail')->select()->toArray();
            foreach ($lick7 as $k=>$v){
                $lick7[$k]['cate_name'] = db('portal_category')->where(['id'=>$v['typeid']])->value('name');
                $lick7[$k]['cate_path'] = db('portal_category')->where(['id'=>$v['typeid']])->value('path');
            }

            //项目推荐
            $where20['aid'] = ['in','93055,72114,86936,86993,9050,10120,9214,83968,104352,93996,119350,118876,80367,97174,86895,8968,86931,91118,78235,91119'];
            $tuijian = db('portal_xm')->where($where20)->orderRaw("field(aid,93055,72114,86936,86993,9050,10120,9214,83968,104352,93996,119350,118876,80367,97174,86895,8968,86931,91118,78235,91119)")->field('aid,typeid,class,title,sum,companyname,litpic,invested,thumbnail')->select()->toArray();
            foreach ($tuijian as $k=>$v){
                $tuijian[$k]['cate_name'] = db('portal_category')->where(['id'=>$v['typeid']])->value('name');
                $tuijian[$k]['cate_path'] = db('portal_category')->where(['id'=>$v['typeid']])->value('path');
            }

            //最新入驻
            $newruzhu = db('portal_xm')->where('arcrank = 1 and status = 1')->field('aid,title,invested,litpic,class')->order('aid desc')->limit(10)->select();

            //创业资讯
            $where25['parent_id'] = ['in','399,401,402,403,404,405,406,407,408,409,433'];
            $zixun = db('portal_post')->where($where25)->where('post_status = 1 and status = 1')->field('id,post_title,post_excerpt,thumbnail,published_time,class')->order('published_time desc')->limit(5)->select();


            //创业知识
            $where26['parent_id'] = ['in','20,22,27,31'];
            $zhishi = db('portal_post')->where($where26)->where('post_status = 1 and status = 1')->field('id,post_title,post_excerpt,thumbnail,published_time,class')->order('published_time desc')->limit(5)->select();


            //创业故事
            $where27['parent_id'] = ['in','11'];
            $gushi = db('portal_post')->where($where27)->where('post_status = 1 and status = 1')->field('id,post_title,post_excerpt,thumbnail,published_time,class')->order('published_time desc')->limit(5)->select();


            //创业之道
            $where28['parent_id'] = ['in','32'];
            $zhidao = db('portal_post')->where($where28)->where('post_status = 1 and status = 1')->field('id,post_title,post_excerpt,thumbnail,published_time,class')->order('published_time desc')->limit(5)->select();

            //加盟行业
            $where['id'] = ['in','2,312,1,3,5,4,7,9,6,10,8,339,313,420,396'];
            $type2 = db('portal_category')->where($where)->field('name,path')->orderRaw("field(id,2,312,1,3,5,4,7,9,6,10,8,339,313,420,396)")->select();

            //热门项目
            $arr = '2,1,4,5,7,10,3,6,8,9,312,313,396,420';
            $catess = db("portal_category")->where('id', 'in', $arr)->where('status = 1 and ishidden = 1')->field('id,name,path')->order('list_order asc')->select();
            $cates = $catess->all();
            foreach($cates as $keys=>$v)
            {
                $cated = db('portal_category')->where(['parent_id' => $v['id'],'ishidden' => 1,'status' => 1])->column('id');
                array_unshift($cated, $v['id']);
                $cates[$keys]['ids'] = implode(',', $cated);
            }
            foreach ($cates as $key => $val) {
                $wheres['typeid'] = array('in', $val['ids']);
                $where3['status'] = 1;
                $where3['arcrank'] = 1;
                $val['data'] = db("portal_xm")->where($wheres)->where($where3)->field('aid,title,invested,litpic,class')->order('pubdate asc')->limit(24)->select();
                $datas[] = $val;
            }
            //调用图片地址路径
            $img = 'http://www.91chuangye.com';
            $this->assign('img',$img);
            $this->assign('catess',$catess);
            $this->assign('datas',$datas);
            $this->assign('zixun',$zixun);
            $this->assign('zhishi',$zhishi);
            $this->assign('gushi',$gushi);
            $this->assign('zhidao',$zhidao);
            $this->assign('newruzhu',$newruzhu);
            $this->assign('tuijian',$tuijian);
            $this->assign('lick7',$lick7);
            $this->assign('type',$type);
            $this->assign('type2',$type2);
            $this->assign('sys',$areaModel->allarea('(evalue MOD 500)=0'));
            // //查询数据
            $website = DB('website')->where(['id' => 1])->find();
            $this->assign('website',$website);

            return $this->fetch(':mobile/index');
        } else {
            //$t1 = microtime(true);
            set_time_limit(0);
            //加入redis缓存
            //1 获取redis是否有数据
            // if ( true ) 去数据 并赋值
            // else 查询数据 并存储redis 传值
//            $redis = new Redis();
//            if(false){
////                //取出缓存
//                $news_canyin = json_decode($redis->get('index_news_canyin' ),true);
//                $brandName = json_decode($redis->get('index_brand' ),true);
//                $datu = json_decode($redis->get('index_datu' ),true);
//                $zuice = json_decode($redis->get('index_zuice'),true);
//                $news_hot = json_decode($redis->get('index_news_hot'),true);
//                $news_hot2 = json_decode($redis->get('index_news_hot2' ),true);
//                $dapai = json_decode($redis->get('index_dapai'),true);
//                $lick6 = json_decode($redis->get('index_lick6'),true);
//                $lick7 = json_decode($redis->get('index_lick7'),true);
//                $ban = json_decode($redis->get('index_ban'),true);
//                $hot = json_decode($redis->get('index_hot'),true);
//                $meishi = json_decode($redis->get('index_meishi'),true);
//                $peixun = json_decode($redis->get('index_peixun'),true);
//                $muying = json_decode($redis->get('index_muying'),true);
//                $zhuantixm = json_decode($redis->get('index_zhuantixm'),true);
//                $class1 = json_decode($redis->get('index_class1'),true);
//                $mothhot = json_decode($redis->get('index_mothhot'),true);
//                $newsxm = json_decode($redis->get('index_newsxm'),true);
//                $zixun1 = json_decode($redis->get('index_zixun1'),true);
//                $zixun2 = json_decode($redis->get('index_zixun2'),true);
//                $zhishi1 = json_decode($redis->get('index_zhishi1'),true);
//                $zhishi2 = json_decode($redis->get('index_zhishi2'),true);
//                $gushi1 = json_decode($redis->get('index_gushi1'),true);
//                $gushi2 = json_decode($redis->get('index_gushi2'),true);
//                $zhidao1 = json_decode($redis->get('index_zhidao1'),true);
//                $zhidao2 = json_decode($redis->get('index_zhidao2'),true);
//                $zhinan1 = json_decode($redis->get('index_zhinan1'),true);
//                $zhinan2 = json_decode($redis->get('index_zhinan2'),true);
//                $youlian = json_decode($redis->get('index_youlian'),true);
//                $website = json_decode($redis->get('index_website'),true);
//
//            }else {
                //首页banner
//                $aid['aid'] = ['in','84335,80367,12988'];
                $banner = Db::name('advertisement')->where(['type'=>1,'is_delete'=>2])->order('inputtime asc')->select();
                $this->assign('banner',$banner);
                
                //分类右侧俩大图
                $arr_id['aid'] = ['in','119371,119288'];
                $arr_id['status'] = 1;
                $arr_id['arcrank'] = 1;
//                $datu = db('portal_xm')->where($arr_id)->field('aid,class,title,thumbnail,invested')->select();



                $where17['parent_id'] =['in','2,1,3,4,5,7'];
                $cateid = db('portal_category')->where($where17)->field('id')->select()->toArray();
                $ids = array_column($cateid,'id');
                array_push($ids,'2','1','3','4','5','7');
                $zuice = db("portal_xm")->where(['typeid'=>['in',$ids]])->where('status = 1 and arcrank = 1')->order('click desc')->limit(10)->select();

                $news = db('portal_post')->where('post_status = 1 and status = 1 ')->where("thumbnail != ''")->order('click desc')->limit(7)->select();
                $news = $news->all();
                $news_hot = array_slice($news,0,2);
                $news_hot2 = array_slice($news,2,5);

                $gg_aid = Db::name('advertisement')->where(['type'=>2,'is_delete'=>2])->value('aid');
                $aid = explode(',',$gg_aid);
                $where18['aid'] = ['in',$aid];
//                $where18['aid'] = ['in','59586,92858,103409'];
                $dapai = db('portal_xm')->where($where18)->where('status = 1 and arcrank = 1')->field('aid,title,thumbnail,invested,typeid,sum,class')->select()->toArray();
                foreach ($dapai as $ks => $vs){
                    $paths = db('portal_category')->where('id = '.$vs['typeid'])->field('id,name,path')->find();
                    $dapai[$ks]['paths'] = str_replace('加盟','',$paths['name']);
                    $dapai[$ks]['class2'] = $paths['path'];
                }
//                print_r($dapai);die;

                $lick6 = db('portal_xm')->where('arcrank =1 and status = 1 and find_in_set("c",flag) ')->order('update_time desc')->limit(30)->select();
                //火爆招商
//                $lick7 = db('portal_xm')->where('arcrank =1 and status = 1 and thumbnail!="" and find_in_set("a",flag) ')->limit(3,10)->select();
                $where19['aid'] = ['in','120632,103525,119056,94500,119289,1169,86522,90909,119539,119502'];
                $lick7 = db('portal_xm')->where($where19)->select()->toArray();

                //项目推荐
                $tuijian_id = Db::name('advertisement')->where(['type'=>3,'is_delete'=>2])->value('aid');

                $ban = db('portal_xm')->where(['aid'=>$tuijian_id])->find();
                $mothhot = db('portal_xm')->where('arcrank =1 and status = 1')->field('aid,title,class')->order('click desc')->limit(8)->select();
                //热门
                $where20['aid'] = ['in','98102,94311,84335,119350,118876,80367,78235,93250,93996'];
                $hot = db('portal_xm')->where($where20)->select();
                $hot = $hot->all();
                foreach ($hot as $k=>$v){
                    $name = db('portal_category')->where('id = '.$v['typeid'])->field('name')->find();
                    $hot[$k]['cate'] = $name['name'];
                }

                //餐饮美食
                $where21['aid'] = ['in','92183,93276,77596,93055,72114,97174,91118,91119,104352'];
                $meishi = db('portal_xm')->where($where21)->select();
                $meishi = $meishi->all();
                foreach ($meishi as $k=>$v){
                    $name = db('portal_category')->where('id = '.$v['typeid'])->field('name')->find();
                    $meishi[$k]['cate'] = $name['name'];
                }

                //教育培训
                $where22['aid'] = ['in','86879,86886,86877,86936,86993,86895,86913,86931,83968'];
                $peixun = db('portal_xm')->where($where22)->select();
                $peixun = $peixun->all();
                foreach ($peixun as $k=>$v){
                    $name = db('portal_category')->where('id = '.$v['typeid'])->field('name')->find();
                    $peixun[$k]['cate'] = $name['name'];
                }

                //母婴推荐
                $where23['aid'] = ['in','118441,9130,9057,9365,8968,9050,10120,87245,9214'];
                $muying = db('portal_xm')->where($where23)->select();
                $muying = $muying->all();
                foreach ($muying as $k=>$v){
                    $name = db('portal_category')->where('id = '.$v['typeid'])->field('name')->find();
                    $muying[$k]['cate'] = $name['name'];
                }
                //创业专题项目
                $zhuantixm = db('portal_xm')->where('arcrank =1 and status = 1')->order('click desc')->limit('3','4')->select();
                //行业分类
                $where24['id'] = ['in','2,1,4,5,7,10,3,6,8,9,312,313,420'];
                $categ = db("portal_category")->where($where24)->where("ishidden =1 and status =1")->orderRaw("field(id,2,1,4,5,7,10,3,6,8,9,312,313,396,420)")->select();
            
                foreach ($categ as $key => $val) {
                    if($val['id'] == 2){
                        $limit = ($val['id']==2) ? 28 : '';
                    }else{
                        $limit = ($val['id'] == 10 && $val['id']!=2) ? 8 : 11;
                    }
                    if($val['id'] == 420){
                        $licai = db('portal_category')->where('id = 397')->select()->toArray();
                        $val['cate'] = db("portal_category")->where("parent_id", 'in', $val['id'])->where('status =1 and ishidden = 1')->limit($limit)->order('list_order','asc')->select()->toArray();
                        $val['cate'] = array_merge($val['cate'],$licai);
                        $class1[] = $val;
                    }else{
                        $val['cate'] = db("portal_category")->where("parent_id", 'in', $val['id'])->where('status =1 and ishidden = 1')->limit($limit)->order('list_order','asc')->select()->toArray();
                        $class1[] = $val;
                    }

                }

                $newsxm = db('portal_xm')->where('arcrank =1 and status = 1')->field('aid,typeid,title,class')->order('inputtime desc')->limit(28)->select();
                $newsxm = $newsxm->all();
                foreach($newsxm as $k=>$v){
                    $type = db('portal_category')->where('id = '.$v['typeid'])->field('name,path')->find();
                    $newsxm[$k]['cate'] = $type['name'];
                    $newsxm[$k]['paths'] = $type['path'];
                }
                //创业资讯
                $where25['parent_id'] = ['in','399,401,402,403,404,405,406,407,408,409,433'];
                $zixun = db('portal_post')->where($where25)->where('post_status = 1 and status = 1')->order('published_time desc')->limit(9)->select();
                $zixun = $zixun->all();
                $zixun1 = array_slice($zixun,0, 1);
                $zixun2 = array_slice($zixun,1, 8);

                //创业知识
                $where26['parent_id'] = ['in','20,22,27,31'];
                $zhishi = db('portal_post')->where($where26)->where('post_status = 1 and status = 1')->order('published_time desc')->limit(9)->select();
                $zhishi = $zhishi->all();
                $zhishi1 = array_slice($zhishi,0, 1);
                $zhishi2 = array_slice($zhishi,1, 8);

                //创业故事
                $where27['parent_id'] = ['in','11'];
                $gushi = db('portal_post')->where($where27)->where('post_status = 1 and status = 1')->order('published_time desc')->limit(9)->select();
                $gushi = $gushi->all();
                $gushi1 = array_slice($gushi,0, 1);
                $gushi2 = array_slice($gushi,1, 8);

                //创业之道
                $where28['parent_id'] = ['in','32'];
                $zhidao = db('portal_post')->where($where28)->where('post_status = 1 and status = 1')->order('published_time desc')->limit(9)->select();
                $zhidao = $zhidao->all();
                $zhidao1 = array_slice($zhidao,0, 1);
                $zhidao2 = array_slice($zhidao,1, 8);

                //创业指南
                $where29['parent_id'] = ['in','37,38,41,43,392'];
                $zhinan = db('portal_post')->where($where29)->where('post_status = 1 and status = 1')->order('published_time desc')->limit(9)->select();
                $zhinan = $zhinan->all();
                $zhinan1 = array_slice($zhinan,0, 1);
                $zhinan2 = array_slice($zhinan,1, 8);

           //查询数据
                $website = DB('website')->where(['id' => 1])->find();
                $youlian = db("flink")->where("typeid = 9999 and ischeck =1 ")->order("dtime desc")->limit(50)->select();

                //品牌名称
            $xiangmu_name = Db::name('xiangmu_name')->select()->toArray();
            $sort = 300-count($xiangmu_name);

            $where_not['aid'] = ['not in','119750,119350,119089,118566,114088,113567,113544,113537,113319,113226,113167,113153,113137,113126,113106,113097,112877,112769,112432,111952,111939,111522,111446,111435,111417,111308,111286,110994,110948,110894,110815,110754,110649,110606,110539,110472,110446,110409,110406,110303,110274,110054,109940,109883,109873,109871,109766,109688,109516,109350,109283,109273,109161,109005,108874,107705,107685,107676,107660,107551,107484,107259,107201,107100,107078,107005,107002,106974,106753,106752,106657,106643,106591,106448,106445,105784,105633,105436,105183,105145,105074,104940,104740,104643,104352,104351,104345,104271,104197,103775,103721,103490,103458,103259,103142,102970,102658,102143,101781,101659,101437,101318,101000,100878,100870,100529,100449,100321,100315,100273,100265,100245,100029,99874,99862,99766,99680,99627,99529,99526,99391,99385,99314,98778,98706,98398,98390,98275,98261,97833,97728,97651,97625,97454,97226,97100,97046,96860,95182,95174,94642,
94311,93875,93835,93682,93475,93455,93078,92401,92400,91815,89587,88678,88665,88636,87620,87612,87380,87294,87101,86923,86919'];
            $brandName = db('xiangmu_id')->where($where_not)->field('title,aid,class')->limit(300,$sort)->select()->toArray();
            $brandName = array_merge($xiangmu_name,$brandName);

                //最新餐饮加盟
                $canyin_id = Db::name('portal_category')->where(['parent_id'=>2])->column('id');
                array_push($canyin_id,'2');
                  $where30['typeid'] = ['in',$canyin_id];
                  $where30['status'] = 1;
                  $where30['arcrank'] = 1;
                $news_canyin = Db::name('portal_xm')->where($where30)->field('title,aid,class,typeid')->order('aid desc')->limit(26)->select()->toArray();
                foreach($news_canyin as $k=>$v){
                    $type = db('portal_category')->where('id = '.$v['typeid'])->field('name,path')->find();
                    $news_canyin[$k]['cate'] = $type['name'];
                    $news_canyin[$k]['paths'] = $type['path'];
                }
                //加入缓存
//                $redis->set('index_flg' , 1 , 300);
//                $redis->set('index_news_canyin' , json_encode($news_canyin,JSON_UNESCAPED_UNICODE) , 300);
//                $redis->set('index_datu' , json_encode($datu,JSON_UNESCAPED_UNICODE) , 300);
//                $redis->set('index_brand' , json_encode($datu,JSON_UNESCAPED_UNICODE) , 300);
//                $redis->set('index_zuice' , json_encode($zuice,JSON_UNESCAPED_UNICODE) , 300);
//                $redis->set('index_news_hot' , json_encode($news_hot,JSON_UNESCAPED_UNICODE) , 300);
//                $redis->set('index_news_hot2' , json_encode($news_hot2,JSON_UNESCAPED_UNICODE) , 300);
//                $redis->set('index_dapai' , json_encode($dapai,JSON_UNESCAPED_UNICODE) , 300);
//                $redis->set('index_lick6' , json_encode($lick6,JSON_UNESCAPED_UNICODE) , 300);
//                $redis->set('index_lick7' , json_encode($lick7,JSON_UNESCAPED_UNICODE) , 300);
//                $redis->set('index_ban' , json_encode($ban,JSON_UNESCAPED_UNICODE) , 300);
//                $redis->set('index_hot' , json_encode($hot,JSON_UNESCAPED_UNICODE) , 300);
//                $redis->set('index_meishi' , json_encode($meishi,JSON_UNESCAPED_UNICODE) , 300);
//                $redis->set('index_peixun' , json_encode($peixun,JSON_UNESCAPED_UNICODE) , 300);
//                $redis->set('index_muying' , json_encode($muying,JSON_UNESCAPED_UNICODE) , 300);
//                $redis->set('index_zhuantixm' , json_encode($zhuantixm,JSON_UNESCAPED_UNICODE) , 300);
//                $redis->set('index_class1' , json_encode($class1,JSON_UNESCAPED_UNICODE) , 300);
//                $redis->set('index_mothhot' , json_encode($mothhot,JSON_UNESCAPED_UNICODE) , 300);
//                $redis->set('index_newsxm' , json_encode($newsxm,JSON_UNESCAPED_UNICODE) , 300);
//                $redis->set('index_zixun1' , json_encode($zixun1,JSON_UNESCAPED_UNICODE) , 300);
//                $redis->set('index_zixun2' , json_encode($zixun2,JSON_UNESCAPED_UNICODE) , 300);
//                $redis->set('index_zhishi1' , json_encode($zhishi1,JSON_UNESCAPED_UNICODE) , 300);
//                $redis->set('index_zhishi2' , json_encode($zhishi2,JSON_UNESCAPED_UNICODE) , 300);
//                $redis->set('index_gushi1' , json_encode($gushi1,JSON_UNESCAPED_UNICODE) , 300);
//                $redis->set('index_gushi2' , json_encode($gushi2,JSON_UNESCAPED_UNICODE) , 300);
//                $redis->set('index_zhidao1' , json_encode($zhidao1,JSON_UNESCAPED_UNICODE) , 300);
//                $redis->set('index_zhidao2' , json_encode($zhidao2,JSON_UNESCAPED_UNICODE) , 300);
//                $redis->set('index_zhinan1' , json_encode($zhinan1,JSON_UNESCAPED_UNICODE) , 300);
//                $redis->set('index_zhinan2' , json_encode($zhinan2,JSON_UNESCAPED_UNICODE) , 300);
//                $redis->set('index_youlian' , json_encode($youlian,JSON_UNESCAPED_UNICODE) , 300);
//                $redis->set('index_website' , json_encode($website,JSON_UNESCAPED_UNICODE) , 300);
//            }
            $this->assign('news_canyin',$news_canyin);
//            $this->assign('datu',$datu);
            $this->zuoce();
            $this->assign('zuice',$zuice);
            $this->assign('brandName',$brandName);
            $this->assign('news_hot',$news_hot);
            $this->assign('news_hot2',$news_hot2);
            $this->assign('dapai',$dapai);
            $this->assign('lick6',$lick6);
            $this->assign('lick7',$lick7);
            $this->assign('ban',$ban);
            $this->assign('hot',$hot);
            $this->assign('meishi',$meishi);
            $this->assign('peixun',$peixun);
            $this->assign('muying',$muying);
            $this->assign('zhuantixm',$zhuantixm);
            $this->assign('class1',$class1);
            $this->assign('mothhot',$mothhot);
            $this->assign('newsxm',$newsxm);
            $this->assign('zixun1',$zixun1);
            $this->assign('zixun2',$zixun2);
            $this->assign('zhishi1',$zhishi1);
            $this->assign('zhishi2',$zhishi2);
            $this->assign('gushi1',$gushi1);
            $this->assign('gushi2',$gushi2);
            $this->assign('zhidao1',$zhidao1);
            $this->assign('zhidao2',$zhidao2);
            $this->assign('zhinan1',$zhinan1);
            $this->assign('zhinan2',$zhinan2);
            $this->assign('youlian',$youlian);
            $this->assign('website',$website);
            $this->daohang();
            $this->dibu();
            return $this->fetch(':index');
        }


    }

    

   
}
