<?php

#定义删除路径
$path = '/www/wwwroot/www.news91chuangye.com/data/runtime';
#调用删除方法
deleteDir($path);

function deleteDir($dir)
{
    if (!$handle = @opendir($dir)) {
        return false;
    }
    while (false !== ($file = readdir($handle))) {
        if ($file !== "." && $file !== "..") {       //排除当前目录与父级目录
            $file = $dir . '/' . $file;
            if (is_dir($file)) {
                deleteDir($file);
            } else {
                @unlink($file);
            }
        }

    }
    @rmdir($dir);
}
