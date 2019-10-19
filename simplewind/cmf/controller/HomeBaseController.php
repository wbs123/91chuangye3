<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2018 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +---------------------------------------------------------------------
// | Author: Dean <zxxjjforever@163.com>
// +----------------------------------------------------------------------
namespace cmf\controller;

use think\Db;
use app\admin\model\ThemeModel;
use app\portal\model\CategoryModel;
use app\portal\model\ProductModel;
use think\Debug;
use think\View;
use think\cache\driver\Redis;
use think\Request;

class HomeBaseController extends BaseController
{

    public function _initialize()
    {
        // 监听home_init
        hook('home_init');
        parent::_initialize();
        $siteInfo = cmf_get_site_info();
        View::share('site_info', $siteInfo);
    }

    public function _initializeView()
    {
        $cmfThemePath    = config('cmf_theme_path');
        $cmfDefaultTheme = cmf_get_current_theme();

        $themePath = "{$cmfThemePath}{$cmfDefaultTheme}";

        $root = cmf_get_root();
        //使cdn设置生效
        $cdnSettings = cmf_get_option('cdn_settings');
        if (empty($cdnSettings['cdn_static_root'])) {
            $viewReplaceStr = [
                '__ROOT__'     => $root,
                '__TMPL__'     => "{$root}/{$themePath}",
                '__STATIC__'   => "{$root}/static",
                '__WEB_ROOT__' => $root
            ];
        } else {
            $cdnStaticRoot  = rtrim($cdnSettings['cdn_static_root'], '/');
            $viewReplaceStr = [
                '__ROOT__'     => $root,
                '__TMPL__'     => "{$cdnStaticRoot}/{$themePath}",
                '__STATIC__'   => "{$cdnStaticRoot}/static",
                '__WEB_ROOT__' => $cdnStaticRoot
            ];
        }

        $viewReplaceStr = array_merge(config('view_replace_str'), $viewReplaceStr);
        config('template.view_base', "{$themePath}/");
        config('view_replace_str', $viewReplaceStr);

        $themeErrorTmpl = "{$themePath}/error.html";
        if (file_exists_case($themeErrorTmpl)) {
            config('dispatch_error_tmpl', $themeErrorTmpl);
        }

        $themeSuccessTmpl = "{$themePath}/success.html";
        if (file_exists_case($themeSuccessTmpl)) {
            config('dispatch_success_tmpl', $themeSuccessTmpl);
        }


    }

    /**
     * 加载模板输出
     * @access protected
     * @param string $template 模板文件名
     * @param array $vars 模板输出变量
     * @param array $replace 模板替换
     * @param array $config 模板参数
     * @return mixed
     */
    protected function fetch($template = '', $vars = [], $replace = [], $config = [])
    {
        $template = $this->parseTemplate($template);
		//不知道干啥用的/ 增加执行时间
        //$more     = $this->getThemeFileMore($template);
        $this->assign('theme_vars', '');
        $this->assign('theme_widgets', '');
        $content = parent::fetch($template, $vars, $replace, $config);

        $designingTheme = session('admin_designing_theme');

        if ($designingTheme) {
            $app        = $this->request->module();
            $controller = $this->request->controller();
            $action     = $this->request->action();

            $output = <<<hello
<script>
var _themeDesign=true;
var _themeTest="test";
var _app='{$app}';
var _controller='{$controller}';
var _action='{$action}';
var _themeFile='{$more['file']}';
parent.simulatorRefresh();
</script>
hello;

            $pos = strripos($content, '</body>');
            if (false !== $pos) {
                $content = substr($content, 0, $pos) . $output . substr($content, $pos);
            } else {
                $content = $content . $output;
            }
        }


        return $content;
    }

