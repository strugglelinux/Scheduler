<?php
namespace Scheduler\Core;

/**
 * 容器类
 * Created by PhpStorm.
 * User: struggleLinx
 * Date: 17/7/26
 * Time: 下午3:01
 * 请了解 self::method 和 static::method
 */
class  Container {
    //注册服务列表
    private static $registerService = [];

    /**
     * 构造函数 初始化服务
     * Container constructor.
     */
    public function __construct(){
        //$this->loadService();
    }

    /**
     * 批量注册
     * @param $service
     * @throws \Exception
     */
    public Static function registerAll($service){
        try {
            if(!empty($service)){
                foreach($service as $key =>$serviceClass){
                    static::$registerService[$key] = new $serviceClass();
                }
            }
        }catch (\Exception $e){
            throw new \Exception( 'Exception:' . $e->getMessage() .' Line:'.$e->getLine(),404); //未找到指定注册服务
        }

    }

    /**
     * 注册服务
     * @param $name
     * @param callable $resolver
     * @throws \Exception
     */
    public  static  function register($name, $resolver){
        if(empty($name)){
            throw new \Exception('The Service Name Is Empty',401);
        }
        static::$registerService[$name] = $resolver;
    }

    /**
     * 创建返回服务实例
     * @param $name
     * @return mixed
     * @throws \Exception
     */
    public static function make($name){
        if(isset(static::$registerService[$name])){
            $resover = static::$registerService[$name];
            if(is_callable($resover) ){ //判断是否可函数调用
                return $resover();
            }
            return $resover;
        }
        throw new \Exception($name .' not exist in the Container registry',404); //未找到指定注册服务
    }

}
