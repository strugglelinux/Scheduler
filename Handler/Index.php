<?php
namespace Scheduler\Handler;

use Scheduler\Core\Scheduler;
use Scheduler\Core\Task;
use Scheduler\Tools\Config;
use Scheduler\Tools\Log;

/**
 * Created by PhpStorm.
 * User: yuliu
 * Date: 17/8/23
 * Time: 下午5:14
 */
class Index {

    private function task1(){
       for($i=0 ; $i<5 ; $i++ ){
           Log::debug(sprintf("task1 ==>%d \n", $i));
           yield;
       }
    }
    private function task2(){
        for($i=0 ; $i<10 ; $i++ ){
            if($i%2 == 0){
                Log::debug(sprintf("task2 ==>%d \n", $i));
                yield;
            }else{
                yield function($tak,$s) use($i) {
                    //$s->schedule($tak);
                    //printf("task2 ==>%d ", $i);
                    Log::info(sprintf("task2 ==>%d  Id => %d \n", $i,$tak->getId()));
                };
            }
        }
    }

    public function run(){
        Log::setIsShow(true);
        $scheduler = new Scheduler();
        $scheduler->newTask($this->task1());
        $scheduler->newTask($this->task2());
        $scheduler->run();
    }
}
