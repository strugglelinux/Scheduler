<?php
namespace Scheduler\Core;
use Scheduler\Tools\Config;
use Scheduler\Tools\File;
use Scheduler\Tools\Log;

/**
 * Created by PhpStorm.
 * User: struggleLinux
 * Date: 17/8/22
 * Time: 下午5:09
 */

class AutoLoad {

    protected static $prefixes = [];

    public  static function loadClass( $className ){
        $prefix = $className;
        if(false !== $pos = strrpos($prefix,'\\')){
            $prefix = substr($className,0,$pos+1);
            $class = substr($className,$pos+1);
            $file = self::loadFile($prefix,$class);
            if($file){
                return $file;
            }
        }
        return false;
    }

    public static function setNamespace($prefix,$baseDir,$append = true){
        $prefix = trim($prefix,'\\').'\\';
        $baseDir = rtrim($baseDir,DIRECTORY_SEPARATOR).'/';
        if( isset(self::$prefixes[$prefix]) === false){
            self::$prefixes[$prefix] = [];
        }
        if($append){
            array_push(self::$prefixes[$prefix],$baseDir);
        }else{
            array_unshift(self::$prefixes[$prefix],$baseDir);
        }
    }

    public static function loadFile($prefix ,$class){
        $key = $prefix.$class.'\\';
        if (isset(self::$prefixes[$key]) === false) {
            $path = rtrim(ROOT_PATH,DIRECTORY_SEPARATOR).'/';
            $filePath = $path.str_replace('\\','/',$prefix).$class.'.php';
            if(file_exists($filePath)){
                self::$prefixes[$key] = [$path];
            }else{
                return false;
            }
        }
        foreach(self::$prefixes[$key] as $baseDir){
            $file = $baseDir.str_replace('\\','/',$prefix).$class.'.php';
            if(file_exists($file)){
                require_once $file;
                return $file;
            }
        }
        return false;
    }

    private static function setLog(){
        $logPath = Config::get('LOG_PATH');
        if(!empty($logPath)){
            Log::setDir($logPath);
        }
    }

    public static function start(){
        define('ROOT_PATH',dirname(dirname(dirname(__FILE__)))); //根目录路劲
        define('APP_PATH',dirname(dirname(__FILE__))); //应用目录路劲
        define('CORE_PATH',APP_PATH.'/Core'); //应用目录路劲
        define('COMMON_PATH',APP_PATH.'/Common'); //公共调用函数路径
        define('CONFIG_PATH',APP_PATH.'/Config'); //配置目录路径
        self::setNamespace('Scheduler\Tools\File',ROOT_PATH);
        self::setNamespace('Scheduler\Tools\Config',ROOT_PATH);
        spl_autoload_register('Scheduler\Core\Autoload::loadClass');
        $commonFiles = File::getDirFiles(COMMON_PATH);
        foreach($commonFiles as $file){
            File::loadRequire($file);
        }
        $configFiles = File::getDirFiles(CONFIG_PATH);
        foreach($configFiles as $file){
            $value = File::loadInclude($file);
            Config::set($value);
        }
        self::setLog();
    }
}


