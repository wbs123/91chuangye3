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
namespace app\member\controller;




class FunCommon
{
    /**
     * 判断手机号格式是否正确
     * @param $phone 校验手机号
     * @return bool  false / true
     */
    public static function isPhone($phone){
        $preg_phone='/^1[34578]\d{9}$/ims';
        if(preg_match($preg_phone,$phone)){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 图片base64上传
     * @param $base64
     * @param string $path
     * @return bool|string
     */
    public static  function base64_upload($base64,$path = '') {
        $web_path = '/upload/member/';
        if(empty($path)){
            $path = $_SERVER['DOCUMENT_ROOT'].$web_path;
        }
        $base64_image = str_replace(' ', '+', $base64);
        //post的数据里面，加号会被替换为空格，需要重新替换回来，如果不是post的数据，则注释掉这一行
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image, $result)){
            //匹配成功
            if($result[2] == 'jpeg'){
                $image_name = md5(uniqid(mt_rand(), true)) .'.jpg';
                //纯粹是看jpeg不爽才替换的
            }else{
                $image_name = md5(uniqid(mt_rand(), true)) .'.'.$result[2];
            }
            $image_file = $path.$image_name;
            //服务器文件存储路径
            if (file_put_contents($image_file, base64_decode(str_replace($result[1], '', $base64_image)))){
                return $web_path.$image_name;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    /**
     * 删除项目根目录下指定路径下的文件
     * @param $path
     * @return bool
     */
    public static function del_file($path){
        if(empty($path)){
           return false;
        }
        $root = $_SERVER['DOCUMENT_ROOT'];
        $file_path = $root.$path;
        if(!file_exists($file_path)){
            return false;
        }
        if(unlink($file_path)){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 通过pathinfo获取文件扩展
     * @param $file
     * @return mixed
     */
    public static function get_files_ext($file) {
        return pathinfo($file,PATHINFO_EXTENSION);
    }
    
}