    /**
     * 自动定位模板文件
     * @access private
     * @param string $template 模板文件规则
     * @return string
     */
    private function parseTemplate($template)
    {
        // 分析模板文件规则
        $request = $this->request;
        // 获取视图根目录
        if (strpos($template, '@')) {
            // 跨模块调用
            list($module, $template) = explode('@', $template);
        }

        $viewBase = config('template.view_base');

        if ($viewBase) {
            // 基础视图目录
            $module = isset($module) ? $module : $request->module();
            $path   = $viewBase . ($module ? $module . DS : '');
        } else {
            $path = isset($module) ? APP_PATH . $module . DS . 'view' . DS : config('template.view_path');
        }

        $depr = config('template.view_depr');
        if (0 !== strpos($template, '/')) {
            $template   = str_replace(['/', ':'], $depr, $template);
            $controller = cmf_parse_name($request->controller());
            if ($controller) {
                if ('' == $template) {
                    // 如果模板文件名为空 按照默认规则定位
                    $template = str_replace('.', DS, $controller) . $depr . cmf_parse_name($request->action(true));
                } elseif (false === strpos($template, $depr)) {
                    $template = str_replace('.', DS, $controller) . $depr . $template;
                }
            }
        } else {
            $template = str_replace(['/', ':'], $depr, substr($template, 1));
        }
        return $path . ltrim($template, '/') . '.' . ltrim(config('template.view_suffix'), '.');
    }

    /**
     * 获取模板文件变量
     * @param string $file
     * @param string $theme
     * @return array
     */
    private function getThemeFileMore($file, $theme = "")
    {

        //TODO 增加缓存
        $theme = empty($theme) ? cmf_get_current_theme() : $theme;

        // 调试模式下自动更新模板
        if (APP_DEBUG) {
            $themeModel = new ThemeModel();
            $themeModel->updateTheme($theme);
        }

        $themePath = config('cmf_theme_path');
        $file      = str_replace('\\', '/', $file);
        $file      = str_replace('//', '/', $file);
        $themeFile = str_replace(['.html', '.php', $themePath . $theme . "/"], '', $file);

        $files = Db::name('theme_file')->field('more')->where(['theme' => $theme])->where(function ($query) use ($themeFile) {
            $query->where(['is_public' => 1])->whereOr(['file' => $themeFile]);
        })->select();

        $vars    = [];
        $widgets = [];
        foreach ($files as $file) {
            $oldMore = json_decode($file['more'], true);
            if (!empty($oldMore['vars'])) {
                foreach ($oldMore['vars'] as $varName => $var) {
                    $vars[$varName] = $var['value'];
                }
            }

            if (!empty($oldMore['widgets'])) {
                foreach ($oldMore['widgets'] as $widgetName => $widget) {

                    $widgetVars = [];
                    if (!empty($widget['vars'])) {
                        foreach ($widget['vars'] as $varName => $var) {
                            $widgetVars[$varName] = $var['value'];
                        }
                    }

                    $widget['vars']       = $widgetVars;
                    $widgets[$widgetName] = $widget;
                }
            }
        }

        return ['vars' => $vars, 'widgets' => $widgets, 'file' => $themeFile];
    }

    public function checkUserLogin()
    {
        $userId = cmf_get_current_user_id();
        if (empty($userId)) {
            if ($this->request->isAjax()) {
                $this->error("您尚未登录", cmf_url("user/Login/index"));
            } else {
                $this->redirect(cmf_url("user/Login/index"));
            }
        }
    }

    //是否是地区
    public function regionMode($array_str){
        $string="taiwan,aomenqu,xianggangqu,xinjiangqu,qinghai,ningxiaqu,gansu,xicangqu,guizhou,yunnan,hainan,guangxiqu,hunan,anhui,heilongjiang,jilin,neimenggu,shanxi,hebei,henan,hubei,shanxis,sichuan,jiangxi,liaoning,shandong,jiangsu,zhejiang,fujian,guangdong,zhongqing,tianjin,shanghai,beijing";
        $string=explode(',',$string);
        foreach($string as $key){
            if($array_str==$key){
                return  true;
            }
        }
        return false;
    }
    //投资金额格式
    function investmentAmount($array_str){
        if(strstr($array_str,'-')){
            return  true;
        }
        if($array_str==100){return  true;}
        return false;
    }

