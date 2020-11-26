<?php


class LinkData {

     protected $originHost;

     protected $route;

     protected $method;

     protected $redis;

     protected $redisData;

    protected  $requestData;

    protected  $queueID;

    protected $config;

    public $isNull = true;

     public function  __construct(RedisConnect $redis,$config)
     {
         $this->config = $config;
         $this->redis = $redis->cache;
         if($redis->connected) {
             $queueId = $this->redis->lPop($config['taskKey']);
             $this->queueID = $queueId;
             if (!is_null($queueId) && $queueId !== false) {
                 $ret = $this->redis->get(trim($queueId));
                 if (!is_null($ret)) {
                     $retDecode = json_decode($ret);
                     $this->redisData = $retDecode;
                     $this->setMethod($this->redisData->method);
                     $this->setRoute($this->redisData->route);
                     $this->setOriginHost($this->redisData->originHost);
                     $this->setRequestData($ret);
                     $this->isNull = false;
                 }
             }
         }else{
             throw new Exception($redis->exception);
         }
     }
     protected function setMethod($method)
     {
          $this->method = $method;
     }
    protected function setRoute($route)
    {
        $this->route = $route;
    }
    protected function setOriginHost($originHost)
    {
        $this->originHost = $originHost;
    }
    public function setRequestData($requestData)
    {
        return $this->requestData = $requestData;
    }
    public function getMethod()
    {
        return $this->method;
    }
    public function getRoute()
    {
        return $this->route;
    }
    public function getOriginHost()
    {
        return $this->originHost;
    }
    public function getRequestData()
    {
        return $this->requestData;
    }
    public function clear()
    {
        $this->redis->del($this->queueID);
    }
    public function getQueueId()
    {
        return $this->queueID;
    }
    public function retry()
    {
        $this->redis->lPush($this->config['taskKey'],$this->queueID);
    }
}