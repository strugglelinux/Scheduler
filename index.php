<?php
/**
 * Created by PhpStorm.
 * User: yuliu
 * Date: 17/8/23
 * Time: 下午2:49
 */
define('_ROOT_',__DIR__);
include_once _ROOT_.'/Core/AutoLoad.php';
\Scheduler\Core\AutoLoad::start();

$index = new \Scheduler\Handler\Index();
$index->run();
