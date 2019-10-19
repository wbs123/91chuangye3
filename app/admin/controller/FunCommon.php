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
use app\portal\model\PortalCategoryModel;
use app\portal\model\PortalXmModel;


class FunCommon
{
    /**
     * @param string $content 文章内容
     * @param string $attr 标签 默认为src
     * @param string $tag  标签 img
     * @return mixed 返回第一张图片
     */
    public  static  function get_html_attr_by_tag($content="",$attr="src",$tag="img")
    {
        $arr=array();
        $cache_arr=array();
        $attr=explode(',',$attr);
        $tag=explode(',',$tag);
        foreach($tag as $i=>$t)
        {
            foreach($attr as $a)
            {
                $content = htmlspecialchars_decode($content);
                preg_match_all("/<\s*".$t."\s+[^>]*?".$a."\s*=\s*(\'|\")(.*?)\\1[^>]*?\/?\s*>/i",$content,$match);
                foreach($match[2] as $n=>$m)
                {
                    $arr[$t][$n][$a]=$m;
                }
            }
        }
        if(count($arr) == 0 ){
            return false;
        }else{
            return $arr['img'][0]['src'];
        }
    }
    /**
     * @param $page    当前页
     * @param $_total_page 共多少页
     * @param $showPage    显示几个页码
     * @param $utl         url
     * @param string $Parameter 参数
     * @param string $pageGetParam get参数设置 默认page
     * @param string $countLinks 共多条记录
     * @return string
     */
    public static function page($page, $_total_page, $showPage, $utl, $Parameter = "",$pageGetParam = 'page',$countLinks = 0){
        $showPage = 7;
        $pageOffset = ($showPage - 1) / 2;//计算偏移量；
        $start = 1;//初始化数据；
        //加上分页效果
        $page_banner = '<ul class="pagination">';//用来存放分页信息；
        if ($page > 1) {
            $page_banner .= '<li class="page-item"><a class="page-link" href="' . $utl . '?'.$pageGetParam.'=1'.$Parameter.'">首页';
            $page_banner .= '<li class="page-item"><a class="page-link" href="' . $utl . '?'.$pageGetParam.'=' . ($page - 1) . $Parameter . '">上一页';
        } else {
            $page_banner .= '<li class="page-item disabled"><span class="page-link">首页</span></li>';
            $page_banner .= '<li class="page-item disabled"><span class="page-link">上一页</span></li>';
        }
        if ($_total_page > $showPage) {
            if ($page > $pageOffset + 1) {
                $page_banner .= '<li class="page-item disabled"><span class="page-link">...</span></li>';
            }
            if ($page > $pageOffset) {
                $start = $page - $pageOffset;//计算起始位置；
                $end = $_total_page > $page + $pageOffset ? $page + $pageOffset : $_total_page;
            } else {
                $start = 1;
                $end = $_total_page > $showPage ? $showPage : $_total_page;
            }
            if ($page + $pageOffset > $_total_page) {
                $start = $start - ($page + $pageOffset - $end);
            }
        } else {
            $end = $_total_page;
        }
        //显示数字页码；
        for ($i = $start; $i <= $end; $i++) {
            if ($page == $i) {
                $page_banner .= '<li class="page-item disabled"><span class="page-link">' . $i . '</span></li>';
            } else {
                $page_banner .= '<li class="page-item"><a class="page-link" href="' . $utl . '?'.$pageGetParam.'=' . $i . $Parameter . '">' . $i . '</a></li>';

            }
        }
        //尾部省略；
        if ($_total_page > $showPage && $_total_page > $page + $pageOffset) {
            $page_banner .= '<li class="page-item disabled"><span class="page-link">...</span></li>';
        }

        if ($page < $_total_page) {
            $page_banner .= '<li class="page-item"><a class="page-link" href="' . $utl . '?'.$pageGetParam.'=' . ($page + 1) . $Parameter . '">下一页</a></li>';
            $page_banner .= '<li class="page-item"><a class="page-link" href="' . $utl . '?'.$pageGetParam.'=' . $_total_page. $Parameter . '">末页</a></li>';

        } else {
            $page_banner .= '<li class="page-item disabled"><span class="page-link">下一页</span></li>';
            if($page == $_total_page){
                $page_banner .= '<li class="page-item disabled"><span class="page-link">末页</span></li>';

            }
        }
        $page_banner .= '<li class="page-item disabled"><span class="page-link">共'.$_total_page.'页/'.$countLinks.'条记录</span></li>';
        return $page_banner;

    }

    /**
     * 关键词内链替换
     * @param string $content 文章内容
     * @param int $id 产品Id
     * return content
     */
    public static function replace_html_keyword($content="",$id=0)
    {
        $domain = request()->domain();
        //暂存内链数组
        $tagArr = [];
        if(!empty($content)){
            //获取行业类别
            $portalCategoryModel = new PortalCategoryModel();
            $where      = [
                'delete_time' => 0,
                'channeltype' => 17
            ];
            //内容
            $content = htmlspecialchars_decode($content);
			$regImg = '/<img[^>]*>/';
			$res = preg_match_all($regImg,$content,$matchAll);
			if($res){
				foreach($matchAll[0] as $key=>$v){
					$content = self::replaceImg($v,'{images_'.$key.'}',$content);
				}
			}
            //行业词
            $categories = $portalCategoryModel->field('name,path')->where($where)->select()->toArray();
            //遍历内容中是否出现行业词
            foreach($categories as $c){
                $regular = "<a .*>".$c['name']."<\/a>";
                $bool = preg_match("/($regular)/Ui", $content);
                //排除已有内链
                if (!$bool) {
                    if(($position = strpos($content,$c['name']))!==false && !in_array($c['name'], $tagArr)){
                        $leng     = strlen($c['name']);
                        $replIntro = "<a href='".$domain.'/'.$c['path']."/' target='_blank' title='".$c['name']."'>"
                            .$c['name']."</a>";
                        $content       =  substr_replace($content,$replIntro,$position,$leng);
                        $tagArr[]  = $c['name']; //记录已内链的关键字
                    }
                }
            }
			if(!empty($id)) {
				//匹配产品
				$portalXmModel = new PortalXmModel();
				$where = [
					'aid' => $id,
					'arcrank' => 1,
					'status' => 1
				];
				$product = $portalXmModel->field('aid,title,class')->where($where)->find();

				$title = $product['title']."加盟";
				$regular = "<a .*>".$title."<\/a>";
				$bool = preg_match("/($regular)/Ui", $content);
				//排除已有内链
				if (!$bool) {
					if(($position = strpos($content,$title))!==false && !in_array($title, $tagArr)){
						$leng     = strlen($title);
						$url = $domain.'/'.$product['class'].'/'.$product['aid'].'.html';
						$replIntro = "<a href='".$url."' target='_blank' title='".$title."'>".$title."</a>";
						$content       =  substr_replace($content,$replIntro,$position,$leng);
						$tagArr[]  = $title; //记录已内链的关键字
					}
				}
			}
			if($res){
				foreach($matchAll[0] as $key=>$v){
					$content = self::replaceImg('{images_'.$key.'}',$v,$content);
				}
			}
        }
        return $content;
    }
	private static function replaceImg($old,$news,$content){
		return str_replace($old,$news,$content);
	}

}