    //是否是分页格式
    function getPageModel($array_str){
        if(strstr($array_str,'list_')){
            return  true;
        }
        return false;
    }

    public function dibu()
    {
		$redis = new Redis();
        $dibu = db("portal_category")->where("parent_id",'in','52,53')->where('status =1 and ishidden = 1')->select();
		if($redis->get('dibu')){
			$dibu = json_decode($redis->get('dibu'),true);
		}else{
			$redis->set('dibu',json_encode($dibu));
		}
        $this->assign('dibu',$dibu);
    }
    public function zuoce(){
		$redis = new Redis();
		$this->CategoryModel = new CategoryModel();
		$ProductModel = new ProductModel();
		//餐饮
		if($redis->get('cate1')){
			$cate1 = json_decode($redis->get('cate1'),true);
		}else{
			$cate1 = $this->CategoryModel->allList(['parent_id'=>['in','2']],'id,name,path',54);
			$redis->set('cate1',json_encode($cate1));
		}
		//酒店
		if($redis->get('cate2')){
			$cate2 = json_decode($redis->get('cate2'),true);
		}else{
			$cate2 = $this->CategoryModel->allList(['parent_id'=>['in','734']],'id,name,path');
			$redis->set('cate2',json_encode($cate2));
		}
		//母婴
		if($redis->get('cate3')){
			$cate3 = json_decode($redis->get('cate3'),true);
		}else{
			$cate3 = $this->CategoryModel->allList(['parent_id'=>['in','8']],'id,name,path');
			$redis->set('cate3',json_encode($cate3));
		}
		//教育
		if($redis->get('cate4')){
			$cate4 = json_decode($redis->get('cate4'),true);
		}else{
			$cate4 = $this->CategoryModel->allList(['parent_id'=>['in','10']],'id,name,path');
			$redis->set('cate4',json_encode($cate4));
		}
		//酒水
		if($redis->get('cate5')){
			$cate5 = json_decode($redis->get('cate5'),true);
		}else{
			$cate5 = $this->CategoryModel->allList(['parent_id'=>['in','312']],'id,name,path');
			$redis->set('cate5',json_encode($cate5));
		}
		//珠宝
		if($redis->get('cate6')){
			$cate6 = json_decode($redis->get('cate6'),true);
		}else{
			$cate6 = $this->CategoryModel->allList(['parent_id'=>['in','5']],'id,name,path');
			$redis->set('cate6',json_encode($cate6));
		}
		//饰品
		if($redis->get('cate7')){
			$cate7 = json_decode($redis->get('cate7'),true);
		}else{
			$cate7 = $this->CategoryModel->allList(['parent_id'=>['in','4']],'id,name,path');
			$redis->set('cate7',json_encode($cate7));
		}
		//家居
		if($redis->get('cate8')){
			$cate8 = json_decode($redis->get('cate8'),true);
		}else{
			$cate8 = $this->CategoryModel->allList(['parent_id'=>['in','7']],'id,name,path');
			$redis->set('cate8',json_encode($cate8));
		}
		//女性
		if($redis->get('cate9')){
			$cate9 = json_decode($redis->get('cate9'),true);
		}else{
			$cate9 = $this->CategoryModel->allList(['parent_id'=>['in','9']],'id,name,path');
			$redis->set('cate9',json_encode($cate9));
		}
		//鞋业
		if($redis->get('cate10')){
			$cate10 = json_decode($redis->get('cate10'),true);
		}else{
			$cate10 = $this->CategoryModel->allList(['parent_id'=>['in','339']],'id,name,path');
			$redis->set('cate10',json_encode($cate10));
		}
		//服装
		if($redis->get('cate11')){
			$cate11 = json_decode($redis->get('cate11'),true);
		}else{
			$cate11 = $this->CategoryModel->allList(['parent_id'=>['in','1']],'id,name,path');
			$redis->set('cate11',json_encode($cate11));
		}
		//建材
		if($redis->get('cate12')){
			$cate12 = json_decode($redis->get('cate12'),true);
		}else{
			$cate12 = $this->CategoryModel->allList(['parent_id'=>['in','313']],'id,name,path');
			$redis->set('cate12',json_encode($cate12));
		}
		//汽车
		if($redis->get('cate13')){
			$cate13 = json_decode($redis->get('cate13'),true);
		}else{
			$cate13 = $this->CategoryModel->allList(['parent_id'=>['in','6']],'id,name,path');
			$redis->set('cate13',json_encode($cate13));
		}
		//干洗
		if($redis->get('cate14')){
			$cate14 = json_decode($redis->get('cate14'),true);
		}else{
			$cate14 = $this->CategoryModel->allList(['parent_id'=>['in','3']],'id,name,path');
			$redis->set('cate14',json_encode($cate14));
		}
		//金融
		if($redis->get('cate15')){
			$cate15 = json_decode($redis->get('cate15'),true);
		}else{
			$cate15 = $this->CategoryModel->allList(['parent_id'=>['in','396']],'id,name,path');
			$redis->set('cate15',json_encode($cate15));
		}
		//互联网
		if($redis->get('cate16')){
			$cate16 = json_decode($redis->get('cate16'),true);
		}else{
			$cate16 = $this->CategoryModel->allList(['parent_id'=>['in','420']],'id,name,path');
			$redis->set('cate16',json_encode($cate16));
		}
				
		//xm1
		if($redis->get('xm1')){
			$xm1 = json_decode($redis->get('xm1'),true);
		}else{
			$xm1 = $ProductModel->conditionlist(['aid'=>['in','123377,565']],'aid,title,logo,class,invested',3,'click','desc');
			$redis->set('xm1',json_encode($xm1));
		}
		//xm2
		if($redis->get('xm2')){
			$xm2 = json_decode($redis->get('xm2'),true);
		}else{
			$xm2 = $ProductModel->conditionlist(['aid'=>['in','123656,116150']],'aid,title,logo,class,invested',3,'click','desc');
			$redis->set('xm2',json_encode($xm2));
		}
		//xm3
		if($redis->get('xm3')){
			$xm3 = json_decode($redis->get('xm3'),true);
		}else{
			$xm3 = $ProductModel->conditionlist(['aid'=>['in','1254,9048']],'aid,title,logo,class,invested',3,'click','desc');
			$redis->set('xm3',json_encode($xm3));
		}
		//xm4
		if($redis->get('xm4')){
			$xm4 = json_decode($redis->get('xm4'),true);
		}else{
			$xm4 = $ProductModel->conditionlist(['aid'=>['in','86857,86873']],'aid,title,logo,class,invested',3,'click','desc');
			$redis->set('xm4',json_encode($xm4));
		}
		//xm5
		if($redis->get('xm5')){
			$xm5 = json_decode($redis->get('xm5'),true);
		}else{
			$xm5 = $ProductModel->conditionlist(['aid'=>['in','552,8405']],'aid,title,logo,class,invested',3,'click','desc');
			$redis->set('xm5',json_encode($xm5));
		}
		//xm6
		if($redis->get('xm6')){
			$xm6 = json_decode($redis->get('xm6'),true);
		}else{
			$xm6 = $ProductModel->conditionlist(['aid'=>['in','1232,9645']],'aid,title,logo,class,invested',3,'click','desc');
			$redis->set('xm6',json_encode($xm6));
		}
		//xm7
		if($redis->get('xm7')){
			$xm7 = json_decode($redis->get('xm7'),true);
		}else{
			$xm7 = $ProductModel->conditionlist(['aid'=>['in','8374,8395']],'aid,title,logo,class,invested',3,'click','desc');
			$redis->set('xm7',json_encode($xm7));
		}
		//xm8
		if($redis->get('xm8')){
			$xm8 = json_decode($redis->get('xm8'),true);
		}else{
			$xm8 = $ProductModel->conditionlist(['aid'=>['in','1267,8443']],'aid,title,logo,class,invested',3,'click','desc');
			$redis->set('xm8',json_encode($xm8));
		}
		//xm9
		if($redis->get('xm9')){
			$xm9 = json_decode($redis->get('xm9'),true);
		}else{
			$xm9 = $ProductModel->conditionlist(['aid'=>['in','1269,10250']],'aid,title,logo,class,invested',3,'click','desc');
			$redis->set('xm9',json_encode($xm9));
		}
		//xm10
		if($redis->get('xm10')){
			$xm10 = json_decode($redis->get('xm10'),true);
		}else{
			$xm10 = $ProductModel->conditionlist(['aid'=>['in','59597,59599']],'aid,title,logo,class,invested',3,'click','desc');
			$redis->set('xm10',json_encode($xm10));
		}
		//xm11
		if($redis->get('xm11')){
			$xm11 = json_decode($redis->get('xm11'),true);
		}else{
			$xm11 = $ProductModel->conditionlist(['aid'=>['in','548,1307']],'aid,title,logo,class,invested',3,'click','desc');
			$redis->set('xm11',json_encode($xm11));
		}
		//xm12
		if($redis->get('xm12')){
			$xm12 = json_decode($redis->get('xm12'),true);
		}else{
			$xm12 = $ProductModel->conditionlist(['aid'=>['in','1292,10249']],'aid,title,logo,class,invested',3,'click','desc');
			$redis->set('xm12',json_encode($xm12));
		}
		//xm13
		if($redis->get('xm13')){
			$xm13 = json_decode($redis->get('xm13'),true);
		}else{
			$xm13 = $ProductModel->conditionlist(['aid'=>['in','51129,51131']],'aid,title,logo,class,invested',3,'click','desc');
			$redis->set('xm13',json_encode($xm13));
		}
		//xm14
		if($redis->get('xm14')){
			$xm14 = json_decode($redis->get('xm14'),true);
		}else{
			$xm14 = $ProductModel->conditionlist(['aid'=>['in','598,84313']],'aid,title,logo,class,invested',3,'click','desc');
			$redis->set('xm14',json_encode($xm14));
		}
		//xm15
		if($redis->get('xm15')){
			$xm15 = json_decode($redis->get('xm15'),true);
		}else{
			$xm15 = $ProductModel->conditionlist(['aid'=>['in','89185,89186']],'aid,title,logo,class,invested',3,'click','desc');
			$redis->set('xm15',json_encode($xm15));
		}
		//xm16
		if($redis->get('xm16')){
			$xm16 = json_decode($redis->get('xm16'),true);
		}else{
			$xm16 = $ProductModel->conditionlist(['aid'=>['in','94007,94008']],'aid,title,logo,class,invested',3,'click','desc');
			$redis->set('xm16',json_encode($xm16));
		}
		$this->assign('xm1',$xm1);
        $this->assign('xm2',$xm2);
        $this->assign('xm3',$xm3);
        $this->assign('xm4',$xm4);
        $this->assign('xm5',$xm5);
        $this->assign('xm6',$xm6);
        $this->assign('xm7',$xm7);
        $this->assign('xm8',$xm8);
        $this->assign('xm9',$xm9);
        $this->assign('xm10',$xm10);
        $this->assign('xm11',$xm11);
        $this->assign('xm12',$xm12);
        $this->assign('xm13',$xm13);
        $this->assign('xm14',$xm14);
        $this->assign('xm15',$xm15);
        $this->assign('xm16',$xm16);
        $this->assign('cate1',$cate1);
        $this->assign('cate2',$cate2);
        $this->assign('cate3',$cate3);
        $this->assign('cate4',$cate4);
        $this->assign('cate5',$cate5);
        $this->assign('cate6',$cate6);
        $this->assign('cate7',$cate7);
        $this->assign('cate8',$cate8);
        $this->assign('cate9',$cate9);
        $this->assign('cate10',$cate10);
        $this->assign('cate11',$cate11);
        $this->assign('cate12',$cate12);
        $this->assign('cate13',$cate13);
        $this->assign('cate14',$cate14);
        $this->assign('cate15',$cate15);
        $this->assign('cate16',$cate16);
    }

