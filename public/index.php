<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2018 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 老猫 <zxxjjforever@163.com>
// +----------------------------------------------------------------------

// [ 入口文件 ]
// 调试模式开关
define("APP_DEBUG", true);

// 定义CMF根目录,可更改此目录
define('CMF_ROOT', __DIR__ . '/../');

// 定义应用目录
define('APP_PATH', CMF_ROOT . 'app/');

// 定义CMF核心包目录
define('CMF_PATH', CMF_ROOT . 'simplewind/cmf/');

// 定义插件目录
define('PLUGINS_PATH', __DIR__ . '/plugins/');
define('PUBLIC_PATH', __DIR__ . '/');

// 定义扩展目录
define('EXTEND_PATH', CMF_ROOT . 'simplewind/extend/');
define('VENDOR_PATH', CMF_ROOT . 'simplewind/vendor/');

// 定义应用的运行时目录
define('RUNTIME_PATH', CMF_ROOT . 'data/runtime/');

// 定义CMF 版本号
define('THINKCMF_VERSION', '5.0.180901');

// 加载框架基础文件
require CMF_ROOT . 'simplewind/thinkphp/base.php';

//判断是不是手机端访问
$http = is_https() ? 'https://' : 'http://';
if (\think\Request::instance()->isMobile()) {
    if($_SERVER['HTTP_HOST'] == '91.com' || $_SERVER['HTTP_HOST'] == 'www.91.com'){
        header('HTTP/1.1 301 Moved Permanently');
        header("location:".$http."m.91.com".$_SERVER['REQUEST_URI']);exit;
    }
}else{
    if($_SERVER['HTTP_HOST'] == 'm.91.com'){
        header('HTTP/1.1 301 Moved Permanently');
        header("location:".$http."www.91.com".$_SERVER['REQUEST_URI']);exit;
    }
}



$url = explode('/',$_SERVER['QUERY_STRING']);
if($_SERVER['QUERY_STRING'] == 's=admin'){
    require CMF_ROOT . "app/route.php";
}else if(in_array('s=admin',$url)){
    //require CMF_ROOT . "public/admin.php";
    require CMF_ROOT . "app/route.php";

}else{
    require CMF_ROOT . "data/conf/route.php";
}
\think\App::route(true);
// 执行应用
\think\App::run()->send();



function is_https() {
    if ( !empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off') {
        return true;
    } elseif ( isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https' ) {
        return true;
    } elseif ( !empty($_SERVER['HTTP_FRONT_END_HTTPS']) && strtolower($_SERVER['HTTP_FRONT_END_HTTPS']) !== 'off') {
        return true;
    }
    return false;
}