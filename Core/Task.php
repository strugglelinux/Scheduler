<?php
namespace Scheduler\Core;

/**
 * Created by PhpStorm.
 * User: yuliu
 * Date: 17/8/22
 * Time: 下午3:15
 */

class Task implements Tasker {

    protected  $id;
    protected  $coroutine;
    protected  $sendValue = null;
    protected  $beforeFistYield  = true;
    protected  $exception = null;

    public function getId() {
        return $this->id;
    }

    public function  setException($exception){
        $this->exception = $exception;
    }

    public function setId($taskId){
        $this->id = $taskId;
    }

    public function setCoroutine(\Generator $coroutine){
        $this->coroutine = $coroutine;
    }

    public function setValue($sendValue) {
        $this->sendValue = $sendValue;
    }

    public function run(){
        if ($this->beforeFistYield) {
            $this->beforeFistYield = false;
            return $this->coroutine->current();
        } else {
            $retval = $this->coroutine->send($this->sendValue);
            $this->sendValue = null;
            return $retval;
        }
    }

    public function isFinished() {
        return !$this->coroutine->valid();
    }
}
