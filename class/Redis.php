<?php


class RedisConnect
{
     public $connected;
     public $cache;
     public $exception;

     public function __construct($config)
     {
         try {
             $redis = new Redis();
             $this->connected = $redis->connect($config["redis"]["host"], $config["redis"]["port"]);
             if (!empty($config["redis"]["password"])) {
                 $redis->auth($config["redis"]["password"]);
             }
             if($this->connected == false) {
                 $this->exception  = "redis connect error 1:".$redis->getLastError();
             }
             $this->cache = $redis;
         }
         catch (RedisException $e) {
            $this->exception =  "redis connect error 2:".$e->getMessage();
         }
     }
}