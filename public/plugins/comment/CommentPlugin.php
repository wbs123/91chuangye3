<?php

namespace plugins\comment;
//Demo插件英文名，改成你的插件英文就行了
use cmf\lib\Plugin;
use plugins\comment\model\PluginCommentModel;

class CommentPlugin extends Plugin
{

    public $info = [
        'name' => 'Comment',
        'title' => '系统评论插件',
        'description' => '系统评论插件,利用系统自带的评论表',
        'status' => 1,
        'author' => ' M',
        'version' => '1.0.1',
        'demo_url' => ''
    ];
    public $hasAdmin = 1;//插件是否有后台管理界面

    public function install()
    {
        return true;
    }

    public function uninstall()
    {
        return true;
    }

    //实现的comment钩子方法
    public function comment($param)
    {

        $config = $this->getConfig();
        $this->assign($config);
        $model = new PluginCommentModel();

        $where = ['object_id' => $param['object_id'],'status'=>['neq',0], 'delete_time' => 0];
        $datas = $model->with("touser,parent")
            ->order('create_time desc')->where($where)->select();
        $this->assign("datas", $datas);
 
        $this->assign($param);
		$this->assign('total',count($datas));

        return $this->fetch('widget');
    }
}