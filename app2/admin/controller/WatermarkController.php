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

class WatermarkController extends AdminBaseController
{
    public function index()
    {
    	$config = require '../data/conf/watermark.php';
    	$this->assign('config',$config['watermark']);
        return $this->fetch();

    }
    public function addPost()
    {
    	$fiele = '../data/conf/watermark.php';
    	$param = $this->request->param();
    	$watermark = $param['watermark'];
$array = "<?php 
    		return ['watermark'=>'".$watermark."']
    	?>";
    	if(file_put_contents($fiele, $array)){
    		$this->success('配置成功', url('Watermark/index'));
    	}else{
    		$this->success('配置失败', url('Watermark/index'));
    	}
    }
}