<?php


// [ 应用入口文件 ]
$http = is_https() ? 'https://' : 'http://';
$url = $_SERVER["PHP_SELF"];
$array = explode('/', $url);

if(in_array('sqyadmin.php',$array)){
    header("location:".$http.$_SERVER['HTTP_HOST'].'/sqyadmin');exit;
}
if(in_array('admin.php',$array)){
    header("location:".$http.$_SERVER['HTTP_HOST'].'/sqyadmin');exit;
}
if(in_array('admin',$array)){
    header("location:".$http.$_SERVER['HTTP_HOST'].'/sqyadmin');exit;
}

// 绑定模块
//define("BIND_MODULE",'admin');
// 加载框架引导文件
//require CMF_ROOT . 'simplewind/thinkphp/base.php';
define('CMF_ROOT', __DIR__ . '/../');
require CMF_ROOT . "app/route.php";
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
