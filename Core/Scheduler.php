<?php
namespace Scheduler\Core;
use Scheduler\Tools\Log;

/**
 * Created by PhpStorm.
 * User: yuliu
 * Date: 17/8/22
 * Time: 下午3:26
 */
class Scheduler {
    protected  $maxTaskId = 0;
    protected $taskmap = [];
    protected $taskQueue;

    public function __construct(){
        $this->taskQueue  = new \SplQueue();
    }

    public function setTask(Task $task){
        $tid = ++$this->maxTaskId;
        $this->taskmap[$tid] = $task;
        $task->setId($tid);
        $this->schedule($task);
    }

    public function newTask(\Generator $coroutine) {
        $tid = ++$this->maxTaskId;
        $task = new Task();
        $task->setCoroutine($coroutine);
        $task->setId($tid);
        $this->taskmap[$tid] = $task;
        $this->schedule($task);
        return $tid;
    }

    public  function schedule(Task $task) {
        $this->taskQueue->enqueue($task);
    }

    public function killTask($tid){
        if(isset($this->taskmap[$tid])){
            return false;
        }
        unset($this->taskmap[$tid]);
        foreach( $this->taskQueue as $i =>$task ){
            if($task->getId() == $tid){
                unset($this->taskQueue[$i]);
                break;
            }
        }
        return true;
    }

    public function run(){
        while(!$this->taskQueue->isEmpty()){
            $task = $this->taskQueue->dequeue();
            $revalue = $task->run();
            if(is_callable($revalue)){
                try {
                    $revalue($task,$this);
                }catch (\Exception $e) {
                    $task->setException($e);
                    $this->schedule($task);
                    continue;
                }
            }
            if($task->isFinished()){
                unset($this->taskmap[$task->getId()]);
            }else{
                $this->schedule($task);
            }
        }
    }
}
