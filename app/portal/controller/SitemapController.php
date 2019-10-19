<?php
// +----------------------------------------------------------------------
// | Caiji
// +----------------------------------------------------------------------
// | Author: Mirng
// +----------------------------------------------------------------------
namespace app\portal\controller;

use cmf\controller\HomeBaseController;
use think\Db;

class SitemapController extends HomeBaseController
{

    public function index()
    {
        $param = $this->request->param();
        if(isset($param['a'])){
            if($param['a'] == 'rand'){
                $path = ROOT_PATH . "/public/sitemap.xml";
                self::randdata_sitemap_xml('http://www.91chuangye.com',$path);

                $path = ROOT_PATH . "/m/sitemap.xml";
                self::randdata_sitemap_xml('http://m.91chuangye.com',$path);
            }
            if($param['a'] == 'new'){
                $path = ROOT_PATH . "/public/sitemap_today.xml";
                self::newdata_sitemap_xml('http://www.91chuangye.com',$path);
                $path = ROOT_PATH . "/m/sitemap_today.xml";
                self::newdata_sitemap_xml('http://m.91chuangye.com',$path);
            }
			//PC主动推送代码
			$urls = array(
				'http://www.91chuangye.com/sitemap.xml',
				'http://www.91chuangye.com/sitemap_today.xml',
			);
			$api = 'http://data.zz.baidu.com/urls?site=www.91chuangye.com&token=Ig2oEawlxEunVlMD';
			$ch = curl_init();
			$options =  array(
				CURLOPT_URL => $api,
				CURLOPT_POST => true,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_POSTFIELDS => implode("\n", $urls),
				CURLOPT_HTTPHEADER => array('Content-Type: text/plain'),
			);
			curl_setopt_array($ch, $options);
			$result = curl_exec($ch);
			echo $result;
			
			//WAP主动推送代码
			$urls = array(
				'http://m.91chuangye.com/sitemap.xml',
				'http://m.91chuangye.com/sitemap_today.xml',
			);
			$api = 'http://data.zz.baidu.com/urls?site=m.91chuangye.com&token=Ig2oEawlxEunVlMD';
			$ch = curl_init();
			$options =  array(
				CURLOPT_URL => $api,
				CURLOPT_POST => true,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_POSTFIELDS => implode("\n", $urls),
				CURLOPT_HTTPHEADER => array('Content-Type: text/plain'),
			);
			curl_setopt_array($ch, $options);
			$result = curl_exec($ch);
			echo $result;
			
        }
    }

    /**
     * 当天数据生成sitemap - Xml
     */
    private function newdata_sitemap_xml($domain,$path)
    {
        //header('Content-Type: text/xml');
        $xml = '<?xml version="1.0" encoding="utf-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";
        $xml.="\t".'<url>'."\n";
        $xml.="\t"."\t".'<loc>'.$domain.'</loc>'."\n";
        $xml.="\t"."\t".'<lastmod>'.date('Y-m-d',time()).'</lastmod>'."\n";
        $xml.="\t"."\t".'<changefreq>daily</changefreq>'."\n";
        $xml.="\t"."\t".'<priority>1.0</priority>'."\n";
        $xml.="\t".'</url>'."\n";
        /*最新文档*/
        $bgtime = strtotime(date('Y-m-d'));
        $where = [
            'update_time' => ['gt',$bgtime]
            ,'arcrank' => 1
            ,'status' => 1
        ];
        //新闻资讯
        //$result_archives = Db::name('portal_post')->field('id as loc,published_time as lastmod,create_time')->where($where)->select();
        $result_archives = Db::name('portal_xm')->field('aid loc,update_time lastmod,class')->where($where)->select();
        foreach ($result_archives as $val) {
            $val['changefreq'] = 'monthly';
            $val['priority'] = '0.7';
            if (is_array($val)) {
                $xml.="\t".'<url>'."\n";
                foreach ($val as $key => $row) {
                    if (in_array($key, array('loc','lastmod','changefreq','priority'))) {
                        if ($key == 'loc') {
                            $row = $domain.'/'.$val['class'].'/'.$val['loc'].'.html';
                        } elseif ($key == 'lastmod') {
                            $lastmod_time = !empty($val['lastmod']) ? $val['lastmod'] : $val['create_time'];
                            $row = date('Y-m-d', $lastmod_time);
                        }

                        $xml.="\t\t".'<'.$key.'>'.$row.'</'.$key.'>'."\n";
                    }
                }
                $xml.="\t".'</url>'."\n";
            }
        }
        /*--end*/
        $xml .= '</urlset>';
        //Xml 目录
        @file_put_contents($path, $xml);
    }

    /**
     * 随机数据生成sitemap - Xml
     */
    private function randdata_sitemap_xml($domain,$path)
    {

        //header('Content-Type: text/xml');
        $xml = '<?xml version="1.0" encoding="utf-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";
        $xml.="\t".'<url>'."\n";
        $xml.="\t"."\t".'<loc>'.$domain.'</loc>'."\n";
        $xml.="\t"."\t".'<lastmod>'.date('Y-m-d',time()).'</lastmod>'."\n";
        $xml.="\t"."\t".'<changefreq>daily</changefreq>'."\n";
        $xml.="\t"."\t".'<priority>1.0</priority>'."\n";
        $xml.="\t".'</url>'."\n";
        /*最新文档*/
        $where = [
            'arcrank' => 1
            ,'status' => 1
        ];
        $result_archives = Db::name('portal_xm')->field('aid loc,pubdate lastmod,class')->where($where)->select()->toArray();
        //打乱数组排序
        $result_archives = self::shuffle_assoc($result_archives);
        $i = 0;
        foreach ($result_archives as $val) {
            $val['changefreq'] = 'monthly';
            $val['priority'] = '0.7';
            $i++;
            if (is_array($val)) {
                $xml.="\t".'<url>'."\n";
                foreach ($val as $key => $row) {
                    if (in_array($key, array('loc','lastmod','changefreq','priority'))) {
                        if ($key == 'loc') {
                            $row = $domain.'/'.$val['class'].'/'.$val['loc'].'.html';
                        } elseif ($key == 'lastmod') {
                            $lastmod_time = !empty($val['lastmod']) ? $val['lastmod'] : strtotime(date('Y-m-d'));
                            $row = date('Y-m-d', $lastmod_time);
                        }
                        $xml.="\t\t".'<'.$key.'>'.$row.'</'.$key.'>'."\n";
                    }
                }
                $xml.="\t".'</url>'."\n";
            }
            if($i==20000){break;}
        }
        /*--end*/
        $xml .= '</urlset>';
        //Xml 目录
        @file_put_contents($path, $xml);
    }
    //打乱数组
    private  function shuffle_assoc($list) {
        if (!is_array($list)) return $list;
        $keys = array_keys($list);
        shuffle($keys);
        $random = array();
        foreach ($keys as $key)
        $random[$key] = $list[$key];
        return $random;
    }

}