    public function error1(){
        $request = Request::instance();
        $pathinfo = $request->pathinfo();
        //判断是否有静态页面
        if (\think\Request::instance()->isMobile()) {
            if(is_file('./themes/simpleboot3/portal/mhtml/'.$pathinfo)){
                $assign = ':mhtml/'.$request->path();
                return $this->fetch($assign);
            }
        }else{
            if(is_file('./themes/simpleboot3/portal/html/'.$pathinfo)){
                $assign = ':html/'.$request->path();
                return $this->fetch($assign);
            }
        }
		$this->daohang();
		$this->zuoce();
		$this->dibu();
        //判断是否有静态页面
        if (\think\Request::instance()->isMobile()) {
            return $this->fetch(":mobile/error");
        }else{
            return $this->fetch(":error");
        }
    }

    public function position($cid){//传递当前栏目id

        static $pos=array();//创建接受面包屑导航的数组

        if(empty($pos)){//哦，这个就比较重要了，如果需要把当前栏目也放到面包屑导航中的话就要加上
            $cates=db('portal_category')->field('id,name,parent_id,path')->find($cid);
            $pos[]=$cates;
        }
        $data=db('portal_category')->field('id,name,parent_id,path')->select();//所有栏目信息

        $cates=db('portal_category')->field('id,name,parent_id,path')->find($cid);//当前栏目信息

        foreach ($data as $k => $v) {

            if($cates['parent_id']==$v['id']){
                $pos[]=$v;
                $this->position($v['id']);
            }
        }
        $position = array_reverse($pos);
        $count= count(array_reverse($position));
        $position[$count-1]['style'] = 'on';

        return $position;
    }
	//加盟导航
	public function daohang(){
		$redis = new Redis();
		$this->CategoryModel = new CategoryModel();
        //导航
		if($redis->get('cates1')){
			$cates1 = json_decode($redis->get('cates1'),true);
		}else{
			$cates1 = $this->CategoryModel->allListArray(['id'=>['in','2,5,8,9']],'name,id,path','','list_order','asc');
			foreach ($cates1 as $key=>$cate){
				$cates1[$key]['data'] = $this->CategoryModel->allList(['parent_id'=>$cate['id']],'name,id,path');
			}
			$redis->set('cates1',json_encode($cates1));
		}
		if($redis->get('cates2')){
			$cates2 = json_decode($redis->get('cates2'),true);
		}else{
			$cates2 = $this->CategoryModel->allListArray(['id'=>['in','10,4,1,7,6,313']],'name,id,path','','list_order','asc');
			foreach ($cates2 as $key=>$cate){
				$cates2[$key]['data'] = $this->CategoryModel->allList(['parent_id'=>$cate['id']],'name,id,path');
			}
			$redis->set('cates2',json_encode($cates2));
		}
		if($redis->get('cates3')){
			$cates3 = json_decode($redis->get('cates3'),true);
		}else{
			$cates3 = $this->CategoryModel->allListArray(['id'=>['in','312,420,3,396,339,734']],'name,id,path','','list_order','asc');
			foreach ($cates3 as $key=>$cate){
				$cates3[$key]['data'] = $this->CategoryModel->allList(['parent_id'=>$cate['id']],'name,id,path');
			}
			$redis->set('cates3',json_encode($cates3));
		}
        $this->assign(['cates1'=>$cates1,'cates2'=>$cates2,'cates3'=>$cates3]);
	}
	//底部品牌推荐
	public function foot_hytj(){
		$redis = new Redis();
		$ProductModel = new ProductModel();
		if($redis->get('hytj_datas')){
			$datas = json_decode($redis->get('hytj_datas'),true);
		}else{
			$datas = $ProductModel->typelist(['id'=>['in','2,312,8,10,5,4,7,313,9,1']],12,'click','desc');
			$redis->set('hytj_datas',json_encode($datas));
		}
		$this->assign('htyj_datas',$datas);
	}
}