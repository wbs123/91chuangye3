<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2018 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: Dean <zxxjjforever@163.com>
// +----------------------------------------------------------------------

// if (file_exists(CMF_ROOT . "data/conf/route.php")) {
//     $runtimeRoutes = include CMF_ROOT . "data/conf/route.php";
// } else {
//     $runtimeRoutes = [];
// }

// return $runtimeRoutes;
$url = explode('/',$_SERVER['QUERY_STRING']);
if($_SERVER['QUERY_STRING'] == 's=admin'){
   // think\Route::rule('admin/setting',$_SERVER['HTTP_HOST'].'/admin#/admin/setting/site');
}else if(in_array('s=admin',$url)){
//    think\Route::rule('admin/setting',$_SERVER['HTTP_HOST'].'/admin#/admin/setting/site');
}else{
    require CMF_ROOT . "data/conf/route.php";
}