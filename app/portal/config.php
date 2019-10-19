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
if (file_exists(CMF_ROOT . "data/conf/config.php")) {
    $runtimeConfig = include CMF_ROOT . "data/conf/config.php";
} else {
    $runtimeConfig = [];
}
$configs = [
    'default_filter'          => 'trim,strip_tags,addslashes,htmlspecialchars'
];
return array_merge($configs, $runtimeConfig);