<?php
namespace Scheduler\Tools;

/**
 * Created by PhpStorm.
 * User: struggleLinux
 * Date: 17/8/23
 * Time: 上午10:47
 */

class File {

    public static function loadInclude($file){
        if(file_exists($file)){
            return include_once $file;
        }
        return false;
    }

    public static  function loadRequire($file){
        if(file_exists($file)){
            require_once $file;
            return true;
        }
        return false;
    }

    /**
     * 获取目录所有文件
     */
    public static function getDirFiles($dir){
        if(!is_dir($dir)){
            return false;
        }
        $allFiles = [];
        if(function_exists('scandir')){
            $files = scandir($dir);
            foreach($files as $key =>$file){
                $filePath = $dir.'/'.$file;
                if(is_file($filePath)){
                    array_push($allFiles,$filePath);
                }
            }
        }else{
            $handle = opendir($dir);
            while($file = readdir($handle)) {
                if($file != '.' && $file != '..'){
                    if(is_file($file)){
                        array_push($allFiles,$dir.'/'.$file);
                    }
                }
            }
            closedir($handle);
        }
        return $allFiles;
    }
}
