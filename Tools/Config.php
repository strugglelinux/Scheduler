<?php
namespace Scheduler\Tools;

/**
 * Created by PhpStorm.
 * User: yuliu
 * Date: 17/8/23
 * Time: 下午4:06
 */
class Config {

    private static $config = [];
    /**
     * 设置信息
     * @param $key
     * @param $val
     */
    public static function set($key,$val=null){
        if(is_string($key)){
            $key = strtoupper($key);
            self::$config[$key] = $val;
        }
        if(is_array($key)){
            self::$config = array_merge(self::$config,array_change_key_case($key,CASE_UPPER));
        }
    }

    /**
     * 获取信息
     * @param $key
     */
    public static  function get($key=null){
        if(empty($key)){ //为空时返回所有配置数据
            return self::$config;
        }
        $key = strtoupper($key);
        if(empty(self::$config[$key])){
            return false;
        }
        return self::$config[$key];
    }
}
